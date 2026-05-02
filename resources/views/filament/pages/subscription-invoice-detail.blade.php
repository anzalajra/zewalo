<x-filament-panels::page>
    @php
        $statusEnum = $invoice->status;
        $statusColor = $statusEnum instanceof \App\Enums\SaasInvoiceStatus
            ? $statusEnum->getColor()
            : 'gray';
        $statusLabel = $statusEnum instanceof \App\Enums\SaasInvoiceStatus
            ? $statusEnum->getLabel()
            : ucfirst((string) $statusEnum);
        $sub = $invoice->tenantSubscription;
        $plan = $sub?->subscriptionPlan;
        $hasInstructions = $invoice->hasPaymentInstructions() && ! $invoice->isPaid() && ! $invoice->isPaymentExpired();
        $payData = $invoice->payment_data ?? [];
    @endphp

    {{-- Status Banner --}}
    <x-filament::section>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Invoice</p>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</h2>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <x-filament::badge :color="$statusColor" size="lg">
                    {{ $statusLabel }}
                </x-filament::badge>
                <div class="text-right">
                    <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Total</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($invoice->total, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </x-filament::section>

    {{-- Invoice Info + Bill To --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-filament::section heading="Informasi Invoice">
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Tanggal Terbit</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ $invoice->issued_at?->format('d M Y') ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Jatuh Tempo</dt>
                    <dd class="font-medium {{ ! $invoice->isPaid() && $invoice->due_at?->isPast() ? 'text-danger-600' : 'text-gray-900 dark:text-white' }}">
                        {{ $invoice->due_at?->format('d M Y') ?? '—' }}
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Tanggal Bayar</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ $invoice->paid_at?->format('d M Y H:i') ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Mata Uang</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ strtoupper($invoice->currency ?? 'IDR') }}</dd>
                </div>
            </dl>
        </x-filament::section>

        <x-filament::section heading="Ditagihkan Kepada">
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Nama Toko</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ $invoice->tenant?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Email</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ $invoice->tenant?->email ?? '—' }}</dd>
                </div>
                @if ($invoice->tenant?->phone ?? null)
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Telepon</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $invoice->tenant->phone }}</dd>
                    </div>
                @endif
            </dl>
        </x-filament::section>
    </div>

    {{-- Plan Detail + Amount Breakdown --}}
    <x-filament::section heading="Rincian Langganan">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Deskripsi</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900 dark:text-white">
                            Langganan {{ $plan?->name ?? 'Zewalo' }}
                        </div>
                        @if ($sub)
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Siklus: {{ ucfirst($sub->billing_cycle ?? '—') }}
                                @if ($sub->started_at && $sub->ends_at)
                                    · {{ $sub->started_at->format('d M Y') }} – {{ $sub->ends_at->format('d M Y') }}
                                @endif
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right text-gray-900 dark:text-white">
                        Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="px-4 py-2 text-right text-gray-500 dark:text-gray-400">Subtotal</td>
                    <td class="px-4 py-2 text-right text-gray-900 dark:text-white">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                </tr>
                @if ((float) $invoice->tax > 0)
                    <tr>
                        <td class="px-4 py-2 text-right text-gray-500 dark:text-gray-400">Pajak</td>
                        <td class="px-4 py-2 text-right text-gray-900 dark:text-white">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="border-t border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">Total</td>
                    <td class="px-4 py-3 text-right text-lg font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($invoice->total, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </x-filament::section>

    {{-- Payment Info (paid) --}}
    @if ($invoice->isPaid())
        <x-filament::section heading="Informasi Pembayaran">
            <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Metode Pembayaran</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">
                        {{ $invoice->paymentMethod?->display_name ?? $invoice->payment_method ?? '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Referensi Pembayaran</dt>
                    <dd class="font-medium text-gray-900 dark:text-white font-mono text-xs">
                        {{ $invoice->payment_reference ?? $invoice->gateway_reference_id ?? '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Dibayar Pada</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">
                        {{ $invoice->paid_at?->format('d M Y H:i') ?? '—' }}
                    </dd>
                </div>
            </dl>
        </x-filament::section>
    @endif

    {{-- Active payment instructions (unpaid + has payment_data) --}}
    @if ($hasInstructions)
        <x-filament::section heading="Instruksi Pembayaran Aktif">
            <div class="space-y-4">
                @if (! empty($payData['vaNumber']))
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-900">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Virtual Account</p>
                        <p class="mt-1 text-2xl font-bold tracking-wider text-gray-900 dark:text-white">
                            {{ $payData['vaNumber'] }}
                        </p>
                    </div>
                @endif

                @if (! empty($payData['paymentUrl']))
                    <div>
                        <a href="{{ $payData['paymentUrl'] }}" target="_blank"
                           class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 dark:text-primary-400">
                            <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                            Buka Halaman Pembayaran
                        </a>
                    </div>
                @endif

                @if (! empty($payData['expires_at']))
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Berlaku hingga: {{ \Carbon\Carbon::parse($payData['expires_at'])->format('d M Y H:i') }}
                    </p>
                @endif

                <div class="flex justify-end">
                    <x-filament::button color="primary" wire:click="checkPaymentStatus">
                        <x-heroicon-o-arrow-path class="h-4 w-4" wire:loading.class="animate-spin" wire:target="checkPaymentStatus" />
                        Cek Status Pembayaran
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>
    @endif

    @include('filament.partials.subscription-payment-modal')
</x-filament-panels::page>
