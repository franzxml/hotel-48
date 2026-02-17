<?php
namespace App\Config;

use PDO;
use PDOException;
use App\Exceptions\DatabaseException;

class Database {
    private static $instance = null;
    private $conn;
    
    // Properti ini akan diisi ulang di dalam constructor
    private $driver;
    private $host; 
    private $db_name;
    private $username;
    private $password;
    private $port;
    private $ssl_mode;

    // Constructor Private agar tidak bisa di-new dari luar
    private function __construct() {
        
        // --- BAGIAN INI YANG HILANG DI FILE ANDA ---
        // Ambil konfigurasi dari Environment Variables (Vercel / .env)
        // Jika tidak ada di Env, baru fallback ke nilai default (untuk local)
        $this->driver   = $_ENV['DB_CONNECTION'] ?? getenv('DB_CONNECTION') ?? 'mysql';
        $this->host     = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
        $this->db_name  = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'db_hotel48';
        $this->username = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '';
        $this->port     = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? '3306';
        $this->ssl_mode = $_ENV['DB_SSL_MODE'] ?? getenv('DB_SSL_MODE') ?? 'prefer';
        // -------------------------------------------

        try {
            $dsn = "";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            ];

            if ($this->driver === 'pgsql') {
                // Konfigurasi untuk PostgreSQL (Neon)
                $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
                
                if ($this->ssl_mode !== 'disable') {
                    $dsn .= ";sslmode=" . $this->ssl_mode;
                }
            } else {
                // Default ke MySQL
                // Tambahkan Port di DSN (Penting untuk Aiven karena port bukan 3306)
                $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
                
                // OPSI SSL (Wajib untuk Aiven)
                $options[PDO::MYSQL_ATTR_SSL_CA] = true;
                $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
            }

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $e) {
            // Tampilkan host agar kita tahu dia mencoba connect kemana
            throw new DatabaseException("Gagal Konek ke " . $this->host . " (" . $this->driver . "): " . $e->getMessage());
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