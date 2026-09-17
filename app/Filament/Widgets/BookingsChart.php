<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use App\Models\Booking\Booking;

class BookingsChart extends ChartWidget
{
    protected  ?string $heading = 'Daily Bookings (Last 30 Days)';

    /**
     * Same restriction as ReportsOverview — admin/manager only.
     */
    public static function canView(): bool
    {
        $user = auth()->user();

        return $user
            && $user->role
            && in_array($user->role->name, ['admin', 'manager'], true);
    }

    protected function getData(): array
    {
        $dates = collect();
        $counts = collect();

        // آخر 30 يوم
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();

            $count = Booking::whereDate('created_at', $date)->count();

            $dates->push(Carbon::parse($date)->format('M d'));
            $counts->push($count);
        }

        return [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => $counts,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59,130,246,0.3)',
                    'tension' => 0.4,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
