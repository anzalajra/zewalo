<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $levelMeta = [
                    'emergency' => ['color' => 'danger', 'icon' => 'heroicon-o-fire', 'label' => 'Emergency'],
                    'alert'     => ['color' => 'danger', 'icon' => 'heroicon-o-bell-alert', 'label' => 'Alert'],
                    'critical'  => ['color' => 'danger', 'icon' => 'heroicon-o-exclamation-triangle', 'label' => 'Critical'],
                    'error'     => ['color' => 'danger', 'icon' => 'heroicon-o-x-circle', 'label' => 'Error'],
                    'warning'   => ['color' => 'warning', 'icon' => 'heroicon-o-exclamation-circle', 'label' => 'Warning'],
                    'notice'    => ['color' => 'info', 'icon' => 'heroicon-o-information-circle', 'label' => 'Notice'],
                    'info'      => ['color' => 'info', 'icon' => 'heroicon-o-information-circle', 'label' => 'Info'],
                    'debug'     => ['color' => 'gray', 'icon' => 'heroicon-o-bug-ant', 'label' => 'Debug'],
                ];

                $prominent = ['error', 'warning', 'critical', 'info'];
            @endphp

            @foreach($prominent as $lvl)
                @php $meta = $levelMeta[$lvl]; $count = $summary[$lvl] ?? 0; @endphp
                <button
                    type="button"
                    wire:click="setLevel('{{ $lvl }}')"
                    class="text-left"
                >
                    <x-filament::section
                        @class([
                            'hover:ring-2 hover:ring-primary-500 transition',
                            'ring-2 ring-primary-500' => $levelFilter === $lvl,
                        ])
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $meta['label'] }}</div>
                                <div @class([
                                    'text-2xl font-bold mt-1',
                                    'text-danger-600' => $meta['color'] === 'danger' && $count > 0,
                                    'text-warning-600' => $meta['color'] === 'warning' && $count > 0,
                                    'text-info-600' => $meta['color'] === 'info' && $count > 0,
                                    'text-gray-600' => $count === 0,
                                ])>{{ number_format($count) }}</div>
                            </div>
                            <x-dynamic-component
                                :component="$meta['icon']"
                                @class([
                                    'w-8 h-8',
                                    'text-danger-500' => $meta['color'] === 'danger',
                                    'text-warning-500' => $meta['color'] === 'warning',
                                    'text-info-500' => $meta['color'] === 'info',
                                    'text-gray-400' => $meta['color'] === 'gray',
                                ])
                            />
                        </div>
                    </x-filament::section>
                </button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- File Sidebar --}}
            <div class="lg:col-span-1">
                <x-filament::section>
                    <x-slot name="heading">Log Files</x-slot>
                    <x-slot name="description">
                        {{ count($files) }} {{ count($files) === 1 ? 'file' : 'files' }}
                    </x-slot>

                    <div class="space-y-1 max-h-96 overflow-y-auto -mx-2">
                        <button
                            type="button"
                            wire:click="selectFile(null)"
                            @class([
                                'w-full text-left px-3 py-2 rounded-md text-sm transition',
                                'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 font-semibold' => $selectedFile === null,
                                'hover:bg-gray-100 dark:hover:bg-gray-800' => $selectedFile !== null,
                            ])
                        >
                            <div class="flex items-center justify-between">
                                <span>All Files</span>
                                <x-heroicon-o-queue-list class="w-4 h-4" />
                            </div>
                        </button>

                        @forelse($files as $file)
                            <div class="group">
                                <button
                                    type="button"
                                    wire:click="selectFile('{{ $file['name'] }}')"
                                    @class([
                                        'w-full text-left px-3 py-2 rounded-md text-sm transition',
                                        'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 font-semibold' => $selectedFile === $file['name'],
                                        'hover:bg-gray-100 dark:hover:bg-gray-800' => $selectedFile !== $file['name'],
                                    ])
                                >
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="truncate font-mono text-xs">{{ $file['name'] }}</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        <span>{{ $file['size_formatted'] }}</span>
                                        <span>{{ $file['modified'] }}</span>
                                    </div>
                                </button>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 dark:text-gray-400 px-3 py-4 text-center">
                                Tidak ada file log.
                            </div>
                        @endforelse
                    </div>
                </x-filament::section>
            </div>

            {{-- Entries --}}
            <div class="lg:col-span-3 space-y-4">
                {{-- Filter Bar --}}
                <x-filament::section>
                    <div class="flex flex-col md:flex-row gap-3">
                        <div class="flex-1">
                            <label class="sr-only" for="log-search">Search</label>
                            <div class="relative">
                                <x-heroicon-o-magnifying-glass class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                                <input
                                    id="log-search"
                                    type="text"
                                    wire:model.live.debounce.500ms="search"
                                    placeholder="Cari di message / stack trace..."
                                    class="w-full pl-10 pr-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                />
                            </div>
                        </div>

                        <div class="md:w-48">
                            <select
                                wire:model.live="levelFilter"
                                class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 py-2 px-3"
                            >
                                <option value="">All Levels</option>
                                @foreach($levels as $lvl)
                                    <option value="{{ $lvl }}">{{ ucfirst($lvl) }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if($selectedFile)
                            <x-filament::button
                                wire:click="downloadFile('{{ $selectedFile }}')"
                                color="gray"
                                size="sm"
                                icon="heroicon-o-arrow-down-tray"
                            >
                                Download
                            </x-filament::button>

                            <x-filament::button
                                wire:click="clearFile('{{ $selectedFile }}')"
                                wire:confirm="Kosongkan file log ini? Isi tidak bisa dikembalikan."
                                color="danger"
                                size="sm"
                                icon="heroicon-o-trash"
                            >
                                Clear
                            </x-filament::button>
                        @endif
                    </div>

                    @if($selectedFile || $levelFilter || $search)
                        <div class="mt-3 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span>Active filters:</span>
                            @if($selectedFile)
                                <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-800 font-mono">{{ $selectedFile }}</span>
                            @endif
                            @if($levelFilter)
                                <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-800">level:{{ $levelFilter }}</span>
                            @endif
                            @if($search)
                                <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-800">"{{ $search }}"</span>
                            @endif
                            <span class="ml-auto">Showing {{ $totalFound }} / max {{ $limit }}</span>
                        </div>
                    @endif
                </x-filament::section>

                {{-- Entries List --}}
                <x-filament::section>
                    <x-slot name="heading">Entries</x-slot>
                    <x-slot name="description">
                        Menampilkan {{ $totalFound }} entri (terbaru di atas).
                    </x-slot>

                    @if($entries->isEmpty())
                        <div class="py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                            <x-heroicon-o-inbox class="w-10 h-10 mx-auto mb-2 opacity-60" />
                            Tidak ada entri log yang cocok dengan filter ini.
                        </div>
                    @else
                        <div class="space-y-2">
                            @foreach($entries as $entry)
                                @php
                                    $level = strtolower($entry['level'] ?? 'info');
                                    $meta = $levelMeta[$level] ?? $levelMeta['info'];
                                @endphp
                                <details
                                    @class([
                                        'group border rounded-lg overflow-hidden',
                                        'border-danger-300 dark:border-danger-700' => $meta['color'] === 'danger',
                                        'border-warning-300 dark:border-warning-700' => $meta['color'] === 'warning',
                                        'border-gray-200 dark:border-gray-700' => in_array($meta['color'], ['info', 'gray']),
                                    ])
                                >
                                    <summary class="cursor-pointer px-4 py-2 list-none flex items-start gap-3 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <span @class([
                                            'shrink-0 px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wide',
                                            'bg-danger-100 text-danger-700 dark:bg-danger-900/50 dark:text-danger-300' => $meta['color'] === 'danger',
                                            'bg-warning-100 text-warning-700 dark:bg-warning-900/50 dark:text-warning-300' => $meta['color'] === 'warning',
                                            'bg-info-100 text-info-700 dark:bg-info-900/50 dark:text-info-300' => $meta['color'] === 'info',
                                            'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300' => $meta['color'] === 'gray',
                                        ])>{{ $level }}</span>

                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white break-words">
                                                {{ \Illuminate\Support\Str::limit($entry['message'], 220) }}
                                            </div>
                                            <div class="flex items-center gap-2 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                @if($entry['datetime'])
                                                    <span class="font-mono">{{ $entry['datetime'] }}</span>
                                                @endif
                                                @if($entry['env'])
                                                    <span class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-800 font-mono">{{ $entry['env'] }}</span>
                                                @endif
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-800 font-mono text-[10px]">{{ $entry['source'] }}</span>
                                            </div>
                                        </div>
                                        <x-heroicon-o-chevron-down class="w-4 h-4 text-gray-400 group-open:rotate-180 transition shrink-0" />
                                    </summary>

                                    <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-4 py-3">
                                        @if($entry['context'])
                                            <pre class="text-xs font-mono whitespace-pre-wrap break-all text-gray-700 dark:text-gray-300 max-h-96 overflow-y-auto">{{ $entry['context'] }}</pre>
                                        @else
                                            <div class="text-xs text-gray-500 dark:text-gray-400 italic">No additional context.</div>
                                        @endif

                                        <div class="mt-3 flex gap-2">
                                            <button
                                                type="button"
                                                class="text-xs px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300"
                                                onclick="navigator.clipboard.writeText(this.closest('details').querySelector('pre')?.innerText || this.closest('details').querySelector('.font-medium')?.innerText || ''); this.innerText = 'Copied!'; setTimeout(() => this.innerText = 'Copy', 1500);"
                                            >Copy</button>
                                        </div>
                                    </div>
                                </details>
                            @endforeach
                        </div>

                        @if($totalFound >= $limit)
                            <div class="mt-4 text-center">
                                <x-filament::button wire:click="loadMore" color="gray" size="sm" icon="heroicon-o-arrow-down">
                                    Load more (current limit: {{ $limit }})
                                </x-filament::button>
                            </div>
                        @endif
                    @endif
                </x-filament::section>
            </div>
        </div>
    </div>
</x-filament-panels::page>
