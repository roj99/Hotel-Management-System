<?php

namespace App\Filament\Pages\Reports;

use BackedEnum;
use UnitEnum;
use App\Filament\Pages\Reports\Concerns\ExportsCsv;
use App\Filament\Pages\Reports\Concerns\HasPrintAction;
use App\Models\Booking\Booking;
use Filament\Pages\Page;
use App\Filament\Concerns\RestrictedPageToRoles;

class CancellationRateReport extends Page
{
    use ExportsCsv, HasPrintAction,RestrictedPageToRoles;
    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Cancellation Rate';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-x-circle';

    protected static ?int $navigationSort = 7;

    protected static ?string $title = 'Booking Cancellation Rate';

    protected  string $view = 'filament.pages.reports.cancellation-rate-report';

    public int $total = 0;

    public int $cancelled = 0;

    public int $completed = 0;

    public int $active = 0;

    public float $cancellationRate = 0.0;

    public function mount(): void
    {
        $this->total = Booking::query()->count();
        $this->cancelled = Booking::query()->where('status', 'cancelled')->count();
        $this->completed = Booking::query()->where('status', 'checked_out')->count();
        $this->active = $this->total - $this->cancelled - $this->completed;

        $this->cancellationRate = $this->total > 0
            ? round(($this->cancelled / $this->total) * 100, 1)
            : 0.0;
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->exportCsvAction(),
            $this->printAction(),
        ];
    }

    /**
     * This page has no table — it's a handful of pre-computed stats — so
     * export a single summary row instead of iterating table records.
     */
    protected function exportRows(): iterable
    {
        return [$this];
    }

    protected function exportColumns(): array
    {
        return [
            'Total Bookings' => fn (self $r) => $r->total,
            'Completed (Checked Out)' => fn (self $r) => $r->completed,
            'Cancelled' => fn (self $r) => $r->cancelled,
            'Active' => fn (self $r) => $r->active,
            'Cancellation Rate (%)' => fn (self $r) => $r->cancellationRate,
        ];
    }
}
