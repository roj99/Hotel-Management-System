<?php

namespace Database\Seeders;

use App\Models\Hotel\RoomsType;
use App\Models\Hotel\RoomImage;
use Illuminate\Database\Seeder;

class RoomImageSeeder extends Seeder
{
    public function run(): void
    {
        // Two images per room type
        RoomsType::all()->each(function ($roomType) {
            RoomImage::factory()->count(2)->create(['room_type_id' => $roomType->id]);
        });
    }
}
