<?php

namespace App\Filament\Resources\Booking\Payments\Tables;

use App\Filament\Resources\Booking\Bookings\BookingResource;
use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
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
                        BookingResource::getUrl('edit', [
                            'record' => $record->booking_id,
                        ])
                    )
                    ->color('primary')
                    ->weight('bold'),
                TextColumn::make('invoice.id')
                        ->label('Invoice ID')
                        ->toggleable()
                        ->searchable()
                        ->url(fn ($record) =>
                            $record->invoice
                                ? InvoiceResource::getUrl('edit', [
                                    'record' => $record->invoice,
                                ])
                                : null
                        )
                        ->color('primary')
                        ->weight('bold'),
                TextColumn::make('amount')
                    ->toggleable()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('method')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('stripe_payment_intent_id')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('paid_at')
                    ->dateTime()
                    ->toggleable()
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
