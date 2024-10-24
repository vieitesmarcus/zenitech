<?php

declare(strict_types=1);

namespace Marcus\Zenitech\Services;

use Exception;
use Marcus\Zenitech\Database\Dao;
use Marcus\Zenitech\Exceptions\ExceptionUserEmail;
use Marcus\Zenitech\Helper\UploadFoto;
use Marcus\Zenitech\Models\User;
use PDO;
use Throwable;

/**
 * Classe de serviço para usuários.
 * Ele extende a classe de acesso aos dados e manipula o usuário
 */
class UserService extends Dao
{

    /**
     * Lista usuários com paginação e pesquisa opcional.
     *
     * @param int $page O número da página atual.
     * @param int $limite O número de itens por página.
     * @param string $pesquisa (opcional) A string de pesquisa para filtrar os resultados.
     * @return array Os dados paginados dos usuários e a contagem total de usuários.
     */
    public function list(int $page, int $limite, string $pesquisa = ''): array
    {
        $limiteMaximo = [5, 10, 25, 50, 100];
        if (!in_array($limite, $limiteMaximo)) {
            $limite = 5;
        }
        if ($page < 0) {
            $page = 1;
            // throw new Exception('Página inválida!', 400);
        }

        $offset = ($page - 1) * $limite;
        $like = "";
        if ($pesquisa != '') {
            $like = "WHERE nome LIKE ? OR email LIKE ?";
        }
        $select = "SELECT * FROM users ";
        $order = " ORDER BY id DESC LIMIT ? OFFSET ?";
        $sql = $select . $like . $order;


        $contagem = $this->countUsers("SELECT COUNT(id) as users_count FROM users " . $like, $pesquisa);

        $stmt = $this->conexao->prepare($sql);
        if ($like != '') {
            $pesquisa = "%$pesquisa%";
            $stmt->bindParam(1, $pesquisa);
            $stmt->bindParam(2, $pesquisa);
            $stmt->bindParam(3, $limite, PDO::PARAM_INT);
            $stmt->bindParam(4, $offset, PDO::PARAM_INT);
        } else {

            $stmt->bindParam(1, $limite, PDO::PARAM_INT);
            $stmt->bindParam(2, $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        $users = $stmt->fetchAll();
        return ["data" => $users, "users_count" => $contagem['users_count'] ?? 0];
    }

    /**
     * Calcula o número total de páginas necessárias para paginar um conjunto de dados.
     *
     * @param int $total O número total de itens.
     * @param int $limit O número de itens por página.
     * @return float O número total de páginas.
     */

    public function montaPaginate(int $total, int $limit): float
    {
        $paginas = ceil($total / $limit);
        return $paginas;
    }

    /**
     * Conta os usuários no banco de dados com base em uma query SQL e um termo de pesquisa opcional.
     *
     * @param string $sql A query SQL usada para contar os usuários. Pode incluir placeholders para pesquisa.
     * @param string $pesquisa O termo de pesquisa para filtrar os usuários. Se vazio, a pesquisa não será aplicada.
     * 
     * @return array|bool Retorna um array associativo com o resultado da contagem ou `false` em caso de falha.
     * 
     * Funcionamento:
     *  - Prepara uma query SQL personalizada para contar os usuários.
     *  - Se houver um termo de pesquisa, ajusta o termo para ser usado com "LIKE" na query.
     *  - Executa a query e retorna o resultado da contagem.
     */
    private function countUsers(string $sql, string $pesquisa): array|bool
    {
        $stmt = $this->conexao->prepare($sql);
        if ($pesquisa != '') {
            $pesquisa = "%$pesquisa%";
            $stmt->bindParam(1, $pesquisa);
            $stmt->bindParam(2, $pesquisa);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um usuário pelo ID no banco de dados.
     *
     * @param int $id O ID do usuário a ser buscado.
     * 
     * @return User Retorna um objeto `User` com os dados do usuário.
     * 
     * @throws Exception Lança uma exceção se o usuário não for encontrado.
     *
     * Funcionamento:
     *  - Prepara e executa uma query de seleção para buscar o usuário com o ID fornecido.
     *  - Se o usuário não for encontrado, uma exceção será lançada.
     *  - Caso o usuário seja encontrado, cria e preenche uma instância da classe `User` com os dados retornados.
     */
    public function findById(int $id): User
    {
        $stmt = $this->conexao->prepare("SELECT * FROM users WHERE  id = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $object = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$object) {
            throw new Exception('Usuário não encontrado!', 400);
            exit();
        }
        $user =  (new User())
            ->setId($object['id'])
            ->setNome($object['nome'])
            ->setEmail($object['email'])
            ->setDataNascimento($object['data_nascimento'])
            ->setFoto($object['foto']);

        return $user;
    }

    /**
     * Cria um novo usuário no banco de dados.
     *
     * @param array $dados Dados do usuário, incluindo nome, e-mail, data de nascimento e foto.
     * 
     * @return bool Retorna `true` se o usuário foi criado com sucesso, ou `false` em caso de falha.
     *
     * @throws ExceptionUserEmail Lança uma exceção personalizada se o e-mail já estiver cadastrado.
     *
     * Funcionamento:
     *  - Cria uma nova instância de `User` e define os atributos (nome, e-mail, data de nascimento).
     *  - Verifica se uma foto foi enviada e, em caso positivo, faz o upload e armazena o caminho no banco de dados.
     *  - Verifica se o e-mail já está cadastrado. Se o e-mail já existir, lança uma exceção `ExceptionUserEmail`.
     *  - Insere os dados do novo usuário no banco de dados.
     */
    public function create(array $dados): bool
    {
        $user = new User();
        $user->setEmail($dados['email']);
        $user->setNome($dados['nome']);
        $user->setDataNascimento($dados['data_nascimento']);
        $user->setFoto('');
        if ($dados['foto']['name'] != "") {
            $obUpload = (new UploadFoto())
                ->adicionaFoto($dados['foto']);
            $user->setFoto($obUpload);
        }
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


    /**
     * Verifica se o e-mail já está cadastrado no banco de dados.
     *
     * @param string $email O e-mail que será verificado.
     *
     * @return bool Retorna `false` se o e-mail não estiver cadastrado.
     *
     * @throws ExceptionUserEmail Lança uma exceção personalizada se o e-mail já estiver cadastrado.
     *
     * Funcionamento:
     *  - Prepara uma query SQL para verificar a existência de um e-mail na tabela `users`.
     *  - A query busca o `id`, `nome` e `email` dos usuários cujo e-mail corresponde ao fornecido.
     *  - Se o e-mail não for encontrado, retorna `false`, indicando que o e-mail está disponível.
     *  - Caso o e-mail já exista no banco de dados, lança uma exceção `ExceptionUserEmail` com uma mensagem informando que o e-mail já está cadastrado.
     */
    private function verificaEmailExistente(string $email): bool|Throwable
    {
        $stmt = $this->conexao->prepare("SELECT id,nome,email FROM users where email=?");
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            return false;
        }
        throw new ExceptionUserEmail('O email já está cadastrado!');
    }


    /**
     * Atualiza os dados de um usuário existente no banco de dados.
     *
     * @param int $id O ID do usuário a ser atualizado.
     * @param array $dados Um array contendo os dados atualizados do usuário (nome, email, data de nascimento, e a foto).
     *
     * @return User|Throwable Retorna o objeto atualizado do usuário ou lança uma exceção em caso de erro.
     *
     * Funcionamento:
     *  - A função busca o usuário pelo ID fornecido.
     *  - Armazena o email e a foto antigos do usuário para verificar e processar mudanças.
     *  - Atualiza os campos de nome, email e data de nascimento do usuário com base nos dados fornecidos.
     *  - Se uma nova foto for enviada, ela é processada e salva no sistema. Caso uma foto antiga exista, ela será removida.
     *  - Caso o email tenha sido alterado, verifica se o novo email já existe no sistema para evitar duplicações.
     *  - Atualiza o registro do usuário no banco de dados com os novos valores.
     *  - Retorna o objeto do usuário atualizado.
     *
     * Exceções:
     *  - Lança uma exceção se não for possível salvar a nova foto ou remover a antiga.
     *  - Lança uma exceção se o email já estiver em uso por outro usuário.
     *  - Lança uma exceção se a atualização no banco de dados falhar.
     */
    public function update(int $id, array $dados): User|Throwable
    {
        $user = $this->findById($id);
        $emailAntigo = $user->getEmail();
        $fotoAntiga = $user->getFoto();
        $user->setEmail($dados['email']);
        $user->setNome($dados['nome']);
        $user->setDataNascimento($dados['data_nascimento']);
        if ($dados['foto']['name'] != "") {
            $obUpload = new UploadFoto();
            $path = (new UploadFoto())
                ->adicionaFoto($dados['foto']);
            $user->setFoto($path);
            if ($fotoAntiga !== '') {
                $obUpload->removeFoto($fotoAntiga);
            }
        }

        if ($emailAntigo != $user->getEmail()) {
            $this->verificaEmailExistente($user->getEmail());
        }

        $stmt = $this->conexao->prepare("UPDATE users SET nome = ?, email = ?, data_nascimento= ?, foto= ? WHERE id = ?");
        $stmt->bindValue(1, $user->getNome());
        $stmt->bindValue(2, $user->getEmail());
        $stmt->bindValue(3, $user->getDataNascimento());
        $stmt->bindValue(4, $user->getFoto());
        $stmt->bindValue(5, $user->getId());
        if (!$stmt->execute()) {
            throw new Exception('Não foi possível atualizar o usuário', 400);
        }

        return $user;
    }

    /**
     * Exclui um usuário do banco de dados e remove a foto associada, se existir.
     *
     * @param int $id O ID do usuário a ser excluído.
     *
     * @return bool Retorna true se a exclusão for bem-sucedida.
     *
     * @throws Exception Lança uma exceção se ocorrer um erro ao tentar excluir o usuário.
     *
     * Funcionamento:
     *  - A função busca o usuário pelo ID.
     *  - Verifica se o usuário possui uma foto associada, e, se sim, remove a foto do servidor.
     *  - Executa uma query `DELETE` para remover o usuário do banco de dados com base no ID.
     *  - Se a query de exclusão for executada com sucesso, retorna `true`.
     *  - Caso contrário, lança uma exceção informando que não foi possível excluir o usuário.
     *
     * Exceções:
     *  - Se ocorrer um erro ao remover a foto ou excluir o registro do banco de dados, uma exceção é lançada.
     */
    public function destroy(int $id): bool
    {
        $user = $this->findById($id);

        //Verifica se existe alguma imagem atribuída ao usuário, caso tenha, é apagado junto
        if ($user->getFoto() != '') {
            (new UploadFoto())->removeFoto($user->getFoto());
        }

        $stmt = $this->conexao->prepare('DELETE FROM users WHERE id = ?');
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return true;
        }
        throw new Exception('Não foi possível excluir este usuário');
    }
}
