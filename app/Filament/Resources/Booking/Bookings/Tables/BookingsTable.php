<?php

namespace App\Filament\Resources\Booking\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['rooms.roomType', 'payments', 'user']))
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                ViewColumn::make('booking_info')
                    ->label('')
                    ->view('filament.tables.booking-hover-card')
                    ->extraAttributes([
                        'class' => 'booking-hover-column',
                    ]),

                TextColumn::make('user.full_name')
                    ->label('Full Name')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('check_in_date')
                    ->toggleable()
                    ->date()
                    ->sortable(),

                TextColumn::make('check_out_date')
                    ->toggleable()
                    ->date()
                    ->sortable(),

                TextColumn::make('status_badge')
                    ->label('Status')
                    ->state(fn ($record) => $record->status)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'confirmed' => 'info',
                        'checked_in' => 'success',
                        'checked_out' => 'primary',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),

                SelectColumn::make('status')
                    ->label('Change Status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'checked_in' => 'Checked In',
                        'checked_out' => 'Checked Out',
                        'cancelled' => 'Cancelled',
                    ])
                    ->toggleable(),

                TextColumn::make('total_price')
                    ->toggleable()
                    ->money()
                    ->sortable(),

                TextColumn::make('deposit_amount')
                    ->toggleable()
                    ->numeric()
                    ->sortable(),

                TextColumn::make('guests_count')
                    ->toggleable()
                    ->numeric()
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

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                    Filter::make('booking_date')
        ->label('Booking Date')
        ->form([
            DatePicker::make('from')
                ->label('From'),

            DatePicker::make('until')
                ->label('Until'),
        ])
        ->query(function (Builder $query, array $data): Builder {
            return $query
                ->when(
                    $data['from'],
                    fn (Builder $query, $date) =>
                        $query->whereDate('check_in_date', '>=', $date)
                )
                ->when(
                    $data['until'],
                    fn (Builder $query, $date) =>
                        $query->whereDate('check_in_date', '<=', $date)
                );
        }),
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
