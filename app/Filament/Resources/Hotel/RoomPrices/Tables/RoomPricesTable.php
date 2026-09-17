<?php

namespace App\Filament\Resources\Hotel\RoomPrices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;


class RoomPricesTable
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
                TextColumn::make('roomType.name')
    ->label('Room Type')
    ->formatStateUsing(function ($state) {
        $type = strtolower(trim($state ?? 'unknown'));

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
                'background' => '#FFFDF8',
                'color' => '#263746',
                'border' => '#E5DDD0',
            ],
        };

        return new HtmlString("
            <span style=\"
                display:inline-flex;
                align-items:center;
                justify-content:center;
                min-width:90px;
                padding:6px 14px;
                border-radius:999px;
                background:{$styles['background']};
                color:{$styles['color']};
                border:1px solid {$styles['border']};
                font-size:12px;
                font-weight:600;
                letter-spacing:.3px;
                box-shadow:0 2px 6px rgba(38,55,70,.10);
            \">
                " . e($state ?? 'N/A') . "
            </span>
        ");
    })
    ->sortable()
    ->searchable(),
                TextColumn::make('start_date')
                     ->toggleable()
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                     ->toggleable()
                    ->date()
                    ->sortable(),
                TextColumn::make('price_per_night')
                     ->toggleable()
                    ->numeric()
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
