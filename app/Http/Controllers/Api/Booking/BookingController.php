<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\Booking\BookingResource;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingAction;
use App\Models\Booking\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Refund;
use Stripe\Stripe;

class BookingController extends Controller
{
    public function index(): JsonResponse
    {
        $bookings = Booking::with(['user', 'rooms'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(BookingResource::collection($bookings));
    }

    /**
     * STEP 1 — The guest picks a room and dates. We check availability
     * first: if the room is not free, we reject immediately (409) with a
     * clear reason, before any booking or payment is created. If it IS
     * free, we create the booking as "pending" (which itself blocks other
     * guests from booking the same room while this payment is in
     * progress), then create a Stripe Checkout Session for just the
     * deposit amount and return its payment URL.
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $data = $request->validated();
        $roomIds = $data['room_ids'];
        unset($data['room_ids']);

        $unavailableRooms = [];

        foreach ($roomIds as $roomId) {
            $isBooked = Booking::whereHas('rooms', function ($query) use ($roomId) {
                    $query->where('rooms.id', $roomId);
                })
                // A "pending" booking still blocks the room since it's
                // mid-payment. Only "cancelled" bookings free the room up.
                ->whereNotIn('status', ['cancelled'])
                ->where(function ($query) use ($data) {
                    $query->where('check_in_date', '<', $data['check_out_date'])
                        ->where('check_out_date', '>', $data['check_in_date']);
                })
                ->exists();

            if ($isBooked) {
                $unavailableRooms[] = $roomId;
            }
        }

        if (! empty($unavailableRooms)) {
            return response()->json([
                'message' => 'One or more selected rooms are not available for the requested dates.',
                'unavailable_room_ids' => $unavailableRooms,
            ], 409);
        }

        $data['status'] = 'pending';
        $booking = Booking::create($data);
        $booking->rooms()->attach($roomIds);

        /*
         * DEMO / MOCK PAYMENT MODE
         * -------------------------
         * Stripe blocks all API access from a handful of OFAC-sanctioned
         * countries (Syria among them) at the network level, regardless of
         * account setup. When STRIPE_MOCK=true in .env, we skip the real
         * Stripe call entirely and simulate a successful payment right
         * away — this mirrors exactly what StripeWebhookController does
         * when a real payment succeeds (create the deposit Payment row,
         * mark the booking confirmed). Turn this back off (STRIPE_MOCK=
         * false) the moment real Stripe access is available.
         */
        if (config('services.stripe.mock')) {
            Payment::create([
                'booking_id' => $booking->id,
                'invoice_id' => null,
                'amount' => $booking->deposit_amount,
                'type' => 'deposit',
                'method' => 'card',
                'stripe_payment_intent_id' => 'mock_' . $booking->id,
                'paid_at' => now(),
            ]);

            $booking->update(['status' => 'confirmed']);

            return response()->json([
                'booking' => new BookingResource($booking->load('rooms')),
                'payment_url' => config('app.url') . '/payment-success?booking_id=' . $booking->id,
            ], 201);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => (int) round($booking->deposit_amount * 100),
                    'product_data' => [
                        'name' => 'Deposit for booking #' . $booking->id,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            // Tag the session with the booking id so the webhook knows
            // which booking to confirm once payment succeeds.
            'metadata' => [
                'booking_id' => $booking->id,
            ],
            'success_url' => config('app.url') . '/payment-success?booking_id=' . $booking->id,
            'cancel_url' => config('app.url') . '/payment-cancelled?booking_id=' . $booking->id,
        ]);

        return response()->json([
            'booking' => new BookingResource($booking->load('rooms')),
            'payment_url' => $checkoutSession->url,
        ], 201);
    }

    public function show(Booking $booking): JsonResponse
    {
        $booking->load(['user', 'rooms', 'guests', 'actions', 'payments']);

        return response()->json(new BookingResource($booking));
    }

    public function update(UpdateBookingRequest $request, Booking $booking): JsonResponse
    {
        $booking->update($request->validated());

        return response()->json(new BookingResource($booking));
    }

    public function destroy(Booking $booking): JsonResponse
    {
        $booking->delete();

        return response()->json(null, 204);
    }

    /**
     * STEP 3 — The guest cancels a pending or confirmed booking. If it's 2
     * or more days before check-in, we issue a real refund through Stripe
     * for the exact deposit payment recorded, and log it as a "refund"
     * payment. If it's less than 2 days out, the booking is still
     * cancelled, but no refund happens - the deposit is kept.
     */
    public function cancel(Request $request, Booking $booking): JsonResponse
    {
        if (! in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'message' => 'This booking can no longer be cancelled.',
            ], 422);
        }

        $daysUntilCheckIn = now()->diffInDays($booking->check_in_date, false);
        $isEligibleForRefund = $daysUntilCheckIn >= 2;

        if ($isEligibleForRefund) {
            $depositPayment = $booking->payments()
                ->where('type', 'deposit')
                ->whereNotNull('stripe_payment_intent_id')
                ->latest('paid_at')
                ->first();

            if ($depositPayment) {
                // Mock deposits (from STRIPE_MOCK mode) were never charged
                // through Stripe, so there is nothing real to refund there.
                if (config('services.stripe.mock') || str_starts_with($depositPayment->stripe_payment_intent_id, 'mock_')) {
                    Payment::create([
                        'booking_id' => $booking->id,
                        'invoice_id' => null,
                        'amount' => $depositPayment->amount,
                        'type' => 'refund',
                        'method' => 'card',
                        'stripe_payment_intent_id' => $depositPayment->stripe_payment_intent_id,
                        'paid_at' => now(),
                    ]);
                } else {
                    Stripe::setApiKey(config('services.stripe.secret'));

                    Refund::create([
                        'payment_intent' => $depositPayment->stripe_payment_intent_id,
                    ]);

                    Payment::create([
                        'booking_id' => $booking->id,
                        'invoice_id' => null,
                        'amount' => $depositPayment->amount,
                        'type' => 'refund',
                        'method' => 'card',
                        'stripe_payment_intent_id' => $depositPayment->stripe_payment_intent_id,
                        'paid_at' => now(),
                    ]);
                }
            }
        }

        $booking->update(['status' => 'cancelled']);

        BookingAction::create([
            'booking_id' => $booking->id,
            'action_type' => 'cancelled',
            'performed_by' => auth()->id(),
            'reason' => $request->input(
                'reason',
                $isEligibleForRefund ? 'Cancelled by guest - within refund window' : 'Cancelled by guest - outside refund window'
            ),
        ]);

        return response()->json([
            'booking' => new BookingResource($booking),
            'refunded' => $isEligibleForRefund,
        ]);
    }
}
