<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Staff\StaffSchedule;
use Illuminate\Database\Seeder;

class StaffScheduleSeeder extends Seeder
{
    // Every staff member (everyone except guests) gets a few shifts.
    public function run(): void
    {
        $staff = User::whereHas('role', fn ($q) => $q->where('name', '!=', 'guest'))->get();

        foreach ($staff as $member) {
            StaffSchedule::factory()->count(3)->create(['user_id' => $member->id]);
        }
    }
}
