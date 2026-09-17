<?php

namespace App\Filament\Resources\Hotel\RoomsTypes\Pages;

use App\Filament\Resources\Hotel\RoomsTypes\RoomsTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoomsType extends EditRecord
{
    protected static string $resource = RoomsTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
