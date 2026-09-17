<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking\Booking;
use App\Models\User;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'user_id' => User::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->randomElement([
                'Excellent stay and premium service.', 'The room was clean and staff were very helpful.',
                'Good overall experience with a few minor notes.',
                'Great location and the breakfast was amazing.', null,
            ]),
        ];
    }
}
