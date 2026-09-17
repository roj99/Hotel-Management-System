<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-3">
        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Rooms</div>
            <div class="text-2xl font-bold text-gray-950 dark:text-white">{{ $this->totalRooms }}</div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Bookings</div>
            <div class="text-2xl font-bold text-gray-950 dark:text-white">{{ $this->totalBookings }}</div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">Most Requested Room</div>
            <div class="text-2xl font-bold text-warning-600">
                {{ $this->mostRequestedRoom?->room_number ?? '—' }}
            </div>
            @if ($this->mostRequestedRoom)
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $this->mostRequestedRoom->roomType?->name }} ·
                    {{ $this->mostRequestedRoom->bookings_count }} bookings
                </div>
            @endif
        </x-filament::section>
    </div>

    <div class="mt-4">
        @livewire(\App\Filament\Widgets\MostRequestedRoomsChart::class)
    </div>

    <div class="mt-4">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
