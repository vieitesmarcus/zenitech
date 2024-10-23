<?php

declare(strict_types=1);

namespace Marcus\Zenitech\Controllers;

use Exception;
use Marcus\Zenitech\Exceptions\ExceptionUserDataNascimento;
use Marcus\Zenitech\Exceptions\ExceptionUserEmail;
use Marcus\Zenitech\Exceptions\ExceptionUserName;
use Marcus\Zenitech\Services\UserService;

class UserController
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
        session_destroy();
        return $page;
    }

    public function create()
    {
        $page = require __DIR__ . "/../../resources/pages/users/create.php";
        session_destroy();
        return $page;
    }
    public function store()
    {
        $_SESSION['email'] = filter_input(INPUT_POST, 'email');
        $_SESSION['nome'] = filter_input(INPUT_POST, 'nome');
        $_SESSION['data_nascimento'] = filter_input(INPUT_POST, 'data_nascimento');
        $dadosASeremValidados = [
            'nome' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'email' => FILTER_VALIDATE_EMAIL,
            'data_nascimento' => FILTER_DEFAULT
        ];
        $arrayDados = filter_input_array(INPUT_POST, $dadosASeremValidados);
        try {
            $retorno = $this->userService->create($arrayDados);
            if ($retorno) {
                $_SESSION['mensagem'] = "Usuário cadastrado com sucesso!";
                $_SESSION['tipo_mensagem'] = "success";
            }
            if (!$retorno) {
                $_SESSION['mensagem'] = "Erro ao cadastrar usuário!";
                $_SESSION['tipo_mensagem'] = "warning";
            }
            header('Location: /users', true, 302);
            exit();
        } catch (ExceptionUserEmail $e) {
            $_SESSION['errors']['email'] = $e->getMessage();
            header('Location: /users/create', true, 302);
            exit();
        } catch (ExceptionUserName $e) {
            $_SESSION['errors']['nome'] = $e->getMessage();
            header('Location: /users/create', true, 302);
            exit();
        } catch (ExceptionUserDataNascimento $e) {
            $_SESSION['errors']['data_nascimento'] = $e->getMessage();
            header('Location: /users/create', true, 302);
            exit();
        } catch (Exception $e) {
            $_SESSION['errors']['geral'] = $e->getMessage();
            header('Location: /users/create', true, 302);
            exit();
        }
    }
}
