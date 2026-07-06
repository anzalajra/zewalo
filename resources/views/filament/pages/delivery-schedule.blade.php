<x-filament-panels::page>
    @php
        $groups = $this->deliveryGroups();
        $summary = $this->summary();
        $statusColors = \App\Models\Delivery::class;
    @endphp

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <x-filament::button size="sm" color="gray" wire:click="shiftDay(-1)" icon="heroicon-o-chevron-left" />
            <x-filament::button size="sm" color="primary" wire:click="goToday">Hari Ini</x-filament::button>
            <x-filament::button size="sm" color="gray" wire:click="shiftDay(1)" icon="heroicon-o-chevron-right" />
            <input type="date" wire:model.live="date"
                class="rounded-lg border-gray-300 text-sm dark:bg-gray-800 dark:border-gray-700" />
        </div>
        <div class="flex items-center gap-2">
            <select wire:model.live="direction"
                class="rounded-lg border-gray-300 text-sm dark:bg-gray-800 dark:border-gray-700">
                <option value="all">Semua</option>
                <option value="out">Keluar (Check-out)</option>
                <option value="in">Masuk (Check-in)</option>
            </select>
        </div>
    </div>

    {{-- Summary --}}
    <div class="grid gap-3 sm:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs text-gray-500">Total</p>
            <p class="mt-1 text-lg font-bold">{{ $summary['total'] }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs text-gray-500">Draft</p>
            <p class="mt-1 text-lg font-bold text-gray-600">{{ $summary['draft'] }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs text-gray-500">Pending</p>
            <p class="mt-1 text-lg font-bold text-warning-600">{{ $summary['pending'] }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs text-gray-500">Selesai</p>
            <p class="mt-1 text-lg font-bold text-success-600">{{ $summary['completed'] }}</p>
        </div>
    </div>

    {{-- Delivery board --}}
    @forelse ($groups as $statusLabel => $deliveries)
        <x-filament::section>
            <x-slot name="heading">{{ $statusLabel }} <span class="text-gray-400">({{ $deliveries->count() }})</span></x-slot>
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($deliveries as $d)
                    <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                        <div class="flex items-start justify-between">
                            <div class="min-w-0">
                                <p class="font-mono text-xs text-gray-500">{{ $d->delivery_number }}</p>
                                <p class="font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $d->rental?->rental_code ?? '—' }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">{{ $d->rental?->user?->name ?? '—' }}</p>
                            </div>
                            <x-filament::badge :color="$d->type === \App\Models\Delivery::TYPE_OUT ? 'warning' : 'success'">
                                {{ $d->type === \App\Models\Delivery::TYPE_OUT ? 'Keluar' : 'Masuk' }}
                            </x-filament::badge>
                        </div>

                        <div class="mt-2 space-y-1 text-xs text-gray-500">
                            <p>🕒 {{ $d->scheduled_at ? $d->scheduled_at->format('d/m H:i') : ($d->date?->format('d/m') ?? '—') }}</p>
                            @if ($d->address)
                                <p class="truncate">📍 {{ $d->address }}</p>
                            @endif
                            <p>{{ $d->items->count() }} item</p>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <x-filament::button size="xs" color="gray" wire:click="openEdit({{ $d->id }})">Edit</x-filament::button>
                            @if ($d->status !== \App\Models\Delivery::STATUS_COMPLETED)
                                <x-filament::button size="xs" color="success"
                                    wire:click="setStatus({{ $d->id }}, '{{ \App\Models\Delivery::STATUS_COMPLETED }}')">
                                    Selesai
                                </x-filament::button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    @empty
        <x-filament::section>
            <p class="text-sm text-gray-500">Tidak ada pengiriman terjadwal pada tanggal ini.</p>
        </x-filament::section>
    @endforelse

    {{-- Inline edit modal --}}
    @if ($editingId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" wire:click.self="closeEdit">
            <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-xl dark:bg-gray-900">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Edit Jadwal Pengiriman</h3>
                <div class="mt-4 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500">Waktu Terjadwal</label>
                        <input type="datetime-local" wire:model="editScheduledAt"
                            class="mt-1 w-full rounded-lg border-gray-300 text-sm dark:bg-gray-800 dark:border-gray-700" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500">Alamat</label>
                        <textarea wire:model="editAddress" rows="3"
                            class="mt-1 w-full rounded-lg border-gray-300 text-sm dark:bg-gray-800 dark:border-gray-700"></textarea>
                    </div>
                </div>
                <div class="mt-5 flex justify-end gap-2">
                    <x-filament::button color="gray" wire:click="closeEdit">Batal</x-filament::button>
                    <x-filament::button color="primary" wire:click="saveEdit">Simpan</x-filament::button>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
