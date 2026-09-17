<?php

namespace App\Filament\Pages\Reports;

use BackedEnum;
use UnitEnum;
use App\Filament\Pages\Reports\Concerns\ExportsCsv;
use App\Filament\Pages\Reports\Concerns\HasPrintAction;
use App\Models\Invoice;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Concerns\RestrictedPageToRoles;

class CustomerInvoiceBreakdownReport extends Page implements HasTable
{
    use InteractsWithTable, ExportsCsv, HasPrintAction,RestrictedPageToRoles;

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Customer Invoice Breakdown';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 4;

    protected static ?string $title = 'Every Customer With All Their Invoices';

    protected  string $view = 'filament.pages.reports.customer-invoice-breakdown-report';

    /**
     * Every invoice, with the booking + customer it belongs to — grouped
     * below by customer, so each customer's section lists all of their
     * invoices together (this is the "report per customer" version of
     * CustomerInvoicesReport, which instead lists + filters invoices flat).
     */
    protected function getTableQuery(): Builder
    {
        return Invoice::query()->with(['booking.user']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->groups([
                Group::make('booking.user.full_name')
                    ->label('Customer')
                    ->collapsible(),
            ])
            ->defaultGroup('booking.user.full_name')
            ->defaultSort('issued_at', 'desc')
            ->columns([
                TextColumn::make('issued_at')
                    ->label('Issued At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('room_charge')
                    ->money('usd')
                    ->sortable(),

                TextColumn::make('services_charge')
                    ->money('usd')
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('usd')
                    ->sortable()
                    ->weight('bold')
                    ->summarize(
                        Sum::make()
                            ->label('Customer total')
                            ->money('usd'),
                    ),
            ])
            ->filters([
                Filter::make('customer')
                    ->label('Customer')
                    ->schema([
                        Select::make('user_id')
                            ->label('Customer')
                            ->options(fn (): array => User::query()->pluck('full_name', 'id')->all())
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['user_id'] ?? null,
                            fn (Builder $q, $userId): Builder => $q->whereHas(
                                'booking',
                                fn (Builder $bookingQuery) => $bookingQuery->where('user_id', $userId),
                            ),
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
            'Customer' => fn (Invoice $r) => $r->booking?->user?->full_name,
            'Issued At' => fn (Invoice $r) => $r->issued_at?->toDateTimeString(),
            'Room Charge' => fn (Invoice $r) => $r->room_charge,
            'Services Charge' => fn (Invoice $r) => $r->services_charge,
            'Total' => fn (Invoice $r) => $r->total_amount,
        ];
    }
}
