<?php

namespace App\Filament\Resources\Hotel\RoomPrices\Pages;

use App\Filament\Resources\Hotel\RoomPrices\RoomPriceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoomPrice extends EditRecord
{
    protected static string $resource = RoomPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
