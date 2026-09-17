<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel\Room;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Report 1:
     * Show room bookings between two dates.
     */
    public function roomBookingReport(
        Request $request,
        Room $room
    ): JsonResponse {
        $request->validate([
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
        ]);

        $bookings = $room->bookings()
            ->with('user')
            ->whereDate('check_in_date', '<=', $request->to)
            ->whereDate('check_out_date', '>=', $request->from)
            ->orderBy('check_in_date')
            ->get();

        return response()->json([
            'room' => [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'status' => $room->status,
            ],

            'period' => [
                'from' => $request->from,
                'to' => $request->to,
            ],

            'bookings_count' => $bookings->count(),

            'bookings' => $bookings->map(function ($booking) {
                return [
                    'booking_id' => $booking->id,
                    'customer_name' => $booking->user?->full_name,
                    'check_in_date' => $booking->check_in_date,
                    'check_out_date' => $booking->check_out_date,
                    'status' => $booking->status,
                ];
            }),
        ]);
    }


    /**
     * Report 2:
     * Show all invoices of a customer.
     */
    public function customerInvoices(User $user): JsonResponse
    {
        $bookings = $user->bookings()
            ->with('invoice')
            ->orderBy('check_in_date')
            ->get();

        $invoices = $bookings
            ->filter(fn ($booking) => $booking->invoice !== null)
            ->map(function ($booking) {
                return [
                    'booking_id' => $booking->id,
                    'invoice_id' => $booking->invoice->id,
                    'room_charge' => $booking->invoice->room_charge,
                    'services_charge' => $booking->invoice->services_charge,
                    'total_amount' => $booking->invoice->total_amount,
                    'issued_at' => $booking->invoice->issued_at,
                ];
            })
            ->values();

        return response()->json([
            'customer' => [
                'id' => $user->id,
                'name' => $user->full_name,
                'email' => $user->email,
            ],

            'bookings_count' => $bookings->count(),

            'invoices_count' => $invoices->count(),

            'invoices' => $invoices,
        ]);
    }


    /**
     * Report 3:
     * Show the most requested rooms.
     */
    public function mostRequestedRooms(): JsonResponse
    {
        $rooms = Room::with('roomType')
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->get();

        return response()->json([
            'rooms' => $rooms->map(function ($room) {
                return [
                    'room_id' => $room->id,
                    'room_number' => $room->room_number,
                    'room_type' => $room->roomType?->name,
                    'bookings_count' => $room->bookings_count,
                ];
            }),
        ]);
    }
}
