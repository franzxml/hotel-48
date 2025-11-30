<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\Unit;
use App\Models\RoomType;

class UnitController
{
    private $db;
    private $unit;
    private $roomType;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        // PERBAIKAN: Gunakan Singleton
        $this->db = Database::getInstance()->getConnection();
        $this->unit = new Unit($this->db);
        $this->roomType = new RoomType($this->db);
    }

    public function index()
    {
        $units = $this->unit->getAll();
        $data = ['title' => 'Kelola Unit Kamar', 'units' => $units];
        require_once __DIR__ . '/../Views/admin/units/index.php';
    }

    public function create()
    {
        $types = $this->roomType->getAll();
        
        $data = [
            'title' => 'Tambah Nomor Kamar',
            'types' => $types
        ];
        require_once __DIR__ . '/../Views/admin/units/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->unit->room_number = $_POST['room_number'];
            $this->unit->room_type_id = $_POST['room_type_id'];

            if ($this->unit->create()) {
                header("Location: index.php?action=units");
            } else {
                echo "Gagal. Mungkin nomor kamar sudah ada?";
            }
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) $this->unit->delete($id);
        header("Location: index.php?action=units");
    }

    public function toggle()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->unit->toggleStatus($id);
        }
        header("Location: index.php?action=units");
    }
}