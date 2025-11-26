<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\User;

class AuthController
{
    private $db;
    private $user;

    public function __construct()
    {
        // Siapkan koneksi DB & Model User setiap kali controller dipanggil
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    // Menampilkan Halaman Login
    public function index()
    {
        // Panggil view (tampilan HTML)
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    // Memproses Data Login dari Form
    public function loginProcess()
    {
        // Mulai session biar server "ingat" user ini
        session_start();

        // Ambil data dari form POST
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Panggil Model untuk cek login
        if ($this->user->login($email, $password)) {
            // Jika SUKSES: Simpan data penting ke Session
            $_SESSION['user_id'] = $this->user->id;
            $_SESSION['user_name'] = $this->user->name;
            $_SESSION['user_role'] = $this->user->role;

            // Redirect ke halaman dashboard (sementara ke index dulu)
            header("Location: index.php?action=dashboard");
            exit();
        }
    }

    public function logout()
    {
            if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        session_destroy();
        
        // Redirect ke file index.php relatif (lebih aman)
        header("Location: index.php");
        exit();
    }
}