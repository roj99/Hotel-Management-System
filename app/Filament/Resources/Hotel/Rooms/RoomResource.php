<?php

namespace App\Filament\Resources\Hotel\Rooms;

use App\Filament\Resources\Hotel\Rooms\Pages\CreateRoom;
use App\Filament\Resources\Hotel\Rooms\Pages\EditRoom;
use App\Filament\Resources\Hotel\Rooms\Pages\ListRooms;
use App\Filament\Resources\Hotel\Rooms\Schemas\RoomForm;
use App\Filament\Resources\Hotel\Rooms\Tables\RoomsTable;
use App\Models\Hotel\Room;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Concerns\RestrictedToRoles;


class RoomResource extends Resource
{
    use RestrictedToRoles;
    protected static ?string $model = Room::class;
    protected static string|UnitEnum|null $navigationGroup = 'Hotel';
protected static ?int $navigationSort = 1;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Room';

    public static function form(Schema $schema): Schema
    {
        return RoomForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoomsTable::configure($table);
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
            'index' => ListRooms::route('/'),
            'create' => CreateRoom::route('/create'),
            'edit' => EditRoom::route('/{record}/edit'),
        ];
    }
}
