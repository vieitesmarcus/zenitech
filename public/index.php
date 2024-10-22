<?php
//Arquivo de entrada
declare(strict_types=1);
header('Content-Type:text/html',true, 200);
session_start();
ini_set('error_reporting', E_ALL);

$routes = require __DIR__ . "/../routes/web.php";
$path = $_SERVER['REQUEST_URI'] ?? "/home";
$method = $_SERVER["REQUEST_METHOD"];

// echo '<pre>';
// var_dump("$method|$path", $path);
// echo '</pre>';exit();

// Verifica se a rota existe
if (array_key_exists("$method|$path", $routes)) {
    $controller = new $routes["$method|$path"][0]();
    $methodName = $routes["$method|$path"][1];
    $controller->$methodName();
    
    ob_end_flush();
} else {
    
    require __DIR__ ."/../resources/pages/error404.php";
}