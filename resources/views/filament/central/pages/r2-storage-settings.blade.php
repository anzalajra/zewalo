<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Health Status Card --}}
        @if($isConfigured)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        @if(($healthInfo['status'] ?? 'error') === 'healthy')
                            <x-heroicon-o-check-circle class="w-5 h-5 text-success-500" />
                        @else
                            <x-heroicon-o-x-circle class="w-5 h-5 text-danger-500" />
                        @endif
                        Status Koneksi
                    </div>
                </x-slot>
                <div class="text-sm">
                    @if(($healthInfo['status'] ?? 'error') === 'healthy')
                        <span class="text-success-600 font-medium">Terhubung</span>
                    @else
                        <span class="text-danger-600 font-medium">
                            {{ $healthInfo['connection']['message'] ?? 'Tidak terhubung' }}
                        </span>
                    @endif
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-folder class="w-5 h-5" />
                        Total File
                    </div>
                </x-slot>
                <div class="text-2xl font-bold">
                    {{ number_format($storageStats['total_files'] ?? 0) }}
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-server class="w-5 h-5" />
                        Total Penyimpanan
                    </div>
                </x-slot>
                <div class="text-2xl font-bold">
                    {{ $storageStats['total_size_formatted'] ?? '0 B' }}
                </div>
            </x-filament::section>
        </div>

        <div class="flex justify-end">
            <x-filament::button wire:click="refreshStats" size="sm" color="gray">
                <x-heroicon-m-arrow-path class="w-4 h-4 mr-1" />
                Refresh Statistik
            </x-filament::button>
        </div>
        @else
        <x-filament::section class="border-warning-500 bg-warning-50 dark:bg-warning-900/20">
            <x-slot name="heading">
                <div class="flex items-center gap-2 text-warning-600 dark:text-warning-400">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                    Konfigurasi Diperlukan
                </div>
            </x-slot>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Cloudflare R2 belum dikonfigurasi. Silakan isi kredensial API di bawah ini untuk mengaktifkan penyimpanan cloud.
            </p>
        </x-filament::section>
        @endif

        {{-- Write Probe Results --}}
        @if(!empty($probeResults))
            @php
                $probeFailed = collect($probeResults)->where('success', false)->count();
                $probeOk = collect($probeResults)->where('success', true)->count();
            @endphp
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        @if($probeFailed === 0)
                            <x-heroicon-o-check-circle class="w-5 h-5 text-success-500" />
                        @else
                            <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-danger-500" />
                        @endif
                        Hasil Probe Tulis R2
                    </div>
                </x-slot>
                <x-slot name="description">
                    {{ $probeOk }} berhasil, {{ $probeFailed }} gagal dari {{ count($probeResults) }} scope.
                </x-slot>

                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach($probeResults as $r)
                        <div @class([
                            'flex items-start gap-3 p-3 rounded-lg border text-sm',
                            'border-success-300 bg-success-50 dark:bg-success-900/20 dark:border-success-700' => $r['success'],
                            'border-danger-300 bg-danger-50 dark:bg-danger-900/20 dark:border-danger-700' => ! $r['success'],
                        ])>
                            @if($r['success'])
                                <x-heroicon-o-check-circle class="w-5 h-5 text-success-600 dark:text-success-400 shrink-0 mt-0.5" />
                            @else
                                <x-heroicon-o-x-circle class="w-5 h-5 text-danger-600 dark:text-danger-400 shrink-0 mt-0.5" />
                            @endif

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-mono text-xs font-semibold">{{ $r['scope'] }}</span>
                                    <span class="text-xs text-gray-500">{{ $r['latency_ms'] }}ms</span>
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400 mt-1 break-words">{{ $r['message'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>
        @endif

        {{-- Configuration Form --}}
        <x-filament::section>
            <x-slot name="heading">
                Konfigurasi R2
            </x-slot>
            <x-slot name="description">
                Masukkan kredensial dan konfigurasi Cloudflare R2 Anda.
            </x-slot>

            <form wire:submit="save">
                {{ $this->form }}

                <div class="mt-6 flex gap-3 flex-wrap">
                    <x-filament::button type="submit">
                        <x-heroicon-m-check class="w-4 h-4 mr-1" />
                        Simpan Konfigurasi
                    </x-filament::button>

                    <x-filament::button type="button" wire:click="testConnection" color="info">
                        <x-heroicon-m-signal class="w-4 h-4 mr-1" />
                        Test Koneksi
                    </x-filament::button>

                    <x-filament::button type="button" wire:click="probeWrite" color="info">
                        <x-heroicon-m-pencil-square class="w-4 h-4 mr-1" />
                        Test Tulis (Central)
                    </x-filament::button>

                    <x-filament::button
                        type="button"
                        wire:click="probeAllTenants"
                        wire:confirm="Menulis file probe kecil ke prefix setiap tenant — aman, file dihapus setelah verifikasi. Lanjutkan?"
                        color="warning"
                    >
                        <x-heroicon-m-users class="w-4 h-4 mr-1" />
                        Test Tulis Semua Tenant
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        {{-- Current Configuration Info --}}
        @if($isConfigured)
        <x-filament::section collapsible collapsed>
            <x-slot name="heading">
                Informasi Konfigurasi Saat Ini
            </x-slot>
            
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Bucket:</span>
                    <span class="font-mono ml-2">{{ $healthInfo['bucket'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Region:</span>
                    <span class="font-mono ml-2">{{ $healthInfo['region'] ?? 'N/A' }}</span>
                </div>
                <div class="col-span-2">
                    <span class="text-gray-500">Endpoint:</span>
                    <span class="font-mono ml-2 break-all">{{ $healthInfo['endpoint'] ?? 'N/A' }}</span>
                </div>
            </div>
        </x-filament::section>
        @endif

        {{-- Quick Links --}}
        <x-filament::section>
            <x-slot name="heading">
                Akses Cepat
            </x-slot>
            
            <div class="flex gap-3">
                <x-filament::button tag="a" href="{{ url('/admin/r2-file-browser') }}" color="gray">
                    <x-heroicon-m-folder-open class="w-4 h-4 mr-1" />
                    File Browser
                </x-filament::button>
                
                <x-filament::button 
                    tag="a" 
                    href="https://dash.cloudflare.com/?to=/:account/r2/overview" 
                    target="_blank"
                    color="gray"
                >
                    <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4 mr-1" />
                    Cloudflare Dashboard
                </x-filament::button>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
