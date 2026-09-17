<?php

namespace Database\Factories\Staff;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Hotel\Room;
use App\Models\User;

class LostFoundItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_id' => Room::factory(),
            'found_by' => User::factory(),
            'item_description' => fake()->randomElement([
                'Phone charger', 'Sunglasses', 'Book', 'Wrist watch', 'Small wallet',
            ]),
            'storage_location' => 'Front desk - lost and found drawer',
            'status' => fake()->randomElement(['stored', 'returned', 'disposed']),
            'found_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
