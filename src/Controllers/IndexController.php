<?php

declare(strict_types=1);

namespace Marcus\Zenitech\Controllers;

use Marcus\Zenitech\Services\UserService;

class IndexController
{
    private UserService $userService;
    public function __construct()
    {
        $this->userService = new UserService();
    }
    
    public function index()
    {
        $users = $this->userService->list();
        $page = require __DIR__ . "/../../resources/pages/users/index.php";
        return $page;
    }
}
