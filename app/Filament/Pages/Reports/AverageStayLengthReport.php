<?php

namespace App\Filament\Pages\Reports;

use BackedEnum;
use UnitEnum;
use App\Filament\Pages\Reports\Concerns\ExportsCsv;
use App\Filament\Pages\Reports\Concerns\HasPrintAction;
use App\Models\Booking\Booking;
use App\Models\Hotel\RoomsType;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Concerns\RestrictedPageToRoles;


class AverageStayLengthReport extends Page implements HasTable
{
    use InteractsWithTable, ExportsCsv, HasPrintAction,RestrictedPageToRoles;

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Average Stay Length';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-moon';

    protected static ?int $navigationSort = 6;

    protected static ?string $title = 'Average Stay Length per Room Type';

    protected  string $view = 'filament.pages.reports.average-stay-length-report';

    protected function getTableQuery(): Builder
    {
        return RoomsType::query()->withCount('rooms');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('name')
                    ->label('Room Type')
                    ->searchable(),

                TextColumn::make('rooms_count')
                    ->label('Rooms'),

                TextColumn::make('capacity')
                    ->label('Capacity'),

                // Computed per row (not a plain column) because it needs to
                // average check_out_date - check_in_date across every
                // completed/active booking that used a room of this type.
                TextColumn::make('avg_stay_nights')
                    ->label('Avg. Stay (nights)')
                    ->state(fn (RoomsType $record): string => $this->averageStayNights($record)),
            ]);
    }

    /**
     * Shared by the table column above and the CSV export below, so both
     * always agree on the same number.
     */
    protected function averageStayNights(RoomsType $record): string
    {
        $roomIds = $record->rooms()->pluck('id');

        if ($roomIds->isEmpty()) {
            return '—';
        }

        $average = Booking::query()
            ->whereHas('rooms', fn (Builder $q) => $q->whereIn('rooms.id', $roomIds))
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->get(['check_in_date', 'check_out_date'])
            ->avg(fn (Booking $booking) => $booking->check_in_date->diffInDays($booking->check_out_date));

        return $average ? number_format($average, 1) : '—';
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
            'Room Type' => fn (RoomsType $r) => $r->name,
            'Rooms' => fn (RoomsType $r) => $r->rooms_count,
            'Capacity' => fn (RoomsType $r) => $r->capacity,
            'Avg. Stay (nights)' => fn (RoomsType $r) => $this->averageStayNights($r),
        ];
    }
}
