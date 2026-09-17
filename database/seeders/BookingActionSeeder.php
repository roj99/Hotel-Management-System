<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingAction;
use Illuminate\Database\Seeder;

class BookingActionSeeder extends Seeder
{
    // A "cancelled" action only makes sense on a booking that IS cancelled,
    // and a "reviewed" action only on a booking staff actually confirmed.
    public function run(): void
    {
        $staff = User::whereHas('role', fn ($q) => $q->where('name', 'receptionist'))->get();

        Booking::where('status', 'cancelled')->get()->each(function ($booking) use ($staff) {
            BookingAction::factory()->create([
                'booking_id' => $booking->id,
                'action_type' => 'cancelled',
                'performed_by' => $staff->random()->id,
            ]);
        });

        Booking::where('status', 'confirmed')->get()->each(function ($booking) use ($staff) {
            BookingAction::factory()->create([
                'booking_id' => $booking->id,
                'action_type' => 'reviewed',
                'performed_by' => $staff->random()->id,
                'reason' => null,
            ]);
        });
    }
}
