<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // One admin
        User::factory()->create([
            'role_id' => Role::where('name', 'admin')->first()->id,
            'full_name' => 'Faisal Marwan',
            'email' => 'admin@hotel.test',
        ]);

        // One hotel manager
        User::factory()->create([
            'role_id' => Role::where('name', 'manager')->first()->id,
            'full_name' => 'Nadine Farouk',
            'email' => 'manager@hotel.test',
        ]);

        // Two receptionists
        User::factory()->create([
            'role_id' => Role::where('name', 'receptionist')->first()->id,
            'full_name' => 'Ahmad Khatib',
            'email' => 'receptionist1@hotel.test',
        ]);
        User::factory()->create([
            'role_id' => Role::where('name', 'receptionist')->first()->id,
            'full_name' => 'Sara Hassan',
            'email' => 'receptionist2@hotel.test',
        ]);

        // Two housekeeping staff
        User::factory()->create([
            'role_id' => Role::where('name', 'housekeeping')->first()->id,
            'full_name' => 'Layla Shami',
            'email' => 'housekeeping1@hotel.test',
        ]);
        User::factory()->create([
            'role_id' => Role::where('name', 'housekeeping')->first()->id,
            'full_name' => 'Khaled Hamawi',
            'email' => 'housekeeping2@hotel.test',
        ]);

        // Two room service staff
        User::factory()->create([
            'role_id' => Role::where('name', 'room_service')->first()->id,
            'full_name' => 'Yousef Najjar',
            'email' => 'roomservice1@hotel.test',
        ]);
        User::factory()->create([
            'role_id' => Role::where('name', 'room_service')->first()->id,
            'full_name' => 'Rahaf Asaad',
            'email' => 'roomservice2@hotel.test',
        ]);

        // Eight guest users (customers)
        $guestRoleId = Role::where('name', 'guest')->first()->id;
        User::factory()->count(8)->create(['role_id' => $guestRoleId]);
    }
}
