<?php
namespace App\Models;
use App\Traits\Timestampable;

class Booking {
    use Timestampable; // Menggunakan Trait

    // Enkapsulasi: Property protected
    protected $id;
    protected $user_id;
    protected $room_id;
    protected $check_in;
    protected $check_out;
    protected $total_price;
    protected $status;

    // Getter dan Setter (Contoh sebagian)
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function setUserId($id) { $this->user_id = $id; }
    public function getUserId() { return $this->user_id; }

    public function setRoomId($id) { $this->room_id = $id; }
    public function getRoomId() { return $this->room_id; }
    
    // ... Setter/Getter untuk properti lain ...
    
    // Magic method untuk kompabilitas View (akan dijelaskan di solusi error)
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
    
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
}