<?php

namespace App\Payments;

class DanaPayment implements PaymentInterface
{
    public function pay($amount)
    {
        // Simulasi: Ceritanya kita connect ke Server DANA
        return [
            'success' => true,
            'message' => "Pembayaran Rp " . number_format($amount) . " BERHASIL via DANA!",
            'provider' => 'DANA'
        ];
    }
}