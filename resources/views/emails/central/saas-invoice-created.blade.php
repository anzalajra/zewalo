<x-mail::message>
# Invoice Langganan Baru

Halo **{{ $tenantName }}**,

Invoice langganan baru telah dibuat untuk akun Anda. Silakan lakukan pembayaran sebelum tanggal jatuh tempo untuk menghindari gangguan layanan.

<x-mail::panel>
**Detail Invoice**

| | |
|---|---|
| **Nomor Invoice** | {{ $invoiceNumber }} |
| **Periode** | {{ $period }} |
| **Total Tagihan** | Rp {{ number_format((float) $total, 0, ',', '.') }} |
| **Jatuh Tempo** | {{ $dueAt }} |
</x-mail::panel>

<x-mail::button :url="$paymentUrl">
Bayar Sekarang
</x-mail::button>

Jika Anda memiliki pertanyaan mengenai tagihan ini, silakan hubungi tim kami.

Salam,<br>
Tim {{ config('app.name') }}
</x-mail::message>
