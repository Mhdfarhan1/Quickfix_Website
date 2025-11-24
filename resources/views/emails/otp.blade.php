@component('mail::message')
# 🔐 Verifikasi Akun Kamu

Halo **{{ $userName ?? 'Pengguna' }}**,  
Terima kasih telah mendaftar di **{{ $appName }}**!

Untuk menyelesaikan proses registrasi, silakan gunakan kode verifikasi berikut:

@component('mail::panel')
# {{ $otp }}
@endcomponent

Kode OTP ini hanya berlaku selama **{{ $minutes }} menit**.  
Jangan berikan kode ini kepada siapa pun demi keamanan akun kamu.

@component('mail::subcopy')
Jika kamu tidak merasa meminta kode ini, kamu dapat mengabaikan email ini.  
Email ini dikirim otomatis oleh sistem dan tidak perlu dibalas.
@endcomponent

Terima kasih,  
Tim **{{ $appName }}**
@endcomponent
