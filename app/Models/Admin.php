<?php

namespace App\Models;

class Admin extends User
{
    public function __construct($db)
    {
        parent::__construct($db);
        $this->role = 'admin'; // Set otomatis role jadi admin
    }

    // Nanti di sini kita tambah method khusus admin, misal: addRoom()
}