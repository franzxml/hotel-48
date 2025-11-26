<?php

namespace App\Controllers;

use App\Config\Database;
use App\Payments\DanaPayment;
use App\Payments\GopayPayment;

class PaymentController
{
    private $db;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // 1. Tampilkan Halaman Pilih Metode
    public function index()
    {
        $bookingId = $_GET['booking_id'] ?? null;
        if (!$bookingId) { header("Location: index.php"); exit; }
        
        // Ambil total harga dari DB (Query Cepat)
        $stmt = $this->db->prepare("SELECT total_price FROM bookings WHERE id = :id");
        $stmt->execute(['id' => $bookingId]);
        $booking = $stmt->fetch();

        $data = [
            'title' => 'Pilih Pembayaran',
            'booking_id' => $bookingId,
            'amount' => $booking->total_price
        ];
        
        require_once __DIR__ . '/../Views/customer/payment.php';
    }

    // 2. Proses Bayar (Polymorphism Beraksi!)
    public function process()
    {
        $method = $_POST['method'];
        $bookingId = $_POST['booking_id'];
        $amount = $_POST['amount'];

        $paymentGateway = null;

        // Pilih Class berdasarkan input user
        switch ($method) {
            case 'DANA':
                $paymentGateway = new DanaPayment();
                break;
            case 'GOPAY':
                $paymentGateway = new GopayPayment();
                break;
            default:
                die("Metode pembayaran tidak dikenal.");
        }

        // EKSEKUSI (Apapun metodenya, perintahnya sama: PAY)
        $result = $paymentGateway->pay($amount);

        if ($result['success']) {
            // Update Status Booking jadi 'confirmed'
            $this->updateStatus($bookingId, 'confirmed');

            // Simpan log pembayaran (Opsional, query cepat)
            $sql = "INSERT INTO payments (booking_id, amount, provider) VALUES (:bid, :amt, :prov)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['bid' => $bookingId, 'amt' => $amount, 'prov' => $result['provider']]);

            // Redirect ke History dengan pesan sukses
            echo "<script>
                    alert('" . $result['message'] . "');
                    window.location.href='index.php?action=my_bookings';
                  </script>";
        }
    }

    private function updateStatus($id, $status)
    {
        $sql = "UPDATE bookings SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status, 'id' => $id]);
    }
}