<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['admin', 'manager', 'receptionist', 'housekeeping', 'room_service', 'guest']),
        ];
    }
}
