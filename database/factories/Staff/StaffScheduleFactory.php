<?php

namespace Database\Factories\Staff;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class StaffScheduleFactory extends Factory
{
    public function definition(): array
    {
        $shiftDate = fake()->dateTimeBetween('-7 days', '+14 days');
        $shiftStart = (clone $shiftDate)->setTime(8, 0);
        $shiftEnd = (clone $shiftDate)->setTime(16, 0);

        return [
            'user_id' => User::factory(),
            'shift_date' => $shiftDate,
            'shift_start' => $shiftStart,
            'shift_end' => $shiftEnd,
            'status' => fake()->randomElement(['scheduled', 'completed', 'missed']),
        ];
    }
}
