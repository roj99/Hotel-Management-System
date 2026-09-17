<?php

namespace App\Filament\Resources\Booking\BookingActions\Pages;

use App\Filament\Resources\Booking\BookingActions\BookingActionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBookingAction extends CreateRecord
{
    protected static string $resource = BookingActionResource::class;
}
