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
        // DEBUGGING MODE ON
        echo "<h1>🔍 DEBUG LOGIN</h1>";
        echo "1. Masuk ke loginProcess...<br>";

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        echo "2. Data diterima. Email: " . $email . "<br>";
        echo "3. Mencoba memanggil Model User->login()...<br>";

        try {
            // Cek apakah object user sudah ada
            if (!$this->user) {
                die("❌ ERROR: Object User belum dibuat. Cek Constructor!");
            }

            // Panggil fungsi login di Model
            $loginResult = $this->user->login($email, $password);
            
            echo "4. Hasil dari Model User: " . ($loginResult ? "TRUE (Ketemu)" : "FALSE (Gak Ketemu)") . "<br>";

            if ($loginResult) {
                echo "✅ Login Sukses! Menyimpan Session...<br>";
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['user_name'] = $this->user->name;
                $_SESSION['user_role'] = $this->user->role;

                echo "Redirecting ke Dashboard...";
                header("Location: index.php?action=dashboard");
                exit();
            } else {
                echo "⚠️ Login Gagal (Password Salah / Email tidak ada).<br>";
                echo "<a href='index.php?action=login'>Coba Lagi</a>";
            }

        } catch (\Exception $e) {
            die("❌ TERJADI ERROR FATAL: " . $e->getMessage());
        } catch (\PDOException $e) {
            die("❌ ERROR DATABASE: " . $e->getMessage());
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