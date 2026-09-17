<?php

namespace App\Filament\Resources\Booking\Guests\Pages;

use App\Filament\Resources\Booking\Guests\GuestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGuest extends CreateRecord
{
    protected static string $resource = GuestResource::class;
}
