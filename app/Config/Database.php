<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    // Encapsulation: Properti private agar tidak bisa diubah dari luar
    private $host = "localhost";
    private $db_name = "db_hotel48";
    private $username = "root";
    private $password = ""; // Sesuaikan jika xampp ada password
    private $conn;

    // Method untuk mendapatkan koneksi
    public function getConnection()
    {
        $this->conn = null;

        try {
            // Menggunakan PDO seperti requirement
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Setting error mode ke Exception agar mudah debugging
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Setting fetch mode default ke Object (biar enak aksesnya nanti $user->name)
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}