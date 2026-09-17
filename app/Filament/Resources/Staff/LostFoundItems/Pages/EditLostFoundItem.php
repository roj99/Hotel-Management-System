<?php

namespace App\Filament\Resources\Staff\LostFoundItems\Pages;

use App\Filament\Resources\Staff\LostFoundItems\LostFoundItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLostFoundItem extends EditRecord
{
    protected static string $resource = LostFoundItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
