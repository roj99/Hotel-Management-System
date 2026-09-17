<?php

namespace App\Filament\Resources\Booking\Guests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\Users\UserResource;

class GuestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('booking.id')
                    ->label('Booking ID')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) =>
                        $record->booking
                            ? \App\Filament\Resources\Booking\Bookings\BookingResource::getUrl('edit', [
                                'record' => $record->booking,
                            ])
                            : null
                    )
                    ->color('primary')
                    ->weight('bold'),
                 TextColumn::make('booking.user.full_name')
                    ->label('Booking Customer')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) =>
                        $record->booking?->user
            ? UserResource::getUrl('edit', [
                                        'record' => $record->booking->user,
                                    ])
                                    : null
                            )
                            ->color('primary')
                            ->weight('bold'),
                TextColumn::make('full_name')
                        ->label('Guest Name')
                        ->searchable()
                        ->sortable(),

                TextColumn::make('id_document_type')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('id_document_number')
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
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
