<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Hotel\Room;
use App\Models\Staff\LostFoundItem;
use Illuminate\Database\Seeder;

class LostFoundItemSeeder extends Seeder
{
    public function run(): void
    {
        $housekeepingStaff = User::whereHas('role', fn ($q) => $q->where('name', 'housekeeping'))->get();
        $rooms = Room::inRandomOrder()->take(3)->get();

        foreach ($rooms as $room) {
            LostFoundItem::factory()->create([
                'room_id' => $room->id,
                'found_by' => $housekeepingStaff->random()->id,
            ]);
        }
    }
}
