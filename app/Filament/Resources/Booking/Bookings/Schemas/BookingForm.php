<?php

namespace App\Filament\Resources\Booking\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'full_name')
                    ->required(),
                DatePicker::make('check_in_date')
                    ->required(),
                DatePicker::make('check_out_date')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('deposit_amount')
                    ->numeric(),
                TextInput::make('id_document_type'),
                TextInput::make('id_document_number'),
                TextInput::make('guests_count')
                    ->required()
                    ->numeric()
                    ->default(1),
            ]);
    }
}
