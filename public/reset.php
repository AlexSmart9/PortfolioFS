<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use App\Database;
use App\Models\User;
use App\Models\Project;
use App\Models\Certification;
use App\Models\Skill;
use App\Models\Post;

$db = Database::getConnection();

echo "⚠️ INICIANDO REINICIO (MODO FRANCO-TIRADOR)...\n";
echo "-----------------------------------------\n";

try {
    // 1. Limpieza total
    $db->exec("DROP TABLE IF EXISTS users, projects, certifications, skills, posts CASCADE");
    echo "🗑️ Tablas eliminadas correctamente.\n";

    // 2. Reconstrucción explícita y ordenada
    echo "🏗️ Construyendo tablas paso a paso...\n";
    
    $userModel = new User($db);
    $userModel->createTable();
 
    $postModel = new Post($db);
    $postModel->createTable();

    $projectModel = new Project($db);
    $projectModel->createTable();
 

    $certModel = new Certification($db);
    $certModel->createTable();
 

    $skillModel = new Skill($db);
    $skillModel->createTable();


    // 3. Crear Admin
    echo "👑 Insertando al Admin Supremo...\n";
    $hashedPassword = password_hash('123456', PASSWORD_BCRYPT);
    $sqlAdmin = "INSERT INTO users (name, email, password, role) VALUES ('Alejandro Admin', 'admin@test.com', :password, 'admin')";
    $stmt = $db->prepare($sqlAdmin);
    $stmt->execute(['password' => $hashedPassword]);

    echo "-----------------------------------------\n";
    echo "🚀 ¡SISTEMA 100% RESTAURADO Y OPERATIVO!\n";

} catch (\PDOException $e) {
    echo "\n❌ ERROR CRÍTICO SQL: " . $e->getMessage() . "\n";
}