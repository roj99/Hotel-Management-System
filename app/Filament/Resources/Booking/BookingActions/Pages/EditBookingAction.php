<?php

namespace App\Filament\Resources\Booking\BookingActions\Pages;

use App\Filament\Resources\Booking\BookingActions\BookingActionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBookingAction extends EditRecord
{
    protected static string $resource = BookingActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
