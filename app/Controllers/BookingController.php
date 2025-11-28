<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\Booking;
use Dompdf\Dompdf; 
use Dompdf\Options;

class BookingController
{
    private $db;
    private $booking;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->booking = new Booking($this->db);
    }

    // 1. Tampilkan Halaman Cari
    public function index()
    {
        

        if (!isset($_SESSION['user_id'])) {
            // TWEAK: Kirim pesan 'auth_required'
            header("Location: index.php?action=login&msg=auth_required");
            exit();
        }

        $checkIn = $_GET['check_in'] ?? '';
        $checkOut = $_GET['check_out'] ?? '';

        $data = [
            'title' => 'Cari Kamar',
            'check_in' => $checkIn,
            'check_out' => $checkOut
        ];
        
        require_once __DIR__ . '/../Views/customer/search.php';
    }

    // 2. Proses Cari Kamar
    public function search()
    {
        

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login&msg=auth_required");
            exit();
        }

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

    // 3. Proses Booking
    public function book()
    {
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomId = $_POST['room_id'];
            $price = $_POST['price'];
            $checkIn = $_POST['check_in'];
            $checkOut = $_POST['check_out'];

            $d1 = new \DateTime($checkIn);
            $d2 = new \DateTime($checkOut);
            $interval = $d1->diff($d2);
            $days = $interval->days;
            if ($days < 1) $days = 1;

            $this->booking->user_id = $_SESSION['user_id'];
            $this->booking->room_id = $roomId;
            $this->booking->check_in = $checkIn;
            $this->booking->check_out = $checkOut;
            $this->booking->total_price = $price * $days;

            if ($this->booking->create()) {
                $newBookingId = $this->booking->id;
                header("Location: index.php?action=payment&booking_id=" . $newBookingId);
                exit();
            } else {
                echo "Gagal Booking. Silakan coba lagi.";
            }
        }
    }

    // 4. Lihat Pesanan Saya
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

    public function downloadInvoice()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $id = $_GET['id'] ?? null;
        if (!$id) die("ID Booking tidak ditemukan.");

        $booking = $this->booking->getDetailById($id);

        if ($booking->user_id != $_SESSION['user_id']) {
            die("Anda tidak berhak mengakses invoice ini.");
        }

        $html = '
        <html>
        <head>
            <style>
                body { font-family: sans-serif; }
                .header { text-align: center; margin-bottom: 20px; }
                .box { border: 1px solid #333; padding: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .status { font-weight: bold; color: green; text-transform: uppercase; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>HOTEL 48</h1>
                <p>Bukti Reservasi Kamar</p>
            </div>

            <div class="box">
                <p><strong>No. Pesanan:</strong> #' . $booking->id . '</p>
                <p><strong>Nama Tamu:</strong> ' . $booking->guest_name . '</p>
                <p><strong>Email:</strong> ' . $booking->email . '</p>
                <p><strong>Status Pembayaran:</strong> <span class="status">' . $booking->status . '</span></p>
                
                <table>
                    <tr>
                        <th>Tipe Kamar</th>
                        <th>Nomor Kamar</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                    </tr>
                    <tr>
                        <td>' . $booking->type_name . '</td>
                        <td>Unit ' . $booking->room_number . '</td>
                        <td>' . $booking->check_in . '</td>
                        <td>' . $booking->check_out . '</td>
                    </tr>
                </table>

                <h3 style="text-align:right; margin-top:20px;">
                    Total: Rp ' . number_format($booking->total_price) . '
                </h3>
            </div>
            
            <p style="text-align:center; font-size: 12px; margin-top: 50px;">
                Terima kasih telah memilih Hotel 48.<br>
                Simpan dokumen ini sebagai bukti saat Check-in.
            </p>
        </body>
        </html>';

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("invoice_hotel48_" . $id . ".pdf", ["Attachment" => true]);
    }

    public function adminList()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        $bookings = $this->booking->getAllForAdmin();
        $data = ['title' => 'Laporan Semua Pesanan', 'bookings' => $bookings];
        require_once __DIR__ . '/../Views/admin/bookings/index.php';
    }

    public function delete()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->booking->delete($id);
        }
        header("Location: index.php?action=admin_bookings");
        exit();
    }
}