<?php

namespace App\Filament\Resources\Staff\StaffSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StaffSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('user.full_name')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('shift_date')
                    ->toggleable()
                    ->date()
                    ->sortable(),
                TextColumn::make('shift_start')
                    ->toggleable()
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('shift_end')
                    ->toggleable()
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
