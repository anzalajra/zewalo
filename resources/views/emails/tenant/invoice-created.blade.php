<x-mail::message>
# Invoice Baru Dibuat 🧾

Invoice baru telah diterbitkan.

<x-mail::panel>
**Detail Invoice**

| | |
|---|---|
| **Nomor Invoice** | {{ $invoiceNumber }} |
| **Pemesanan** | {{ $rentalCode }} |
| **Pelanggan** | {{ $customerName }} |
| **Total** | Rp {{ number_format((float) $total, 0, ',', '.') }} |
| **Jatuh Tempo** | {{ $dueDate }} |
</x-mail::panel>

<x-mail::button :url="$invoiceUrl">
Lihat Invoice
</x-mail::button>

Salam,<br>
Sistem {{ config('app.name') }}
</x-mail::message>
