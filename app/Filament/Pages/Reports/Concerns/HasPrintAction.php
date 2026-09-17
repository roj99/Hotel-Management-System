<?php

namespace App\Filament\Pages\Reports\Concerns;

use Filament\Actions\Action;

/**
 * Adds a reusable "Print" header action to a report page.
 *
 * This never round-trips to the server — it just triggers the browser's
 * native print dialog on the current page via onclick, so it works
 * instantly with whatever the user is currently viewing (filtered results,
 * sorted order, etc).
 *
 * Tip: for a cleaner printout, add a print stylesheet (e.g. in your panel's
 * theme CSS) that hides the sidebar/topbar with `@media print { ... }`.
 */
trait HasPrintAction
{
    protected function printAction(): Action
    {
        return Action::make('print')
            ->label('Print')
            ->icon('heroicon-o-printer')
            ->color('gray')
            ->action(fn () => null)
            ->extraAttributes([
                'onclick' => 'window.print(); return false;',
            ]);
    }
}
