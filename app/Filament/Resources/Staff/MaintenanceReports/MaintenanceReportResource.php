<?php

namespace App\Filament\Resources\Staff\MaintenanceReports;

use App\Filament\Resources\Staff\MaintenanceReports\Pages\CreateMaintenanceReport;
use App\Filament\Resources\Staff\MaintenanceReports\Pages\EditMaintenanceReport;
use App\Filament\Resources\Staff\MaintenanceReports\Pages\ListMaintenanceReports;
use App\Filament\Resources\Staff\MaintenanceReports\Schemas\MaintenanceReportForm;
use App\Filament\Resources\Staff\MaintenanceReports\Tables\MaintenanceReportsTable;
use App\Models\Staff\MaintenanceReport;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Concerns\RestrictedToRoles;

class MaintenanceReportResource extends Resource
{
    use RestrictedToRoles;
    protected static ?string $model = MaintenanceReport::class;
    protected static string|UnitEnum|null $navigationGroup = 'Processes';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'MaintenanceReport';

    public static function form(Schema $schema): Schema
    {
        return MaintenanceReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaintenanceReportsTable::configure($table);
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
            'index' => ListMaintenanceReports::route('/'),
            'create' => CreateMaintenanceReport::route('/create'),
            'edit' => EditMaintenanceReport::route('/{record}/edit'),
        ];
    }
}
