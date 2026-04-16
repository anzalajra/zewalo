# Tenant Setup Wizard - Implementation Plan

## Overview
Dokumen ini berisi perencanaan implementasi fitur Tenant Setup Wizard di Zewalo. Wizard ini berfungsi sebagai proses *onboarding* untuk tenant yang baru registrasi, memandu mereka melakukan konfigurasi wajib sebelum menggunakan platform.

## Core Behaviors & Logic
1. **Trigger Condition**: 
   - Terdapat penanda (flag) di database (misalnya pada tabel `tenants`, contoh: kolom `setup_status` dengan nilai `pending`, `completed`, atau `skipped`).
   - Ketika tenant baru login ke admin panel untuk pertama kali, Setup Wizard akan otomatis muncul (bisa berupa *full screen modal* atau halaman khusus).
2. **Interruption Handling**: 
   - Jika pengguna tidak sengaja menutup halaman atau berpindah halaman sebelum wizard selesai (dan belum menekan *skip*), sistem (melalui *middleware* atau *global layout component* Filament) akan mendeteksi status `pending` tersebut.
   - Sebuah popup/banner *non-intrusive* akan muncul di halaman admin untuk mengingatkan dan memberikan tombol "Lanjutkan Setup".
3. **Skip Functionality**: 
   - Terdapat tombol "Skip Setup" di dalam wizard.
   - Jika ditekan, flag `setup_status` akan berubah menjadi `skipped`/`completed`, dan pop-up peringatan tidak akan muncul lagi. Pengguna bisa mengatur aplikasi secara manual melalui menu *Settings*.

## Step-by-Step Breakdown

### Step 1: Basic Store Information
**Tujuan**: Mengumpulkan identitas utama toko dan pengaturan visual.
**Input & Aksi**:
- **Upload Logo Toko**:
  - *Action*: Disimpan ke `General Setting`. Sistem juga secara otomatis meng-copy/menyimpan referensi gambar ini ke `Document Layout Settings` (untuk keperluan logo di invoice/tanda terima).
- **Nama Toko**:
  - *Action*: *Pre-filled* (sudah terisi otomatis) mengambil dari data saat awal registrasi tenant. Bisa diedit.
- **Kategori Toko**:
  - *Action*: *Pre-filled* mengambil dari data saat registrasi. Bisa diedit.
- **Alamat, Nomor Telepon, Email**:
  - *Action*: Input teks yang akan disimpan langsung ke `General Setting`.
- **Theme Preset (Warna Tema)**:
  - *Action*: Pemilihan warna tema yang akan menetapkan pengaturan visual di `Appearance Setting`.

### Step 2: Operational Hours
**Tujuan**: Mengatur jam operasional toko untuk keperluan peminjaman.
**Input & Aksi**:
- **Jam Operasional**:
  - *Action*: Form untuk mengatur jam operasional toko. Data ini akan langsung disinkronisasi dan disimpan ke `Rental Settings`.

### Step 3: Payment Configuration
**Tujuan**: Menyiapkan metode pembayaran manual agar tenant bisa langsung menerima pesanan.
**Input & Aksi**:
- **Bank Transfer Details**:
  - *Action*: Input detail Nama Bank, Nomor Rekening, dan Atas Nama. Disimpan ke data `Payment Settings` untuk metode manual transfer bank.

### Step 4: Seed Data / Template Import
**Tujuan**: Mengisi toko dengan *dummy data* sesuai kategori agar tenant mendapat gambaran utuh bagaimana sistem bekerja.
**Input & Aksi**:
- **Auto-detection**: Membaca "Kategori Toko" yang dipilih/disimpan pada Step 1.
- **Prompt**: Menampilkan opsi pesan/tombol "Import Template [Kategori Toko]" (misal: "Import Template Kamera").
- **Proses Import**:
  - Jika disetujui, sistem akan menjalankan *seeder* khusus sesuai kategori.
  - *Contoh*: Jika kategori Kamera, sistem akan otomatis men-generate 3 produk lengkap dengan foto *placeholder*, deskripsi produk, harga, dan konfigurasi Kit-nya.

## Integrasi Auto-fill Document Layout
Untuk memenuhi kebutuhan perizinan integrasi di tab Company Information:
- **Otomatisasi**: Sistem akan mendengarkan proses simpan pada Step 1 (dan juga apabila di kemudian hari tenant mengubah `General Setting` atau Business Information).
- **Sinkronisasi**: Data Nama Toko, Alamat, Email, No Telepon, dan Logo yang diinput akan otomatis diisikan *(auto fill)* ke dalam field `Company Information` pada `Document Layout Settings`.

## Kebutuhan Teknis (Technical Task List)
1. **Migration Update**: Menambahkan kolom `setup_status` (string) di tabel `tenants` (default 'pending').
2. **Middleware / Layout Component**: Membuat UI prompt pengecekan global di layout Filament (mungkin via `RenderHook`) untuk menampilkan popup meneruskan setup.
3. **Setup Wizard Component**: Membuat Livewire component berbentuk Multi-step Wizard UI.
4. **Data Seeders**: Menyiapkan file seeder per kategori toko (contoh: `CameraTenantSeeder`) yang akan berisi set data produk dummy + foto.
5. **Event / Observer**: Membuat Event/Observer di model/setting untuk sinkronisasi data otomatis antara entity profil bisnis (`GeneralSetting`) dan `DocumentLayoutSetting`.
