<?php
// --- 1. KONFIGURASI SYSTEM ---
// Menampilkan error (Bagus untuk fase development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load Composer Autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load Environment Variables (.env)
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (\Exception $e) {
    // Abaikan jika .env tidak ditemukan (misal di production tertentu)
}

// --- 2. IMPORT CONTROLLERS & CLASSES ---
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\GoogleAuthController;
use App\Controllers\DashboardController;
use App\Controllers\RoomController;
use App\Controllers\UnitController;
use App\Controllers\BookingController;
use App\Controllers\PaymentController;
use App\Controllers\ApiController; // Controller untuk API

// IMPORT REPOSITORIES (Manual Dependency Injection)
use App\Repositories\BookingRepository;

// --- 3. INISIALISASI DEPENDENCIES (WIRING) ---
// Kita siapkan objek repository di sini untuk disuntikkan ke Controller
$bookingRepo = new BookingRepository(); 

// --- 4. ROUTING SYSTEM ---
// Mengambil parameter 'action' dari URL, default ke 'home'
$action = $_GET['action'] ?? 'home';

switch ($action) {
    
    // ==========================================
    // 1. HALAMAN PUBLIK & AUTH
    // ==========================================
    case 'home':
        $home = new HomeController();
        $home->index(); 
        break;

    case 'login':
        $auth = new AuthController(); 
        $auth->index(); 
        break;

    case 'login_process':
        $auth = new AuthController();
        $auth->loginProcess();
        break;

    case 'register':
        $auth = new AuthController();
        $auth->register();
        break;

    case 'register_process':
        $auth = new AuthController();
        $auth->registerProcess();
        break;

    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;

    // --- Google Auth ---
    case 'login_google':
        $g = new GoogleAuthController();
        $g->login();
        break;

    case 'google_callback':
        $g = new GoogleAuthController();
        $g->callback();
        break;


    // ==========================================
    // 2. DASHBOARD USER (Admin & Customer)
    // ==========================================
    case 'dashboard':
        $dashboard = new DashboardController();
        $dashboard->index();
        break;


    // ==========================================
    // 3. MODUL ADMIN: MANAJEMEN KAMAR (CRUD)
    // ==========================================
    case 'rooms': 
        $c = new RoomController(); 
        $c->index(); 
        break;
    case 'rooms_create': 
        $c = new RoomController(); 
        $c->create(); 
        break;
    case 'rooms_store': 
        $c = new RoomController(); 
        $c->store(); 
        break;
    case 'rooms_edit': 
        $c = new RoomController(); 
        $c->edit(); 
        break;
    case 'rooms_update': 
        $c = new RoomController(); 
        $c->updateProcess(); 
        break;
    case 'rooms_delete': 
        $c = new RoomController(); 
        $c->delete(); 
        break;


    // ==========================================
    // 4. MODUL ADMIN: UNIT FISIK (CRUD)
    // ==========================================
    case 'units': 
        $u = new UnitController(); 
        $u->index(); 
        break;
    case 'units_create': 
        $u = new UnitController(); 
        $u->create(); 
        break;
    case 'units_store': 
        $u = new UnitController(); 
        $u->store(); 
        break;
    case 'units_toggle': 
        $u = new UnitController(); 
        $u->toggle(); 
        break;
    case 'units_delete': 
        $u = new UnitController(); 
        $u->delete(); 
        break;


    // ==========================================
    // 5. MODUL BOOKING (Customer & Admin)
    // * Menggunakan Dependency Injection ($bookingRepo)
    // ==========================================
    
    // -- Customer Side --
    case 'booking': 
        $b = new BookingController($bookingRepo); 
        $b->index(); 
        break;
    case 'booking_search': 
        $b = new BookingController($bookingRepo); 
        $b->search(); 
        break;
    case 'booking_process': 
        $b = new BookingController($bookingRepo); 
        $b->book(); 
        break;
    case 'my_bookings': 
        $b = new BookingController($bookingRepo); 
        $b->history(); 
        break;
    case 'booking_invoice': 
        $b = new BookingController($bookingRepo); 
        $b->downloadInvoice(); 
        break;
    
    // -- Admin Side (Laporan) --
    case 'admin_bookings': 
        $b = new BookingController($bookingRepo); 
        $b->adminList(); 
        break;
    case 'admin_booking_delete': 
        $b = new BookingController($bookingRepo); 
        $b->delete(); 
        break;


    // ==========================================
    // 6. SISTEM PEMBAYARAN
    // ==========================================
    case 'payment': 
        $p = new PaymentController(); 
        $p->index(); 
        break;
    case 'payment_process': 
        $p = new PaymentController(); 
        $p->process(); 
        break;


    // ==========================================
    // 7. REST API ENDPOINTS (JSON) - FITUR BARU
    // ==========================================
    case 'api_login': 
        $api = new ApiController(); 
        $api->login(); 
        break;

    case 'api_rooms': 
        $api = new ApiController(); 
        $api->getRooms(); 
        break;

    case 'api_search': 
        $api = new ApiController(); 
        $api->searchRooms(); 
        break;

    case 'api_booking_create': 
        $api = new ApiController(); 
        $api->createBooking(); 
        break;

    case 'api_history': 
        $api = new ApiController(); 
        $api->getMyHistory(); 
        break;


    // ==========================================
    // 404 NOT FOUND HANDLER
    // ==========================================
    default:
        http_response_code(404);
        echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>";
        echo "<h1>404 Not Found</h1>";
        echo "<p>Halaman yang Anda tuju tidak ditemukan dalam sistem routing.</p>";
        echo "<a href='index.php'>Kembali ke Beranda</a>";
        echo "</div>";
        break;
}