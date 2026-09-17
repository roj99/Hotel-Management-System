<?php

namespace Database\Factories;

use App\Models\Amenity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Amenity>
 */
class AmenityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
              'name' => fake()->randomElement([
                'Free WiFi', 'Air Conditioning', 'TV', 'Mini Bar',
                'Balcony', 'Safe Box', 'Room Service', 'Jacuzzi',
                'Sea View', 'Work Desk',
            ]),
        ];
    }
}
