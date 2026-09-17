<?php

namespace App\Filament\Resources\Booking\Bookings\Pages;

use App\Filament\Resources\Booking\Bookings\BookingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;
}
