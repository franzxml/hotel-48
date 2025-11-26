<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    
    // PERBAIKAN: Deklarasikan properti $conn agar tidak Deprecated di PHP 8.2+
    private $conn; 

    public function getConnection()
    {
        $this->conn = null;

        // BACA DARI ENVIRONMENT VARIABLE
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'db_hotel48';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        $this->port = getenv('DB_PORT') ?: '3306';

        try {
            // Tambahkan Port di DSN
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                // Opsi SSL untuk database cloud (Aiven/TiDB)
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