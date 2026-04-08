# Planning Fitur: Administration Checklist

## 1. Analisis Kebutuhan Deskripsi
Fitur "Administration Checklist" ini merupakan checklist interaktif berjumlah 4 tahapan untuk peminjam, dimulai sejak quotation terbentuk hingga sebelum melakukan pengambilan (pickup) barang. 

Cakupan Fitur:
- Tracking per step untuk administrasi (sampai step 4).
- Integrasi ke WhatsApp admin warehouse dengan template pesan otomatis.
- Menampilkan step tracking UI di halaman `checkout/success` dan `customer/rentals/{id}`.
- Perubahan pada sistem Settings di admin panel untuk mengakomodasi nomor WA warehouse.

## 2. Struktur Database Baru

Karena tahapan step ini membutuhkan tracking (seperti ketika pelanggan mendownload checklist atau memencet tombol link template Google Doc), kita perlu menyimpan state tersebut ke dalam database.

**Rekomendasi Perubahan Database:**
Menambahkan kolom baru pada tabel `rentals` menggunakan migration:
- `checklist_downloaded_at` (timestamp, nullable): Menyimpan kapan user download checklist form. (Step 2 selesai)
- `permit_template_clicked_at` (timestamp, nullable): Menyimpan kapan user memencet tombol view template surat perizinan. (Step 3 selesai)

Atau jika ingin dibuat lebih generic, tambah kolom:
- `admin_step_2_done` (boolean, default false)
- `admin_step_3_done` (boolean, default false)

*(Note: Step 1 otomatis dianggap selesai jika status rental berubah dari `quotation` menjadi `confirmed` atau lainnya oleh admin. Step 4 adalah tahap final/menunggu).*

## 3. Modifikasi Halaman Success Checkout
**File Target:** `resources/views/frontend/checkout/success.blade.php`

**Perubahan yang dilakukan:**
1. Hilangkan bagian `What's Next?`.
2. Tambahkan UI komponen "Administrasi" yang menampilkan 4 langkah (Steppers). Pada halaman ini, karena baru checkout, UI step secara default masih di **Step 1**.
3. Ubah tombol aksi di bawah menjadi:
   - **Tombol Konfirmasi ke WhatsApp Warehouse** (warna primary/hijau).
   - **View My Rentals** (warna sekunder/abu-abu).
4. Logic pembuatan Link WhatsApp di *Controller* atau langsung di *Blade*. Mengambil nomor WA Admin dari Settings, serta mengambil info *Nama Customer*, *NIM*, dan *Nomor Booking* (`rental_code`), berikut link menuju admin panel rental detail.

## 4. Modifikasi Halaman Rental Detail
**File Target:** `resources/views/frontend/dashboard/rental-detail.blade.php`
**File Target Backend:** `app/Http/Controllers/CustomerDashboardController.php` (untuk endpoint klik surat perizinan)

**Perubahan yang dilakukan:**
1. Di bawah informasi nomor seri rental (misal: RNT202604080008) tambahkan komponen **Administration Checklist** (4 lingkaran step).
2. Logic pewarnaan step (Abu-abu untuk yang belum, Hijau/Biru untuk yang sedang aktif/selesai):
   - **Step 1 (Konfirmasi WA):** Aktif jika status `quotation`. Jika selesai (status >= `confirmed`), step ini menjadi Checked/Selesai.
   - **Step 2 (Download Checklist):** Terbuka jika status `confirmed`. Menampilkan tombol Download. Mengarah ke file Checklist PDF yang sudah ada (misal melalui route `public-documents.rental.checklist`). Jika di klik, update DB `checklist_downloaded_at`.
   - **Step 3 (Surat Perizinan):** Terbuka setelah step 2 selesai, atau bisa dibuat beriringan. Saat memencet link template Google Doc perizinan, sistem akan me-*trigger* endpoint via AJAX/Form Submit untuk mengupdate `permit_template_clicked_at` = true, lalu melanjutkannya ke tahapan akhir (otomatis selesai).
   - **Step 4 (Pengambilan Fisik):** Terbuka jika step 3 sudah dipencet. Menampilkan informasi dinamis: *"Bawa dokumen fisik saat pengambilan pada [Tanggal Mulai Rental (d M Y)] [Jam Mulai (H:i)]"*.

## 5. Konfigurasi WhatsApp Template & Settings
**File Target:** `app/Filament/Pages/...` (Kemungkinan di Settings page atau Notification Settings)

**Perubahan yang dilakukan:**
1. Menambahkan input `warehouse_whatsapp_number` pada page settings.
2. Tambahkan input `permit_document_google_doc_link` untuk mengelola tautan Google Doc Surat Perizinan secara dinamis agar Admin bisa merubah linknya sewaktu-waktu.
3. Template Pesan (disusun di Blade Layout Success dan Detail):
```text
Halo admin warehouse, saya {{ $customer->name }} {{ $customer->custom_nim ?? '' }} ingin konfirmasi booking {{ $rental->rental_code }}.

Mohon konfirmasi booking:
{{ url('/admin/rentals/' . $rental->id) }}
```
*(Catatan: pastikan field NIM customer terpetakan dengan benar sesuai kolom custom field saat registrasi).*

## 6. Route Baru
**File Target:** `routes/web.php`

Tambahkan endpoint untuk tracker interaksi user (Step 2 dan 3):
- `POST /customer/rentals/{id}/mark-checklist-downloaded` 
- `POST /customer/rentals/{id}/mark-permit-clicked` 
(Atau 1 endpoint update step administrasi dengan parameter flag).

## Ringkasan Action Plan untuk Developer (Eksekusi code nantinya)
1. Buat migration penambahan kolom administrasi di tabel `rentals`.
2. Update Filament Setting page untuk nomor WA & link google doc template.
3. Buat UI Stepper (4 steps) menggunakan Tailwind CSS (lingkaran aktif/tidak).
4. Update view `success.blade.php`.
5. Update view `rental-detail.blade.php`.
6. Tulis AJAX / Controller endpoint untuk update progress saat klik download/view link template.

*Dokumen ini merupakan perencanaan (Planning) teknikal yang dapat langsung diterapkan di codebase Zewalo.*
