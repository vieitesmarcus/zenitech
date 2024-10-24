<?php

use Marcus\Zenitech\Providers\Enviroment;

require __DIR__ . '/vendor/autoload.php';

//INSERE AS VARIAVEIS DE AMBIENTE DO BANCO
Enviroment::addEnv();


//Faz a conexão com o Banco
$conexao = new PDO(
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
$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$inicio = true;
while ($inicio) {
    limparTerminal();
    if (file_exists('./banner.txt')) {
        echo file_get_contents('banner.txt');
    }
    echo <<<Banco
    +----------------------------------------------------+
    |------------------- Banco de Dados -----------------|
    |----------------------------------------------------|
    |-------------- Criar tabela:           1 -----------|
    |-------------- Excluir tabela:         2 -----------|
    |-------------- Inserir Seed:           3 -----------|
    |-------------- Sair:                   0 -----------|
    +----------------------------------------------------+
    |---------------- Escolha a opção -------------------|
    |---------------- Aperte enter ----------------------|\n
    Banco;
    // Obtém a entrada do usuário
    $opcao = readline();

    $opcao = trim($opcao);
    // Remove qualquer espaço em branco extra do início e do final da entrada
    if ($opcao === 'exit') {
        break;
    }
    system('clear');
    switch ($opcao) {
        case 1:
            criaBancoDeDados($conexao);
            break;
        case 2:
            dropAllTable($conexao);
            aguardarTeclaEnter();
            break;
        case 3:
            seeds($conexao);
            break;
        case 0:
            echo "Saindo do script.\n";
            $inicio = false;
            break;
    }
}

function criaBancoDeDados($conexao): void
{
    limparTerminal();
    try {


        echo <<<Banco
    +----------------------------------------------------+
    |------------ Gerador de Banco de Dados -------------|
    |----------------------------------------------------|\n
    Banco;

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'zenitechdb' AND table_name = 'users'";
        $stmt = $conexao->prepare($sql);
        $resultado = $stmt->execute();
        $tabelas = $stmt->rowCount();
        
        if ($tabelas ==0) {
            echo "|--------------- tabela inexistente ------------------|\n";
            echo "|----------------------------------------------------|\n";
            //|----------------------------------------------------|
            echo "|--- Deseja criar a tabela ? S ou N?  ---|\n";
            echo "|----------------------------------------------------|\n";
            $o = fgets(STDIN);
            $o = trim($o);
            if ($o == 's' || $o == 'S' || $o == "Sim" || $o == 'sim') {
                $usersCreateTable = file_get_contents(__DIR__ . "/Database/Migrations/UsersCreateTable.sql");

                $stmt = $conexao->prepare($usersCreateTable);
                $stmt->execute();
                echo <<<Banco
                    +----------------------------------------------------+
                    |---- Tabela gerada com sucesso ---|
                    |----------------------------------------------------|\n
                    Banco;
                aguardarTeclaEnter();
                return;
            }
            return;
        }
        echo <<<Banco
            |----- Tabela existente, escolha opção 3 e 4 ---------|
            |-------------- Para inserir usuários ----------------|
            |------------------- Obrigado ------------------------|
            +-----------------------------------------------------+\n
            Banco;
        aguardarTeclaEnter();
    } catch (Exception $e) {
        echo "Erro criar Database: " . $e->getMessage() . "\n";
        aguardarTeclaEnter();
        return;
    }
}

function dropAllTable($conexao)
{
    limparTerminal();
    try {
        $usersDropTable = file_get_contents(__DIR__ . "/Database/Migrations/UsersDropTable.sql");
        $stmt = $conexao->prepare("SELECT * FROM users LIMIT 1");
        $resultado = $stmt->execute();
        if ($resultado) {
            $stmt = $conexao->prepare($usersDropTable);
            $stmt->execute();
            echo <<<Banco
            |----------- Apagando tabela totalmente -------------|
            |----------- Escolha opção 1 para criar -------------|
            |------------------- novamente ----------------------|
            +----------------------------------------------------+\n
            Banco;
            echo <<<Banco
            |----------------------------------------------------|
            |##########                                          |
            |####################                                |
            |###################################                 |
            |#################################################   |
            |-------------- Apagado com sucesso! ----------------|
            +----------------------------------------------------+\n
            Banco;
            return;
        }
        echo <<<Banco
            |----------------------------------------------------|
            |---------------- Tabela inexistente ----------------|
            +----------------------------------------------------+\n
            Banco;
        return;
    } catch (Exception $e) {
        echo "Erro excluir Database: " . $e->getMessage() . "\n";
    }
}

function limparTerminal()
{
    // Verifica se o terminal suporta sequências de controle ANSI
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        echo "\033c"; // Sequência de controle ANSI para limpar o terminal
    } else {
        system('clear');
    }
}

function aguardarTeclaEnter()
{
    echo "Pressione Enter para continuar...\n";
    fgets(STDIN);
}

function tableExists($tableName, $conexao)
{
    try {
        

        // Consulta para verificar se a tabela existe
        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'zenitechdb' AND table_name = 'users'";
        $stmt = $conexao->prepare($sql);
        $resultado = $stmt->execute();
        $tabelas = $stmt->rowCount();
        if($tabelas > 0){
            return true;
        }
        return false;
    } catch (Exception $e) {
        echo "Erro ao verificar a existência da tabela: " . $e->getMessage() . "\n";
        return false;
    }
}

//Aqui poderia ser colocado mais de uma tabela, podendo fazer a seed em mais de uma
function seeds($conexao)
{
    if (tableExists('users', $conexao)) {
        echo <<<Banco
            +----------------------------------------------------+
            |----------- Inserindo dados em users... ------------|
            +----------------------------------------------------+\n
            Banco;
        InserirSeeds('users', $conexao);
    } else {
        echo <<<Banco
            +----------------------------------------------------+
            |------------ Tabela users inexistente! -----------|
            +----------------------------------------------------+\n
            Banco;
    }
    aguardarTeclaEnter();
    return;
}
function InserirSeeds($tableName, $conexao)
{
    try {
        $usersSeeds = file_get_contents(__DIR__."/DataBase/Seeds/UsersSeeds.sql");
        if ($tableName == "users") {
            $stmt = $conexao->prepare($usersSeeds);
            $resultado = $stmt->execute();

            if (!$resultado) {
                throw new Exception('Não foi possível inserir usuarios');
            }
        }
        
        return $resultado;
    } catch (Exception $e) {
        echo "+----------------------------------------------------+\n";
        echo "            " . $e->getMessage() . PHP_EOL;
        echo "+----------------------------------------------------+\n";
        return false;
    }
}
