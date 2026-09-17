<?php

namespace Database\Seeders;

use App\Models\Booking\Booking;
use App\Models\Booking\Guest;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        // Add exactly (guests_count - 1) companions per booking,
        // since the main booker isn't counted as a separate "guest" row
        Booking::all()->each(function ($booking) {
            $companionsNeeded = $booking->guests_count - 1;

            if ($companionsNeeded > 0) {
                Guest::factory()->count($companionsNeeded)->create(['booking_id' => $booking->id]);
            }
        });
    }
}
