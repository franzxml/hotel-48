<?php

namespace App\Models;

use PDO;

class Unit
{
    private $conn;
    private $table = "rooms"; 

    public $id;
    public $room_type_id;
    public $room_number;
    public $status;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 1. GET ALL (Sederhana, tanpa cek booking)
    public function getAll()
    {
        $query = "SELECT u.id, u.room_number, u.status, r.type_name 
                  FROM " . $this->table . " u
                  JOIN room_types r ON u.room_type_id = r.id
                  ORDER BY u.room_number ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 2. CREATE
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " SET room_type_id=:room_type_id, room_number=:room_number, status='available'";
        $stmt = $this->conn->prepare($query);

        $this->room_number = htmlspecialchars(strip_tags($this->room_number));
        $stmt->bindParam(":room_type_id", $this->room_type_id);
        $stmt->bindParam(":room_number", $this->room_number);

        if ($stmt->execute()) return true;
        return false;
    }

    // 3. DELETE
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // 4. BARU: Ubah Status (Available <-> Maintenance)
    public function toggleStatus($id)
    {
        // Logika SQL: Jika 'available' jadi 'maintenance', dan sebaliknya
        $query = "UPDATE " . $this->table . " 
                  SET status = IF(status='available', 'maintenance', 'available') 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}