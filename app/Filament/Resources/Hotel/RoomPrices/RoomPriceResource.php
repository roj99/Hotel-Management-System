<?php

namespace App\Filament\Resources\Hotel\RoomPrices;

use App\Filament\Resources\Hotel\RoomPrices\Pages\CreateRoomPrice;
use App\Filament\Resources\Hotel\RoomPrices\Pages\EditRoomPrice;
use App\Filament\Resources\Hotel\RoomPrices\Pages\ListRoomPrices;
use App\Filament\Resources\Hotel\RoomPrices\Schemas\RoomPriceForm;
use App\Filament\Resources\Hotel\RoomPrices\Tables\RoomPricesTable;
use App\Models\Hotel\RoomPrice;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Concerns\RestrictedToRoles;


class RoomPriceResource extends Resource
{
    use RestrictedToRoles;
    protected static ?string $model = RoomPrice::class;
    protected static string|UnitEnum|null $navigationGroup = 'Hotel';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'RoomPrice';

    public static function form(Schema $schema): Schema
    {
        return RoomPriceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoomPricesTable::configure($table);
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
            'index' => ListRoomPrices::route('/'),
            'create' => CreateRoomPrice::route('/create'),
            'edit' => EditRoomPrice::route('/{record}/edit'),
        ];
    }
}
