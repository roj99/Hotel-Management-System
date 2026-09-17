<?php

namespace Database\Factories\Staff;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Hotel\Room;
use App\Models\User;

class MaintenanceReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_id' => Room::factory(),
            'reported_by' => User::factory(),
            'issue_description' => fake()->randomElement([
                'Water leak in the bathroom', 'Air conditioner not working', 'Light bulb needs replacing',
                'Closet door is broken', 'Water heater malfunction',
            ]),
            'severity' => fake()->randomElement(['low', 'medium', 'high', 'critical']),
            'status' => fake()->randomElement(['open', 'in_progress', 'resolved']),
            'reported_at' => fake()->dateTimeBetween('-14 days', 'now'),
        ];
    }
}
