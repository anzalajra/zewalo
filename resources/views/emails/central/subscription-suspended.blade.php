<x-mail::message>
# Akun Anda Telah Disuspend ⚠️

Halo **{{ $tenantName }}**,

Kami menginformasikan bahwa akun toko Anda di **{{ config('app.name') }}** telah disuspend karena terdapat tagihan langganan yang belum dibayar.

**Dampak Suspensi:**
- Anda tidak dapat mengakses dashboard admin toko
- Pelanggan tidak dapat melakukan transaksi baru
- Data Anda tetap aman dan tersimpan

Untuk mengaktifkan kembali akun Anda, segera lakukan pembayaran tagihan yang tertunggak.

<x-mail::button :url="$paymentUrl" color="red">
Bayar & Aktifkan Kembali
</x-mail::button>

Jika Anda telah melakukan pembayaran atau membutuhkan bantuan, silakan hubungi tim support kami segera.

Salam,<br>
Tim {{ config('app.name') }}
</x-mail::message>
