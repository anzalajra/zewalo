# Execution Plan: Central & Tenant Payment & Region Adjustment

Berikut adalah rencana eksekusi terstruktur untuk mengimplementasikan sistem multi-region, pengaturan payment gateway, dan manajemen metode pembayaran di Zewalo. Dokumen ini dirancang sebagai *prompt/instruksi* untuk dieksekusi oleh Claude secara bertahap.

## 1. Persiapan Database & Migrasi
*   **Tabel `packages` / `plans` (Paket Langganan):**
    *   Beri tambahan kolom `price_idr` (decimal/integer) untuk representasi harga Rupiah.
    *   Beri tambahan kolom `price_usd` (decimal) untuk representasi harga USD.
*   **Tabel `tenants` (Data Tenant):**
    *   Tambahkan kolom `region` (enum/string: `'id'`, `'intl'`) untuk mengunci region tenant sejak mendaftar/membuat instance.
*   **Tabel Pengaturan Central (Central Settings):**
    *   Buat tabel atau gunakan fitur Settings di aplikasi (misalnya menggunakan package `spatie/laravel-settings`) untuk menyimpan kredensial API secara global:
        *   Duitku: `duitku_merchant_code`, `duitku_api_key`, `duitku_env`.
        *   LemonSqueezy: `lemonsqueezy_api_key`, `lemonsqueezy_store_id`, `lemonsqueezy_webhook_secret`.
*   **Pengaturan Tenant (Tenant Settings):**
    *   Sediakan kolom/tabel untuk menyimpan preferensi metode pembayaran untuk *customer* dari tenant tersebut (termasuk status aktif/tidak, serta data manual transfer: Nama Bank, No Rekening, Atas Nama).

## 2. Penyesuaian Central Admin Panel (Filament)
*   **Resource Paket Langganan:** 
    *   Ubah form pembuatan/edit paket langganan. Tambahkan dua field input terpisah: "Harga IDR" dan "Harga USD".
*   **Halaman Pengaturan Pembayaran (Settings):** 
    *   Buat custom page `PaymentSettings` di Central Admin Panel.
    *   Sediakan form untuk mengisi dan menyimpan kredensial API Duitku dan API LemonSqueezy.

## 3. Penyesuaian Tenant Admin Panel (Filament)
*   **Halaman Pengaturan Metode Pembayaran Customer:**
    *   Buat halaman/menu setting untuk tenant agar bisa mengatur cara bayar customernya.
    *   Tenant dapat melihat dan meng-enable/disable payment gateway yang telah diatur oleh Central.
    *   Tambahkan seksi form untuk **Manual Transfer**, di mana tenant bisa memasukkan instruksi transfer manual beserta detail rekening bank (Bank, No Rekening, Pemilik Rekening).

## 4. Logika Deteksi Regional & Mekanisme Anti-Bypass
*   **Mencegah Bypass Harga oleh Tenant:**
    1.  **Deteksi pada Registrasi:** Gunakan *IP Geolocation* (contoh: package `stevebauman/location` atau mengandalkan header HTTP dari layanan seperti Cloudflare `CF-IPCountry`) ketika pendaftaran. Set dan *kunci* state `region` tenant ('id' atau 'intl') di tabel `tenants` secara permanen.
    2.  **Backend Pricing Enforcement:** Saat tombol checkout / langganan ditekan, proses kalkulasi harga **HARUS** dilakukan murni di sisi backend (berdasarkan kolom `region` yang sudah terkunci di database). *Sistem tidak boleh mempercayai nominal atau meta "region" dari payload form/frontend.*
    3.  **Filter Ketersediaan Gateway:**
        *   Jika *Tenant Region* == ID (`id`): Gunakan harga `price_idr`. Gateway yang muncul: Duitku & LemonSqueezy.
        *   Jika *Tenant Region* == International (`intl`): Gunakan harga `price_usd`. Gateway yang muncul: HANYA LemonSqueezy.

## 5. Implementasi Alur Pembayaran Langganan (Tenant ke Central)
*   Sesuaikan Livewire/Controller untuk proses subscription tenant:
    *   Pengecekan region otomatis.
    *   Routing pembayaran:
        *   Jika pilih Duitku -> Generate token/invoice via Duitku API.
        *   Jika pilih LemonSqueezy -> Generate checkout link via LemonSqueezy API.
*   **Webhook Listener:** 
    *   Buat route dan controller khusus webhook untuk masing-masing gateway (Duitku & LemonSqueezy) guna menerima *callback* dari pusat pembayaran dan meng-update status langganan tenant secara otomatis.

## 6. Implementasi Alur Pembayaran Customer (Customer ke Tenant)
*   Modifikasi halaman checkout toko/app milik tenant.
*   Tampilkan metode pembayaran berdasarkan pilihan yang sudah di-*enable* pada Tenant Admin (Langkah 3).
*   Jika memilih *Manual Transfer*, tampilkan informasi rekening yang dituju dan beri opsi pelanggan untuk upload bukti transfer.

## 7. Penyesuaian Tampilan (Central Homepage & Global System)
*   **IP Detection Guest/Visitor:** 
    *   Buat *Middleware* atau *Helper* untuk mendeteksi region dari visitor yang mengakses Homepage / halaman Pricing pengunjung publik.
*   **Dynamic UI Rendering (Blade/Livewire):**
    *   Tarik daftar `plans` dari database.
    *   Jika sistem mendeteksi IP Indonesia (atau pengunjung secara manual mengganti currency ke Rupiah): Tampilkan format konversi `price_idr` (contoh: `Rp 150.000`).
    *   Jika IP selain Indonesia: Tampilkan format konversi `price_usd` (contoh: `$ 10.00`).


---
**Pesan Eksekusi ke Claude:** 
"Berdasarkan plan di atas, mari kita mulai eksekusinya selangkah demi selangkah. Silakan mulai melakukan koding untuk Langkah 1 (Sistem Tabel, Migration, dan Model). Jangan pindah ke langkah berikutnya sebelum langkah 1 selesai dan di-review."
