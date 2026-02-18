<?php

namespace App;

use PDO;
use PDOException;

class Database {

    //Static property to save the only instance
    private static $instance = null;
    
    private $connection;


    // PRIVATE constructor: Nobody can instance a database Except itseLf.
    private function __construct() {
        
        $host = $_ENV['DB_HOST'];
        $db = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];
        $port = $_ENV['DB_PORT'];

        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$db";
            $this->connection = new PDO($dsn, $user, $pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            exit;
        }

    }

    //Static method tp get the instance
    public static function getConnection() {
        
        //If the instance desn't exis, then it's created.
        if(self::$instance === null){
            self::$instance = new Database();

        }
        
        //Returning the connection of the instance.
        return self::$instance->connection;
        
    }
}
