<?php
//Arquivo de entrada
declare(strict_types=1);
ini_set('error_reporting', E_ALL);
require_once __DIR__ . "/../vendor/autoload.php";
header('Content-Type:text/html', true, 200);
session_start();
$routes = require __DIR__ . "/../routes/web.php";
$teste = require __DIR__ . "/../Config/Database.php";
$path = $_SERVER['REQUEST_URI'] ?? "/home";
$method = $_SERVER["REQUEST_METHOD"];

// Verifica se a rota existe
if (array_key_exists("$method|$path", $routes)) {
    $controller = new $routes["$method|$path"][0]();
    $methodName = $routes["$method|$path"][1];
    $controller->$methodName();
    
    ob_end_flush();
} else {
    
    require __DIR__ . "/../resources/pages/error404.php";
}
