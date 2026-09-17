<?php

namespace App\Filament\Resources\Staff\Housekeepings\Pages;

use App\Filament\Resources\Staff\Housekeepings\HousekeepingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHousekeeping extends EditRecord
{
    protected static string $resource = HousekeepingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
