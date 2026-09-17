<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'manager', 'receptionist', 'housekeeping', 'room_service', 'guest'];

        foreach ($roles as $roleName) {
            Role::factory()->create(['name' => $roleName]);
        }
    }
}
