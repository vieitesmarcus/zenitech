<?php

declare(strict_types=1);

namespace Marcus\Zenitech\Services;

use Marcus\Zenitech\Database\Dao;
use Marcus\Zenitech\Exceptions\ExceptionUserEmail;
use Marcus\Zenitech\Models\User;
use PDO;

class UserService extends Dao
{
    public function list(): array
    {
        $stmt = $this->conexao->prepare("SELECT * FROM users ORDER BY id DESC");
        $stmt->execute();
        $users = $stmt->fetchAll();
        return $users;
    }

    public function create(array $dados): bool
    {
        $user = new User();
        $user->setEmail($dados['email']);
        $user->setNome($dados['nome']);
        $user->setDataNascimento($dados['data_nascimento']);
        $user->setFoto('');
        $verificado = $this->verificaEmailExistente($user->getEmail());
        if ($verificado) {
            throw new ExceptionUserEmail('O email já está cadastrado!');
        }

        $stmt = $this->conexao->prepare("INSERT INTO users (nome, email, data_nascimento, foto) VALUES (?,?,?,?)");
        $stmt->bindValue(1, $user->getNome());
        $stmt->bindValue(2, $user->getEmail());
        $stmt->bindValue(3, $user->getDataNascimento());
        $stmt->bindValue(4, $user->getFoto());
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    private function verificaEmailExistente(string $email): bool
    {
        $stmt = $this->conexao->prepare("SELECT id,nome,email FROM users where email=?");
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            return false;
        }
        return true;
    }
}
