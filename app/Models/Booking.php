<?php

namespace App\Models;

use PDO;

class Booking
{
    private $conn;
    private $table = "bookings";

    public $id;
    public $user_id;
    public $room_id;
    public $check_in;
    public $check_out;
    public $total_price;
    public $status;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 1. FUNGSI CEK KETERSEDIAAN (The Magic Logic)
    public function getAvailableRooms($checkIn, $checkOut)
    {
        // Query ini mencari kamar yang TIDAK ada di daftar booking pada tanggal tersebut
        // Logic: (ExistingCheckIn <= NewCheckOut) AND (ExistingCheckOut >= NewCheckIn)
        
        $query = "SELECT r.id, r.room_number, rt.type_name, rt.price, rt.description 
                  FROM rooms r
                  JOIN room_types rt ON r.room_type_id = rt.id
                  WHERE r.status = 'available' 
                  AND r.id NOT IN (
                      SELECT room_id FROM bookings 
                      WHERE (check_in <= :check_out AND check_out >= :check_in)
                      AND status != 'cancelled'
                  )";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":check_in", $checkIn);
        $stmt->bindParam(":check_out", $checkOut);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 2. FUNGSI SIMPAN PESANAN
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
                  SET user_id=:user_id, room_id=:room_id, check_in=:check_in, 
                      check_out=:check_out, total_price=:total_price, status='pending'";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":room_id", $this->room_id);
        $stmt->bindParam(":check_in", $this->check_in);
        $stmt->bindParam(":check_out", $this->check_out);
        $stmt->bindParam(":total_price", $this->total_price);

        if ($stmt->execute()) {
            // Ambil ID booking yang baru dibuat (untuk pembayaran nanti)
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // 3. FUNGSI AMBIL HISTORY PESANAN (Per User)
    public function getByUser($userId)
    {
        $query = "SELECT b.*, r.room_number, rt.type_name 
                  FROM bookings b
                  JOIN rooms r ON b.room_id = r.id
                  JOIN room_types rt ON r.room_type_id = rt.id
                  WHERE b.user_id = :user_id 
                  ORDER BY b.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getDetailById($id)
    {
        $query = "SELECT b.*, u.name as guest_name, u.email, r.room_number, rt.type_name, rt.price 
                  FROM bookings b
                  JOIN users u ON b.user_id = u.id
                  JOIN rooms r ON b.room_id = r.id
                  JOIN room_types rt ON r.room_type_id = rt.id
                  WHERE b.id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllForAdmin()
    {
        $query = "SELECT b.*, u.name as guest_name, r.room_number, rt.type_name 
                  FROM bookings b
                  JOIN users u ON b.user_id = u.id
                  JOIN rooms r ON b.room_id = r.id
                  JOIN room_types rt ON r.room_type_id = rt.id
                  ORDER BY b.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}