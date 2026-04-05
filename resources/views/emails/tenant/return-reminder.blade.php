<x-mail::message>
# Pengingat: Jadwal Pengembalian ⏰

Halo **{{ $customerName }}**,

Ini adalah pengingat bahwa jadwal **pengembalian barang** pesanan Anda sudah dekat.

<x-mail::panel>
**Detail Pemesanan**

| | |
|---|---|
| **Kode Pemesanan** | {{ $rentalCode }} |
| **Tanggal Pengembalian** | {{ $returnDate }} |
| **Lokasi Pengembalian** | {{ $location }} |
</x-mail::panel>

<x-mail::button :url="$rentalUrl">
Lihat Detail Pemesanan
</x-mail::button>

Pastikan barang dikembalikan dalam kondisi baik dan tepat waktu untuk menghindari biaya keterlambatan.

Terima kasih telah memilih **{{ $storeName }}**!

Salam,<br>
Tim {{ $storeName }}
</x-mail::message>
