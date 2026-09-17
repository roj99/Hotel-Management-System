<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'manage_users', 'manage_rooms', 'manage_bookings',
                'view_reports', 'manage_room_service', 'manage_housekeeping',
                'manage_maintenance', 'view_own_bookings',
            ]),
        ];
    }
}
