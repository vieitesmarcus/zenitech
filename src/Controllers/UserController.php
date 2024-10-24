<?php

declare(strict_types=1);

namespace Marcus\Zenitech\Controllers;

use Exception;
use Marcus\Zenitech\Exceptions\ExceptionUserDataNascimento;
use Marcus\Zenitech\Exceptions\ExceptionUserEmail;
use Marcus\Zenitech\Exceptions\ExceptionUserFoto;
use Marcus\Zenitech\Exceptions\ExceptionUserName;
use Marcus\Zenitech\Models\User;
use Marcus\Zenitech\Services\UserService;
use PDOException;

class UserController
{
    private UserService $userService;
    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * Processa e exibe a página de listagem de usuários com paginação e pesquisa opcional.
     *
     * @throws PDOException Se houver um erro de conexão com o banco de dados.
     * @throws Exception Se houver qualquer outra exceção.
     */
    public function index()
    {
        // recebe os dados da url 
        //@var $pesquisa
        $pesquisa = filter_input(INPUT_GET, 'pesquisa', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        $limite = filter_input(INPUT_GET, 'limite', FILTER_VALIDATE_INT);
        if (is_null($page) || empty($page) || $page < 0) {
            $page = 1;
        }
        if (is_null($limite) || empty($limite)) {
            $limite = 5;
        }
        try {

            if (!empty($pesquisa)) {
                $paginate = $this->userService->list($page, $limite, $pesquisa);
            } else {
                $paginate = $this->userService->list($page, $limite);
            }
            $totalPaginas = $this->userService->montaPaginate($paginate['users_count'], $limite);
            $page = require __DIR__ . "/../../resources/pages/users/index.php";
            session_destroy();
            return $page;
        } catch (PDOException $e) {
            $_SESSION['mensagem'] = "erro com a conexão de banco de dados, Tente rodar as migrations";
            $_SESSION['tipo_mensagem'] = 'warning';
            header('Location: /error');
            exit();
        } catch (Exception $e) {
            $_SESSION['mensagem'] = $e->getMessage();
            $_SESSION['tipo_mensagem'] = 'warning';
            header('Location: /users', true, 302);
            exit();
        }
    }
    /**
     * Carrega a página de criação de usuários e encerra a sessão atual.
     *
     * @return mixed A página de criação de usuários.
     */
    public function create()
    {
        $page = require __DIR__ . "/../../resources/pages/users/create.php";
        session_destroy();
        return $page;
    }


    /**
     * Processa o formulário de criação de usuário e valida os dados de entrada.
     *
     * @throws ExceptionUserEmail Se houver um erro relacionado ao campo email.
     * @throws ExceptionUserName Se houver um erro relacionado ao campo nome.
     * @throws ExceptionUserDataNascimento Se houver um erro relacionado ao campo data de nascimento.
     * @throws ExceptionUserFoto Se houver um erro relacionado ao campo foto.
     * @throws Exception Se houver qualquer outra exceção.
     */
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
        $arrayDados['foto'] = $_FILES['foto'];
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
        } catch (ExceptionUserFoto $e) {
            $_SESSION['errors']['foto'] = $e->getMessage();
            header('Location: /users/create', true, 302);
            exit();
        } catch (Exception $e) {
            $_SESSION['errors']['geral'] = $e->getMessage();
            header('Location: /users/create', true, 302);
            exit();
        }
    }

    /**
     * Carrega a página de edição de usuário baseado no ID fornecido.
     *
     * @throws Exception Se houver qualquer exceção ao buscar o usuário.
     * @return mixed A página de edição de usuário.
     */
    public function edit()
    {

        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        try {
            $user = $this->userService->findById((int)$id);
            $page = require __DIR__ . "/../../resources/pages/users/edit.php";
            session_destroy();
            return $page;
        } catch (Exception $e) {
            $_SESSION['mensagem'] = $e->getMessage();
            $_SESSION['tipo_mensagem'] = 'info';

            header('Location: /users');
            exit();
        }
    }

    /**
     * Atualiza os dados de um usuário baseado no ID fornecido e valida os dados de entrada.
     *
     * @throws ExceptionUserEmail Se houver um erro relacionado ao campo email.
     * @throws ExceptionUserName Se houver um erro relacionado ao campo nome.
     * @throws ExceptionUserDataNascimento Se houver um erro relacionado ao campo data de nascimento.
     * @throws ExceptionUserFoto Se houver um erro relacionado ao campo foto.
     * @throws Exception Se houver qualquer outra exceção.
     */
    public function update()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $_SESSION['email'] = filter_input(INPUT_POST, 'email');
        $_SESSION['nome'] = filter_input(INPUT_POST, 'nome');
        $_SESSION['data_nascimento'] = filter_input(INPUT_POST, 'data_nascimento');
        $dadosASeremValidados = [
            'nome' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'email' => FILTER_VALIDATE_EMAIL,
            'data_nascimento' => FILTER_DEFAULT
        ];
        $arrayDados = filter_input_array(INPUT_POST, $dadosASeremValidados);
        $arrayDados['foto'] = $_FILES['foto'];
        try {
            $user = $this->userService->update((int)$id, $arrayDados);
            if ($user instanceof User) {
                $_SESSION['mensagem'] = "Usuário atualizado com sucesso!";
                $_SESSION['tipo_mensagem'] = "success";
                header('Location: /users');
                exit();
            }
            $_SESSION['mensagem'] = "Não foi possível atualizar o usuário!";
            $_SESSION['tipo_mensagem'] = "info";
            header('Location: /users');
            exit();
        } catch (ExceptionUserEmail $e) {
            $_SESSION['errors']['email'] = $e->getMessage();
            header('Location: /users/edit?id=' . $id, true, 302);
            exit();
        } catch (ExceptionUserName $e) {
            $_SESSION['errors']['nome'] = $e->getMessage();
            header('Location: /users/edit?id=' . $id, true, 302);
            exit();
        } catch (ExceptionUserDataNascimento $e) {
            $_SESSION['errors']['data_nascimento'] = $e->getMessage();
            header('Location: /users/edit?id=' . $id, true, 302);
            exit();
        } catch (ExceptionUserFoto $e) {
            $_SESSION['errors']['foto'] = $e->getMessage();
            header('Location: /users/edit?id=' . $id, true, 302);
            exit();
        } catch (Exception $e) {
            $_SESSION['mensagem'] = $e->getMessage();
            $_SESSION['tipo_mensagem'] = 'info';

            header('Location: /users/edit?id=' . $id);
            exit();
        }
    }

    /**
     * Exclui um usuário baseado no ID fornecido.
     *
     * @throws Exception Se houver qualquer exceção ao excluir o usuário.
     */
    public function destroy()
    {
        //filtra da url o campo id
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        try {
            //Chama a camada de serviço e retorna se foi apagado ou não
            if ($this->userService->destroy((int)$id)) {
                $_SESSION['mensagem'] = 'Usuário excluído com sucesso!';
                $_SESSION['tipo_mensagem'] = 'success';
                header('Location: /users', true, 302);
                exit();
            }
            $_SESSION['mensagem'] = 'Não foi possível fazer a exclusão do usuário!';
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: /users', true, 302);
            exit();
        } catch (Exception $e) {
            $_SESSION['mensagem'] = $e->getMessage();
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: /users', true, 400);
            exit();
        }
    }
}
