<?php

namespace App\Filament\Resources\Booking\Guests;

use App\Filament\Resources\Booking\Guests\Pages\CreateGuest;
use App\Filament\Concerns\RestrictedToRoles;
use App\Filament\Resources\Booking\Guests\Pages\EditGuest;
use App\Filament\Resources\Booking\Guests\Pages\ListGuests;
use App\Filament\Resources\Booking\Guests\Schemas\GuestForm;
use App\Filament\Resources\Booking\Guests\Tables\GuestsTable;
use App\Models\Booking\Guest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GuestResource extends Resource
{
    use RestrictedToRoles;
    protected static ?string $model = Guest::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Users';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Guests';



    public static function form(Schema $schema): Schema
    {
        return GuestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GuestsTable::configure($table);
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
            'index' => ListGuests::route('/'),
            'create' => CreateGuest::route('/create'),
            'edit' => EditGuest::route('/{record}/edit'),
        ];
    }
}
