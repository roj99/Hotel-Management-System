<?php

namespace App\Filament\Pages\Reports;

use BackedEnum;
use UnitEnum;
use App\Filament\Pages\Reports\Concerns\ExportsCsv;
use App\Filament\Pages\Reports\Concerns\HasPrintAction;
use App\Models\Booking\Payment;
use App\Models\Hotel\RoomService;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use App\Filament\Concerns\RestrictedPageToRoles;

class MonthlyRevenueReport extends Page implements HasTable
{
    use InteractsWithTable, ExportsCsv, HasPrintAction,RestrictedPageToRoles;

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Monthly Revenue';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 9;

    protected static ?string $title = 'Monthly Revenue — Bookings + Room Services';

    protected string $view = 'filament.pages.reports.monthly-revenue-report';

    public function table(Table $table): Table
    {
        return $table
            // This report combines two revenue sources grouped by month, so
            // it isn't one Eloquent model's rows — ->records() lets the
            // table render a plain array instead of a query() builder.
            ->records(fn (): array => $this->buildMonthlyRevenueRows())
            ->columns([
                TextColumn::make('month')
                    ->label('Month'),

                TextColumn::make('bookings_revenue')
                    ->label('Bookings (Payments)')
                    ->money('usd'),

                TextColumn::make('room_services_revenue')
                    ->label('Room Services')
                    ->money('usd'),

                TextColumn::make('total_revenue')
                    ->label('Total')
                    ->money('usd')
                    ->weight('bold'),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->exportCsvAction(),
            $this->printAction(),
        ];
    }

    /**
     * This page builds its rows as a plain array (see ->records() above)
     * rather than an Eloquent query, so the generic query-based export in
     * ExportsCsv doesn't apply — reuse the same row-building method instead,
     * so the export always matches exactly what's on screen.
     */
    protected function exportRows(): iterable
    {
        return $this->buildMonthlyRevenueRows();
    }

    protected function exportColumns(): array
    {
        return [
            'Month' => fn (array $r) => $r['month'],
            'Bookings (Payments)' => fn (array $r) => $r['bookings_revenue'],
            'Room Services' => fn (array $r) => $r['room_services_revenue'],
            'Total' => fn (array $r) => $r['total_revenue'],
        ];
    }

    protected function buildMonthlyRevenueRows(): array
    {
        $payments = Payment::query()
            ->whereNotNull('paid_at')
            ->selectRaw($this->monthExpression('paid_at') . ' as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $roomServices = RoomService::query()
            ->where('status', 'delivered')
            // A "delivered" room service should always have a delivered_at,
            // but if one slipped through without it, grouping by month on a
            // NULL date produces a row with a blank month — skip those.
            ->whereNotNull('delivered_at')
            ->selectRaw($this->monthExpression('delivered_at') . ' as month, SUM(price * quantity) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $months = $payments->keys()
            ->merge($roomServices->keys())
            ->unique()
            ->sortDesc()
            ->values();

        return $months->map(function (string $month) use ($payments, $roomServices): array {
            $bookingsRevenue = (float) ($payments[$month] ?? 0);
            $roomServicesRevenue = (float) ($roomServices[$month] ?? 0);

            return [
                'id' => $month,
                'month' => $month,
                'bookings_revenue' => $bookingsRevenue,
                'room_services_revenue' => $roomServicesRevenue,
                'total_revenue' => $bookingsRevenue + $roomServicesRevenue,
            ];
        })->toArray();
    }

    /**
     * "Group by month" is written differently per database driver — this
     * picks the right one automatically based on the active connection, so
     * the report works whether the project runs on SQLite (local dev) or
     * MySQL/PostgreSQL (e.g. production).
     */
    protected function monthExpression(string $column): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m', {$column})",
            'pgsql' => "TO_CHAR({$column}, 'YYYY-MM')",
            default => "DATE_FORMAT({$column}, '%Y-%m')", // mysql / mariadb
        };
    }
}
