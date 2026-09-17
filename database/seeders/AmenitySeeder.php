<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            'Free WiFi', 'Air Conditioning', 'TV', 'Mini Bar',
            'Balcony', 'Safe Box', 'Room Service', 'Jacuzzi',
            'Sea View', 'Work Desk',
        ];

        foreach ($amenities as $name) {
            Amenity::factory()->create(['name' => $name]);
        }
    }
}
