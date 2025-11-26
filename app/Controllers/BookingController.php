<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\Booking;

class BookingController
{
    private $db;
    private $booking;

    public function __construct()
    {
        // Mulai session jika belum dimulai
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // CATATAN: Kita HAPUS pengecekan login di sini agar Tamu bisa masuk
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->booking = new Booking($this->db);
    }

    // 1. Tampilkan Halaman Cari (Bisa Diakses Siapa Saja)
    public function index()
    {
        $data = ['title' => 'Cari Kamar'];
        require_once __DIR__ . '/../Views/customer/search.php';
    }

    // 2. Proses Cari Kamar (Bisa Diakses Siapa Saja)
    public function search()
    {
        $checkIn = $_GET['check_in'] ?? '';
        $checkOut = $_GET['check_out'] ?? '';

        if ($checkIn && $checkOut) {
            $availableRooms = $this->booking->getAvailableRooms($checkIn, $checkOut);
            
            $data = [
                'title' => 'Hasil Pencarian',
                'rooms' => $availableRooms,
                'check_in' => $checkIn,
                'check_out' => $checkOut
            ];
            require_once __DIR__ . '/../Views/customer/results.php';
        } else {
            header("Location: index.php?action=booking");
        }
    }

    // 3. Proses Booking (WAJIB LOGIN)
    public function book()
    {
        // PROTEKSI: Cek apakah user sudah login
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomId = $_POST['room_id'];
            $price = $_POST['price'];
            $checkIn = $_POST['check_in'];
            $checkOut = $_POST['check_out'];

            $d1 = new \DateTime($checkIn);
            $d2 = new \DateTime($checkOut);
            $interval = $d1->diff($d2);
            $days = $interval->days;

            $this->booking->user_id = $_SESSION['user_id'];
            $this->booking->room_id = $roomId;
            $this->booking->check_in = $checkIn;
            $this->booking->check_out = $checkOut;
            $this->booking->total_price = $price * $days;

            if ($this->booking->create()) {
                header("Location: index.php?action=my_bookings");
            } else {
                echo "Gagal Booking.";
            }
        }
    }

    // 4. Lihat Pesanan Saya (WAJIB LOGIN)
    public function history()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $bookings = $this->booking->getByUser($_SESSION['user_id']);
        $data = ['title' => 'Pesanan Saya', 'bookings' => $bookings];
        require_once __DIR__ . '/../Views/customer/history.php';
    }
}