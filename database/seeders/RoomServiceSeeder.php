<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Booking\Booking;
use App\Models\Hotel\RoomService;
use Illuminate\Database\Seeder;

class RoomServiceSeeder extends Seeder
{
    // Only bookings where the guest is actually staying (checked_in) or already
    // finished (checked_out) can have room service orders.
    public function run(): void
    {
        $roomServiceStaff = User::whereHas('role', fn ($q) => $q->where('name', 'room_service'))->get();
        $eligibleBookings = Booking::whereIn('status', ['checked_in', 'checked_out'])->get();

        foreach ($eligibleBookings as $booking) {
            RoomService::factory()->create([
                'booking_id' => $booking->id,
                'handled_by' => $roomServiceStaff->random()->id,
                'status' => $booking->status === 'checked_out' ? 'delivered' : 'preparing',
            ]);
        }
    }
}
