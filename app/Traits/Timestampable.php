<?php
namespace App\Traits;

trait Timestampable {
    protected $created_at;
    protected $updated_at;

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function setCreatedAt($date) {
        $this->created_at = $date;
    }
    
    // Fungsi bantuan untuk mengisi waktu saat insert
    public function touchTimestamp() {
        $this->created_at = date('Y-m-d H:i:s');
    }
}