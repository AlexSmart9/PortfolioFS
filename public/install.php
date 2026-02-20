<?php
// Este archivo solo sirve para instalar la BD.
// Una vez que funcione, no necesitas visitarlo de nuevo.

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;
use Dotenv\Dotenv;

// 1. Load config
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// 2. Getting the connection
$db = Database::getConnection();

echo "Iniciando instalación dinámica de tablas...\n";
echo "-----------------------------------------\n";

// 3. Scaning files inside Models folder
$modelFIles = glob(__DIR__ . '/src/Models/*.php');

foreach($modelFIles as $file) {

    // 4. Extract name without .php extention
    $className = basename($file, '.php');

    // 5. Ignore base class Model
    if($className === 'Model') {
        continue;
    }

    // 6. Build full class name whit it's Namespace
    $fullClassName = "\\App\\Models\\$className";

    if (class_exists($fullClassName)) {

        $modelInstance = new $fullClassName($db);

        if(method_exists($modelInstance, 'createTable')) {
            $modelInstance->createTable();
            echo "📂Tables created succesfuly for model: $className";
        }
    }
}

echo "-----------------------------------------\n";
echo "¡Instalación y escaneo completados!\n";