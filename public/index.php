<?php
//Arquivo de entrada
declare(strict_types=1);
ini_set('display_errors', 1);            // Habilita a exibição de erros
ini_set('display_startup_errors', 1);    // Exibe erros ocorridos durante o carregamento inicial
error_reporting(E_ALL); // Habilita todos os erros
require_once __DIR__ . "/../vendor/autoload.php";
header('Content-Type:text/html', true, 200);
session_start();

//Chama os arquivos de rotas
$routes = require __DIR__ . "/../routes/web.php";

//verifica o uri do site se caso não for passado, redireciona para /users
$path = $_SERVER['REQUEST_URI'] ?? "/users";

// trata para somente a uri e elimina a querystring 
$path = explode('?', $path)[0];

// armazena o verbo http
$method = $_SERVER["REQUEST_METHOD"];

// Verifica se a rota existe
if (array_key_exists("$method|$path", $routes)) {

    //Cria o controller dinamicamente
    $controller = new $routes["$method|$path"][0]();

    //verifica qual o metodo dessa rota e qual o verbo http atual
    $methodName = $routes["$method|$path"][1];

    //Roda o metodo do controller
    $controller->$methodName();
    exit();
    
} else {
    require __DIR__ . "/../resources/pages/error404.php";
    session_destroy();
    exit();
}
