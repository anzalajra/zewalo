# Panduan Implementasi Schedule Calendar di Store Front

Berikut adalah langkah-langkah implementasi (planning) untuk menambahkan halaman Schedule Calendar di Store Front, yang menampilkan seluruh rental terbooking dengan berbagai status, serta menyematkan menu "Schedule" di navigasi.

## 1. Menambahkan Route Baru untuk Schedule
Buka file `routes/web.php`. Tambahkan route untuk halaman schedule di dalam blok `Public Routes` atau di bawah route `Catalog`:

```php
// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Tambahkan Route Ini
Route::get('/schedule', [\App\Http\Controllers\Frontend\ScheduleController::class, 'index'])->name('frontend.schedule'); 
```

## 2. Membuat Controller Baru (`ScheduleController`)
Buat file controller baru di `app/Http/Controllers/Frontend/ScheduleController.php`. Fitur ini akan mengambil data tabel `Rental`  dan mengirimkannya (passing data) ke view kalender. 

Jalankan perintah ini di terminal:
```bash
php artisan make:controller Frontend/ScheduleController
```

Controller baru nantinya perlu me-map data rental ke array berbentuk _event_ kalender (mengandung field seperti `title`, `start`, `end`, `color`). Silakan samakan map warnanya dengan konfigurasi `$colorMap` yang ada di `app/Filament/Pages/Schedule.php` atau `RentalCalendarWidget`.

Contoh return pada controller:
```php
return view('frontend.schedule.index', compact('events'));
```

## 3. Membuat Tampilan Blade Baru (`index.blade.php`)
Buat direktori dan file baru secara manual di: `resources/views/frontend/schedule/index.blade.php`.
File ini akan memuat layout store front dan memakai library **FullCalendar** (karena Filament's calendar tidak secara native di-expose untuk desain store front tanpa otentikasi). Alternatifnya Anda bisa menggunakan custom HTML yang sama dengan "By Unit" timeline di admin schedule. 

Berikut adalah contoh kerangka `index.blade.php` dengan FullCalendar JS CDN:
```blade
@extends('layouts.frontend')

@section('title', 'Schedule Calendar')

@push('styles')
<!-- Load FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-900">Rental Schedule</h1>
    
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
        <!-- Render Calendar Di Sini -->
        <div id="calendar"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($events), // Data ini dipassing dari controller
            eventDisplay: 'block',
        });
        calendar.render();
    });
</script>
@endpush
```

## 4. Menambahkan Menu Navigasi Header "Schedule"
Dari pengecekan pada file `resources/views/layouts/frontend.blade.php`, Zewalo menggunakan fallback array secara default dan plugin `LaraZeus/Sky` jika user sudah men-setup "Navigation". 

Tergantung dari setup lokal saat ini:

* **Opsi 1: Menambahkan dari Central Admin (Database Builder) - Jika Navigation Plugin aktif**
   1. Akses Admin panel dan masuk ke menu form **Navigation**.
   2. Pilih handle/menu `main-menu` atau `navigation`.
   3. Tambahkan Custom Link/URL menu baru berlabel `Schedule` dengan URL `/schedule`.

* **Opsi 2: Hardcode ke File Layout (Fallback Array)**
   Buka file `resources/views/layouts/frontend.blade.php`. 
   Cari variabel `$menuItems` (sekitar line 97) yang isinya adalah array fallback, lalu selipkan route schedule:

```php
// Fallback logic layout
$menuItems = [
    ['label' => 'Home', 'url' => url('/'), 'target' => '_self'],
    ['label' => 'Catalog', 'url' => route('catalog.index'), 'target' => '_self'],
    ['label' => 'Schedule', 'url' => route('frontend.schedule'), 'target' => '_self'], // <- Tambahkan baris ini
];
```

Ikuti langkah-langkah di atas jika sudah ingin menerapkan halaman schedule public store front. File ini hanya dokumen planning dan bebas Anda ubah.
