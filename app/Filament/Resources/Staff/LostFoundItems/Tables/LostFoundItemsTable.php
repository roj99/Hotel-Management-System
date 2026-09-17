<?php

namespace App\Filament\Resources\Staff\LostFoundItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\Hotel\Rooms\RoomResource;
use App\Filament\Resources\Users\UserResource;

class LostFoundItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('ID')
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
                        ->searchable()
                        ->weight('bold'),
                TextColumn::make('foundBy.full_name')
                            ->label('Found By')
                            ->searchable()
                            ->sortable()
                            ->url(fn ($record) =>
                                $record->foundBy
                                    ? UserResource::getUrl('edit', [
                                        'record' => $record->foundBy,
                                    ])
                                    : null
                            )
                            ->color('primary')
                            ->weight('bold'),
                TextColumn::make('item_description')
                            ->label('Item Description')
                            ->searchable()
                            ->sortable()
                            ->wrap(),

                TextColumn::make('storage_location')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('status_badge')
                    ->label('Status')
                    ->state(fn ($record) => $record->status)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'stored'   => 'warning',
                            'returned' => 'success',
                            'disposed' => 'danger',
                            default    => 'gray',
                    })
                    ->toggleable(),
                SelectColumn::make('status')
                    ->label('Change Status')
                    ->options([
                        'stored'   => 'Stored',
                        'returned' => 'Returned',
                        'disposed' => 'Disposed',
                    ])
                    ->toggleable(),


                TextColumn::make('found_at')
                    ->toggleable()
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('returned_at')
                    ->dateTime()
                    ->sortable(),
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
