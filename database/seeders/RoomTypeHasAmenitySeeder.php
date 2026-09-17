<?php

namespace Database\Seeders;

use App\Models\Hotel\RoomsType;
use App\Models\Amenity;
use Illuminate\Database\Seeder;

class RoomTypeHasAmenitySeeder extends Seeder
{
    // Higher-tier room types get more amenities, matching their description.
    public function run(): void
    {
        $map = [
            'Single Room' => ['Free WiFi', 'Air Conditioning', 'TV'],
            'Double Room' => ['Free WiFi', 'Air Conditioning', 'TV', 'Mini Bar'],
            'Twin Room' => ['Free WiFi', 'Air Conditioning', 'TV', 'Work Desk'],
            'Family Room' => ['Free WiFi', 'Air Conditioning', 'TV', 'Mini Bar', 'Balcony'],
            'Suite' => ['Free WiFi', 'Air Conditioning', 'TV', 'Mini Bar', 'Balcony', 'Safe Box', 'Room Service'],
            'Deluxe Suite' => ['Free WiFi', 'Air Conditioning', 'TV', 'Mini Bar', 'Balcony', 'Safe Box', 'Room Service', 'Jacuzzi', 'Sea View'],
        ];

        foreach ($map as $typeName => $amenityNames) {
            $roomType = RoomsType::where('name', $typeName)->first();
            $amenityIds = Amenity::whereIn('name', $amenityNames)->pluck('id');
            $roomType->amenities()->attach($amenityIds);
        }
    }
}
