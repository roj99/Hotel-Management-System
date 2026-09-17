<?php

namespace App\Filament\Resources\Booking\BookingActions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BookingActionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('booking_id')
                    ->relationship('booking', 'id')
                    ->required(),
                TextInput::make('action_type')
                    ->required(),
                TextInput::make('performed_by'),
                Textarea::make('reason')
                    ->columnSpanFull(),
                DateTimePicker::make('performed_at')
                    ->required(),
            ]);
    }
}
