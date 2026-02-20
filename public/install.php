<?php
// Este archivo solo sirve para instalar la BD.
// Una vez que funcione, no necesitas visitarlo de nuevo.

require __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Models\User;
use App\Models\Project;
use App\Models\Certification;
use App\Models\Skill;
use Dotenv\Dotenv;

// 1. Load config
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// 2. Getting the connection
$db = Database::getConnection();


// 3. Creating Instance Model
$userModel = new User($db);
$projectModel = new Project($db);
$certificationModel = new Certification($db);
$skillModel = new Skill($db);

// 4. Crear la Tabla
$userModel->createTable();
$projectModel->createTable();
$certificationModel->createTable();
$projectModel->createTable();
$skillModel->createTable();

// 5. Insertar Usuario Admin por defecto (Para que puedas probar ya)
// Verificamos si ya existe para no duplicarlo
$existingUser = $userModel->getByEmail('admin@test.com');

if (!$existingUser) {
    echo "Creando usuario administrador...\n";
    $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
    $stmt = $db->prepare($sql);
    
    $stmt->execute([
        ':name' => 'Admin Supremo',
        ':email' => 'admin@test.com',
        ':password' => password_hash('123456', PASSWORD_BCRYPT), // ¡Contraseña segura!
        ':role' => 'admin'
    ]);
    echo "¡Usuario Admin creado con éxito!\n";
    echo "Email: admin@test.com\n";
    echo "Pass: 123456\n";
} else {
    echo "El usuario admin ya existe.\n";
}

echo "--- INSTALACIÓN COMPLETADA ---";