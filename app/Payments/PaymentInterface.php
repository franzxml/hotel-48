<?php

namespace App\Payments;

interface PaymentInterface
{
    // Semua metode bayar (DANA, GoPay) WAJIB punya fungsi ini
    public function pay($amount);
}