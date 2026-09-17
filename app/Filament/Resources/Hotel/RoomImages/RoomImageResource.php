<?php

namespace App\Filament\Resources\Hotel\RoomImages;

use App\Filament\Resources\Hotel\RoomImages\Pages\CreateRoomImage;
use App\Filament\Resources\Hotel\RoomImages\Pages\EditRoomImage;
use App\Filament\Resources\Hotel\RoomImages\Pages\ListRoomImages;
use App\Filament\Resources\Hotel\RoomImages\Schemas\RoomImageForm;
use App\Filament\Resources\Hotel\RoomImages\Tables\RoomImagesTable;
use App\Models\Hotel\RoomImage;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Concerns\RestrictedToRoles;

class RoomImageResource extends Resource
{
    use RestrictedToRoles;
    protected static ?string $model = RoomImage::class;
    protected static string|UnitEnum|null $navigationGroup = 'Hotel';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'RoomImage';

    public static function form(Schema $schema): Schema
    {
        return RoomImageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoomImagesTable::configure($table);
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
            'index' => ListRoomImages::route('/'),
            'create' => CreateRoomImage::route('/create'),
            'edit' => EditRoomImage::route('/{record}/edit'),
        ];
    }
}
