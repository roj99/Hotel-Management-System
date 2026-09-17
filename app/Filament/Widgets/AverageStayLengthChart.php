<?php

namespace App\Filament\Widgets;

use App\Models\Booking\Booking;
use App\Models\Hotel\RoomsType;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class AverageStayLengthChart extends ChartWidget
{
    protected ?string $heading = 'Average Stay Length per Room Type';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $roomTypes = RoomsType::query()->withCount('rooms')->get();

        $averages = $roomTypes->map(function (RoomsType $type) {
            $roomIds = $type->rooms()->pluck('id');

            if ($roomIds->isEmpty()) {
                return 0;
            }

            $average = Booking::query()
                ->whereHas('rooms', fn (Builder $q) => $q->whereIn('rooms.id', $roomIds))
                ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
                ->get(['check_in_date', 'check_out_date'])
                ->avg(fn (Booking $booking) => $booking->check_in_date->diffInDays($booking->check_out_date));

            return $average ? round($average, 1) : 0;
        });

        $colors = $averages->map(fn ($value) => $value > 0 ? '#9B8055' : '#D9D2C5');

        return [
            'datasets' => [
                [
                    'label' => 'Avg. Stay (nights)',
                    'data' => $averages->toArray(),
                    'backgroundColor' => $colors->toArray(),
                ],
            ],
            'labels' => $roomTypes->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Nights',
                    ],
                    'beginAtZero' => true,
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Room Type',
                    ],
                ],
            ],
        ];
    }
}
