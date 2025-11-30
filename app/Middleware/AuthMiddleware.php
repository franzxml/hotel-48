<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Config\JwtConfig;
use Exception;

class AuthMiddleware {
    public function validateToken() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $this->sendJson(['status' => 401, 'message' => 'Token tidak ditemukan'], 401);
        }

        $jwt = $matches[1];

        try {
            $decoded = JWT::decode($jwt, new Key(JwtConfig::SECRET_KEY, JwtConfig::ALGORITHM));
            return $decoded->data; // Mengembalikan Data User (ID, Email)
        } catch (Exception $e) {
            $this->sendJson(['status' => 401, 'message' => 'Token tidak valid: ' . $e->getMessage()], 401);
        }
    }

    private function sendJson($data, $code) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}