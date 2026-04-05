# Instruksi Claude Code: Integrasi Amazon SES ke Zewalo

> Jalankan file ini sebagai panduan kerja Claude Code di root project Zewalo.
> Semua perintah dijalankan secara berurutan. Jangan lewati step manapun.

---

## Konteks Proyek

- Framework: Laravel (dengan Filament & Livewire)
- Repo: github.com/anzalajra/zewalo
- Mail driver target: Amazon SES v2
- Queue: database driver
- VPS: Jetorbit, Ubuntu 22.04, deploy via Dokploy

---

## STEP 1 — Install AWS SDK

Jalankan perintah berikut di terminal root project:

```bash
composer require aws/aws-sdk-php
```

Verifikasi bahwa `aws/aws-sdk-php` muncul di `composer.json` setelah instalasi.

---

## STEP 2 — Update `config/services.php`

Buka file `config/services.php`. Tambahkan atau pastikan ada blok berikut di dalam array return:

```php
'ses' => [
    'key'    => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION', 'ap-southeast-1'),
],
```

Jika sudah ada blok `'ses'` lain, ganti isinya dengan yang di atas.

---

## STEP 3 — Update `config/mail.php`

Buka file `config/mail.php`.

**3a.** Pastikan nilai `default` mengarah ke env `MAIL_MAILER`:

```php
'default' => env('MAIL_MAILER', 'log'),
```

**3b.** Di dalam array `'mailers'`, cari atau tambahkan entry `'ses'` dan ubah menjadi:

```php
'ses' => [
    'transport' => 'sesv2',
],
```

Tidak perlu menambahkan key/secret di sini karena sudah dibaca dari `config/services.php`.

---

## STEP 4 — Buat file `.env.example` entry (dokumentasi)

Buka file `.env.example`. Tambahkan baris berikut di bagian Mail (setelah entry MAIL yang sudah ada):

```env
# Amazon SES
MAIL_MAILER=ses
MAIL_FROM_ADDRESS=no-reply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-southeast-1
```

> Jangan isi nilai sesungguhnya di `.env.example`. File `.env` asli akan diisi manual oleh developer di local dan di VPS.

---

## STEP 5 — Buat Queue Migration

Cek apakah tabel `jobs` sudah ada di migration folder (`database/migrations/`).

Jika belum ada file migration untuk tabel `jobs`, jalankan:

```bash
php artisan queue:table
php artisan migrate
```

Jika sudah ada tabel `jobs`, lewati perintah `queue:table` dan langsung jalankan:

```bash
php artisan migrate
```

---

## STEP 6 — Buat folder dan base Mailable

Jalankan perintah artisan berikut untuk membuat Mailable pertama:

```bash
php artisan make:mail TestConnectionMail --markdown=emails.test-connection
```

Ini akan membuat dua file:
- `app/Mail/TestConnectionMail.php`
- `resources/views/emails/test-connection.blade.php`

---

## STEP 7 — Isi `app/Mail/TestConnectionMail.php`

Buka file `app/Mail/TestConnectionMail.php`. Ganti seluruh isinya dengan:

```php
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestConnectionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipientName = 'Admin'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Zewalo] Test Koneksi Amazon SES',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.test-connection',
        );
    }
}
```

---

## STEP 8 — Isi `resources/views/emails/test-connection.blade.php`

Buka file `resources/views/emails/test-connection.blade.php`. Ganti seluruh isinya dengan:

```blade
<x-mail::message>
# Halo, {{ $recipientName }}

Email ini dikirim sebagai uji koneksi Amazon SES dari aplikasi **{{ config('app.name') }}**.

Jika kamu menerima email ini, konfigurasi SES sudah berjalan dengan benar.

<x-mail::button :url="config('app.url')">
Buka Zewalo
</x-mail::button>

Salam,
Tim {{ config('app.name') }}
</x-mail::message>
```

---

## STEP 9 — Buat Route Test (hanya untuk local, WAJIB dihapus sebelum push)

Buka file `routes/web.php`. Tambahkan route berikut di bagian **paling bawah**, dan beri komentar penanda agar mudah dihapus nanti:

```php
// =============================================
// HAPUS ROUTE INI SEBELUM PUSH KE PRODUCTION
// =============================================
Route::get('/test-ses', function () {
    $email = request('email', 'test@example.com');
    \Illuminate\Support\Facades\Mail::to($email)
        ->send(new \App\Mail\TestConnectionMail('Admin'));
    return 'Email terkirim ke: ' . $email;
})->name('test.ses');
// =============================================
```

---

## STEP 10 — Buat Mailable untuk Notifikasi Utama

Jalankan untuk masing-masing jenis notifikasi yang dibutuhkan Zewalo:

```bash
php artisan make:mail UserRegisteredMail --markdown=emails.user-registered
php artisan make:mail PasswordResetMail --markdown=emails.password-reset
php artisan make:mail NotificationAlertMail --markdown=emails.notification-alert
```

---

## STEP 11 — Isi `app/Mail/UserRegisteredMail.php`

Ganti seluruh isinya dengan:

```php
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $userName,
        public string $loginUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Datang di ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.user-registered',
        );
    }
}
```

---

## STEP 12 — Isi `resources/views/emails/user-registered.blade.php`

Ganti seluruh isinya dengan:

```blade
<x-mail::message>
# Selamat Datang, {{ $userName }}!

Akun kamu di **{{ config('app.name') }}** telah berhasil dibuat.

Kamu sekarang bisa masuk dan mulai menggunakan aplikasi.

<x-mail::button :url="$loginUrl">
Masuk ke Zewalo
</x-mail::button>

Jika kamu tidak merasa mendaftar, abaikan email ini.

Salam,
Tim {{ config('app.name') }}
</x-mail::message>
```

---

## STEP 13 — Isi `app/Mail/PasswordResetMail.php`

Ganti seluruh isinya dengan:

```php
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $userName,
        public string $resetUrl,
        public int $expiryMinutes = 60
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[' . config('app.name') . '] Reset Password',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.password-reset',
        );
    }
}
```

---

## STEP 14 — Isi `resources/views/emails/password-reset.blade.php`

Ganti seluruh isinya dengan:

```blade
<x-mail::message>
# Reset Password

Halo, {{ $userName }}.

Kami menerima permintaan reset password untuk akun kamu di **{{ config('app.name') }}**.

Klik tombol di bawah untuk mengatur password baru. Tautan ini berlaku selama **{{ $expiryMinutes }} menit**.

<x-mail::button :url="$resetUrl" color="red">
Reset Password
</x-mail::button>

Jika kamu tidak meminta reset password, abaikan email ini. Password kamu tidak akan berubah.

Salam,
Tim {{ config('app.name') }}
</x-mail::message>
```

---

## STEP 15 — Isi `app/Mail/NotificationAlertMail.php`

Ganti seluruh isinya dengan:

```php
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $userName,
        public string $title,
        public string $body,
        public string|null $actionUrl = null,
        public string|null $actionLabel = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[' . config('app.name') . '] ' . $this->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.notification-alert',
        );
    }
}
```

---

## STEP 16 — Isi `resources/views/emails/notification-alert.blade.php`

Ganti seluruh isinya dengan:

```blade
<x-mail::message>
# {{ $title }}

Halo, {{ $userName }}.

{{ $body }}

@if ($actionUrl && $actionLabel)
<x-mail::button :url="$actionUrl">
{{ $actionLabel }}
</x-mail::button>
@endif

Salam,
Tim {{ config('app.name') }}
</x-mail::message>
```

---

## STEP 17 — Update `app/Providers/AppServiceProvider.php`

Buka file `app/Providers/AppServiceProvider.php`. Di dalam method `boot()`, pastikan sudah ada baris berikut (untuk force HTTPS, yang sudah pernah ditambahkan sebelumnya):

```php
\Illuminate\Support\Facades\URL::forceScheme('https');
```

Tidak ada perubahan tambahan yang diperlukan untuk SES di file ini.

---

## STEP 18 — Verifikasi Struktur File

Setelah semua step selesai, verifikasi bahwa file-file berikut ada dan tidak kosong:

```
app/
  Mail/
    TestConnectionMail.php
    UserRegisteredMail.php
    PasswordResetMail.php
    NotificationAlertMail.php

resources/views/emails/
    test-connection.blade.php
    user-registered.blade.php
    password-reset.blade.php
    notification-alert.blade.php

config/
    mail.php        ← pastikan 'default' => env('MAIL_MAILER') dan mailer 'ses' transport sesv2
    services.php    ← pastikan ada blok 'ses'
```

Jalankan perintah berikut untuk memastikan tidak ada syntax error:

```bash
php artisan config:clear
php artisan view:clear
php artisan optimize:clear
```

---

## STEP 19 — Commit ke Git

```bash
git add .
git commit -m "feat: integrate Amazon SES v2 for email notifications

- Install aws/aws-sdk-php
- Configure sesv2 mail driver
- Add queue:database setup
- Add Mailable classes: TestConnection, UserRegistered, PasswordReset, NotificationAlert
- Add blade email templates with markdown
- Add test route (LOCAL ONLY, to be removed before production)"

git push origin main
```

> ⚠️ Sebelum push ke main, pastikan route `/test-ses` di `routes/web.php` sudah **dihapus** jika tidak ingin route itu ada di production. Atau bungkus dengan `if (app()->isLocal())`.

---

## STEP 20 — Yang Harus Dilakukan Manual di VPS (bukan oleh Claude Code)

Setelah push dan Dokploy men-deploy ulang, SSH ke VPS dan jalankan:

```bash
# Masuk ke container Zewalo (sesuaikan nama container di Dokploy)
docker exec -it <nama-container> bash

# Set environment variables di Dokploy dashboard, bukan di file .env langsung:
# MAIL_MAILER=ses
# AWS_ACCESS_KEY_ID=<isi dari IAM>
# AWS_SECRET_ACCESS_KEY=<isi dari IAM>
# AWS_DEFAULT_REGION=ap-southeast-1
# MAIL_FROM_ADDRESS=no-reply@warehouse2.ftvupi.id
# MAIL_FROM_NAME=Zewalo

# Setelah env diisi via Dokploy, clear config cache
php artisan config:clear
php artisan optimize:clear

# Jalankan migration untuk tabel jobs (queue)
php artisan migrate

# Restart queue worker
php artisan queue:restart
```

---

## STEP 21 — Test Pengiriman Email (di Local)

Sebelum test di local, isi `.env` lokal dengan credentials AWS yang sudah dibuat:

```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=AKIAxxxxxxxxxxxxxxxxx
AWS_SECRET_ACCESS_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
AWS_DEFAULT_REGION=ap-southeast-1
MAIL_FROM_ADDRESS=no-reply@warehouse2.ftvupi.id
MAIL_FROM_NAME=Zewalo
```

Kemudian buka browser dan akses:

```
http://localhost/test-ses?email=alamat-email-yang-sudah-diverifikasi-di-SES@gmail.com
```

> Selama masih di Sandbox AWS, email penerima harus sudah diverifikasi dulu di SES Console (Verified Identities → Create Identity → Email Address).

Jika berhasil, browser akan menampilkan:
```
Email terkirim ke: alamat@gmail.com
```

Dan email akan masuk ke inbox (cek juga folder spam).

---

## Catatan Penting untuk Claude Code

1. Jangan ubah file `.env` yang sudah ada — hanya `.env.example` yang boleh dimodifikasi.
2. Jangan hapus konfigurasi mailer lain di `config/mail.php` (seperti `smtp`, `log`, dsb.) — hanya tambahkan/update entry `ses`.
3. Jika ada konflik di `config/services.php` karena sudah ada entry `'ses'` sebelumnya, replace saja dengan versi yang ada di STEP 2.
4. Setelah semua file dibuat, jalankan `php artisan config:clear` sebelum melakukan test apapun.
5. Jika ditemukan error saat `composer require aws/aws-sdk-php`, cek versi PHP yang berjalan — AWS SDK membutuhkan PHP >= 8.0.
