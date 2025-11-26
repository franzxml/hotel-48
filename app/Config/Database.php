<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    // Hapus nilai default hardcode
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;

    public function getConnection()
    {
        $this->conn = null;

        // BACA DARI ENVIRONMENT VARIABLE
        // Logikanya: Kalau ada Env (di Vercel), pakai itu. Kalau gak ada, pakai localhost (di laptop).
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'db_hotel48';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        $this->port = getenv('DB_PORT') ?: '3306';

        try {
            // Tambahkan Port di DSN
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            
            // Tambahkan Opsi SSL (Wajib buat Database Cloud)
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                // Baris di bawah ini PENTING untuk Aiven/TiDB
                PDO::MYSQL_ATTR_SSL_CA => true, 
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            ];

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}