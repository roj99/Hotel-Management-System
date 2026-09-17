<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-4">
        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Bookings</div>
            <div class="text-2xl font-bold text-gray-950 dark:text-white">{{ $total }}</div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">Completed (Checked Out)</div>
            <div class="text-2xl font-bold text-success-600">{{ $completed }}</div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">Cancelled</div>
            <div class="text-2xl font-bold text-danger-600">{{ $cancelled }}</div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">Cancellation Rate</div>
            <div class="text-2xl font-bold text-gray-950 dark:text-white">{{ $cancellationRate }}%</div>
        </x-filament::section>
    </div>

    <x-filament::section class="mt-4">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            "Active" bookings (pending / confirmed / checked-in, not yet checked out or cancelled): <strong>{{ $active }}</strong>
        </div>
    </x-filament::section>
</x-filament-panels::page>
