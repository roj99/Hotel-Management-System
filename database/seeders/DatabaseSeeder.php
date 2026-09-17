<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RoleHasPermissionSeeder::class,

            RoomsTypeSeeder::class,
            AmenitySeeder::class,
            RoomTypeHasAmenitySeeder::class,

            UserSeeder::class,

            RoomImageSeeder::class,
            RoomSeeder::class,
            RoomPriceSeeder::class,

            BookingSeeder::class,
            GuestSeeder::class,

            StaffScheduleSeeder::class,
            HousekeepingSeeder::class,
            MaintenanceReportSeeder::class,
            LostFoundItemSeeder::class,

            RoomServiceSeeder::class,
            InvoiceSeeder::class,
            PaymentSeeder::class,
            ReviewSeeder::class,
            BookingActionSeeder::class,
        ]);
    }
}
