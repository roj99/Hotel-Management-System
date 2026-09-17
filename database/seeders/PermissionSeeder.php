<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage_users', 'manage_rooms', 'manage_bookings',
            'view_reports', 'manage_room_service', 'manage_housekeeping',
            'manage_maintenance', 'view_own_bookings',
        ];

        foreach ($permissions as $permissionName) {
            Permission::factory()->create(['name' => $permissionName]);
        }
    }
}
