<?php

declare(strict_types=1);

use Marcus\Zenitech\Controllers\IndexController;

//Responsável pela criação de rotas
return [
    'GET|/users'           => [IndexController::class, 'index'],
];
