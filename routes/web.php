<?php

declare(strict_types=1);

use Marcus\Zenitech\Controllers\UserController;

//Responsável pela criação de rotas
return [
    'GET|/users'          => [UserController::class, 'index'],
    'GET|/users/create'   => [UserController::class, 'create'],
    'POST|/users/store'   => [UserController::class, 'store'],
    'GET|/users/edit'     => [UserController::class, 'edit'],
    'POST|/users/update'  => [UserController::class, 'update'],
    'POST|/users/destroy' => [UserController::class, 'destroy'],
];
