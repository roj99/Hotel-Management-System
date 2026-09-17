<?php

namespace App\Filament\Resources\Booking\BookingActions\Tables;

use App\Filament\Resources\Booking\Bookings\BookingResource;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class BookingActionsTable
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
                TextColumn::make('booking_id')
                    ->label('Booking ID')
                    ->sortable()
                    ->searchable()
                    ->url(fn ($record) =>
                        BookingResource::getUrl('edit', [
                            'record' => $record->booking_id,
                        ])
                    )
                    ->color('primary')
                    ->weight('bold'),
                TextColumn::make('action_type')
                        ->label('Action Type')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'cancelled' => 'danger',
                            'reviewed' => 'success',
                            default => 'gray',
                        })
                        ->formatStateUsing(fn (string $state): string => ucfirst($state))
                        ->sortable(),

            TextColumn::make('performed_by')
                        ->label('Performed By')
                        ->toggleable()
                        ->searchable()
                        ->sortable()
                        ->url(function ($record) {
                            if (! $record->performed_by) {
                                return null;
                            }

                            $user = \App\Models\User::find($record->performed_by);

                            if (! $user) {
                                return null;
                            }

                            return UserResource::getUrl('edit', [
                                'record' => $user,
                            ]);
                        })
                        ->color('primary')
                        ->weight('bold'),

                TextColumn::make('performed_at')
                    ->label('Performed At')
                    ->toggleable()
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
