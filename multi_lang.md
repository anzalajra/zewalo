# Planning Sistem Multi-Language & Dynamic Pricing (Zewalo)

Dokumen ini merangkum strategi dan arsitektur untuk mengimplementasikan sistem multi-bahasa (Multi-Language), deteksi wilayah otomatis (Geo-Location), dynamic pricing (Rupiah & USD), serta *Translation Panel* pada ekosistem Zewalo.

## 1. Deteksi Wilayah & Bahasa Otomatis (Geo-IP Middleware)
**Tujuan:** Mendeteksi lokasi pengunjung dan menentukan region, bahasa, serta mata uang secara otomatis.

*   **Metode Deteksi:** Menggunakan IP Address pengunjung melalui layanan pembacaan *GeoIP* (bisa menggunakan package `MaxMind GeoLite2` lokal, `ipinfo.io`, atau jika Zewalo dihosting di balik Cloudflare, cukup membaca header HTTP `CF-IPCountry`).
*   **Logika Sistem (Middleware `RegionLocaleMiddleware`):**
    *   **Jika Negara == 'ID' (Indonesia):**
        *   Region aktif: `Indonesia`
        *   Mata Uang/Harga: `IDR` (Rupiah)
        *   Bahasa Default: `id` (Bahasa Indonesia)
    *   **Jika Negara != 'ID' (Global/Luar Indonesia):**
        *   Region aktif: `Global`
        *   Mata Uang/Harga: `USD` (Dollar)
        *   Bahasa Default: `en` (Bahasa Inggris)
*   **Sistem Penyimpanan Pilihan / Override:** 
    Hasil deteksi IP akan disimpan ke dalam **Session / Cookie** pengunjung (misalnya cookie `zewalo_locale_preference`). Mengapa?
    *   Agar sistem tidak membebani server dengan melakukan cek GeoIP di setiap kali pindah ralaman.
    *   Memungkinkan fungsionalitas **Language Switcher (Dropdown Bahasa)** di header/footer website. Pengunjung Indonesia (region ID) tetap bisa memaksakan mengganti tampilan ke Bahasa Inggris (EN) jika mereka menginginkan.

## 2. Dynamic Pricing & Mata Uang (Regional Pricing)
**Tujuan:** Memisahkan harga langganan untuk pasar lokal dan pasar internasional secara mulus (*seamless*), terinspirasi dari strategi harga Adobe.

*   **Struktur Database Paket (`plans` / `subscriptions`):**
    Pembaruan pada tabel database layanan/paket agar mendukung *multi-currency*.
    *   Opsi penyediaan kolom: Menambahkan dua tipe harga per paket seperti `price_idr` dan `price_usd`.
    *   Opsi terpusat (Relasional): Membuat tabel `plan_prices` yang isinya `plan_id`, `currency_code` (IDR/USD), dan `amount`.
*   **Tampilan Sisi Klien (Frontend):**
    Saat halaman Pricing pada *Central Homepage* dimuat, controller website akan mengidentifikasi: *Apakah Session Region saat ini ID?*. Jika ya, render daftar harga dengan nilai `price_idr` (berformat Rp). Jika bukan, panggil `price_usd` (berformat $). Tidak butuh duplikasi file `.blade.php`—semuanya dirender dinamis dalam satu file.
*   **Dampak Skema Billing/Invoice:**
    Sistem invoice dan integrasi payment gateway (seperti Xendit/Stripe) secara adaptif menarik konfigurasi mata uang sesuai deteksi region. Wilayah ID via IDR channels (VA/QRIS), wilayah luar via USD channels (Credit Card otomatis dikonversi atau dibebankan USD).

## 3. Translation Panel & Ekstraksi Manajemen Bahasa
**Tujuan:** Mempermudah tim (khususnya non-developer) untuk melakukan penambahan atau revisi bahasa pada seluruh sistem langsung lewat antarmuka Central Admin.

*   **Implementasi Database Translation System:**
    Alih-alih menyusun terjemahan di file kode/sistem seperti `lang/en/home.php`, sistem digunakan untuk memuat file dari Database. 
    *   Pemanfaatan Library seperti `spatie/laravel-translation-loader`.
    *   Semua "key" teks di website disimpan di database pada tabel `language_lines`.
*   **Pembuatan Translation Panel (Filament):**
    Pada Dashboard Superadmin (*Central Admin* Filament), akan ditambahkan sebuah fitur khusus bernama **"Translations Manager"**.
    *   Menampilkan UI seperti Excel / Tabel di mana terdapat kolom `Translation Key`, `Bahasa Indonesia`, `English`.
    *   Jika ada terjemahan UI yang bocor atau kaku hasil translate AI, SuperAdmin dapat meng-edit teksnya langsung lewat Filament, Klik *Save*, dan *Central Homepage* otomatis memperbarui kalimatnya tanpa perlu me-*restart/redeploy* aplikasi.

## 4. Frontend In-Context Translation (Saran Pemantik Kemudahan Edit)
**Tujuan:** Membantu reviewer (developer/client) mengidentifikasi dan menerjemahkan kalimat secara *point-and-click* di halaman browser saat membuka frontend Zewalo.

*   **Fitur Translation Mode (Bagi Admin):**
    *   Admin mendapat sebuah saklar (*toggle switch*) "UI Translation Mode".
    *   Jika aktif, setiap teks di elemen *Central Homepage* (seperti Hero Title, Teks Deskripsi, Tombol Pricing) akan dibingkai *dashed-box* ringan.
    *   Saat teks tersebut di-*click*, sebuah popup modal kecil (misal menggunakan Livewire) tertampil: *"Edit Translate untuk Key: homepage.hero_title"*. Admin bisa langsung mengetik revisi terjemahan bahasa Inggris / Indonesia, Simpan, dan halaman otomatis berubah. Ini menghindari kerepotan pencarian *Key Translation* satu.

## 5. Rencana Tahapan Eksekusi Development (Roadmap)
Urutan logis pengerjaan apabila perancangan ini disetujui untuk dikoding:
1.  **Fase 1 - Database Schema & Library Initialization:** Instalasi library Language Loader, persiapan Migration tabel `language_lines`, dan pengaturan Middleware GeoIP localization.
2.  **Fase 2 - Refactoring Teks Front-End:** Menyisir seluruh blade *Central Homepage* / komponen untuk mengubah teks *hardcoded* menjadi Laravel translate helper (contoh: dari tulisan `Welcome` menjadi `@lang('homepage.welcome')`).
3.  **Fase 3 - Panel Dashboard:** Pembuatan CRUD *Translation Panel* di antarmuka Central Admin pengguna panel Filament untuk kelola data dari Fase 2.
4.  **Fase 4 - Dynamic Pricing:** Eksekusi skema pembedaan kolom rupiah/dollar beserta integrasinya pada halaman Penjualan dan pendaftaran Tenant Zewalo.
5.  **Fase 5 - Final Review:** Implementasi opsional dari In-Context (Frontend) click-to-edit translation tool untuk pemolesan operasional bahasa.
