<?php

namespace Database\Seeders;

use App\Models\Booking\Booking;
use App\Models\Invoice;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    // An invoice only makes sense once the guest has checked in (or out) -
    // that's when room charges actually start accruing. room_charge is taken
    // directly from the booking's own total_price so the two stay consistent,
    // and services_charge sums up any room_service orders tied to it.
    public function run(): void
    {
        $eligibleBookings = Booking::whereIn('status', ['checked_in', 'checked_out'])
            ->with('roomServices')
            ->get();

        foreach ($eligibleBookings as $booking) {
            $servicesCharge = $booking->roomServices->sum(fn ($service) => $service->price * $service->quantity);

            Invoice::factory()->create([
                'booking_id' => $booking->id,
                'room_charge' => $booking->total_price,
                'services_charge' => $servicesCharge,
                'total_amount' => $booking->total_price + $servicesCharge,
            ]);
        }
    }
}
