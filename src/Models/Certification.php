<?php

namespace App\Models;

use PDO;
use PDOException;

class Certification extends Model {


    protected $table = "certifications";

    public function createTable() {

        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id SERIAL PRIMARY KEY,
            title VARCHAR(100) NOT NULL,
            issuing_entity VARCHAR(100),
            date_acquisition VARCHAR(20),
            image_url VARCHAR(255)
        )";

        try {
            
            $this->conn->exec($sql);

        } catch (\PDOException $e) {
            echo "Error creating table: " . $e->getMessage();
        }   

    }
}