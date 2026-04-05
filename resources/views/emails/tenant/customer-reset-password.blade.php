<x-mail::message>
# Reset Password

Halo **{{ $customerName }}**,

Kami menerima permintaan untuk mereset password akun Anda di **{{ $storeName }}**.

Klik tombol di bawah untuk mengatur password baru. Tautan ini berlaku selama **{{ $expiryMinutes }} menit**.

<x-mail::button :url="$resetUrl" color="red">
Reset Password
</x-mail::button>

Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.

Salam,<br>
Tim {{ $storeName }}
</x-mail::message>
