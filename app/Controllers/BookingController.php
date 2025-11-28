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
        // Proteksi: Admin tidak boleh masuk sini
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            echo "<script>alert('Selamat datang admin! Anda akan di arahkan ke dashboard.'); window.location.href='index.php?action=dashboard';</script>";
            exit();
        }

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
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            header("Location: index.php?action=dashboard");
            exit();
        }

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
        // Proteksi Admin
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            echo "<script>alert('Admin tidak bisa melakukan booking!'); window.location.href='index.php?action=dashboard';</script>";
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
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Bukti Reservasi - Hotel 48</title>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
            <style>
                body {
                    font-family: Roboto, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: #f4f4f4;
                    color: #333;
                }
                .header {
                    text-align: center;
                    background-color: #004080; /* Tema biru gelap */
                    color: white;
                    padding: 20px;
                    border-radius: 8px;
                    margin-bottom: 30px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 2.5em;
                    font-weight: 700;
                }
                .header p {
                    margin: 5px 0 0;
                    font-size: 1.2em;
                }
                .logo {
                    width: 100px;
                    height: auto;
                    margin-bottom: 10px;
                }
                .box {
                    background-color: white;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    padding: 30px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    max-width: 800px;
                    margin: 0 auto;
                }
                .box p {
                    margin: 10px 0;
                    font-size: 1em;
                }
                .status {
                    font-weight: bold;
                    text-transform: uppercase;
                    padding: 5px 10px;
                    border-radius: 4px;
                }
                .status.success { color: white; background-color: #28a745; }
                .status.failed { color: white; background-color: #dc3545; }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                    font-size: 0.9em;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 12px;
                    text-align: left;
                }
                th {
                    background-color: #004080;
                    color: white;
                    font-weight: 700;
                }
                .total {
                    text-align: right;
                    font-size: 1.5em;
                    font-weight: 700;
                    color: #004080;
                    margin-top: 20px;
                    border-top: 2px solid #ddd;
                    padding-top: 10px;
                }
                .footer {
                    text-align: center;
                    font-size: 0.8em;
                    color: #666;
                    margin-top: 40px;
                    padding: 20px;
                    background-color: #f9f9f9;
                    border-radius: 8px;
                }
                .footer p {
                    margin: 5px 0;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <!-- Placeholder untuk logo; ganti dengan <img src="path/to/logo.png" alt="Logo Hotel 48" class="logo"> -->
                <h1>HOTEL 48</h1>
                <p>Bukti Reservasi Kamar</p>
            </div>

            <div class="box">
                <p><strong>No. Pesanan:</strong> #' . $booking->id . '</p>
                <p><strong>Tanggal Pemesanan:</strong> ' . date('d M Y') . '</p> <!-- Tambahan tanggal -->
                <p><strong>Nama Tamu:</strong> ' . $booking->guest_name . '</p>
                <p><strong>Email:</strong> ' . $booking->email . '</p>
                <p><strong>Status Pembayaran:</strong> <span class="status ' . ($booking->status == 'paid' ? 'success' : 'failed') . '">' . $booking->status . '</span></p>
                
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

                <div class="total">
                    Total: Rp ' . number_format($booking->total_price) . '
                </div>
            </div>
            
            <div class="footer">
                <p>Terima kasih telah memilih Hotel 48. Simpan dokumen ini sebagai bukti saat Check-in.</p>
                <p>Alamat: Jl. Contoh No. 48, Kota, Indonesia | Telepon: (021) 123-4567 | Email: info@hotel48.com</p>
                <p>&copy; 2023 Hotel 48. Semua hak dilindungi.</p>
            </div>
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