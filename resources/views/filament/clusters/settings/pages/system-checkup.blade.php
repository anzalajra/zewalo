<x-filament-panels::page>
    {{-- System info --}}
    <x-filament::section>
        <x-slot name="heading">System Info</x-slot>
        <div class="grid gap-4 sm:grid-cols-3">
            @foreach ($systemInfo as $label => $value)
                <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <p class="text-xs text-gray-500">{{ $label }}</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $value }}</p>
                </div>
            @endforeach
        </div>
    </x-filament::section>

    {{-- Health checks --}}
    <x-filament::section>
        <x-slot name="heading">Health Checks</x-slot>
        <div class="grid gap-3 sm:grid-cols-2">
            @foreach ($checks as $key => $check)
                <div class="flex items-center justify-between rounded-lg border border-gray-100 px-4 py-3 dark:border-gray-700">
                    <span class="text-sm font-medium capitalize text-gray-700 dark:text-gray-200">{{ str_replace('_', ' ', $key) }}</span>
                    <x-filament::badge :color="$check['color']">{{ $check['message'] }}</x-filament::badge>
                </div>
            @endforeach
        </div>
    </x-filament::section>

    {{-- Safe maintenance actions --}}
    <x-filament::section>
        <x-slot name="heading">Actions</x-slot>
        <div class="flex flex-wrap gap-3">
            {{ $this->clearCacheAction }}
            {{ $this->cleanLogsAction }}
        </div>
    </x-filament::section>
</x-filament-panels::page>
