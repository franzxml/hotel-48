<?php
namespace App\Exceptions;
use Exception;

class DatabaseException extends Exception {
    public function errorMessage() {
        return "Kesalahan Database: " . $this->getMessage();
    }
}