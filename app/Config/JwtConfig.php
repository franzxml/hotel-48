<?php
namespace App\Config;

class JwtConfig {
    // Kunci rahasia (Sebaiknya taruh di .env, tapi ini hardcoded untuk kemudahan)
    public const SECRET_KEY = 'RahasiaNegaraHotel48_JanganDisebar!';
    public const ALGORITHM = 'HS256';
    public const TOKEN_EXPIRY = 3600; // 1 Jam
}