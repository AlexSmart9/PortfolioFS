<?php

namespace App\Models;

use PDO;

class Post extends Model {

    protected $table = 'posts';

    public function createTable() {

        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255),
            content TEXT NOT NULL,
            image_url VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        try {
            $this->conn->exec($sql);
                echo "   ✅ Table '{$this->table}' ready.\n";
        } catch (\PDOException $e) {
            echo "❌ Error creating table '{$this->table}': " . $e->getMessage() . "\n";
        }

        
        }
        
        public function searchByTitle($keyword) {

            $sql = "SELECT id, title, image_url, created_at FROM {$this->table} WHERE title ILIKE :keyword ORDER BY created_at DESC ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':keyword', "%$keyword%");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
        
    
}