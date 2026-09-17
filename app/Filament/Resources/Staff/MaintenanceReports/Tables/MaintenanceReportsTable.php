<?php

namespace App\Filament\Resources\Staff\MaintenanceReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\Hotel\Rooms\RoomResource;
use App\Filament\Resources\Users\UserResource;

class MaintenanceReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('room_id')
                    ->label('Room ID')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) =>
                        $record->room
                            ? RoomResource::getUrl('edit', [
                                'record' => $record->room,
                            ])
                            : null
                    )
                    ->color('primary')
                    ->weight('bold'),
                TextColumn::make('issue_description')
                        ->label('Issue')
                        ->searchable()
                        ->sortable()
                        ->wrap(),
                TextColumn::make('severity')
                        ->label('Severity')
                        ->badge()
                        ->color(fn (string $state): string => match (strtolower(trim($state))) {
                            'low'      => 'success',
                            'medium'   => 'warning',
                            'high'     => 'danger',
                            'critical' => 'danger',
                            default    => 'gray',
                        })
                        ->formatStateUsing(fn (string $state): string => ucfirst($state))
                        ->sortable()
                        ->searchable(),
                    TextColumn::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match (strtolower(trim($state))) {
                                'open'        => 'danger',
                                'in_progress' => 'warning',
                                'resolved'    => 'success',
                                default       => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match (strtolower(trim($state))) {
                                'open'        => 'Open',
                                'in_progress' => 'In Progress',
                                'resolved'    => 'Resolved',
                                default       => ucfirst($state),
                            })
                            ->sortable()
                            ->searchable(),
                TextColumn::make('reportedBy.full_name')
                    ->label('Reported By')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) =>
                        $record->reportedBy
                            ? UserResource::getUrl('edit', [
                                'record' => $record->reportedBy,
                            ])
                            : null
                    )
                    ->color('primary')
                    ->weight('bold'),
                TextColumn::make('reported_at')
                    ->toggleable()
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('resolved_at')
                    ->toggleable()
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
