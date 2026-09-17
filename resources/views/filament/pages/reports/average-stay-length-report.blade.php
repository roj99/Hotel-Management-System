<x-filament-panels::page>
    <div class="mb-4">
        @livewire(\App\Filament\Widgets\AverageStayLengthChart::class)
    </div>

    {{ $this->table }}
</x-filament-panels::page>
