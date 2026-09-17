<?php

namespace App\Filament\Resources\Hotel\RoomServices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;
use App\Filament\Resources\Booking\Bookings\BookingResource;
use App\Filament\Resources\Users\UserResource;
use Illuminate\Support\HtmlString;

class RoomServicesTable
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
                TextColumn::make('booking.id')
                        ->label('Booking ID')
                        ->searchable()
                        ->sortable()
                        ->url(fn ($record) =>
                            $record->booking
                                ? BookingResource::getUrl('edit', [
                                    'record' => $record->booking,
                                ])
                                : null
                        )
                        ->color('primary')
                        ->weight('bold'),
                TextColumn::make('handledBy.full_name')
                            ->label('Handled By')
                            ->searchable()
                            ->sortable()
                            ->url(fn ($record) =>
                                $record->handledBy
                                    ? UserResource::getUrl('edit', [
                                        'record' => $record->handledBy,
                                    ])
                                    : null
                            )
                            ->color('primary')
                            ->weight('bold'),
                TextColumn::make('item_description')
                     ->toggleable()
                    ->searchable(),
                TextColumn::make('quantity')
                    ->toggleable()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price')
                    ->toggleable()
                    ->money()
                    ->sortable(),
                TextColumn::make('status_badge')
                        ->label('Status')
                        ->badge()
                        ->state(fn ($record) => $record->status)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                            'pending'   => 'warning',
                            'preparing' => 'info',
                            'delivered' => 'success',
                            'cancelled' => 'danger',
                            default     => 'gray',
                        })
                        ->sortable()
                        ->searchable(),

                SelectColumn::make('status')
                    ->label('Change Status')
                    ->options([
                        'pending' => 'Pending',
                        'preparing'   => 'Preparing',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',

                    ])
                    ->toggleable(),


                TextColumn::make('ordered_at')
                    ->toggleable()
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('delivered_at')
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
