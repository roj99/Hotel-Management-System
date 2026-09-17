<?php

namespace Database\Factories\Hotel;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Hotel\RoomsType;

class RoomFactory extends Factory
{
    public function definition(): array
    {
        $floor = fake()->numberBetween(1, 5);
        $roomOnFloor = fake()->numberBetween(1, 20);

        return [
            'room_type_id' => RoomsType::factory(),
            'room_number' => $floor . str_pad($roomOnFloor, 2, '0', STR_PAD_LEFT),
            'status' => fake()->randomElement(['available', 'occupied', 'maintenance', 'cleaning']),
            'floor' => $floor,
        ];
    }
}
