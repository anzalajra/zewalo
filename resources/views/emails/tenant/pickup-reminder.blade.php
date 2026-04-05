<x-mail::message>
# Pengingat: Jadwal Pengambilan ⏰

Halo **{{ $customerName }}**,

Ini adalah pengingat bahwa jadwal **pengambilan barang** pesanan Anda sudah dekat.

<x-mail::panel>
**Detail Pemesanan**

| | |
|---|---|
| **Kode Pemesanan** | {{ $rentalCode }} |
| **Tanggal Pengambilan** | {{ $pickupDate }} |
| **Lokasi** | {{ $location }} |
</x-mail::panel>

<x-mail::button :url="$rentalUrl">
Lihat Detail Pemesanan
</x-mail::button>

Pastikan Anda hadir tepat waktu. Jika ada kendala, segera hubungi kami.

Terima kasih telah memilih **{{ $storeName }}**!

Salam,<br>
Tim {{ $storeName }}
</x-mail::message>
