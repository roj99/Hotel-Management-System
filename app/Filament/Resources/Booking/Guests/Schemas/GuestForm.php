<?php

namespace App\Filament\Resources\Booking\Guests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GuestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('booking_id')
                    ->relationship('booking', 'id')
                    ->required(),
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('id_document_type'),
                TextInput::make('id_document_number'),
            ]);
    }
}
