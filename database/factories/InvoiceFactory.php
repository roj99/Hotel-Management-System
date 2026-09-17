<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking\Booking;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        $roomCharge = fake()->randomElement([100000, 150000, 200000, 300000]);
        $servicesCharge = fake()->randomElement([0, 15000, 25000, 40000]);

        return [
            'booking_id' => Booking::factory(),
            'room_charge' => $roomCharge,
            'services_charge' => $servicesCharge,
            'total_amount' => $roomCharge + $servicesCharge,
            'issued_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ];
    }
}
