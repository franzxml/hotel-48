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
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }

        // PERBAIKAN: Gunakan Singleton
        $this->db = Database::getInstance()->getConnection();
        $this->roomType = new RoomType($this->db);
    }

    public function index()
    {
        $rooms = $this->roomType->getAll();
        
        $data = [
            'title' => 'Kelola Tipe Kamar',
            'rooms' => $rooms
        ];

        require_once __DIR__ . '/../Views/admin/rooms/index.php';
    }

    public function create()
    {
        $data = ['title' => 'Tambah Tipe Kamar'];
        require_once __DIR__ . '/../Views/admin/rooms/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->roomType->type_name = $_POST['type_name'];
            $this->roomType->description = $_POST['description'];
            $this->roomType->price = $_POST['price'];

            if ($this->roomType->create()) {
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

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->roomType->delete($id);
        }
        header("Location: index.php?action=rooms");
    }
}