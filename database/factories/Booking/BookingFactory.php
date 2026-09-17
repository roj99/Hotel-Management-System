<?php

namespace Database\Factories\Booking;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('-10 days', '+20 days');
        $nights = fake()->numberBetween(1, 5);
        $checkOut = (clone $checkIn)->modify("+{$nights} days");
        $pricePerNight = fake()->randomElement([50000, 75000, 100000]);
        $total = $pricePerNight * $nights;

        return [
            'user_id' => User::factory(),
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'status' => fake()->randomElement(['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled']),
            'total_price' => $total,
            'deposit_amount' => $total * 0.3,
            'id_document_type' => fake()->randomElement(['passport', 'national_id']),
            'id_document_number' => fake()->numerify('##########'),
            'guests_count' => fake()->numberBetween(1, 4),
        ];
    }
}
