<?php

namespace App\Models;

use PDO;

class User
{
    protected $conn;
    protected $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $google_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 1. FUNGSI LOGIN
    public function login($email, $password)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                if (password_verify($password, $row->password)) {
                    $this->id = $row->id;
                    $this->name = $row->name;
                    $this->role = $row->role;
                    return true;
                }
            }
            return false;
        } catch (\PDOException $e) {
            return false;
        }
    }

    // 2. CEK EMAIL (Untuk Validasi Register)
    public function isEmailExists($email)
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // 3. REGISTER USER BARU (Update: Menerima Parameter)
    public function register($name, $email, $password)
    {
        $query = "INSERT INTO " . $this->table_name . " (name, email, password, role) 
                  VALUES (:name, :email, :password, 'customer')";

        $stmt = $this->conn->prepare($query);

        // Sanitasi
        $name = htmlspecialchars(strip_tags($name));
        $email = htmlspecialchars(strip_tags($email));
        
        // Hash Password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password_hash);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}