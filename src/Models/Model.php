<?php
namespace App\Models;

use PDO;

abstract class Model {
    protected $conn;
    protected $table;

    public function __construct($db) {
        $this->conn = $db;
    }

    //Generic method to get all data.
    public function getAll() {
        $table = $this->table;
        $query = "SELECT * FROM {$table} ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Generic method tpo get data by id,
    public function getById($id) {
        
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Genernic method to delete data.
    public function delete($id) {
        
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;;
    }

    // Generic method to create data
    public function create($data) {

        // Getting the keys
        $keys = array_keys($data);

        // Creating string columns
        $columns = implode(", ",$keys);

        // Creating a placeholders string
        $placeholders = ":" . implode(", :", $keys);

        // Bulding the final SQL query.
        $sql = "INSERT INTO {$this->table} ($columns) VAlUES ($placeholders)";

        $stmt = $this->conn->prepare($sql);

        // Executing throwing the clean array data
        return $stmt->execute($data);
    }

    //Generic method to update data.
    public function update($id, $data) {
        
        //1. Preparing the parts of the SQL query.
        $fields = "";
        foreach($data as $key => $value) {
            $fields .= "$key = :$key, ";
        }

        $fields = rtrim($fields, ", ");


        //3. Bulding the final SQL query.
        $query = "UPDATE {$this->table} SET $fields WHERE id = :id";
            
        $stmt = $this->conn->prepare($query);

        //4. Linking the array values (Binding).
        $data['id'] = $id;

        $stmt->execute($data);
            
        return $stmt->rowCount() > 0;

    }
}

