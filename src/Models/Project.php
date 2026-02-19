<?php

    namespace App\Models;

    use PDO;
    use PDOException;

    class Project extends Model {
        
        protected $table = 'projects';

        public function createTable() {
                $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
                    id SERIAL PRIMARY KEY,
                    title VARCHAR(100) NOT NULL,
                    description TEXT,
                    image_url VARCHAR(255),
                    link VARCHAR(255),
                    technologies VARCHAR(100),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
                
                try {

                    $this->conn->exec($sql);

                } catch(\PDOException $e) {
                    echo "Error creating table: " . $e->getMessage();
                }
        }

    }