<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");



require __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Router;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

//Database
$db = Database::getConnection();

//Router
$router = new Router();

//Load routes
require __DIR__ . '/../src/routes.php';

//Dispatch
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method, $db);




