<?php

namespace Database\Factories\Booking;

use App\Models\Booking\BookingAction;
use App\Models\Booking\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingAction>
 */
class BookingActionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actionType = fake()->randomElement(['reviewed', 'cancelled']);

        return [
            'booking_id' => Booking::factory(),
            'action_type' => $actionType,
            'performed_by' => User::factory(),
            'reason' => $actionType === 'cancelled'
                ? fake()->randomElement(['Change of travel plans', 'Scheduling conflict', 'Emergency circumstances'])
                : null,
            'performed_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ];
    }
}
