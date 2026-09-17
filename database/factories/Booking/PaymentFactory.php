<?php

namespace Database\Factories\Booking;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking\Booking;
use App\Models\Invoice;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'invoice_id' => Invoice::factory(),
            'amount' => fake()->randomElement([50000, 100000, 150000, 200000]),
            'type' => fake()->randomElement(['deposit', 'full_payment', 'refund']),
            'method' => fake()->randomElement(['cash', 'card', 'bank_transfer']),
            'paid_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ];
    }
}
