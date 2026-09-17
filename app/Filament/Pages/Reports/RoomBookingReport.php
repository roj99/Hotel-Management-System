<?php

namespace App\Filament\Pages\Reports;

use BackedEnum;
use UnitEnum;
use App\Filament\Pages\Reports\Concerns\ExportsCsv;
use App\Filament\Pages\Reports\Concerns\HasPrintAction;
use App\Models\Booking\Booking;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Concerns\RestrictedPageToRoles;

class RoomBookingReport extends Page implements HasTable
{
    use InteractsWithTable, ExportsCsv, HasPrintAction,RestrictedPageToRoles;

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Room Bookings';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Room Bookings Report';

    protected  string $view = 'filament.pages.reports.room-booking-report';

    /**
     * Same base data as ReportController::roomBookingReport() — all bookings,
     * with their room(s) and the guest (user) who made them. The "room" and
     * "from / to" filters below narrow this down, same as the room/from/to
     * parameters the API endpoint requires.
     */
    protected function getTableQuery(): Builder
    {
        return Booking::query()->with(['user', 'rooms']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->defaultSort('check_in_date', 'desc')
            ->columns([
                TextColumn::make('user.full_name')
                    ->label('Guest')
                    ->searchable(),

                TextColumn::make('rooms.room_number')
                    ->label('Room(s)')
                    ->badge(),

                TextColumn::make('check_in_date')
                    ->label('Check-in')
                    ->date()
                    ->sortable(),

                TextColumn::make('check_out_date')
                    ->label('Check-out')
                    ->date()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed', 'checked_in' => 'success',
                        'cancelled' => 'danger',
                        'checked_out' => 'gray',
                        default => 'warning',
                    }),

                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('usd')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('room')
                    ->label('Room')
                    ->relationship('rooms', 'room_number')
                    ->searchable()
                    ->preload(),

                Filter::make('period')
                    ->label('Period')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('from')
                            ->label('From')
                            ->native(false),
                        DatePicker::make('to')
                            ->label('To')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $q, $date): Builder => $q->whereDate('check_in_date', '>=', $date),
                            )
                            ->when(
                                $data['to'] ?? null,
                                fn (Builder $q, $date): Builder => $q->whereDate('check_out_date', '<=', $date),
                            );
                    }),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->exportCsvAction(),
            $this->printAction(),
        ];
    }

    protected function exportColumns(): array
    {
        return [
            'Guest' => fn (Booking $r) => $r->user?->full_name,
            'Room(s)' => fn (Booking $r) => $r->rooms->pluck('room_number')->join(', '),
            'Check-in' => fn (Booking $r) => $r->check_in_date?->toDateString(),
            'Check-out' => fn (Booking $r) => $r->check_out_date?->toDateString(),
            'Status' => fn (Booking $r) => $r->status,
            'Total' => fn (Booking $r) => $r->total_price,
        ];
    }
}
