<?php

namespace Database\Factories\Hotel;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking\Booking;

class RoomServiceFactory extends Factory
{
    public function definition(): array
    {
        $items = [
            ['name' => 'Full Breakfast', 'price' => 15000],
            ['name' => 'Dinner Set', 'price' => 25000],
            ['name' => 'Fresh Juice', 'price' => 5000],
            ['name' => 'Extra Towels', 'price' => 0],
            ['name' => 'Laundry Service', 'price' => 10000],
        ];
        $item = fake()->randomElement($items);

        return [
            'booking_id' => Booking::factory(),
            'item_description' => $item['name'],
            'quantity' => fake()->numberBetween(1, 3),
            'price' => $item['price'],
            'status' => fake()->randomElement(['pending', 'preparing', 'delivered', 'cancelled']),
            'ordered_at' => fake()->dateTimeBetween('-5 days', 'now'),
        ];
    }
}

