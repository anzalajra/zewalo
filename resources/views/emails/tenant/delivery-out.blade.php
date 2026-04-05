<x-mail::message>
# Surat Jalan Keluar Dibuat 📦

Surat jalan keluar baru telah dibuat.

<x-mail::panel>
**Detail Pengiriman**

| | |
|---|---|
| **No. Surat Jalan** | {{ $deliveryNumber }} |
| **Pemesanan** | {{ $rentalCode }} |
| **Pelanggan** | {{ $customerName }} |
| **Tanggal** | {{ $deliveryDate }} |
| **Status** | 🚚 Keluar |
</x-mail::panel>

@if (!empty($items))
**Item yang Dikirim:**

@foreach ($items as $item)
- {{ $item }}
@endforeach
@endif

<x-mail::button :url="$deliveryUrl">
Lihat Detail Surat Jalan
</x-mail::button>

Salam,<br>
Sistem {{ config('app.name') }}
</x-mail::message>
