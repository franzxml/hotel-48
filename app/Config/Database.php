<?php
namespace App\Config;

use PDO;
use PDOException;
use App\Exceptions\DatabaseException;

class Database {
    private static $instance = null;
    private $conn;
    
    // Host dsb diambil dari env nanti
    private $host = 'localhost'; 
    private $db_name = 'db_hotel48';
    private $username = 'root';
    private $password = '';

    // Constructor Private agar tidak bisa di-new dari luar
    private function __construct() {
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch(PDOException $e) {
            throw new DatabaseException("Koneksi gagal: " . $e->getMessage());
        }
    }

    // Metode Statis untuk mengambil instance tunggal
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}