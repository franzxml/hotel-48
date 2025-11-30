<?php
namespace App\Repositories;

use App\Config\Database;
use App\Models\Booking;
use PDO;

class BookingRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // 1. CREATE BOOKING
    public function create(Booking $booking) {
        $sql = "INSERT INTO bookings (user_id, room_id, check_in, check_out, total_price, status) 
                VALUES (:uid, :rid, :cin, :cout, :total, 'pending')";
        
        $stmt = $this->db->prepare($sql);
        
        $data = [
            ':uid' => $booking->getUserId(),
            ':rid' => $booking->getRoomId(),
            ':cin' => $booking->check_in,
            ':cout' => $booking->check_out,
            ':total' => $booking->total_price
        ];

        if($stmt->execute($data)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // 2. CEK KETERSEDIAAN KAMAR
    public function getAvailableRooms($checkIn, $checkOut) {
        $query = "SELECT r.id, r.room_number, rt.type_name, rt.price, rt.description 
                  FROM rooms r
                  JOIN room_types rt ON r.room_type_id = rt.id
                  WHERE r.status = 'available' 
                  AND r.id NOT IN (
                      SELECT room_id FROM bookings 
                      WHERE (check_in <= :check_out AND check_out >= :check_in)
                      AND status != 'cancelled'
                  )";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([':check_in' => $checkIn, ':check_out' => $checkOut]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 3. AMBIL HISTORY USER (Untuk fitur 'my_bookings')
    public function getByUser($userId) {
        $query = "SELECT b.*, r.room_number, rt.type_name 
                  FROM bookings b
                  JOIN rooms r ON b.room_id = r.id
                  JOIN room_types rt ON r.room_type_id = rt.id
                  WHERE b.user_id = :user_id 
                  ORDER BY b.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 4. AMBIL DETAIL LENGKAP (Untuk Invoice)
    public function getDetailById($id) {
        $query = "SELECT b.*, u.name as guest_name, u.email, r.room_number, rt.type_name, rt.price 
                  FROM bookings b
                  JOIN users u ON b.user_id = u.id
                  JOIN rooms r ON b.room_id = r.id
                  JOIN room_types rt ON r.room_type_id = rt.id
                  WHERE b.id = :id LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // 5. AMBIL SEMUA DATA (Untuk Admin)
    public function getAllForAdmin() {
        $query = "SELECT b.*, u.name as guest_name, r.room_number, rt.type_name 
                  FROM bookings b
                  JOIN users u ON b.user_id = u.id
                  JOIN rooms r ON b.room_id = r.id
                  JOIN room_types rt ON r.room_type_id = rt.id
                  ORDER BY b.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 6. HAPUS DATA
    public function delete($id) {
        $query = "DELETE FROM bookings WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}