<x-filament-panels::page>
    @php
        $invoice = $this->getInvoiceData();
        $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
        $statusColor = match ($invoice->status) {
            \App\Models\Invoice::STATUS_PAID => 'success',
            \App\Models\Invoice::STATUS_PARTIAL => 'warning',
            'cancelled' => 'gray',
            default => 'info',
        };
    @endphp

    <x-filament::section>
        <x-slot name="heading">Invoice {{ $invoice->number }}</x-slot>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 text-sm">
            <div><div class="text-xs text-gray-500">Customer</div><div class="font-semibold">{{ $invoice->customer?->name ?? '—' }}</div></div>
            <div><div class="text-xs text-gray-500">Tanggal</div><div class="font-semibold">{{ optional($invoice->date)->format('d M Y') ?? '—' }}</div></div>
            <div><div class="text-xs text-gray-500">Jatuh Tempo</div><div class="font-semibold">{{ optional($invoice->due_date)->format('d M Y') ?? '—' }}</div></div>
            <div>
                <div class="text-xs text-gray-500">Status</div>
                <x-filament::badge :color="$statusColor">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</x-filament::badge>
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
                    @forelse ($invoice->rentals->flatMap->items->filter(fn ($i) => ! $i->parent_item_id) as $item)
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

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Payments --}}
        <x-filament::section>
            <x-slot name="heading">Pembayaran</x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-xs uppercase text-gray-500">
                            <th class="py-2 pr-4">Tanggal</th>
                            <th class="py-2 pr-4">Metode</th>
                            <th class="py-2 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoice->transactions->where('type', \App\Models\FinanceTransaction::TYPE_INCOME) as $tx)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-1.5 pr-4">{{ optional($tx->date)->format('d M Y') }}</td>
                                <td class="py-1.5 pr-4">{{ $tx->payment_method ?? $tx->category }}</td>
                                <td class="py-1.5 text-right">{{ $rp($tx->amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-3 text-center text-gray-400">Belum ada pembayaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        {{-- Totals --}}
        <x-filament::section>
            <x-slot name="heading">Ringkasan</x-slot>
            <table class="w-full text-sm">
                <tr><td class="py-1 text-gray-500">Subtotal</td><td class="py-1 text-right">{{ $rp($invoice->subtotal) }}</td></tr>
                @if (($invoice->ppn_amount ?? 0) > 0)
                    <tr><td class="py-1 text-gray-500">PPN</td><td class="py-1 text-right">{{ $rp($invoice->ppn_amount) }}</td></tr>
                @endif
                @if (($invoice->late_fee ?? 0) > 0)
                    <tr><td class="py-1 text-gray-500">Denda Keterlambatan</td><td class="py-1 text-right">{{ $rp($invoice->late_fee) }}</td></tr>
                @endif
                <tr class="border-t font-semibold"><td class="py-1.5">Total</td><td class="py-1.5 text-right">{{ $rp($invoice->total) }}</td></tr>
                <tr><td class="py-1 text-gray-500">Dibayar</td><td class="py-1 text-right text-green-600">{{ $rp($invoice->paid_amount) }}</td></tr>
                <tr class="border-t-2 font-bold text-base">
                    <td class="py-2">Sisa Tagihan</td>
                    <td class="py-2 text-right {{ $invoice->balance > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $rp($invoice->balance) }}</td>
                </tr>
            </table>
        </x-filament::section>
    </div>
</x-filament-panels::page>
