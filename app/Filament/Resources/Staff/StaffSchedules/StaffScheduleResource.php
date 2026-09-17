<?php

namespace App\Filament\Resources\Staff\StaffSchedules;

use App\Filament\Resources\Staff\StaffSchedules\Pages\CreateStaffSchedule;
use App\Filament\Resources\Staff\StaffSchedules\Pages\EditStaffSchedule;
use App\Filament\Resources\Staff\StaffSchedules\Pages\ListStaffSchedules;
use App\Filament\Resources\Staff\StaffSchedules\Schemas\StaffScheduleForm;
use App\Filament\Resources\Staff\StaffSchedules\Tables\StaffSchedulesTable;
use App\Models\Staff\StaffSchedule;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Concerns\RestrictedToRoles;

class StaffScheduleResource extends Resource
{
    use RestrictedToRoles;
    protected static ?string $model = StaffSchedule::class;
    protected static string|UnitEnum|null $navigationGroup = 'Processes';
    protected static ?int $navigationSort = 1;


    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'StaffSchedule';

    public static function form(Schema $schema): Schema
    {
        return StaffScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffSchedulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStaffSchedules::route('/'),
            'create' => CreateStaffSchedule::route('/create'),
            'edit' => EditStaffSchedule::route('/{record}/edit'),
        ];
    }
}
