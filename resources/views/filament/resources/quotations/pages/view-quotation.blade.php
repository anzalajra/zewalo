<x-filament-panels::page>
    @php
        $quotation = $this->getQuotationData();
        $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
        $statusColor = match ($quotation->status) {
            \App\Models\Quotation::STATUS_ACCEPTED => 'success',
            'rejected', 'expired' => 'danger',
            default => 'info',
        };
    @endphp

    <x-filament::section>
        <x-slot name="heading">Quotation {{ $quotation->number }}</x-slot>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 text-sm">
            <div><div class="text-xs text-gray-500">Customer</div><div class="font-semibold">{{ $quotation->customer?->name ?? '—' }}</div></div>
            <div><div class="text-xs text-gray-500">Tanggal</div><div class="font-semibold">{{ optional($quotation->date ?? $quotation->created_at)->format('d M Y') ?? '—' }}</div></div>
            <div><div class="text-xs text-gray-500">Berlaku Hingga</div><div class="font-semibold">{{ optional($quotation->valid_until)->format('d M Y') ?? '—' }}</div></div>
            <div>
                <div class="text-xs text-gray-500">Status</div>
                <x-filament::badge :color="$statusColor">{{ ucfirst(str_replace('_', ' ', $quotation->status)) }}</x-filament::badge>
            </div>
        </div>
    </x-filament::section>

    {{-- Line items --}}
    <x-filament::section>
        <x-slot name="heading">Item</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-xs uppercase text-gray-500">
                        <th class="py-2 pr-4">Produk</th>
                        <th class="py-2 pr-4">Unit</th>
                        <th class="py-2 pr-4 text-right">Hari</th>
                        <th class="py-2 pr-4 text-right">Tarif</th>
                        <th class="py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($quotation->rentals->flatMap->items->filter(fn ($i) => ! $i->parent_item_id) as $item)
                        @php
                            $product = $item->productUnit?->product ?? $item->product;
                            $variation = $item->productUnit?->productVariation ?? $item->productVariation;
                        @endphp
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-1.5 pr-4">{{ $product?->name ?? '—' }}{{ $variation ? ' (' . $variation->name . ')' : '' }}</td>
                            <td class="py-1.5 pr-4 font-mono text-xs">{{ $item->productUnit?->serial_number ?? '—' }}</td>
                            <td class="py-1.5 pr-4 text-right">{{ $item->days }}</td>
                            <td class="py-1.5 pr-4 text-right">{{ $rp($item->daily_rate) }}</td>
                            <td class="py-1.5 text-right">{{ $rp($item->subtotal) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4 text-center text-gray-400">Tidak ada item.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>

    {{-- Totals --}}
    <x-filament::section>
        <x-slot name="heading">Ringkasan</x-slot>
        <table class="w-full max-w-md ml-auto text-sm">
            <tr><td class="py-1 text-gray-500">Subtotal</td><td class="py-1 text-right">{{ $rp($quotation->subtotal ?? 0) }}</td></tr>
            @if (($quotation->ppn_amount ?? 0) > 0)
                <tr><td class="py-1 text-gray-500">PPN</td><td class="py-1 text-right">{{ $rp($quotation->ppn_amount) }}</td></tr>
            @endif
            <tr class="border-t-2 font-bold text-base"><td class="py-2">Total</td><td class="py-2 text-right">{{ $rp($quotation->total ?? 0) }}</td></tr>
        </table>
    </x-filament::section>
</x-filament-panels::page>
