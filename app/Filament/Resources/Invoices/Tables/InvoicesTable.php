<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Filament\Resources\Booking\Bookings\BookingResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

class InvoicesTable
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
                        ->toggleable()
                        ->searchable()
                        ->url(fn ($record) =>
                            $record->booking
                                ? BookingResource::getUrl('edit', [
                                    'record' => $record->booking,
                                ])
                                : null
                        )
                        ->color('primary')
                        ->weight('bold'),
                    TextColumn::make('booking.user.full_name')
                        ->label('Customer')
                        ->searchable()
                        ->sortable(),
                TextColumn::make('room_charge')
                    ->toggleable()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('services_charge')
                    ->toggleable()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->toggleable()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('issued_at')
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
                  DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
