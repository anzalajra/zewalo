{{-- Headless QR scanner trigger. Listens for global `zw:scanner-open` event from --}}
{{-- the floating capsule (desktop) and the mobile bottom nav. Renders the scan modal. --}}
<div x-data="{
    showScanner: false,
    scanner: null,
    isLoading: false,
    errorMessage: null,
    initScanner() {
        this.showScanner = true;
        this.isLoading = false;
        this.errorMessage = null;
        this.$nextTick(() => {
            if (typeof Html5QrcodeScanner === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js';
                script.onload = () => this.startScanner();
                document.head.appendChild(script);
            } else {
                this.startScanner();
            }
        });
    },
    startScanner() {
        const width = window.innerWidth > 600 ? 250 : 200;
        if (this.scanner) {
            try { this.scanner.clear(); } catch (e) {}
        }
        this.scanner = new Html5QrcodeScanner(
            'zw-reader',
            { fps: 10, qrbox: { width, height: width }, aspectRatio: 1.0, showTorchButtonIfSupported: true },
            false
        );
        this.scanner.render(this.onScanSuccess.bind(this), () => {});
    },
    onScanSuccess(decodedText) {
        if (this.isLoading) return;
        this.isLoading = true;
        try {
            const url = new URL(decodedText);
            if (url.origin === window.location.origin) {
                if (typeof FilamentNotification !== 'undefined') {
                    new FilamentNotification().title('QR Code Detected').body('Redirecting…').success().send();
                }
                setTimeout(() => { window.location.href = decodedText; }, 700);
            } else {
                this.handleError('QR Code tidak valid: bukan dari sistem ini.');
            }
        } catch (e) {
            this.handleError('QR Code tidak valid: bukan URL yang benar.');
        }
    },
    handleError(message) {
        this.isLoading = false;
        this.errorMessage = message;
        if (typeof FilamentNotification !== 'undefined') {
            new FilamentNotification().title('Scan Error').body(message).danger().send();
        }
    },
    stopScanner() {
        if (this.scanner) {
            this.scanner.clear()
                .then(() => { this.scanner = null; })
                .catch(() => {});
        }
        this.showScanner = false;
        this.isLoading = false;
        this.errorMessage = null;
    },
}"
@zw:scanner-open.window="initScanner()"
>
    <div x-show="showScanner" x-cloak
         class="fixed inset-0 z-[10000] flex items-center justify-center bg-black/75 backdrop-blur-sm"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-4 p-6 relative"
             @click.away="stopScanner()">
            <div class="flex justify-between items-center mb-4 border-b pb-2 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    Scan QR Code
                </h3>
                <button @click="stopScanner()" class="text-gray-400 hover:text-gray-500 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                    <span class="sr-only">Close</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="relative overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-900">
                <div id="zw-reader" class="w-full"></div>
                <div x-show="isLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-white/90 dark:bg-gray-800/90 z-10">
                    <svg class="animate-spin h-10 w-10 text-primary-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">QR Code Detected!</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Redirecting…</p>
                </div>
            </div>
            <div class="mt-4">
                <p x-show="!errorMessage" class="text-sm text-gray-500 text-center dark:text-gray-400">Arahkan kamera ke QR code sistem.</p>
                <p x-show="errorMessage" x-text="errorMessage" class="text-sm text-red-500 text-center font-medium animate-pulse"></p>
            </div>
            <style>
                #zw-reader__scan_region { background: white; }
                #zw-reader__dashboard_section_csr button {
                    background-color: #0284c7; color: white; border: none;
                    padding: 8px 16px; border-radius: 6px; font-size: 14px;
                    cursor: pointer; margin-top: 10px;
                }
                #zw-reader__dashboard_section_swaplink { text-decoration: none; color: #0284c7; font-weight: bold; }
                #zw-reader video { object-fit: cover; border-radius: 8px; }
            </style>
        </div>
    </div>
</div>
