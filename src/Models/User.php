<?php

namespace App\Models;

use PDO;

// 1. Inherited from the class "Model" (Mini-ORM)
class User extends Model {
    
    // 2. Definimos la tabla que usará el Padre
    protected $table = 'users';

    /**
     * Search for a user by their email address.
     */
    public function getByEmail($email) {

        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Method for creating the table in the database (Installation).
     */
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(20) DEFAULT 'guest',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        try {
            $this->conn->exec($sql);
            echo "Tabla '{$this->table}' creada o verificada correctamente.<br>";
        } catch(\PDOException $e) {
            echo "Error al crear la tabla '{$this->table}': " . $e->getMessage() . "<br>";
        }
    }
}
