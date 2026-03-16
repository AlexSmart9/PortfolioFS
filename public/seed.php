<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Connection
$db = Database::getConnection();

// Super Admin
$name = 'Alejandro';
$email = 'warxg23@gmail.com';
$password = 'ASDFG1436';
$role = 'admin'; 


$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

try {
    
    $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'password' => $hashedPassword,
        'role' => $role
    ]);
    echo "🕵️‍♂️ ¡Admin Alejandro creado con éxito y con superpoderes! Ya puedes iniciar sesión.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}