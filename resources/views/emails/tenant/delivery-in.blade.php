<x-mail::message>
# Surat Jalan Masuk Dibuat 📥

Surat jalan masuk (pengembalian) baru telah dibuat.

<x-mail::panel>
**Detail Pengembalian**

| | |
|---|---|
| **No. Surat Jalan** | {{ $deliveryNumber }} |
| **Pemesanan** | {{ $rentalCode }} |
| **Pelanggan** | {{ $customerName }} |
| **Tanggal** | {{ $deliveryDate }} |
| **Status** | 📥 Kembali |
</x-mail::panel>

@if (!empty($items))
**Item yang Dikembalikan:**

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
