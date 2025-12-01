# Hotel 48

## Deskripsi
Hotel 48 merupakan sistem informasi manajemen reservasi hotel berbasis *web* yang dirancang untuk memodernisasi proses pemesanan kamar dan pengelolaan operasional hotel. Sistem ini memfasilitasi pelanggan untuk mencari ketersediaan kamar, melakukan reservasi secara *online*, dan memilih metode pembayaran digital. Bagi administrator, sistem ini menyediakan fitur manajemen tipe kamar, unit fisik, serta laporan transaksi secara *real-time*. Hotel 48 bertujuan untuk menggantikan proses reservasi manual dengan solusi digital yang efisien, aman, dan terintegrasi dengan Google OAuth serta simulasi *payment gateway*.

## Teknologi
* PHP 8.0+ (Native MVC)
* MySQL (Database)
* Bootstrap 5 (CSS Framework)
* JavaScript (Vanilla)
* Composer (Dependency Manager)
* Google OAuth 2.0 & JWT

## Struktur Folder
    HOTEL-48/
    │── api/
    │   ├── index.php
    │   └── openapi.yaml
    │── app/
    │   ├── Config/
    │   ├── Controllers/
    │   ├── Core/
    │   ├── Exceptions/
    │   ├── Middleware/
    │   ├── Models/
    │   ├── Payments/
    │   ├── Repositories/
    │   ├── Traits/
    │   └── Views/
    │── public/
    │   └── index.php
    │── vendor/
    │── .env
    │── .gitignore
    │── composer.json
    │── composer.lock
    │── README.md
    └── vercel.json

## Cara Menjalankan
1. **Persiapan Lingkungan:** Pastikan komputer Anda sudah terinstal **PHP**, **Composer**, dan **MySQL** (bisa menggunakan Laragon/XAMPP).
2. **Unduh Repositori:** Unduh atau *clone* repositori ini ke folder server lokal Anda (misalnya: `C:\laragon\www\hotel-48`).
3. **Instalasi Dependensi:** Buka terminal di dalam folder proyek, lalu jalankan perintah:
   ```bash
   composer install
   ```
4. **Konfigurasi Database:**
   * Buat database baru bernama `db_hotel48`.
   * Impor skema database (tabel `users`, `rooms`, `bookings`, dll).
   * Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda.
5. **Jalankan Aplikasi:**
   * Jika menggunakan Laragon, aplikasi dapat diakses melalui `http://hotel-48.test`.
   * Atau gunakan *built-in server* PHP dengan perintah: `php -S localhost:8000 -t public`
6. **Akses:** Buka *browser* dan kunjungi URL lokal untuk masuk ke halaman beranda.

## Domain
Website dapat diakses melalui (Server Production/Vercel):
[https://hotel-48-app.vercel.app/](https://hotel-48-app.vercel.app/)

---
Dikembangkan oleh: @franzxml & Kings-Bilbil