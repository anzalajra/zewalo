<x-mail::message>
# Pemesanan Baru Masuk 🛒

Ada pemesanan baru yang perlu ditinjau.

<x-mail::panel>
**Detail Pemesanan**

| | |
|---|---|
| **Kode Pemesanan** | {{ $rentalCode }} |
| **Pelanggan** | {{ $customerName }} |
| **Tanggal Mulai** | {{ $startDate }} |
| **Tanggal Selesai** | {{ $endDate }} |
| **Total** | Rp {{ number_format((float) $total, 0, ',', '.') }} |
| **Status** | ⏳ Menunggu Konfirmasi |
</x-mail::panel>

<x-mail::button :url="$rentalUrl">
Tinjau & Konfirmasi Pemesanan
</x-mail::button>

Segera konfirmasi atau tolak pemesanan ini untuk memberikan kepastian kepada pelanggan.

Salam,<br>
Sistem {{ config('app.name') }}
</x-mail::message>
