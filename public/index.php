<?php

// Debugging (Bisa dimatikan nanti)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\RoomController;
use App\Controllers\UnitController;
use App\Controllers\BookingController;
use App\Controllers\GoogleAuthController; // <-- Tambahan

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $auth = new AuthController();
        $auth->index(); 
        break;

    case 'login':
        $auth = new AuthController();
        $auth->loginProcess();
        break;

    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;

    case 'dashboard':
        $dashboard = new DashboardController();
        $dashboard->index();
        break;

    // --- Rooms & Units (Admin) ---
    case 'rooms': $c = new RoomController(); $c->index(); break;
    case 'rooms_create': $c = new RoomController(); $c->create(); break;
    case 'rooms_store': $c = new RoomController(); $c->store(); break;
    case 'rooms_edit': $c = new RoomController(); $c->edit(); break;
    case 'rooms_update': $c = new RoomController(); $c->updateProcess(); break;
    case 'rooms_delete': $c = new RoomController(); $c->delete(); break;

    case 'units': $u = new UnitController(); $u->index(); break;
    case 'units_create': $u = new UnitController(); $u->create(); break;
    case 'units_store': $u = new UnitController(); $u->store(); break;
    case 'units_delete': $u = new UnitController(); $u->delete(); break;

    // --- Booking (Customer & Guest) ---
    case 'booking': $b = new BookingController(); $b->index(); break;
    case 'booking_search': $b = new BookingController(); $b->search(); break;
    case 'booking_process': $b = new BookingController(); $b->book(); break;
    case 'my_bookings': $b = new BookingController(); $b->history(); break;

    // --- Google Auth ---
    case 'login_google':
        $g = new GoogleAuthController();
        $g->login();
        break;
    case 'google_callback':
        $g = new GoogleAuthController();
        $g->callback();
        break;

    default:
        echo "404 Halaman Tidak Ditemukan";
        break;
}