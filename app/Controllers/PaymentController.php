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
        
        // PERBAIKAN: Gunakan Singleton
        $this->db = Database::getInstance()->getConnection();
    }

    public function index()
    {
        $bookingId = $_GET['booking_id'] ?? null;
        if (!$bookingId) { header("Location: index.php"); exit; }
        
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

    public function process()
    {
        $method = $_POST['method'];
        $bookingId = $_POST['booking_id'];
        $amount = $_POST['amount'];

        $paymentGateway = null;

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

        $result = $paymentGateway->pay($amount);

        if ($result['success']) {
            $this->updateStatus($bookingId, 'confirmed');

            $sql = "INSERT INTO payments (booking_id, amount, provider) VALUES (:bid, :amt, :prov)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['bid' => $bookingId, 'amt' => $amount, 'prov' => $result['provider']]);

            header("Location: index.php?action=my_bookings&status=payment_success");
            exit();
        }
    }

    private function updateStatus($id, $status)
    {
        $sql = "UPDATE bookings SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status, 'id' => $id]);
    }
}