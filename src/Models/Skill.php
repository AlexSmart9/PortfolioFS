<?php

namespace App\Models;

use PDO;
use PDOException;

class Skill extends Model {

    protected $table = "skills";

    public function createTable() {

        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            icon_class VARCHAR(100) NOT NULL,
            category VARCHAR(100) NOT NULL
        )";

        try {
            $this->conn->exec($sql);
        } catch (\PDOException $e) {
            echo "Error creating table " . $e->getMessage();
        }
    }

}