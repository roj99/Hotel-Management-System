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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use App\Filament\Concerns\RestrictedPageToRoles;

class UpcomingCheckInsReport extends Page implements HasTable
{
    use InteractsWithTable, ExportsCsv, HasPrintAction,RestrictedPageToRoles;

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Upcoming Check-ins';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-right-end-on-rectangle';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Upcoming Check-ins';

    protected  string $view = 'filament.pages.reports.upcoming-check-ins-report';

    /**
     * Defaults to today + tomorrow, excluding cancelled bookings. The "Date
     * range" filter below lets staff widen this window (e.g. the whole
     * upcoming week) without changing the default view.
     */
    protected function getTableQuery(): Builder
    {
        return Booking::query()
            ->with(['user', 'rooms'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereBetween('check_in_date', [now()->toDateString(), now()->addDay()->toDateString()]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->defaultSort('check_in_date')
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
                    ->sortable()
                    ->badge()
                    ->color(fn ($state): string => Carbon::parse($state)->isToday() ? 'success' : 'info'),

                TextColumn::make('check_out_date')
                    ->label('Check-out')
                    ->date(),

                TextColumn::make('guests_count')
                    ->label('Guests'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'confirmed' ? 'success' : 'warning'),
            ])
            ->filters([
                Filter::make('range')
                    ->label('Date range')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('from')->label('From')->default(now())->native(false),
                        DatePicker::make('to')->label('To')->default(now()->addDay())->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $q, $date): Builder => $q->whereDate('check_in_date', '>=', $date))
                            ->when($data['to'] ?? null, fn (Builder $q, $date): Builder => $q->whereDate('check_in_date', '<=', $date));
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
            'Guests' => fn (Booking $r) => $r->guests_count,
            'Status' => fn (Booking $r) => $r->status,
        ];
    }
}
