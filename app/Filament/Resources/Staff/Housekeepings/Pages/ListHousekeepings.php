<?php

namespace App\Filament\Resources\Staff\Housekeepings\Pages;

use App\Filament\Resources\Staff\Housekeepings\HousekeepingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHousekeepings extends ListRecords
{
    protected static string $resource = HousekeepingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
