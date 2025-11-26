<?php

namespace App\Payments;

class GopayPayment implements PaymentInterface
{
    public function pay($amount)
    {
        // Simulasi: Ceritanya kita connect ke Server GoPay
        return [
            'success' => true,
            'message' => "Pembayaran Rp " . number_format($amount) . " BERHASIL via GoPay!",
            'provider' => 'GOPAY'
        ];
    }
}