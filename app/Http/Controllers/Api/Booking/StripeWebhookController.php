<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use App\Models\Booking\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Webhook;
use UnexpectedValueException;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    /**
     * STEP 2 of the reservation flow.
     *
     * Stripe calls this endpoint automatically when a checkout session's
     * payment succeeds. We verify the request really came from Stripe
     * (using the signing secret), pull the booking_id we tagged the session
     * with in store(), record the deposit payment, and confirm the booking.
     */
 public function handle(Request $request): JsonResponse
{
    $payload = $request->getContent();
    $signature = $request->header('Stripe-Signature');
    $webhookSecret = config('services.stripe.webhook_secret');

    try {
        $event = Webhook::constructEvent(
            $payload,
            $signature,
            $webhookSecret
        );
    } catch (UnexpectedValueException|SignatureVerificationException $e) {
        return response()->json([
            'message' => 'Invalid webhook payload.'
        ], 400);
    }

    if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;
        $bookingId = $session->metadata->booking_id ?? null;

        $booking = Booking::find($bookingId);

        if ($booking && $booking->status === 'pending') {
            Payment::create([
                'booking_id' => $booking->id,
                'invoice_id' => null,
                'amount' => $booking->deposit_amount,
                'type' => 'deposit',
                'method' => 'card',
                'stripe_payment_intent_id' => $session->payment_intent,
                'paid_at' => now(),
            ]);

            $booking->update([
                'status' => 'confirmed'
            ]);
        }
    }

    return response()->json([
        'received' => true
    ]);
}
}
