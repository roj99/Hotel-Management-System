<?php

namespace App\Filament\Resources\Staff\LostFoundItems\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LostFoundItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('room_id')
                    ->relationship('room', 'id')
                    ->required(),
                TextInput::make('found_by')
                    ->required(),
                Textarea::make('item_description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('storage_location'),
                TextInput::make('status')
                    ->required()
                    ->default('stored'),
                DateTimePicker::make('found_at')
                    ->required(),
                DateTimePicker::make('returned_at'),
            ]);
    }
}
