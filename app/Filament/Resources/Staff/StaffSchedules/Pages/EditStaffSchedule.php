<?php

namespace App\Filament\Resources\Staff\StaffSchedules\Pages;

use App\Filament\Resources\Staff\StaffSchedules\StaffScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStaffSchedule extends EditRecord
{
    protected static string $resource = StaffScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
