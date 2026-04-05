<x-mail::message>
# Pembayaran Berhasil Diterima ✅

Halo **{{ $tenantName }}**,

Pembayaran Anda telah berhasil dikonfirmasi. Terima kasih! Subscription Anda kini telah aktif.

<x-mail::panel>
**Bukti Pembayaran**

| | |
|---|---|
| **Nomor Invoice** | {{ $invoiceNumber }} |
| **Jumlah Dibayar** | Rp {{ number_format((float) $total, 0, ',', '.') }} |
| **Tanggal Bayar** | {{ $paidAt }} |
| **Status** | ✅ Lunas |
</x-mail::panel>

<x-mail::button :url="$detailUrl">
Lihat Detail Invoice
</x-mail::button>

Subscription Anda akan terus aktif hingga periode berikutnya. Kami akan mengirimkan reminder sebelum jatuh tempo tagihan berikutnya.

Salam,<br>
Tim {{ config('app.name') }}
</x-mail::message>
