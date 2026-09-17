<?php

namespace App\Filament\Widgets;

use App\Models\Hotel\Room;
use Filament\Widgets\ChartWidget;

class MostRequestedRoomsChart extends ChartWidget
{
    protected ?string $heading = 'Most Requested Rooms';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $rooms = Room::query()
            ->with('roomType')
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        $labels = $rooms->map(function (Room $room) {
            $typeName = $room->roomType?->name ?? '';

            return $typeName
                ? "{$room->room_number} ({$typeName})"
                : $room->room_number;
        });

        return [
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => $rooms->pluck('bookings_count')->toArray(),
                    'backgroundColor' => '#9B8055',
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
