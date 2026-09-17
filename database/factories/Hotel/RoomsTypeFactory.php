<?php

namespace Database\Factories\Hotel;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoomsTypeFactory extends Factory
{
    public function definition(): array
    {
        $types = [
            ['name' => 'Single Room',
            'description' => 'A cozy room with a single bed, ideal for solo travelers.',
            'capacity' => 1],
            ['name' => 'Double Room',
            'description' => 'A comfortable room with one large bed, ideal for couples.',
            'capacity' => 2],
            ['name' => 'Twin Room',
            'description' => 'A room with two separate beds, ideal for friends or colleagues.',
            'capacity' => 2],
            ['name' => 'Family Room',
            'description' => 'A spacious room designed to accommodate families.',
            'capacity' => 4],
            ['name' => 'Suite',
            'description' => 'A luxurious suite with a separate living area and bedroom.',
            'capacity' => 3],
            ['name' => 'Deluxe Suite',
             'description' => 'A premium suite with extra space and a scenic view.',
             'capacity' => 4],
        ];

        return fake()->randomElement($types);
    }
}
