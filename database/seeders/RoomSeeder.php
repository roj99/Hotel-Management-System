<?php

namespace Database\Seeders;

use App\Models\Hotel\RoomsType;
use App\Models\Hotel\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    // 4 rooms per room type, spread across floors 1-5, all starting "available".
    public function run(): void
    {
        $roomTypes = RoomsType::all();
        $floor = 1;
        $roomOnFloor = 1;

        foreach ($roomTypes as $roomType) {
            for ($i = 0; $i < 4; $i++) {
                Room::factory()->create([
                    'room_type_id' => $roomType->id,
                    'room_number' => $floor . str_pad($roomOnFloor, 2, '0', STR_PAD_LEFT),
                    'floor' => $floor,
                    'status' => 'available',
                ]);

                $roomOnFloor++;
                if ($roomOnFloor > 20) {
                    $roomOnFloor = 1;
                    $floor++;
                    if ($floor > 5) {
                        $floor = 1;
                    }
                }
            }
        }
    }
}
