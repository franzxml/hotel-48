<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Config\Database;

// Load Environment
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

echo "Memulai migrasi database...
";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Baca file schema.sql
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    
    // Eksekusi (Exec) - PDO akan menjalankan multi-statement jika driver mendukungnya
    // Untuk PostgreSQL biasanya support multi-statement dalam satu exec()
    $conn->exec($sql);
    
    echo "Migrasi BERHASIL! Tabel telah dibuat di database Neon.
";
    
} catch (PDOException $e) {
    echo "GAGAL Migrasi: " . $e->getMessage() . "
";
    exit(1); // Exit code error
} catch (Exception $e) {
    echo "GAGAL System: " . $e->getMessage() . "
";
    exit(1);
}
