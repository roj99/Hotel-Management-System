<?php

namespace App\Filament\Resources\Booking\Payments;

use App\Filament\Concerns\RestrictedToRoles;
use App\Filament\Resources\Booking\Payments\Pages\CreatePayment;
use App\Filament\Resources\Booking\Payments\Pages\EditPayment;
use App\Filament\Resources\Booking\Payments\Pages\ListPayments;
use App\Filament\Resources\Booking\Payments\Schemas\PaymentForm;
use App\Filament\Resources\Booking\Payments\Tables\PaymentsTable;
use App\Models\Booking\Payment;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    use RestrictedToRoles;
    protected static ?string $model = Payment::class;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 1;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Payment';

    public static function form(Schema $schema): Schema
    {
        return PaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentsTable::configure($table);
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
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'edit' => EditPayment::route('/{record}/edit'),
        ];
    }
}
