<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Hotel\Room;
use App\Models\Staff\MaintenanceReport;
use Illuminate\Database\Seeder;

class MaintenanceReportSeeder extends Seeder
{
    public function run(): void
    {
        $staff = User::whereHas('role', fn ($q) => $q->where('name', '!=', 'guest'))->get();
        $rooms = Room::inRandomOrder()->take(4)->get();

        foreach ($rooms as $room) {
            MaintenanceReport::factory()->create([
                'room_id' => $room->id,
                'reported_by' => $staff->random()->id,
            ]);
        }
    }
}
