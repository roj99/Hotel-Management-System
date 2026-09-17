<?php

namespace App\Filament\Resources\Hotel\RoomImages\Pages;

use App\Filament\Resources\Hotel\RoomImages\RoomImageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoomImage extends EditRecord
{
    protected static string $resource = RoomImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
