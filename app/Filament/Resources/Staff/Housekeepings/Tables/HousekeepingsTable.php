<?php

namespace App\Filament\Resources\Staff\Housekeepings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\Hotel\Rooms\RoomResource;

class HousekeepingsTable
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
                TextColumn::make('room_id')
                        ->label('Room ID')
                        ->searchable()
                        ->sortable()
                        ->url(fn ($record) =>
                            $record->room
                                ? RoomResource::getUrl('edit', [
                                    'record' => $record->room,
                                ])
                                : null
                        )
                        ->color('primary')
                        ->weight('bold'),
                TextColumn::make('staff.full_name')
                    ->label('Staff-Name')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('started_at')
                    ->toggleable()
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('finished_at')
                    ->toggleable()
                    ->dateTime()
                    ->sortable(),
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
