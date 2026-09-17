<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;

class CustomersTable
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
               ImageColumn::make('photo')
               ->toggleable()
                ->circular()
                ->size(40)
                ->getStateUsing(fn ($record) =>
                    'https://ui-avatars.com/api/?name=' . urlencode($record->full_name)
                )
                ->label('Photo'),
                TextColumn::make('full_name')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                BadgeColumn::make('role.name')
                ->colors([
                    'danger' => 'admin',
                    'primary' => 'manager',
                    'success' => 'receptionist',
                    'warning' => 'housekeeping',
                    'info' => 'room_service',
                    'secondary' => 'guest',
                ])->toggleable()
                     ->sortable()
                    ->searchable()
                     ->label('Role'),
                TextColumn::make('email')
                    ->label('Email address')
                    ->toggleable()
                    ->searchable(),
                IconColumn::make('email_verified_at')
                    ->toggleable()
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-exclamation-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->label('Verified'),
                TextColumn::make('phone')
                    ->formatStateUsing(fn ($state) => "+1 (" . substr($state, 2, 3) . ") " . substr($state, 5, 3) . "-" . substr($state, 8))
                    ->toggleable()
                    ->searchable()
                    ->label('Phone'),
                IconColumn::make('email_verified_at')
                    ->toggleable()
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-exclamation-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->label('Verified'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->hidden()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
