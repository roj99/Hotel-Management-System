<?php

namespace App\Filament\Resources\Booking\BookingActions;

use App\Filament\Resources\Booking\BookingActions\Pages\CreateBookingAction;
use App\Filament\Concerns\RestrictedToRoles;
use App\Filament\Resources\Booking\BookingActions\Pages\EditBookingAction;
use App\Filament\Resources\Booking\BookingActions\Pages\ListBookingActions;
use App\Filament\Resources\Booking\BookingActions\Schemas\BookingActionForm;
use App\Filament\Resources\Booking\BookingActions\Tables\BookingActionsTable;
use App\Models\Booking\BookingAction;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookingActionResource extends Resource
{
    use RestrictedToRoles;
    protected static ?string $model = BookingAction::class;
    protected static string|UnitEnum|null $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'BookingAction';

    public static function form(Schema $schema): Schema
    {
        return BookingActionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingActionsTable::configure($table);
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
            'index' => ListBookingActions::route('/'),
            'create' => CreateBookingAction::route('/create'),
            'edit' => EditBookingAction::route('/{record}/edit'),
        ];
    }
}
