<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;
use Dotenv\Dotenv;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Conectar a la BD
$db = Database::getConnection();

$email = 'warxg23@gmail.com';
$password = 'ASDFG1436';
// Encriptar la contraseña de forma segura
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

try {
    $stmt = $db->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
    $stmt->execute([
        'email' => $email,
        'password' => $hashedPassword
    ]);
    echo "🕵️‍♂️ ¡Admin creado con éxito! Ya puedes iniciar sesión.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}