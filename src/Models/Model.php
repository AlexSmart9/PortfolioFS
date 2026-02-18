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
        
        $query = "SELECT * FROM {$this->table} ORDER BY id DESC";
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
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    //Generic method to update data.
    public function update($id, $data) {
        
        //1. Preparing the parts of the SQL query.
        $fields = [];
        foreach($data as $key =>$value) {
            $fields[] = "{$key} = :{$key}";
        }

        //2. Combining the array into a comma-separated string
        $setClause = implode(', ', $fields);

        //3. Bulding the final sQL query.
        $query = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";

        try {
            
            $stmt = $this->conn->prepare($query);

            //4. Linking the array values (Binding).

            foreach($data as $key => $value){
                $stmt->bindValue(":{$key}", $value);
            }

            //5. Linking the id.
            $stmt->bindValue(':id', $id);

            //6. Executing
            return $stmt->execute();


        } catch (\PDOException $e) {
            echo "Error update" . $e->getMessage();
            return false;
        }
    }
}

