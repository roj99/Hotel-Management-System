<?php

namespace App\Filament\Resources\Booking\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('booking_id')
                    ->relationship('booking', 'id')
                    ->required(),
                Select::make('invoice_id')
                    ->relationship('invoice', 'id'),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('method')
                    ->required(),
                TextInput::make('stripe_payment_intent_id'),
                DateTimePicker::make('paid_at')
                    ->required(),
            ]);
    }
}
