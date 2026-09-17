<?php

namespace App\Filament\Resources\Hotel\RoomsTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
class RoomsTypesTable
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
                TextColumn::make('name')
    ->label('Room Type')
    ->formatStateUsing(function ($state) {

        $type = strtolower(trim($state ?? ''));

        $styles = match ($type) {

            'single' => [
                'background' => '#F6F1E8',
                'color' => '#6B5E4A',
                'border' => '#D8CBB8',
            ],

            'double' => [
                'background' => '#E8DDCB',
                'color' => '#5F5140',
                'border' => '#CDBFA9',
            ],

            'suite' => [
                'background' => '#E5D3AE',
                'color' => '#70572E',
                'border' => '#BFA36A',
            ],

            'deluxe' => [
                'background' => '#263746',
                'color' => '#FFFDF8',
                'border' => '#526A7A',
            ],

            default => [
                'background' => '#F6F1E8',
                'color' => '#263746',
                'border' => '#D8CBB8',
            ],
        };

        return new HtmlString("
            <span style=\"
                display:inline-flex;
                align-items:center;
                justify-content:center;
                min-width:100px;
                padding:6px 15px;
                border-radius:999px;
                background:{$styles['background']};
                color:{$styles['color']};
                border:1px solid {$styles['border']};
                font-size:12px;
                font-weight:600;
                letter-spacing:.3px;
                box-shadow:0 2px 6px rgba(38,55,70,.10);
                white-space:nowrap;
            \">
                " . e($state ?? 'N/A') . "
            </span>
        ");
    })
    ->sortable()
    ->searchable(),
                TextColumn::make('capacity')
                    ->numeric()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
