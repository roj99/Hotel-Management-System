<?php

namespace App\Filament\Resources\Hotel\RoomsTypes;

use App\Filament\Resources\Hotel\RoomsTypes\Pages\CreateRoomsType;
use App\Filament\Resources\Hotel\RoomsTypes\Pages\EditRoomsType;
use App\Filament\Resources\Hotel\RoomsTypes\Pages\ListRoomsTypes;
use App\Filament\Resources\Hotel\RoomsTypes\Schemas\RoomsTypeForm;
use App\Filament\Resources\Hotel\RoomsTypes\Tables\RoomsTypesTable;
use App\Models\Hotel\RoomsType;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Concerns\RestrictedToRoles;

class RoomsTypeResource extends Resource
{
    use RestrictedToRoles;
    protected static ?string $model = RoomsType::class;
    protected static string|UnitEnum|null $navigationGroup = 'Hotel';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'RoomsType';

    public static function form(Schema $schema): Schema
    {
        return RoomsTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoomsTypesTable::configure($table);
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
            'index' => ListRoomsTypes::route('/'),
            'create' => CreateRoomsType::route('/create'),
            'edit' => EditRoomsType::route('/{record}/edit'),
        ];
    }
}
