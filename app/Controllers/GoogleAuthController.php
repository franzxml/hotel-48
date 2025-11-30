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
        
        // --- 1. AMBIL KREDENSIAL DENGAN AMAN (Anti-Warning) ---
        // Prioritaskan $_ENV, jika tidak ada cek getenv(), jika tidak ada null
        $clientId     = $_ENV['GOOGLE_CLIENT_ID'] ?? getenv('GOOGLE_CLIENT_ID') ?? null;
        $clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? getenv('GOOGLE_CLIENT_SECRET') ?? null;
        $redirectUri  = $_ENV['GOOGLE_REDIRECT_URI'] ?? getenv('GOOGLE_REDIRECT_URI') ?? null;

        // --- 2. VALIDASI MANUAL (Agar error mudah dibaca) ---
        if (!$clientId || !$clientSecret || !$redirectUri) {
            // Tampilkan pesan error bersih, bukan stack trace
            http_response_code(500);
            die("Error Konfigurasi: Google Client ID, Secret, atau Redirect URI belum diset di Vercel Environment Variables.");
        }

        $this->client->setClientId($clientId); 
        $this->client->setClientSecret($clientSecret);
        $this->client->setRedirectUri($redirectUri);
        
        $this->client->addScope("email");
        $this->client->addScope("profile");

        // Gunakan Singleton Database
        $this->db = Database::getInstance()->getConnection();
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
            $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
            
            if(isset($token['error'])){
                // Redirect ke login jika gagal/cancel
                header("Location: index.php?action=login");
                exit;
            }

            $this->client->setAccessToken($token);

            $google_oauth = new Oauth2($this->client);
            $google_account_info = $google_oauth->userinfo->get();
            
            $email = $google_account_info->email;
            $name = $google_account_info->name;
            $google_id = $google_account_info->id;

            $this->handleUser($name, $email, $google_id);

        } else {
             header("Location: index.php?action=login");
        }
    }

    private function handleUser($name, $email, $googleId)
    {
        // Cek User
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            $userId = $user->id;
            $role = $user->role;
            // Update Google ID jika kosong
            if (empty($user->google_id)) {
                $upd = $this->db->prepare("UPDATE users SET google_id = :gid WHERE id = :id");
                $upd->execute(['gid' => $googleId, 'id' => $userId]);
            }
        } else {
            // Register Baru
            $ins = "INSERT INTO users (name, email, role, google_id, password) VALUES (:name, :email, 'customer', :gid, '')";
            $stmt = $this->db->prepare($ins);
            $stmt->execute(['name' => $name, 'email' => $email, 'gid' => $googleId]);
            $userId = $this->db->lastInsertId();
            $role = 'customer';
        }

        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_role'] = $role;

        header("Location: index.php?action=dashboard");
        exit();
    }
}