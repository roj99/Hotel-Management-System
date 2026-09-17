<?php

namespace App\Filament\Resources\Hotel\RoomServices;

use App\Filament\Resources\Hotel\RoomServices\Pages\CreateRoomService;
use App\Filament\Resources\Hotel\RoomServices\Pages\EditRoomService;
use App\Filament\Resources\Hotel\RoomServices\Pages\ListRoomServices;
use App\Filament\Resources\Hotel\RoomServices\Schemas\RoomServiceForm;
use App\Filament\Resources\Hotel\RoomServices\Tables\RoomServicesTable;
use App\Models\Hotel\RoomService;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Concerns\RestrictedToRoles;

class RoomServiceResource extends Resource
{
    use RestrictedToRoles;
    protected static ?string $model = RoomService::class;
    protected static string|UnitEnum|null $navigationGroup = 'Hotel';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'RoomService';

    protected static function allowedRoles(): array
{
    return ['room_service'];
}

    public static function form(Schema $schema): Schema
    {
        return RoomServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoomServicesTable::configure($table);
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
            'index' => ListRoomServices::route('/'),
            'create' => CreateRoomService::route('/create'),
            'edit' => EditRoomService::route('/{record}/edit'),
        ];
    }
}
