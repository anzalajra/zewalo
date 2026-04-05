<x-mail::message>
# Pemesanan Dikonfirmasi ✅

Halo **{{ $customerName }}**,

Pemesanan Anda telah **dikonfirmasi**. Berikut detail pesanan Anda:

<x-mail::panel>
**Detail Pemesanan**

| | |
|---|---|
| **Kode Pemesanan** | {{ $rentalCode }} |
| **Tanggal Mulai** | {{ $startDate }} |
| **Tanggal Selesai** | {{ $endDate }} |
| **Total** | Rp {{ number_format((float) $total, 0, ',', '.') }} |
| **Status** | ✅ Dikonfirmasi |
</x-mail::panel>

<x-mail::button :url="$rentalUrl">
Lihat Detail Pemesanan
</x-mail::button>

Pastikan Anda hadir sesuai jadwal untuk pengambilan barang. Jika ada perubahan atau pertanyaan, silakan hubungi kami.

Terima kasih telah memilih **{{ $storeName }}**!

Salam,<br>
Tim {{ $storeName }}
</x-mail::message>
