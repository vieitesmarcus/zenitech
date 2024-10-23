<?php

declare(strict_types=1);

namespace Marcus\Zenitech\Services;

use Marcus\Zenitech\Database\Dao;
use PDO;

class UserService extends Dao
{
    public function list(): array
    {
        $stmt = $this->conexao->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll();
        return $users;
    }
}
