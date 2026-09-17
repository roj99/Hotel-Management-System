<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('ID')
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
                 SelectFilter::make('role_id')
                    ->relationship('role', 'name')
                    ->label('Role'),
                Filter::make('verified')
                ->query(fn ($query) => $query->whereNotNull('email_verified_at'))
                ->label('Verified'),
                Filter::make('full_name')
                ->form([
                     TextInput::make('name')
                     ->label('Search name'),
                     ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['name'], fn ($q) =>
                            $q->where('full_name', 'like', '%' . $data['name'] . '%')
                        );
                })
                ->label('Full name'),
            ])
            ->recordActions([
               Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => UserResource::getUrl('edit', ['record' => $record])),
               DeleteAction::make()
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
             ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
