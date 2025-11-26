<?php

namespace App\Controllers;

use Google\Client;
use Google\Service\Oauth2;
use App\Config\Database;

class GoogleAuthController
{
    private $client;
    private $db;

    public function __construct()
    {
        $this->client = new Client();
        
        // --- KONFIGURASI GOOGLE ---
        // Masukkan Client ID & Secret punya kamu di sini
        $this->client->setClientId($_ENV['GOOGLE_CLIENT_ID']); 
        $this->client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $this->client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
        
        $this->client->addScope("email");
        $this->client->addScope("profile");

        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login()
    {
        $authUrl = $this->client->createAuthUrl();
        header("Location: " . $authUrl);
        exit();
    }

    public function callback()
    {
        if (isset($_GET['code'])) {
            // Tukar kode dengan token akses
            $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
            
            // Cek jika ada error
            if(isset($token['error'])){
                // Debugging: uncomment baris bawah ini kalau mau lihat errornya apa
                // die("Error Token: " . print_r($token, true));
                header("Location: index.php?action=login");
                exit;
            }

            // PERBAIKAN 2: Set Token ke client
            $this->client->setAccessToken($token);

            // Ambil data profil dari Google
            $google_oauth = new Oauth2($this->client);
            $google_account_info = $google_oauth->userinfo->get();
            
            $email = $google_account_info->email;
            $name = $google_account_info->name;
            $google_id = $google_account_info->id;

            // Proses Login/Register di Database kita
            $this->handleUser($name, $email, $google_id);

        } else {
             // Kalau user membatalkan login / tidak ada code
             header("Location: index.php?action=login");
        }
    }

    private function handleUser($name, $email, $googleId)
    {
        // Cek User Lama
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            $userId = $user->id;
            $role = $user->role;
            // Update Google ID jika belum ada
            if (empty($user->google_id)) {
                $upd = $this->db->prepare("UPDATE users SET google_id = :gid WHERE id = :id");
                $upd->execute(['gid' => $googleId, 'id' => $userId]);
            }
        } else {
            // Register User Baru
            $ins = "INSERT INTO users (name, email, role, google_id) VALUES (:name, :email, 'customer', :gid)";
            $stmt = $this->db->prepare($ins);
            $stmt->execute(['name' => $name, 'email' => $email, 'gid' => $googleId]);
            $userId = $this->db->lastInsertId();
            $role = 'customer';
        }

        // Set Session
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_role'] = $role;

        // Redirect ke Dashboard
        header("Location: index.php?action=dashboard");
        exit();
    }
}