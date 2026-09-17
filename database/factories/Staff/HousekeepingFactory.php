<?php

namespace Database\Factories\Staff;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Hotel\Room;
use App\Models\User;

class HousekeepingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_id' => Room::factory(),
            'staff_id' => User::factory(),
            'started_at' => fake()->dateTimeBetween('-2 days', 'now'),
            'finished_at' => fake()->optional(0.7)->dateTimeBetween('-1 day', 'now'),
            'notes' => fake()->optional(0.3)->randomElement([
                'Room needs a deep clean', 'Linens replaced', 'No issues found',
            ]),
        ];
    }
}
