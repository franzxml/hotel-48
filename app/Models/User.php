<?php

namespace App\Models;

use PDO;

class User
{
    // Encapsulation: Protected agar bisa diakses oleh class Customer/Admin
    protected $conn;
    protected $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;

    // Constructor: Otomatis jalan saat class dipanggil
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method Register (Bisa dipakai Admin maupun Customer)
    public function register()
    {
        // 1. Query SQL (Prepared Statement untuk keamanan)
        $query = "INSERT INTO " . $this->table_name . " 
                  SET name=:name, email=:email, password=:password, role=:role";

        $stmt = $this->conn->prepare($query);

        // 2. Bersihkan input (Sanitasi dasar)
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = htmlspecialchars(strip_tags($this->role));

        // 3. Encapsulation Logic: Password WAJIB di-hash
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        // 4. Binding data (Mengisi placeholder :name dll)
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":role", $this->role);

        // 5. Eksekusi
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login($email, $password)
    {
        // 1. Query cari user berdasarkan email
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        // 2. Cek apakah user ditemukan
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(); // Ambil data user dari DB

            // 3. Verifikasi Password (Hash vs Plaintext)
            // password_verify() adalah pasangan dari password_hash()
            if (password_verify($password, $row->password)) {
                
                // Isi properti object dengan data dari DB (biar bisa dipakai controller)
                $this->id = $row->id;
                $this->name = $row->name;
                $this->role = $row->role;
                return true; // Login Sukses
            }
        }

        return false; // Email gak ada atau Password salah
    }
}