<?php

namespace App\Filament\Resources\Staff\StaffSchedules\Pages;

use App\Filament\Resources\Staff\StaffSchedules\StaffScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStaffSchedules extends ListRecords
{
    protected static string $resource = StaffScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
