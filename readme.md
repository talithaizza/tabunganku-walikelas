# Larabuk
Sistem Informasi Perpustakaan Laravel 5.4 & Admin LTE. Larabuk versi peningkatan dari [Larapus](https://github.com/ryanrahman26/larapus) yang merupakan sample projek dari e-book [Seminggu Belajar Laravel](https://leanpub.com/seminggubelajarlaravel) oleh mas [Rahwat Awaludin](http://facebook.com/rahmat.awaludin).

## Login

**Admin**
- Email: admin@gmail.com
- Password: rahasia

**Member**
- Email: member@gmail.com
- Password: rahasia

## Fitur
**Admin**
- Tambah, Ubah, Hapus Penulis
- Tambah, Ubah, Hapus Buku
- Tambah, Ubah, Hapus Member
- Melihat Detail Peminjaman Member
- Statistics Peminjaman Buku
- Export Buku ke PDF/Excel
- Import Buku dari Excel

**Member**
- Meminjam Buku
- Mengembalikan Buku

**Auth**
- Login
- Register
- Lupa Password
- Ubah Password
- Lihat Profile/Ubah Profile

**Dan masih banyak lagi...**

## Install
- Download repository [ini](https://github.com/ryanrahman26/larabuk/archive/master.zip) kemudian extract di komputar Anda atau clone dengan `git clone https://github.com/ryanrahman26/larabuk.git`
- Buka terminal/command prompt masuk direktori utama dan jalankan `composer install`
- Copy file `.env.examples` menjadi `.env`
- Tambahkan `NOCAPTCHA_SECRET` dan `NOCAPTCHA_SITEKEY` di file `.env` kemudian isi key dari [Google Recaptcha](https://www.google.com/recaptcha)
- Buat database kemudian set `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, di file `.env`
- Set email address di `config/mail.php` dengan email GMail valid karena Larabuk menggunakan SMTP GMail
```
'from' => [
    'address' => env('MAIL_FROM_ADDRESS', 'email@gmail.com'),
    'name' => env('MAIL_FROM_NAME', 'Admin Larabuk'),
],
```
- Set email address di `.env`
```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email@gmail.com
MAIL_PASSWORD=password
MAIL_ENCRYPTION=tls
```
- Jalankan `php artisan migrate --seed`
- Jalankan php web server `php artisan serve`
- Buka `localhost:8000`

## Menemukan error ?
Buat sebuah [issue](https://github.com/ryanrahman26/larabuk/issues)

## License
[MIT](http://opensource.org/licenses/MIT)
