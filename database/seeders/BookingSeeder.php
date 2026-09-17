<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Hotel\Room;
use App\Models\Booking\Booking;
use App\Models\Hotel\RoomPrice;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    // Creates one booking per guest user, linked to a real room via booking_rooms,
    // with total_price computed from that room's actual nightly price.
    public function run(): void
    {
        $guests = User::whereHas('role', fn ($q) => $q->where('name', 'guest'))->get();
        $rooms = Room::all();

        $statuses = ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'];

        foreach ($guests as $index => $guest) {
            $room = $rooms->random();
            $roomPrice = RoomPrice::where('room_type_id', $room->room_type_id)->first();
            $pricePerNight = $roomPrice ? $roomPrice->price_per_night : 50000;

            $checkIn = now()->addDays($index - 4);
            $nights = rand(1, 5);
            $checkOut = (clone $checkIn)->addDays($nights);
            $total = $pricePerNight * $nights;
            $status = $statuses[$index % count($statuses)];

            $booking = Booking::factory()->create([
                'user_id' => $guest->id,
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'status' => $status,
                'total_price' => $total,
                'deposit_amount' => $total * 0.3,
            ]);

            $booking->rooms()->attach($room->id);
        }
    }
}
