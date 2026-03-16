<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;
use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();


$db = Database::getConnection();

$name = 'Alejandro';
$email = 'warxg23@gmail.com';
$password = 'ASDFG1436';
$role = 'admin';


$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

try {
    
    $stmt = $db->prepare("UPDATE users SET name = :name, password = :password, role = :role WHERE email = :email");
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'password' => $hashedPassword,
        'role' => $role
    ]);
    
    //
    if ($stmt->rowCount() > 0) {
        echo "🕵️‍♂️ ¡Usuario actualizado con éxito! La contraseña ha sido re-encriptada y ahora eres Admin.";
    } else {
        echo "🤔 Mmm, no se encontró el correo. Esto es muy raro.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}