<?php

namespace Database\Seeders;

use App\Models\Hotel\RoomsType;
use App\Models\Hotel\RoomPrice;
use Illuminate\Database\Seeder;

class RoomPriceSeeder extends Seeder
{
    // Price scales with room capacity, so bigger rooms cost more.
    public function run(): void
    {
        $prices = [
            'Single Room' => 50000,
            'Double Room' => 75000,
            'Twin Room' => 75000,
            'Family Room' => 120000,
            'Suite' => 150000,
            'Deluxe Suite' => 200000,
        ];

        foreach ($prices as $typeName => $price) {
            $roomType = RoomsType::where('name', $typeName)->first();

            RoomPrice::factory()->create([
                'room_type_id' => $roomType->id,
                'start_date' => now()->subMonth(),
                'end_date' => now()->addMonths(3),
                'price_per_night' => $price,
            ]);
        }
    }
}
