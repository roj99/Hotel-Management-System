<?php

namespace App\Filament\Resources\Hotel\Rooms\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('room_type_id')
                    ->relationship('roomType', 'name')
                    ->required(),
                TextInput::make('room_number')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('available'),
                TextInput::make('floor')
                    ->required()
                    ->numeric(),
            ]);
    }
}
