<?php

namespace Database\Factories\Booking;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking\Booking;

class GuestFactory extends Factory
{
    public function definition(): array
    {
        $names = [
            'Ali Hassan', 'Maya Farah', 'Karim Sultan', 'Dana Zeidan',
            'Basel Haidar', 'Lina Shihab', 'Wael Murad', 'Joud Issa',
        ];

        return [
            'booking_id' => Booking::factory(),
            'full_name' => fake()->randomElement($names),
            'id_document_type' => fake()->randomElement(['passport', 'national_id']),
            'id_document_number' => fake()->numerify('##########'),
        ];
    }
}
