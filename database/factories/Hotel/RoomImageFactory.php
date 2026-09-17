<?php

namespace Database\Factories\Hotel;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Hotel\RoomsType;

class RoomImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_type_id' => RoomsType::factory(),
            'image_path' => 'room-images/' . fake()->uuid() . '.jpg',
        ];
    }
}
