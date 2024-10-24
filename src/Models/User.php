<?php

declare(strict_types=1);

namespace Marcus\Zenitech\Models;

use DateTime;
use Marcus\Zenitech\Exceptions\ExceptionUserDataNascimento;
use Marcus\Zenitech\Exceptions\ExceptionUserEmail;
use Marcus\Zenitech\Exceptions\ExceptionUserName;

class User
{
    private ?int $id;
    private string $nome;
    private string $email;
    private DateTime $data_nascimento;
    private ?string $foto;



    /**
     * Get the value of nome
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Define o valor do nome após validações e sanitização.
     *
     * @param string $nome O nome a ser definido.
     * @throws ExceptionUserName Se o nome for inválido ou não atender aos requisitos.
     * @return self A instância atual do objeto.
     */
    public function setNome($nome)
    {
        // LIMPA OS CARACTERES QUE FOREM ESPECIAIS E RETIRA ESPAÇOS VAZIOS DA VARIAVEL
        $newNome = trim(filter_var($nome, FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        if (strlen($newNome) == 0 || is_null($newNome)) {

            throw new ExceptionUserName("O campo nome é obrigatório", 400);
        }
        if (strlen($newNome) < 3) {

            throw new ExceptionUserName("o nome precisa ter pelo menos 3 caracteres!", 400);
        }
        if (strlen($newNome) > 255) {

            throw new ExceptionUserName("Nome só pode ter no maximo 255 caracteres!", 400);
        }
        $this->nome = $newNome;
        return $this;
    }

    /**
     * Get the value of nome
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Define o valor do email após validações e sanitização.
     *
     * @param string $email O email a ser definido.
     * @throws ExceptionUserEmail Se o email for inválido ou não atender aos requisitos.
     * @return self A instância atual do objeto.
     */
    public function setEmail($email)
    {
        $newEmail = trim(filter_var($email, FILTER_SANITIZE_EMAIL));
        $newEmail = filter_var($newEmail, FILTER_VALIDATE_EMAIL);


        if (!$newEmail) {
            throw new ExceptionUserEmail("E-mail inválido", 400);
        }
        if (is_null($newEmail) || strlen($newEmail) == 0) {
            throw new ExceptionUserEmail("O campo e-mail é obrigatório", 400);
        }

        $this->email = $newEmail;

        return $this;
    }

    /**
     * Get the value of data_nascimento
     */
    public function getDataNascimento()
    {
        return date_format($this->data_nascimento, 'Y-m-d');
    }

    /**
     * Define o valor da data de nascimento após validações e sanitização.
     *
     * @param string $data_nascimento A data de nascimento a ser definida.
     * @throws ExceptionUserDataNascimento Se a data de nascimento for inválida ou não atender aos requisitos.
     * @return self A instância atual do objeto.
     */
    public function setDataNascimento($data_nascimento)
    {
        $newDataNascimento = filter_var($data_nascimento, FILTER_DEFAULT);
        if (is_null($newDataNascimento) || strlen($newDataNascimento) == 0) {
            throw new ExceptionUserDataNascimento("O campo data de nascimento é obrigatório", 400);
        }

        if (
            date_diff(new DateTime($newDataNascimento), new DateTime('now'))->y < 18
        ) {

            throw new ExceptionUserDataNascimento("O Usuário deve ter no mínimo 18 anos", 400);
        }
        // Caso ocorra uma exceção o proprio datetime mostra.
        $newDataNascimento = new DateTime($newDataNascimento);
        $this->data_nascimento = $newDataNascimento;

        return $this;
    }

    /**
     * Get the value of foto
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set the value of foto
     *
     * @return  self
     */
    public function setFoto($foto)
    {
        $this->foto = trim(filter_var($foto, FILTER_DEFAULT));
        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
