<x-filament-panels::page>
    @php
        $showQuotaWarning = \App\Services\Tenancy\RentalLimitService::shouldShowUpgradeWarning();
        $remainingQuota = \App\Services\Tenancy\RentalLimitService::remainingQuota();
    @endphp

    @if($showQuotaWarning && !is_null($remainingQuota))
        <div class="mb-4 flex items-center justify-between rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            <div>
                <p class="font-semibold">
                    Sisa {{ $remainingQuota }} transaksi rental di paket Free bulan ini.
                </p>
                <p class="text-xs mt-1">
                    Upgrade ke paket Basic atau Pro untuk menghilangkan batas transaksi dan membuka fitur penuh.
                </p>
            </div>
        </div>
    @endif

    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-1 bg-gray-100 dark:bg-gray-800 p-1 rounded-lg w-fit">
            <button
                wire:click="setView('list')"
                class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $currentView === 'list' ? 'bg-white dark:bg-gray-700 shadow text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-900 dark:hover:text-gray-300' }}"
            >
                <div class="flex items-center gap-2">
                    <x-heroicon-o-list-bullet class="w-4 h-4" />
                    <span>List View</span>
                </div>
            </button>
            <button
                wire:click="setView('kanban')"
                class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $currentView === 'kanban' ? 'bg-white dark:bg-gray-700 shadow text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-900 dark:hover:text-gray-300' }}"
            >
                <div class="flex items-center gap-2">
                    <x-heroicon-o-view-columns class="w-4 h-4" />
                    <span>Kanban View</span>
                </div>
            </button>
        </div>
    </div>

    @if($currentView === 'list')
        @php
            $availableWidgets = $this->getAvailableWidgets();
            $defaultKeys = $this->getDefaultWidgetKeys();
            $counts = $this->getWidgetCounts();
            $colorClasses = [
                'success' => [
                    'bg' => 'bg-green-50 dark:bg-green-950/40',
                    'border' => 'border-green-200 dark:border-green-800',
                    'iconBg' => 'bg-green-100 dark:bg-green-900/50',
                    'icon' => 'text-green-600 dark:text-green-400',
                    'value' => 'text-green-700 dark:text-green-300',
                    'activeBorder' => 'ring-green-500 border-green-500',
                ],
                'info' => [
                    'bg' => 'bg-blue-50 dark:bg-blue-950/40',
                    'border' => 'border-blue-200 dark:border-blue-800',
                    'iconBg' => 'bg-blue-100 dark:bg-blue-900/50',
                    'icon' => 'text-blue-600 dark:text-blue-400',
                    'value' => 'text-blue-700 dark:text-blue-300',
                    'activeBorder' => 'ring-blue-500 border-blue-500',
                ],
                'warning' => [
                    'bg' => 'bg-amber-50 dark:bg-amber-950/40',
                    'border' => 'border-amber-200 dark:border-amber-800',
                    'iconBg' => 'bg-amber-100 dark:bg-amber-900/50',
                    'icon' => 'text-amber-600 dark:text-amber-400',
                    'value' => 'text-amber-700 dark:text-amber-300',
                    'activeBorder' => 'ring-amber-500 border-amber-500',
                ],
                'danger' => [
                    'bg' => 'bg-red-50 dark:bg-red-950/40',
                    'border' => 'border-red-200 dark:border-red-800',
                    'iconBg' => 'bg-red-100 dark:bg-red-900/50',
                    'icon' => 'text-red-600 dark:text-red-400',
                    'value' => 'text-red-700 dark:text-red-300',
                    'activeBorder' => 'ring-red-500 border-red-500',
                ],
                'gray' => [
                    'bg' => 'bg-gray-50 dark:bg-gray-900/40',
                    'border' => 'border-gray-200 dark:border-gray-800',
                    'iconBg' => 'bg-gray-100 dark:bg-gray-800',
                    'icon' => 'text-gray-600 dark:text-gray-400',
                    'value' => 'text-gray-700 dark:text-gray-300',
                    'activeBorder' => 'ring-gray-500 border-gray-500',
                ],
            ];
        @endphp

        <div
            x-data="{
                open: false,
                maxWidgets: 4,
                allKeys: @js(array_keys($availableWidgets)),
                defaultKeys: @js($defaultKeys),
                visible: [],
                draft: [],
                init() {
                    let saved = null;
                    try { saved = JSON.parse(localStorage.getItem('rental_visible_widgets') || 'null'); } catch (e) {}
                    if (Array.isArray(saved) && saved.length > 0) {
                        this.visible = saved.filter(k => this.allKeys.includes(k)).slice(0, this.maxWidgets);
                    } else {
                        this.visible = [...this.defaultKeys];
                    }
                },
                openCustomizer() {
                    this.draft = [...this.visible];
                    this.open = true;
                },
                toggleDraft(key) {
                    const idx = this.draft.indexOf(key);
                    if (idx >= 0) {
                        this.draft.splice(idx, 1);
                    } else if (this.draft.length < this.maxWidgets) {
                        this.draft.push(key);
                    }
                },
                saveCustomizer() {
                    this.visible = [...this.draft];
                    localStorage.setItem('rental_visible_widgets', JSON.stringify(this.visible));
                    this.open = false;
                },
                resetDefault() {
                    this.draft = [...this.defaultKeys];
                },
                isVisible(key) { return this.visible.includes(key); },
                isDraftSelected(key) { return this.draft.includes(key); },
                isDraftFull() { return this.draft.length >= this.maxWidgets; },
            }"
            x-on:open-widget-customizer.window="openCustomizer()"
        >
            {{-- Widget Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                @foreach($availableWidgets as $key => $widget)
                    @php $cc = $colorClasses[$widget['color']] ?? $colorClasses['gray']; @endphp
                    <button
                        type="button"
                        x-show="isVisible('{{ $key }}')"
                        x-cloak
                        wire:click="setActiveWidget('{{ $key }}')"
                        class="relative text-left rounded-xl border p-4 transition-all hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900
                            {{ $cc['bg'] }} {{ $cc['border'] }}
                            {{ $activeWidget === $key ? 'ring-2 ring-offset-2 dark:ring-offset-gray-900 ' . $cc['activeBorder'] : '' }}"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-600 dark:text-gray-400 truncate">{{ $widget['label'] }}</p>
                                <p class="mt-1 text-2xl font-bold {{ $cc['value'] }}">{{ $counts[$key] ?? 0 }}</p>
                            </div>
                            <div class="flex-shrink-0 rounded-lg p-2 {{ $cc['iconBg'] }}">
                                <x-dynamic-component :component="$widget['icon']" class="w-5 h-5 {{ $cc['icon'] }}" />
                            </div>
                        </div>
                        @if($activeWidget === $key)
                            <div class="mt-2 inline-flex items-center gap-1 text-xs font-medium {{ $cc['value'] }}">
                                <x-heroicon-m-funnel class="w-3 h-3" />
                                <span>Filtered</span>
                            </div>
                        @endif
                    </button>
                @endforeach
            </div>

            @if($activeWidget)
                <div class="mb-3 flex items-center justify-between rounded-lg bg-primary-50 dark:bg-primary-950/40 border border-primary-200 dark:border-primary-800 px-3 py-2">
                    <div class="flex items-center gap-2 text-sm text-primary-700 dark:text-primary-300">
                        <x-heroicon-m-funnel class="w-4 h-4" />
                        <span>Showing: <strong>{{ $availableWidgets[$activeWidget]['label'] ?? $activeWidget }}</strong></span>
                    </div>
                    <button
                        type="button"
                        wire:click="setActiveWidget('{{ $activeWidget }}')"
                        class="text-xs font-medium text-primary-700 dark:text-primary-300 hover:underline"
                    >
                        Clear filter
                    </button>
                </div>
            @endif

            {{-- Customize Widget Modal --}}
            <div
                x-show="open"
                x-cloak
                x-transition.opacity
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
                x-on:click.self="open = false"
                x-on:keydown.escape.window="open = false"
            >
                <div
                    class="bg-white dark:bg-gray-900 rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                >
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Customize Widget</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Pilih maksimal <span x-text="maxWidgets"></span> widget untuk ditampilkan
                                (<span x-text="draft.length"></span>/<span x-text="maxWidgets"></span>)
                            </p>
                        </div>
                        <button type="button" x-on:click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <x-heroicon-m-x-mark class="w-5 h-5" />
                        </button>
                    </div>

                    <div class="p-4 overflow-y-auto space-y-2">
                        @foreach($availableWidgets as $key => $widget)
                            @php $cc = $colorClasses[$widget['color']] ?? $colorClasses['gray']; @endphp
                            <label
                                class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors"
                                :class="isDraftSelected('{{ $key }}')
                                    ? 'border-primary-500 bg-primary-50 dark:bg-primary-950/30'
                                    : (isDraftFull() ? 'border-gray-200 dark:border-gray-700 opacity-50 cursor-not-allowed' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800')"
                            >
                                <input
                                    type="checkbox"
                                    class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                    :checked="isDraftSelected('{{ $key }}')"
                                    :disabled="!isDraftSelected('{{ $key }}') && isDraftFull()"
                                    x-on:change="toggleDraft('{{ $key }}')"
                                >
                                <div class="flex-shrink-0 rounded-md p-1.5 {{ $cc['iconBg'] }}">
                                    <x-dynamic-component :component="$widget['icon']" class="w-4 h-4 {{ $cc['icon'] }}" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $widget['label'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $counts[$key] ?? 0 }} item</p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between gap-2">
                        <button
                            type="button"
                            x-on:click="resetDefault()"
                            class="text-sm text-gray-600 dark:text-gray-400 hover:underline"
                        >
                            Reset ke default
                        </button>
                        <div class="flex items-center gap-2">
                            <x-filament::button color="gray" x-on:click="open = false" tag="button">
                                Batal
                            </x-filament::button>
                            <x-filament::button x-on:click="saveCustomizer()" tag="button">
                                Simpan
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{ $this->table }}
    @else
        <div 
            x-data="{ 
                statuses: (function() {
                    let saved = JSON.parse(localStorage.getItem('kanban_view_settings') || '{}');
                    let defaults = {
                        @foreach($this->getStatuses() as $status => $label)
                            '{{ $status }}': { 
                                visible: true, 
                                minimized: false,
                                label: '{{ $label }}'
                            },
                        @endforeach
                    };
                    
                    // Merge saved state into defaults
                    Object.keys(defaults).forEach(key => {
                        if (saved[key]) {
                            if (saved[key].hasOwnProperty('visible')) defaults[key].visible = saved[key].visible;
                            if (saved[key].hasOwnProperty('minimized')) defaults[key].minimized = saved[key].minimized;
                        }
                    });
                    
                    return defaults;
                })(),
                init() {
                    this.$watch('statuses', (val) => localStorage.setItem('kanban_view_settings', JSON.stringify(val)));
                }
            }"
            class="h-full"
        >
            <!-- View Filter Dropdown -->
            <div class="mb-4 flex justify-end">
                <x-filament::dropdown placement="bottom-end">
                    <x-slot name="trigger">
                        <x-filament::button icon="heroicon-o-adjustments-horizontal" color="gray">
                            View Filter
                        </x-filament::button>
                    </x-slot>
                    
                    <x-filament::dropdown.list>
                        <template x-for="(config, status) in statuses" :key="status">
                            <x-filament::dropdown.list.item
                                x-on:click.stop="config.visible = !config.visible"
                            >
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" :checked="config.visible" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <span x-text="config.label"></span>
                                </div>
                            </x-filament::dropdown.list.item>
                        </template>
                    </x-filament::dropdown.list>
                </x-filament::dropdown>
            </div>

            <div class="flex flex-col md:flex-row gap-4 overflow-x-auto pb-4 h-[calc(100vh-12rem)] items-start">
                @foreach($this->getStatuses() as $status => $label)
                    @php
                        $records = $this->getKanbanRecords()->get($status, collect());
                        $color = match($status) {
                            'quotation' => 'bg-orange-50 border-orange-200',
                            'active' => 'bg-green-50 border-green-200',
                            'late_pickup', 'late_return' => 'bg-red-50 border-red-200',
                            'completed' => 'bg-blue-50 border-blue-200',
                            'cancelled' => 'bg-gray-50 border-gray-200',
                            default => 'bg-gray-50 border-gray-200',
                        };
                        $headerColor = match($status) {
                            'quotation' => 'text-orange-700 bg-orange-200',
                            'active' => 'text-green-700 bg-green-200',
                            'late_pickup', 'late_return' => 'text-red-700 bg-red-200',
                            'completed' => 'text-blue-700 bg-blue-200',
                            'cancelled' => 'text-gray-700 bg-gray-200',
                            default => 'text-gray-700 bg-gray-200',
                        };
                    @endphp
                    
                    <div 
                        x-show="statuses['{{ $status }}'].visible"
                        :class="statuses['{{ $status }}'].minimized ? 'w-12 items-center' : 'w-80'"
                        class="flex-shrink-0 flex flex-col {{ $color }} rounded-lg border h-fit max-h-full transition-all duration-300"
                        style="display: none;" 
                        x-show.important="statuses['{{ $status }}'].visible"
                    >
                        <!-- Column Header -->
                        <div 
                            class="p-3 font-semibold text-sm flex justify-between items-center rounded-t-lg {{ $headerColor }} cursor-pointer"
                            :class="statuses['{{ $status }}'].minimized ? 'flex-col gap-4 h-full py-4 justify-start' : ''"
                            @click="statuses['{{ $status }}'].minimized = !statuses['{{ $status }}'].minimized"
                        >
                            <template x-if="!statuses['{{ $status }}'].minimized">
                                <div class="flex justify-between items-center w-full">
                                    <span>{{ $label }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="bg-white/50 px-2 py-0.5 rounded text-xs">{{ $records->count() }}</span>
                                        <x-heroicon-m-chevron-left class="w-4 h-4 text-gray-500 hover:text-gray-700" />
                                    </div>
                                </div>
                            </template>
                            
                            <template x-if="statuses['{{ $status }}'].minimized">
                                <div class="flex flex-col items-center gap-2 h-full">
                                    <span class="bg-white/50 px-2 py-0.5 rounded text-xs">{{ $records->count() }}</span>
                                    <div class="writing-vertical-rl transform rotate-180 whitespace-nowrap">{{ $label }}</div>
                                    <x-heroicon-m-chevron-right class="w-4 h-4 text-gray-500 hover:text-gray-700 mt-auto" />
                                </div>
                            </template>
                        </div>

                        <!-- Cards Container -->
                        <div 
                            x-show="!statuses['{{ $status }}'].minimized"
                            class="p-2 flex-1 overflow-y-auto space-y-3 custom-scrollbar"
                        >
                            @foreach($records as $record)
                                <a href="{{ \App\Filament\Resources\Rentals\RentalResource::getUrl('view', ['record' => $record]) }}" 
                                   class="block bg-white p-3 rounded shadow-sm border border-gray-100 hover:shadow-md transition cursor-pointer hover:border-primary-500">
                                    
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="font-medium text-sm text-gray-900">{{ $record->rental_code }}</span>
                                        <span class="text-xs text-gray-500">{{ $record->created_at->format('d M') }}</span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <div class="text-sm font-semibold text-gray-800">{{ $record->customer->name ?? 'Unknown Customer' }}</div>
                                    </div>

                                    <div class="text-xs text-gray-600 space-y-1">
                                        <div class="flex items-center gap-1">
                                            <x-heroicon-m-calendar class="w-3 h-3"/>
                                            <span>{{ $record->start_date?->format('d M H:i') }} - {{ $record->end_date?->format('d M H:i') }}</span>
                                        </div>
                                        
                                        @if($record->items->count() > 0)
                                            <div class="flex items-center gap-1">
                                                <x-heroicon-m-shopping-bag class="w-3 h-3"/>
                                                <span>{{ $record->items->count() }} Items</span>
                                            </div>
                                        @endif
                                        
                                        <div class="font-medium text-gray-900 mt-1">
                                            Rp {{ number_format($record->total, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                            @if($records->isEmpty())
                                <div class="text-center py-4 text-gray-400 text-sm italic">
                                    No rentals
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <style>
            .writing-vertical-rl {
                writing-mode: vertical-rl;
            }
        </style>
    @endif
</x-filament-panels::page>
