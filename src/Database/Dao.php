<?php

namespace Marcus\Zenitech\Database;

use Error;
use Marcus\Zenitech\Providers\Enviroment;
use PDO;
use PDOException;

/**
 * Classe de acesso ao banco de dados
 */
abstract class Dao
{
    /**
     * atributo responsável por fazer a conexão
     *
     * @var PDO
     */
    protected PDO $conexao;

    public function __construct()
    {
        try {
            Enviroment::addEnv();

            //Cria a conexão
            $this->conexao = new PDO(
                "mysql:host=" . getenv('MYSQL_ROOT_HOST') . ";dbname=" . getenv('DB_DATABASE') . ';port=3306',
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD'),
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_CASE => PDO::CASE_NATURAL
                ]
            );
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //retorna o PDO
            return $this->conexao;
        } catch (PDOException $error) {

            echo "ERRO => " . $error->getMessage();
            http_response_code(404);
            return false;
        } catch (Error $error) {
            http_response_code(404);
            echo $error->getMessage();
            return false;
        }
    }
}
