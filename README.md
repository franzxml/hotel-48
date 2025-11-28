# Sistem Informasi Reservasi Hotel (Hotel 48) 🏨

Sistem reservasi hotel berbasis web yang dibangun menggunakan **PHP Native** dengan konsep **OOP (Object Oriented Programming)**, arsitektur **MVC**, dan **Composer**.

Proyek ini dibuat untuk memenuhi Tugas Besar Mata Kuliah Pemrograman Berorientasi Objek.

## 🚀 Fitur Utama

### Backend (Admin)
* **CRUD Tipe Kamar:** Mengelola jenis kamar, harga, dan fasilitas.
* **Manajemen Unit:** Mengelola stok fisik kamar (No. Kamar 101, 102, dst).
* **Cek Ketersediaan:** Algoritma untuk mencegah *double booking* pada tanggal yang sama.

### Frontend (Pelanggan)
* **Pencarian Kamar:** Mencari ketersediaan kamar berdasarkan tanggal Check-in/Check-out (Tanpa Login).
* **Booking Online:** Memesan kamar dengan proteksi sesi.
* **Google Login:** Integrasi OAuth 2.0 untuk login instan menggunakan akun Google.
* **Pembayaran:** Simulasi pembayaran menggunakan Interface/Polymorphism (DANA, GoPay).

## 🛠️ Teknologi yang Digunakan

* **Bahasa:** PHP >= 8.0
* **Database:** MySQL
* **Arsitektur:** MVC (Model-View-Controller)
* **Library:**
    * `google/apiclient`: Untuk Google Auth
    * Bootstrap 5: Untuk antarmuka pengguna
* **Tools:** Composer, Laragon/XAMPP

## 📦 Cara Install & Menjalankan

Ikuti langkah-langkah ini untuk menjalankan proyek di komputer lokal:

### 1. Persiapan Database
1.  Buat database baru di MySQL/phpMyAdmin dengan nama **`db_hotel48`**.
2.  Import file database yang disertakan (jika ada) atau jalankan script SQL pembuatan tabel (Users, Rooms, Bookings).

### 2. Instalasi Dependensi
Buka terminal di dalam folder proyek, lalu jalankan perintah:
```bash
composer install

###link laporan
https://docs.google.com/document/d/1H1Jh9jgHLIjUYAZOdk39t9t9U3u3YM0vVqLDMajf4mI/edit?tab=t.0