<?php

namespace App\Filament\Resources\Staff\StaffSchedules\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StaffScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                DatePicker::make('shift_date')
                    ->required(),
                DateTimePicker::make('shift_start')
                    ->required(),
                DateTimePicker::make('shift_end')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('scheduled'),
            ]);
    }
}
