<x-filament-panels::page>
    {{-- Plan Status Card --}}
    <x-filament::section>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $planName }}
                </h3>
                <div class="mt-1 flex items-center gap-3">
                    <x-filament::badge :color="$statusColor">
                        {{ $planStatus }}
                    </x-filament::badge>

                    @if ($expiresAt)
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Valid until: {{ $expiresAt }}
                        </span>
                    @endif

                    @if ($trialEndsAt && $planStatus === 'Trial')
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Trial ends: {{ $trialEndsAt }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </x-filament::section>

    {{-- Usage Statistics --}}
    <x-filament::section heading="Usage">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($usageStats as $stat)
                <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ $stat['label'] }}
                    </p>
                    <div class="mt-2">
                        @if ($stat['limit'] !== null)
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $stat['used'] ?? '—' }}
                                <span class="text-sm font-normal text-gray-400">/ {{ $stat['limit'] }}{{ $stat['suffix'] ?? '' }}</span>
                            </p>
                            @if ($stat['used'] !== null)
                                @php
                                    $percentage = $stat['limit'] > 0 ? min(100, round(($stat['used'] / $stat['limit']) * 100)) : 0;
                                    $barColor = $percentage >= 90 ? 'bg-danger-500' : ($percentage >= 70 ? 'bg-warning-500' : 'bg-primary-500');
                                @endphp
                                <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                                    <div class="{{ $barColor }} h-full rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                                </div>
                            @endif
                        @else
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $stat['used'] ?? '—' }}
                                <span class="text-sm font-normal text-gray-400">/ Unlimited</span>
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>

    {{-- Invoice History --}}
    <x-filament::section heading="Invoice History">
        @if ($invoices->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada invoice.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Invoice #</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Jumlah</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Terbit</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Dibayar</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($invoices as $invoice)
                            @php
                                $detailUrl = \App\Filament\Pages\SubscriptionInvoiceDetail::getUrl(['record' => $invoice->id]);
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 font-medium">
                                    <a href="{{ $detailUrl }}" class="text-primary-600 hover:underline dark:text-primary-400">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    Rp {{ number_format($invoice->total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusEnum = $invoice->status;
                                        $invoiceColor = $statusEnum instanceof \App\Enums\SaasInvoiceStatus
                                            ? $statusEnum->getColor()
                                            : 'gray';
                                        $invoiceLabel = $statusEnum instanceof \App\Enums\SaasInvoiceStatus
                                            ? $statusEnum->getLabel()
                                            : ucfirst((string) $statusEnum);
                                    @endphp
                                    <x-filament::badge :color="$invoiceColor">
                                        {{ $invoiceLabel }}
                                    </x-filament::badge>
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                    {{ $invoice->issued_at->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                    {{ $invoice->due_at->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                    {{ $invoice->paid_at?->format('d M Y') ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ $detailUrl }}"
                                            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                                        >
                                            Detail
                                        </a>
                                        @if (! $invoice->isPaid())
                                            <x-filament::button
                                                size="sm"
                                                color="primary"
                                                wire:click="selectInvoice({{ $invoice->id }})"
                                            >
                                                Bayar
                                            </x-filament::button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>

    @include('filament.partials.subscription-payment-modal')
</x-filament-panels::page>
