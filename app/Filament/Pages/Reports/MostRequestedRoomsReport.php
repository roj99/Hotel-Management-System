<?php

namespace App\Filament\Pages\Reports;

use App\Filament\Pages\Reports\Concerns\ExportsCsv;
use App\Filament\Pages\Reports\Concerns\HasPrintAction;
use App\Models\Hotel\Room;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use BackedEnum;
use App\Filament\Concerns\RestrictedPageToRoles;

class MostRequestedRoomsReport extends Page implements HasTable
{
    use InteractsWithTable, ExportsCsv, HasPrintAction,RestrictedPageToRoles;

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Most Requested Rooms';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Most Requested Rooms';

    protected  string $view ='filament.pages.reports.most-requested-rooms-report';

    protected function getTableQuery(): Builder
    {
        return Room::query()
            ->with('roomType')
            ->withCount('bookings');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->defaultSort('bookings_count', 'desc')
            ->columns([
                TextColumn::make('room_number')
                    ->label('Room Number')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-home')
                    ->weight('bold'),

                TextColumn::make('roomType.name')
                    ->label('Room Type')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('bookings_count')
                    ->label('Total Bookings')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-calendar-days'),
            ])
            ->filters([
                SelectFilter::make('roomType')
                    ->label('Room Type')
                    ->relationship('roomType', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->striped()
            ->paginated([10, 25, 50]);
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
            'Room Number' => fn (Room $r) => $r->room_number,
            'Room Type' => fn (Room $r) => $r->roomType?->name,
            'Total Bookings' => fn (Room $r) => $r->bookings_count,
        ];
    }

    public function getTotalRoomsProperty(): int
    {
        return Room::count();
    }

    public function getTotalBookingsProperty(): int
    {
        return Room::query()
            ->withCount('bookings')
            ->get()
            ->sum('bookings_count');
    }

    public function getMostRequestedRoomProperty(): ?Room
    {
        return Room::query()
            ->with('roomType')
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->first();
    }
}
