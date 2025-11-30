<?php

namespace App\Controllers;

class DashboardController
{
    public function __construct()
    {
        // 1. Mulai session jika belum mulai
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 2. PROTEKSI: Jika belum login, redirect ke login page
        if (!isset($_SESSION['user_id'])) {
            // PERBAIKAN: Gunakan path relatif 'index.php', jangan '/hotel_48/public/...'
            // Ini agar aman dijalankan di server manapun (Localhost, Laragon, Vercel)
            header("Location: index.php?action=login"); 
            exit();
        }
    }

    public function index()
    {
        $role = $_SESSION['user_role'];
        $userName = $_SESSION['user_name'];

        $data = [
            'title' => 'Dashboard ' . ucfirst($role),
            'user' => $userName,
            'role' => $role
        ];

        require_once __DIR__ . '/../Views/dashboard/index.php';
    }
}