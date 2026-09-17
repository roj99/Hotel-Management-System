<?php

namespace App\Filament\Widgets;

use App\Models\Booking\Booking;
use App\Models\Hotel\Room;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReportsOverview extends StatsOverviewWidget
{
    /**
     * Only admin/manager see these revenue/occupancy stats on the
     * dashboard — everyone else (housekeeping, room_service, receptionist)
     * gets a dashboard without them.
     */
    public static function canView(): bool
    {
        $user = auth()->user();

        return $user
            && $user->role
            && in_array($user->role->name, ['admin', 'manager'], true);
    }

    protected function getStats(): array
    {
        $totalBookings = Booking::count();

        $totalRevenue = Booking::sum('total_price');

        $totalRooms = Room::count();

        $occupiedRooms = Room::where('status', 'occupied')->count();

        $occupancyRate = $totalRooms > 0
            ? round(($occupiedRooms / $totalRooms) * 100)
            : 0;

        $totalGuests = User::whereHas('role', function ($query) {
            $query->where('name', 'guest');
        })->count();

        return [
            Stat::make('Total Bookings', $totalBookings)
                ->description('All bookings')
                ->icon('heroicon-o-calendar-days'),

            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('Total booking revenue')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Occupancy Rate', $occupancyRate . '%')
                ->description($occupiedRooms . ' of ' . $totalRooms . ' rooms occupied')
                ->icon('heroicon-o-building-office-2'),

            Stat::make('Total Guests', $totalGuests)
                ->description('Registered guests')
                ->icon('heroicon-o-users'),
        ];
    }
}
