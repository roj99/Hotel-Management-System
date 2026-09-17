<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleHasPermissionSeeder extends Seeder
{
    // Maps each role to the permissions it should logically have.
    public function run(): void
    {
        $map = [
            'admin' => ['manage_users', 'manage_rooms', 'manage_bookings', 'view_reports', 'manage_room_service', 'manage_housekeeping', 'manage_maintenance'],
            'manager' => ['manage_rooms', 'manage_bookings', 'view_reports'],
            'receptionist' => ['manage_bookings'],
            'housekeeping' => ['manage_housekeeping', 'manage_maintenance'],
            'room_service' => ['manage_room_service'],
            'guest' => ['view_own_bookings'],
        ];

        foreach ($map as $roleName => $permissionNames) {
            $role = Role::where('name', $roleName)->first();
            $permissionIds = Permission::whereIn('name', $permissionNames)->pluck('id');
            $role->permissions()->attach($permissionIds);
        }
    }
}
