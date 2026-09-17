<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Hotel\Room;
use App\Models\Staff\Housekeeping;
use Illuminate\Database\Seeder;

class HousekeepingSeeder extends Seeder
{
    public function run(): void
    {
        $housekeepingStaff = User::whereHas('role', fn ($q) => $q->where('name', 'housekeeping'))->get();
        $rooms = Room::inRandomOrder()->take(6)->get();

        foreach ($rooms as $room) {
            Housekeeping::factory()->create([
                'room_id' => $room->id,
                'staff_id' => $housekeepingStaff->random()->id,
            ]);
        }
    }
}
