<?php

namespace App\Controllers;

class DashboardController
{
    public function __construct()
    {
        // 1. Mulai session di setiap method
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 2. PROTEKSI: Jika belum login, redirect ke login page
        if (!isset($_SESSION['user_id'])) {
            header("Location: /hotel_48/public/index.php?action=login");
            exit();
        }
    }

    public function index()
    {
        // 3. Pisahkan tampilan berdasarkan Role
        $role = $_SESSION['user_role'];
        $userName = $_SESSION['user_name'];

        // Kirim data nama ke view
        // Kita simpan di variabel $data biar rapi saat dipanggil di view
        $data = [
            'title' => 'Dashboard ' . ucfirst($role),
            'user' => $userName,
            'role' => $role
        ];

        // Panggil view dashboard utama
        require_once __DIR__ . '/../Views/dashboard/index.php';
    }
}