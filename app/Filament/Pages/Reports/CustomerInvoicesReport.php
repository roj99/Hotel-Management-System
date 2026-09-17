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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Concerns\RestrictedPageToRoles;



class CustomerInvoicesReport extends Page implements HasTable
{
    use InteractsWithTable, ExportsCsv, HasPrintAction,RestrictedPageToRoles;

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Customer Invoices';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Customer Invoices Report';

    protected  string $view = 'filament.pages.reports.customer-invoices-report';

    /**
     * Same base data as ReportController::customerInvoices() — every invoice,
     * with the booking + guest it belongs to. Invoice doesn't have a direct
     * relationship to User, so the "Customer" filter below reaches the user
     * through the booking (whereHas('booking', ...)).
     */
    protected function getTableQuery(): Builder
    {
        return Invoice::query()->with(['booking.user']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->defaultSort('issued_at', 'desc')
            ->columns([
                TextColumn::make('booking.user.full_name')
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('booking_id')
                    ->label('Booking')
                    ->limit(8)
                    ->copyable(),

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
                    ->weight('bold'),
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
            'Booking' => fn (Invoice $r) => $r->booking_id,
            'Issued At' => fn (Invoice $r) => $r->issued_at?->toDateTimeString(),
            'Room Charge' => fn (Invoice $r) => $r->room_charge,
            'Services Charge' => fn (Invoice $r) => $r->services_charge,
            'Total' => fn (Invoice $r) => $r->total_amount,
        ];
    }
}
