<?php

namespace App\Filament\Resources\Staff\Housekeepings;

use App\Filament\Resources\Staff\Housekeepings\Pages\CreateHousekeeping;
use App\Filament\Resources\Staff\Housekeepings\Pages\EditHousekeeping;
use App\Filament\Resources\Staff\Housekeepings\Pages\ListHousekeepings;
use App\Filament\Resources\Staff\Housekeepings\Schemas\HousekeepingForm;
use App\Filament\Resources\Staff\Housekeepings\Tables\HousekeepingsTable;
use App\Models\Staff\Housekeeping;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Concerns\RestrictedToRoles;

class HousekeepingResource extends Resource
{
    use RestrictedToRoles;
    protected static ?string $model = Housekeeping::class;
    protected static string|UnitEnum|null $navigationGroup = 'Processes';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Housekeeping';

    protected static function allowedRoles(): array
{
    return ['housekeeping'];
}

    public static function form(Schema $schema): Schema
    {
        return HousekeepingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HousekeepingsTable::configure($table);
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
            'index' => ListHousekeepings::route('/'),
            'create' => CreateHousekeeping::route('/create'),
            'edit' => EditHousekeeping::route('/{record}/edit'),
        ];
    }
}
