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
        // Ambil konfigurasi dari Environment Variables (Vercel / .env)
        // Gunakan getenv() sebagai fallback utama yang lebih stabil di banyak environment PHP
        $this->driver   = $this->getEnvVar('DB_CONNECTION', 'mysql');
        $this->host     = $this->getEnvVar('DB_HOST', 'localhost');
        $this->db_name  = $this->getEnvVar('DB_NAME', 'db_hotel48');
        $this->username = $this->getEnvVar('DB_USER', 'root');
        $this->password = $this->getEnvVar('DB_PASS', '');
        $this->port     = $this->getEnvVar('DB_PORT', ($this->driver === 'pgsql' ? '5432' : '3306'));
        $this->ssl_mode = $this->getEnvVar('DB_SSL_MODE', 'prefer');

        try {
            $dsn = "";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_TIMEOUT => 5, // Timeout 5 detik
            ];

            if ($this->driver === 'pgsql') {
                // Konfigurasi untuk PostgreSQL (Neon)
                // Format: pgsql:host=xxx;port=5432;dbname=xxx;sslmode=xxx
                $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
                
                if ($this->ssl_mode !== 'disable') {
                    $dsn .= ";sslmode=" . $this->ssl_mode;
                }
            } else {
                // Default ke MySQL
                $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
                
                // OPSI SSL (Wajib untuk cloud DB seperti Aiven/PlanetScale)
                if ($this->ssl_mode !== 'disable') {
                    $options[PDO::MYSQL_ATTR_SSL_CA] = true;
                    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
                }
            }

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $e) {
            throw new DatabaseException("Gagal Konek ke " . $this->host . " (" . $this->driver . "): " . $e->getMessage());
        }
    }

    /**
     * Helper untuk mengambil env variable dengan fallback
     */
    private function getEnvVar($key, $default) {
        $value = $_ENV[$key] ?? getenv($key);
        return ($value !== false && $value !== null && $value !== '') ? $value : $default;
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