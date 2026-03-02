<x-filament-panels::page>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
        <div class="flex flex-wrap gap-2 sm:gap-4 text-xs sm:text-sm">
            <div class="flex items-center gap-1.5 sm:gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 rounded" style="background: #f97316;"></div>
                <span>Quotation</span>
            </div>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 rounded" style="background: #3b82f6;"></div>
                <span>Confirmed</span>
            </div>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 rounded" style="background: #22c55e;"></div>
                <span>Active</span>
            </div>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 rounded" style="background: #a855f7;"></div>
                <span>Completed</span>
            </div>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 rounded" style="background: #6b7280;"></div>
                <span>Cancelled</span>
            </div>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 rounded" style="background: #ef4444;"></div>
                <span>Late</span>
            </div>
        </div>

        <a href="{{ url('/admin/rentals/create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition shrink-0">
            <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="hidden sm:inline">New Rental</span>
            <span class="sm:hidden">Add</span>
        </a>
    </div>

    {{-- Widget dirender via getHeaderWidgets() di Page, jangan panggil @livewire() di sini --}}
    
    <x-filament-actions::modals />
</x-filament-panels::page>