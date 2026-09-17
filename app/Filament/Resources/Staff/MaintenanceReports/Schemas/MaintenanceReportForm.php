<?php

namespace App\Filament\Resources\Staff\MaintenanceReports\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MaintenanceReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('room_id')
                    ->relationship('room', 'id')
                    ->required(),
                TextInput::make('reported_by')
                    ->required(),
                Textarea::make('issue_description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('severity')
                    ->required()
                    ->default('low'),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
                TextInput::make('resolved_by'),
                DateTimePicker::make('reported_at')
                    ->required(),
                DateTimePicker::make('resolved_at'),
            ]);
    }
}
