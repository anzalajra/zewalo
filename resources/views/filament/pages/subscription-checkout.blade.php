<x-filament-panels::page>
    {{-- Step 1: Select Plan --}}
    @if ($step === 1)
        <x-filament::section heading="Pilih Paket Langganan">
            {{-- Billing Cycle Toggle --}}
            <div class="mb-6 flex items-center justify-center gap-4">
                <button
                    wire:click="$set('billingCycle', 'monthly')"
                    class="rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ $billingCycle === 'monthly' ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}"
                >
                    Bulanan
                </button>
                <button
                    wire:click="$set('billingCycle', 'yearly')"
                    class="rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ $billingCycle === 'yearly' ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}"
                >
                    Tahunan
                    <span class="ml-1 rounded bg-green-100 px-1.5 py-0.5 text-xs text-green-700 dark:bg-green-900 dark:text-green-300">Hemat</span>
                </button>
            </div>

            {{-- Plan Cards --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($plans as $plan)
                    <div
                        wire:click="selectPlan({{ $plan['id'] }})"
                        class="relative cursor-pointer rounded-xl border-2 p-6 transition-all hover:shadow-lg
                            {{ $selectedPlanId === $plan['id'] ? 'border-primary-500 bg-primary-50 shadow-md dark:bg-primary-900/20' : 'border-gray-200 dark:border-gray-700' }}
                            {{ $plan['is_featured'] ? 'ring-2 ring-primary-200 dark:ring-primary-800' : '' }}"
                    >
                        @if ($plan['is_featured'])
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                                <span class="rounded-full bg-primary-500 px-3 py-1 text-xs font-semibold text-white">Popular</span>
                            </div>
                        @endif

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $plan['name'] }}</h3>

                        @if ($plan['description'])
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $plan['description'] }}</p>
                        @endif

                        <div class="mt-4">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $billingCycle === 'yearly' ? $plan['pricing']['formatted_yearly'] : $plan['pricing']['formatted_monthly'] }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                / {{ $billingCycle === 'yearly' ? 'tahun' : 'bulan' }}
                            </span>
                        </div>

                        {{-- Limits --}}
                        <ul class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                            <li class="flex items-center gap-2">
                                <x-heroicon-o-users class="h-4 w-4 text-gray-400" />
                                {{ $plan['max_users'] ?? 'Unlimited' }} Users
                            </li>
                            <li class="flex items-center gap-2">
                                <x-heroicon-o-cube class="h-4 w-4 text-gray-400" />
                                {{ $plan['max_products'] ?? 'Unlimited' }} Products
                            </li>
                            <li class="flex items-center gap-2">
                                <x-heroicon-o-document-text class="h-4 w-4 text-gray-400" />
                                {{ $plan['max_rental_transactions_per_month'] ?? 'Unlimited' }} Transaksi/bulan
                            </li>
                        </ul>

                        @if ($selectedPlanId === $plan['id'])
                            <div class="mt-4 flex items-center justify-center gap-1 text-sm font-medium text-primary-600 dark:text-primary-400">
                                <x-heroicon-o-check-circle class="h-5 w-5" />
                                Dipilih
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end">
                <x-filament::button
                    color="primary"
                    wire:click="goToPayment"
                    :disabled="! $selectedPlanId"
                >
                    Lanjut ke Pembayaran
                </x-filament::button>
            </div>
        </x-filament::section>
    @endif

    {{-- Step 2: Select Payment Method --}}
    @if ($step === 2)
        <x-filament::section>
            {{-- Selected Plan Summary --}}
            @php
                $selectedPlan = collect($plans)->firstWhere('id', $selectedPlanId);
            @endphp
            @if ($selectedPlan)
                <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $selectedPlan['name'] }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $billingCycle === 'yearly' ? 'Tahunan' : 'Bulanan' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $billingCycle === 'yearly' ? $selectedPlan['pricing']['formatted_yearly'] : $selectedPlan['pricing']['formatted_monthly'] }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Pilih Metode Pembayaran</h3>

            <div class="space-y-3">
                @foreach ($this->paymentMethods as $method)
                    <label
                        class="flex cursor-pointer items-center gap-3 rounded-lg border p-4 transition-colors
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
                            <span class="ml-2 text-xs text-gray-500">
                                via {{ $method->paymentGateway->name }}
                            </span>
                            @if ((float) $method->admin_fee > 0)
                                <span class="ml-2 text-xs text-gray-500">
                                    + {{ $method->admin_fee_type === 'percentage' ? $method->admin_fee . '%' : ($selectedPlan['pricing']['currency'] === 'IDR' ? 'Rp ' . number_format((float) $method->admin_fee, 0, ',', '.') : '$' . number_format((float) $method->admin_fee, 2)) }}
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
                        Belum ada metode pembayaran yang tersedia untuk region Anda. Hubungi administrator.
                    </p>
                @endif
            </div>

            <div class="mt-6 flex justify-between">
                <x-filament::button color="gray" wire:click="backToPlans">
                    Kembali
                </x-filament::button>
                <x-filament::button
                    color="primary"
                    wire:click="initiatePayment"
                    :disabled="! $selectedPaymentMethodId"
                >
                    Bayar Sekarang
                </x-filament::button>
            </div>
        </x-filament::section>
    @endif

    {{-- Step 3: Payment Instructions --}}
    @if ($step === 3 && $paymentInstructions)
        <x-filament::section heading="Instruksi Pembayaran">
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
                    <x-filament::button tag="a" :href="App\Filament\Pages\SubscriptionBilling::getUrl()" color="gray">
                        Kembali ke Billing
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
