<?php

namespace App\Filament\Resources\Booking\BookingActions\Pages;

use App\Filament\Resources\Booking\BookingActions\BookingActionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookingActions extends ListRecords
{
    protected static string $resource = BookingActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
