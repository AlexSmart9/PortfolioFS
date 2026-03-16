<?php
// 1. Forzar errores desde la línea 1
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. IMPORTACIONES AL PRINCIPIO (Regla de PHP)
use App\Database;
use App\Models\User;
use App\Models\Project;
use App\Models\Certification;
use App\Models\Skill;
use App\Models\Post;

// 3. La Red de Seguridad Absoluta
try {
    require_once __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->safeLoad();

    $db = Database::getConnection();

    echo "⚠️ INICIANDO REINICIO (MODO FRANCO-TIRADOR)...<br>\n";
    echo "-----------------------------------------<br>\n";

    // Limpieza total
    $db->exec("DROP TABLE IF EXISTS users, projects, certifications, skills, posts CASCADE");
    echo "✅ Tablas eliminadas correctamente.<br>\n";

    // Reconstrucción
    echo "🏗️ Construyendo tablas paso a paso...<br>\n";
    
    (new User($db))->createTable();
    (new Post($db))->createTable();
    (new Project($db))->createTable();
    (new Certification($db))->createTable();
    (new Skill($db))->createTable();

    // 4. Leer variables de forma segura
    $adminEmail = getenv('ADMIN_EMAIL') ?: $_ENV['ADMIN_EMAIL'] ?? 'admin@test.com';
    $adminPass = getenv('ADMIN_PASSWORD') ?: $_ENV['ADMIN_PASSWORD'] ?? '123456'; 
    
    echo "👑 Insertando al Admin Supremo...<br>\n";
    $hashedPassword = password_hash($adminPass, PASSWORD_BCRYPT);
    $sqlAdmin = "INSERT INTO users (name, email, password, role) VALUES ('Alejandro', :email , :password, 'admin')";
    $stmt = $db->prepare($sqlAdmin);
    $stmt->execute([
          'email' => $adminEmail,
          'password' => $hashedPassword
      ]);

    echo "-----------------------------------------<br>\n";
    echo "🚀 ¡SISTEMA 100% RESTAURADO Y OPERATIVO!<br>\n";

} catch (\Throwable $e) { 
    echo "<br>❌ <b>ERROR CRÍTICO DETECTADO:</b><br>";
    echo "<b>Mensaje:</b> " . $e->getMessage() . "<br>";
    echo "<b>Archivo:</b> " . $e->getFile() . " (Línea " . $e->getLine() . ")<br>";
}