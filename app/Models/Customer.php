<?php

namespace App\Models;

// Inheritance: Customer mewarisi semua sifat User
class Customer extends User
{
    public function __construct($db)
    {
        parent::__construct($db); // Panggil constructor User
        $this->role = 'customer'; // Set otomatis role jadi customer
    }

    // Nanti di sini kita tambah method khusus customer, misal: bookRoom()
}