<?php

namespace App\Filament\Resources\Staff\LostFoundItems;

use App\Filament\Resources\Staff\LostFoundItems\Pages\CreateLostFoundItem;
use App\Filament\Resources\Staff\LostFoundItems\Pages\EditLostFoundItem;
use App\Filament\Resources\Staff\LostFoundItems\Pages\ListLostFoundItems;
use App\Filament\Resources\Staff\LostFoundItems\Schemas\LostFoundItemForm;
use App\Filament\Resources\Staff\LostFoundItems\Tables\LostFoundItemsTable;
use App\Models\Staff\LostFoundItem;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Concerns\RestrictedToRoles;

class LostFoundItemResource extends Resource
{
    use RestrictedToRoles;
    protected static ?string $model = LostFoundItem::class;
    protected static string|UnitEnum|null $navigationGroup = 'Processes';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'LostFoundItem';

    public static function form(Schema $schema): Schema
    {
        return LostFoundItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LostFoundItemsTable::configure($table);
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
            'index' => ListLostFoundItems::route('/'),
            'create' => CreateLostFoundItem::route('/create'),
            'edit' => EditLostFoundItem::route('/{record}/edit'),
        ];
    }
}
