<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\RoomType;

class RoomController
{
    private $db;
    private $roomType;

    public function __construct()
    {
        // 1. Cek Session & Role Admin
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Kalau belum login ATAU bukan admin, tendang keluar
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        // 2. Siapkan Model
        $database = new Database();
        $this->db = $database->getConnection();
        $this->roomType = new RoomType($this->db);
    }

    // Halaman Utama List Kamar
    public function index()
    {
        $rooms = $this->roomType->getAll();
        
        // Data dikirim ke View
        $data = [
            'title' => 'Kelola Tipe Kamar',
            'rooms' => $rooms
        ];

        require_once __DIR__ . '/../Views/admin/rooms/index.php';
    }

    // Halaman Form Tambah
    public function create()
    {
        $data = ['title' => 'Tambah Tipe Kamar'];
        require_once __DIR__ . '/../Views/admin/rooms/create.php';
    }

    // Proses Simpan Data
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->roomType->type_name = $_POST['type_name'];
            $this->roomType->description = $_POST['description'];
            $this->roomType->price = $_POST['price'];

            if ($this->roomType->create()) {
                // Redirect balik ke index kalau sukses
                header("Location: index.php?action=rooms");
            } else {
                echo "Gagal menyimpan data.";
            }
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) { header("Location: index.php?action=rooms"); exit; }

        $room = $this->roomType->getById($id);
        
        $data = [
            'title' => 'Edit Tipe Kamar',
            'room' => $room
        ];
        
        require_once __DIR__ . '/../Views/admin/rooms/edit.php';
    }

    // Proses Update ke Database
    public function updateProcess()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->roomType->type_name = $_POST['type_name'];
            $this->roomType->description = $_POST['description'];
            $this->roomType->price = $_POST['price'];

            if ($this->roomType->update($id)) {
                header("Location: index.php?action=rooms");
            } else {
                echo "Gagal update data.";
            }
        }
    }

    // Proses Hapus
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->roomType->delete($id);
        }
        header("Location: index.php?action=rooms");
    }
}