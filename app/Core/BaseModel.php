<?php
namespace App\Core;

class BaseModel {
    // Magic Method: Memungkinkan akses properti protected seolah-olah public (Read-Only)
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    // Magic Method: Memungkinkan set properti protected
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
}