<?php

namespace App\Filament\Pages\Reports\Concerns;

use Filament\Actions\Action;

/**
 * Adds a reusable "Export CSV" header action to a report page.
 *
 * Each page using this trait must define exportColumns(): an ordered
 * [Column Label => fn ($record): string] map. This keeps the exported file
 * fully independent from the Filament table's internal column objects
 * (which vary in API across versions), and lets each report format its own
 * values (e.g. formatting money, resolving relationship names) explicitly.
 */
trait ExportsCsv
{
    /**
     * Column definitions for the CSV: label => resolver(record): string.
     */
    abstract protected function exportColumns(): array;

    /**
     * The rows to export. Defaults to the table's current filtered + sorted
     * results (NOT just the current page) for pages that use
     * InteractsWithTable, so exporting respects any active search/filters.
     * Override this on pages without a Filament table (e.g. stat-only pages).
     *
     * Note: Filament has renamed this internal method across versions
     * (getFilteredSortedTableQuery / getFilteredTableQuery). We try both;
     * if neither exists on your installed version, fall back to whatever
     * is currently rendered on screen.
     */
    protected function exportRows(): iterable
    {
        foreach (['getFilteredSortedTableQuery', 'getFilteredTableQuery'] as $method) {
            if (method_exists($this, $method)) {
                return $this->{$method}()->get();
            }
        }

        return $this->getTableRecords();
    }

    protected function exportFileName(): string
    {
        return str(static::getNavigationLabel())->slug() . '-' . now()->format('Y-m-d_His') . '.csv';
    }

    protected function exportCsvAction(): Action
    {
        return Action::make('export')
            ->label('Export CSV')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->action(function () {
                $columns = $this->exportColumns();
                $rows = $this->exportRows();

                return response()->streamDownload(function () use ($columns, $rows) {
                    $out = fopen('php://output', 'w');

                    // UTF-8 BOM so Arabic / non-Latin text opens correctly in Excel.
                    fwrite($out, "\xEF\xBB\xBF");

                    fputcsv($out, array_keys($columns));

                    foreach ($rows as $record) {
                        fputcsv($out, array_map(
                            fn (callable $resolve) => strip_tags((string) $resolve($record)),
                            $columns,
                        ));
                    }

                    fclose($out);
                }, $this->exportFileName());
            });
    }
}
