<?php

namespace App\Filament\Widgets;

use App\Models\Booking\Booking;
use App\Models\Hotel\Room;
use App\Models\Staff\MaintenanceReport;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStats extends BaseWidget
{
    protected function getStats(): array
    {
        $activeBookings = Booking::whereIn('status', ['pending', 'confirmed', 'checked_in'])->count();

        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();

        $monthlyRevenue = Booking::where('status', 'checked_out')
            ->whereMonth('created_at', now()->month)
            ->sum('total_price');

        $openMaintenance = MaintenanceReport::where('status', '!=', 'resolved')->count();

        return [
            Stat::make('Active Bookings', $activeBookings)
    ->description('Pending, confirmed, or currently in the hotel')
    ->color('success'),

Stat::make('Available Rooms', $availableRooms . ' / ' . $totalRooms)
    ->description('Out of the total number of rooms')
    ->color('info'),

Stat::make('This Month Revenue', number_format($monthlyRevenue) . ' $')
    ->description('From completed bookings')
    ->color('warning'),

Stat::make('Open Maintenance Reports', $openMaintenance)
    ->description('Requires follow-up')
    ->color($openMaintenance > 0 ? 'danger' : 'success'),
    ];
    }
}
