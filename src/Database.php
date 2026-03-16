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
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db   = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];

        // 1. Extraemos el Endpoint ID de Neon (es la primera parte de tu DB_HOST antes del primer punto)
        $endpointId = explode('.', $host)[0];

        // 2. Armamos la cadena DSN con los requisitos estrictos de Neon: sslmode y options
        $dsn = "pgsql:host={$host};port={$port};dbname={$db};sslmode=require;options=endpoint={$endpointId}";

        try {
            $pdo = new \PDO($dsn, $user, $pass);
            
            // Para que los errores de la BD nos avisen claramente si algo falla
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            return $pdo;
        } catch (\PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
