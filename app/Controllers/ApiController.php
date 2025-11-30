<?php
namespace App\Controllers;

use App\Config\Database;
use App\Config\JwtConfig;
use App\Models\User;
use App\Models\Booking; // Perlu Model Booking untuk create entity
use App\Repositories\BookingRepository;
use App\Middleware\AuthMiddleware;
use Firebase\JWT\JWT;

class ApiController {
    private $db;
    private $userModel;
    private $bookingRepo;

    public function __construct() {
        // Menggunakan Singleton DB & Repository
        $this->db = Database::getInstance()->getConnection();
        $this->userModel = new User($this->db);
        $this->bookingRepo = new BookingRepository();
    }

    // Helper: Kirim respon JSON
    private function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    // Helper: Ambil Input JSON (Raw Body)
    private function getJsonInput() {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    // === 1. AUTHENTICATION ===

    public function login() {
        $input = $this->getJsonInput();
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        if ($this->userModel->login($email, $password)) {
            // Generate Token
            $payload = [
                'iss' => 'hotel-48-api',
                'iat' => time(),
                'exp' => time() + JwtConfig::TOKEN_EXPIRY,
                'data' => [
                    'id' => $this->userModel->id,
                    'email' => $this->userModel->email,
                    'role' => $this->userModel->role
                ]
            ];

            $jwt = JWT::encode($payload, JwtConfig::SECRET_KEY, JwtConfig::ALGORITHM);

            $this->jsonResponse([
                'status' => 200,
                'message' => 'Login Berhasil',
                'token' => $jwt,
                'user' => [
                    'name' => $this->userModel->name,
                    'role' => $this->userModel->role
                ]
            ]);
        } else {
            $this->jsonResponse(['status' => 401, 'message' => 'Email atau Password Salah'], 401);
        }
    }

    // === 2. PUBLIC DATA ===

    public function getRooms() {
        // Kita pakai query manual simpel untuk ambil tipe kamar
        $stmt = $this->db->query("SELECT * FROM room_types");
        $rooms = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $this->jsonResponse([
            'status' => 200,
            'data' => $rooms
        ]);
    }

    public function searchRooms() {
        // Ambil dari Query Params (?check_in=...&check_out=...)
        $checkIn = $_GET['check_in'] ?? '';
        $checkOut = $_GET['check_out'] ?? '';

        if (!$checkIn || !$checkOut) {
            $this->jsonResponse(['status' => 400, 'message' => 'Parameter check_in dan check_out wajib diisi'], 400);
        }

        $rooms = $this->bookingRepo->getAvailableRooms($checkIn, $checkOut);
        $this->jsonResponse(['status' => 200, 'data' => $rooms]);
    }

    // === 3. PROTECTED ROUTES (Butuh Token) ===

    public function createBooking() {
        // 1. Cek Token dulu
        $middleware = new AuthMiddleware();
        $userData = $middleware->validateToken(); // Kalau gagal, script mati disini (exit)

        // 2. Proses Input
        $input = $this->getJsonInput();
        
        if (empty($input['room_id']) || empty($input['check_in']) || empty($input['check_out']) || empty($input['price_per_night'])) {
            $this->jsonResponse(['status' => 400, 'message' => 'Data tidak lengkap'], 400);
        }

        // 3. Hitung Harga
        $d1 = new \DateTime($input['check_in']);
        $d2 = new \DateTime($input['check_out']);
        $days = $d1->diff($d2)->days ?: 1;
        $totalPrice = $input['price_per_night'] * $days;

        // 4. Simpan via Repository
        $booking = new Booking(); // Pastikan Booking Model extends BaseModel yg kita buat sebelumnya
        $booking->setUserId($userData->id);
        $booking->setRoomId($input['room_id']);
        $booking->check_in = $input['check_in'];
        $booking->check_out = $input['check_out'];
        $booking->total_price = $totalPrice;

        $newId = $this->bookingRepo->create($booking);

        if ($newId) {
            $this->jsonResponse([
                'status' => 201,
                'message' => 'Booking Berhasil Dibuat',
                'data' => [
                    'booking_id' => $newId,
                    'total_price' => $totalPrice,
                    'status' => 'pending'
                ]
            ], 201);
        } else {
            $this->jsonResponse(['status' => 500, 'message' => 'Gagal menyimpan booking'], 500);
        }
    }

    public function getMyHistory() {
        $middleware = new AuthMiddleware();
        $userData = $middleware->validateToken();

        $history = $this->bookingRepo->getByUser($userData->id);
        $this->jsonResponse(['status' => 200, 'data' => $history]);
    }
}