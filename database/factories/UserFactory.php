<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $names = [
            'Ahmad Khatib', 'Mohammad Ali', 'Sara Hassan', 'Layla Shami',
            'Yousef Najjar', 'Rahaf Asaad', 'Khaled Hamawi', 'Nour Eddin',
            'Reem Qassem', 'Omar Jundi', 'Heba Abed', 'Ziad Halabi',
            'Nadine Farouk', 'Samer Kassar', 'Dalia Youssef', 'Firas Hamdan',
            'Maya Suleiman', 'Tarek Bitar', 'Lubna Saleh', 'Karim Deeb',
            'Rana Zaher', 'Bilal Antar', 'Salma Kanaan', 'Fadi Rahal',
        ];

        return [
            'role_id' => Role::factory(),
            'full_name' => fake()->randomElement($names),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->e164PhoneNumber(),
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ];
    }
}
