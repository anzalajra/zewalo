<x-mail::message>
# Peringatan: Pemesanan/Pembayaran Terlambat ⚠️

Halo **{{ $recipientName }}**,

Terdapat pemesanan atau pembayaran yang **sudah melewati jatuh tempo** dan memerlukan tindakan segera.

<x-mail::panel>
**Detail**

| | |
|---|---|
| **Kode Pemesanan** | {{ $rentalCode }} |
| **Pelanggan** | {{ $customerName }} |
| **Jatuh Tempo** | {{ $dueDate }} |
| **Keterlambatan** | {{ $overdaysDays }} hari |
@if (!empty($lateFeeAmount))
| **Denda Keterlambatan** | Rp {{ number_format((float) $lateFeeAmount, 0, ',', '.') }} |
@endif
</x-mail::panel>

<x-mail::button :url="$rentalUrl" color="red">
Tindak Lanjuti Sekarang
</x-mail::button>

Segera selesaikan agar tidak berdampak lebih lanjut.

Salam,<br>
Sistem {{ config('app.name') }}
</x-mail::message>
