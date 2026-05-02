{{-- Reusable payment modal for SaaS subscription invoices.
     Host component must expose: $showPaymentModal, $selectedInvoiceId, $selectedPaymentMethodId,
     $paymentInstructions, computed paymentMethods, methods closePaymentModal/initiatePayment/checkPaymentStatus. --}}
@if ($showPaymentModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="closePaymentModal">
        <div class="mx-4 w-full max-w-lg rounded-xl bg-white p-6 shadow-xl dark:bg-gray-800">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    @if ($paymentInstructions)
                        Instruksi Pembayaran
                    @else
                        Pilih Metode Pembayaran
                    @endif
                </h3>
                <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="h-5 w-5" />
                </button>
            </div>

            @if (! $paymentInstructions)
                {{-- Payment Method Selection --}}
                <div class="space-y-3">
                    @foreach ($this->paymentMethods as $method)
                        <label
                            class="flex cursor-pointer items-center gap-3 rounded-lg border p-3 transition-colors
                                {{ $selectedPaymentMethodId === $method->id ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-200 hover:border-gray-300 dark:border-gray-700' }}"
                            wire:click="$set('selectedPaymentMethodId', {{ $method->id }})"
                        >
                            <input
                                type="radio"
                                name="payment_method"
                                value="{{ $method->id }}"
                                wire:model="selectedPaymentMethodId"
                                class="text-primary-500"
                            >
                            <div class="flex-1">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $method->display_name }}</span>
                                @if ((float) $method->admin_fee > 0)
                                    <span class="ml-2 text-xs text-gray-500">
                                        + {{ $method->admin_fee_type === 'percentage' ? $method->admin_fee . '%' : 'Rp ' . number_format((float) $method->admin_fee, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                            @if ($method->icon)
                                <img src="{{ $method->icon }}" alt="{{ $method->display_name }}" class="h-6" referrerpolicy="no-referrer">
                            @endif
                        </label>
                    @endforeach

                    @if ($this->paymentMethods->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Belum ada metode pembayaran yang tersedia. Hubungi administrator.
                        </p>
                    @endif
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <x-filament::button color="gray" wire:click="closePaymentModal">
                        Batal
                    </x-filament::button>
                    <x-filament::button
                        color="primary"
                        wire:click="initiatePayment"
                        :disabled="! $selectedPaymentMethodId"
                    >
                        Bayar Sekarang
                    </x-filament::button>
                </div>
            @else
                {{-- Payment Instructions --}}
                <div class="space-y-4" wire:poll.5s="checkPaymentStatus">
                    @if (! empty($paymentInstructions['vaNumber']))
                        <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-900">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Virtual Account</p>
                            <p class="mt-1 text-2xl font-bold tracking-wider text-gray-900 dark:text-white">
                                {{ $paymentInstructions['vaNumber'] }}
                            </p>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Transfer sesuai nominal ke nomor VA di atas melalui ATM, Mobile Banking, atau Internet Banking.
                        </p>
                    @endif

                    @if (! empty($paymentInstructions['qrString']))
                        <div class="flex flex-col items-center rounded-lg bg-gray-50 p-4 dark:bg-gray-900">
                            <p class="mb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Scan QR Code</p>
                            <div wire:ignore class="rounded-lg bg-white p-3">
                                <div id="qrcode-container"></div>
                            </div>
                        </div>
                        <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                            Scan QR Code di atas menggunakan aplikasi e-wallet atau mobile banking yang mendukung QRIS.
                        </p>
                        <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
                        <script>
                            (function initQr() {
                                var container = document.getElementById('qrcode-container');
                                if (!container) return;
                                if (typeof QRCode === 'undefined') {
                                    setTimeout(initQr, 100);
                                    return;
                                }
                                container.innerHTML = '';
                                new QRCode(container, {
                                    text: @js($paymentInstructions['qrString']),
                                    width: 200,
                                    height: 200,
                                });
                            })();
                        </script>
                    @endif

                    @if (! empty($paymentInstructions['paymentUrl']))
                        <div class="text-center">
                            <a href="{{ $paymentInstructions['paymentUrl'] }}" target="_blank"
                               class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 dark:text-primary-400">
                                <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                                Buka Halaman Pembayaran
                            </a>
                        </div>
                    @endif

                    <div class="rounded-lg border border-blue-200 bg-blue-50 p-3 dark:border-blue-800 dark:bg-blue-900/20">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            <x-heroicon-o-information-circle class="inline h-4 w-4" />
                            Halaman ini akan otomatis terupdate setelah pembayaran berhasil.
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <x-filament::button color="primary" wire:click="checkPaymentStatus">
                            <x-heroicon-o-arrow-path class="h-4 w-4" wire:loading.class="animate-spin" wire:target="checkPaymentStatus" />
                            Cek Status Pembayaran
                        </x-filament::button>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
