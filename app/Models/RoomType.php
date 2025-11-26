<?php

namespace App\Models;

use PDO;

class RoomType
{
    private $conn;
    private $table = "room_types";

    public $id;
    public $type_name;
    public $description;
    public $price;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 1. READ ALL (Untuk tampil di tabel)
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // 2. CREATE (Tambah Data Baru)
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " SET type_name=:type_name, description=:description, price=:price";
        $stmt = $this->conn->prepare($query);

        // Sanitasi
        $this->type_name = htmlspecialchars(strip_tags($this->type_name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));

        $stmt->bindParam(":type_name", $this->type_name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // 4. UPDATE
    public function update($id)
    {
        $query = "UPDATE " . $this->table . " 
                  SET type_name=:type_name, description=:description, price=:price 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);

        // Sanitasi
        $this->type_name = htmlspecialchars(strip_tags($this->type_name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));

        $stmt->bindParam(":type_name", $this->type_name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 5. DELETE
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }    
}