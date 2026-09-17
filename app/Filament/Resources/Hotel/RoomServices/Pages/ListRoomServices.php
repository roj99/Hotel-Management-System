<?php

namespace App\Filament\Resources\Hotel\RoomServices\Pages;

use App\Filament\Resources\Hotel\RoomServices\RoomServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoomServices extends ListRecords
{
    protected static string $resource = RoomServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
