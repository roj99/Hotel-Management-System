<?php

namespace Database\Seeders;

use App\Models\Booking\Booking;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    // Only guests who actually completed their stay (checked_out) can leave a review.
    public function run(): void
    {
        $completedBookings = Booking::where('status', 'checked_out')->get();

        foreach ($completedBookings as $booking) {
            Review::factory()->create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
            ]);
        }
    }
}
