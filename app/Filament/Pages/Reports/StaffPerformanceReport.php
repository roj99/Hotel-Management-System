<?php

namespace App\Filament\Pages\Reports;

use BackedEnum;
use UnitEnum;
use App\Filament\Pages\Reports\Concerns\ExportsCsv;
use App\Filament\Pages\Reports\Concerns\HasPrintAction;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Concerns\RestrictedPageToRoles;

class StaffPerformanceReport extends Page implements HasTable
{
    use InteractsWithTable, ExportsCsv, HasPrintAction,RestrictedPageToRoles;

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Staff Performance';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 8;

    protected static ?string $title = 'Staff Performance';

    protected string $view = 'filament.pages.reports.staff-performance-report';

    /**
     * housekeepingTasks(), maintenanceReportsReported() and
     * maintenanceReportsResolved() are the relations already defined on the
     * User model — withCount() turns each into a sortable/searchable
     * "<relation>_count" column below.
     *
     * Excludes users whose role is "guest" — this report is about staff
     * workload, and guests always show up as 0/0/0, which just adds noise.
     */
    protected function getTableQuery(): Builder
    {
        return User::query()
            ->with('role')
            ->whereHas('role', fn (Builder $query) => $query->where('name', '!=', 'guest'))
            ->withCount(['housekeepingTasks', 'maintenanceReportsReported', 'maintenanceReportsResolved']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->defaultSort('housekeeping_tasks_count', 'desc')
            ->columns([
                TextColumn::make('full_name')
                    ->label('Staff')
                    ->searchable(),

                TextColumn::make('role.name')
                    ->label('Role')
                    ->badge(),

                TextColumn::make('housekeeping_tasks_count')
                    ->label('Housekeeping Tasks')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('maintenance_reports_reported_count')
                    ->label('Issues Reported')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('maintenance_reports_resolved_count')
                    ->label('Issues Resolved')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->relationship('role', 'name')
                    ->searchable()
                    ->preload(),
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
            'Staff' => fn (User $r) => $r->full_name,
            'Role' => fn (User $r) => $r->role?->name,
            'Housekeeping Tasks' => fn (User $r) => $r->housekeeping_tasks_count,
            'Issues Reported' => fn (User $r) => $r->maintenance_reports_reported_count,
            'Issues Resolved' => fn (User $r) => $r->maintenance_reports_resolved_count,
        ];
    }
}
