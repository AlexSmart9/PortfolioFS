
<?php
// Este archivo solo sirve para instalar la BD.
// Una vez que funcione, no necesitas visitarlo de nuevo.

require __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Models\User;
use Dotenv\Dotenv;

// 1. Cargar configuración
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// 2. Conexión

$db = Database::getConnection();

// 3. Instanciar Modelo
$userModel = new User($db);

// 4. Crear la Tabla
echo "Intentando crear tabla 'users'...\n";
$userModel->createTable();

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