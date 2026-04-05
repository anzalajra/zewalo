<x-mail::message>
# Selamat Datang di {{ config('app.name') }}! 🎉

Halo **{{ $storeName }}**,

Toko Anda telah berhasil dibuat dan siap untuk digunakan. Sekarang Anda bisa masuk ke dashboard admin dan mulai mengelola bisnis penyewaan Anda.

**Detail Toko:**
- **Nama Toko:** {{ $storeName }}
- **Domain:** {{ $domain }}
- **Email Admin:** {{ $adminEmail }}

<x-mail::button :url="$loginUrl">
Masuk ke Dashboard Admin
</x-mail::button>

**Langkah Selanjutnya:**
1. Masuk menggunakan email dan password yang didaftarkan
2. Lengkapi profil dan pengaturan toko Anda
3. Tambahkan produk dan unit yang tersedia untuk disewa
4. Bagikan link toko ke pelanggan Anda

Jika ada pertanyaan atau kendala, tim support kami siap membantu.

Salam,<br>
Tim {{ config('app.name') }}
</x-mail::message>
