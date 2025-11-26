<?php

// --- 1. KONFIGURASI SYSTEM ---
// Menampilkan error (Bagus untuk demo/dev, matikan saat production asli)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Load .env (Hanya berjalan di Localhost)
// Di Vercel, ini akan dilewati karena file .env tidak di-upload
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (\Exception $e) {
    // Ignore error jika di Vercel
}

// --- 2. IMPORT CONTROLLERS ---
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\GoogleAuthController;
use App\Controllers\DashboardController;
use App\Controllers\RoomController;
use App\Controllers\UnitController;
use App\Controllers\BookingController;
use App\Controllers\PaymentController;

// --- 3. ROUTING SYSTEM ---
$action = $_GET['action'] ?? 'home';

switch ($action) {
    
    // === HALAMAN DEPAN (LANDING PAGE) ===
    case 'home':
        $home = new HomeController();
        $home->index(); 
        break;

    // === AUTENTIKASI (LOGIN/LOGOUT) ===
    case 'login':
        $auth = new AuthController();
        $auth->index(); 
        break;

    case 'login_process':
        $auth = new AuthController();
        $auth->loginProcess();
        break;

    case 'login_google':
        $g = new GoogleAuthController();
        $g->login();
        break;

    case 'google_callback':
        $g = new GoogleAuthController();
        $g->callback();
        break;

    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;

    // === DASHBOARD UTAMA ===
    case 'dashboard':
        $dashboard = new DashboardController();
        $dashboard->index();
        break;

    // === MODUL ADMIN: TIPE KAMAR (CRUD) ===
    case 'rooms': $c = new RoomController(); $c->index(); break;
    case 'rooms_create': $c = new RoomController(); $c->create(); break;
    case 'rooms_store': $c = new RoomController(); $c->store(); break;
    case 'rooms_edit': $c = new RoomController(); $c->edit(); break;
    case 'rooms_update': $c = new RoomController(); $c->updateProcess(); break;
    case 'rooms_delete': $c = new RoomController(); $c->delete(); break;

    // === MODUL ADMIN: UNIT FISIK/STOK (CRUD) ===
    case 'units': $u = new UnitController(); $u->index(); break;
    case 'units_create': $u = new UnitController(); $u->create(); break;
    case 'units_store': $u = new UnitController(); $u->store(); break;
    case 'units_toggle': $u = new UnitController(); $u->toggle(); break;
    case 'units_delete': $u = new UnitController(); $u->delete(); break;

    // === MODUL PELANGGAN: BOOKING ===
    case 'booking': $b = new BookingController(); $b->index(); break;
    case 'booking_search': $b = new BookingController(); $b->search(); break;
    case 'booking_process': $b = new BookingController(); $b->book(); break;
    case 'my_bookings': $b = new BookingController(); $b->history(); break;
    case 'booking_invoice': $b = new BookingController(); $b->downloadInvoice(); break;
    
    // === MODUL ADMIN: LAPORAN ===
    case 'admin_bookings': $b = new BookingController(); $b->adminList(); break;
    case 'admin_booking_delete': $b = new BookingController(); $b->delete(); break;

    // === SISTEM PEMBAYARAN ===
    case 'payment': $p = new PaymentController(); $p->index(); break;
    case 'payment_process': $p = new PaymentController(); $p->process(); break;

    // === 404 NOT FOUND ===
    default:
        http_response_code(404);
        echo "<div style='text-align:center; margin-top:50px;'>";
        echo "<h1>404</h1>";
        echo "<p>Halaman yang Anda cari tidak ditemukan.</p>";
        echo "<a href='index.php'>Kembali ke Beranda</a>";
        echo "</div>";
        break;
}