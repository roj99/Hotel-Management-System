<?php

namespace Database\Factories\Hotel;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Hotel\RoomsType;

class RoomPriceFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 month', 'now');

        return [
            'room_type_id' => RoomsType::factory(),
            'start_date' => $start,
            'end_date' => (clone $start)->modify('+3 months'),
            'price_per_night' => fake()->randomElement([50000, 75000, 100000, 150000, 200000]),
        ];
    }
}
