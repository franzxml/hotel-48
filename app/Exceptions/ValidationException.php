<?php
namespace App\Exceptions;
use Exception;

class ValidationException extends Exception {
    public function errorMessage() {
        return "Validasi Gagal: " . $this->getMessage();
    }
}