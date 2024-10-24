## Teste técnico Zenitech

***O candidato deverá desenvolver uma aplicação web simples utilizando PHP e MySQL. O projeto será um sistema de gerenciamento de usuários com funcionalidades básicas de CRUD (Create, Read, Update, Delete) e integração com o banco de dados.***

## 🛠 Tecnologias

As seguintes ferramentas foram usadas na construção do projeto:

[PHPDocker.io](https://phpdocker.io/) 
Site que traz de forma mais simples as tecnologias para ser usadas, nela foi escolhida a versão do php assim como o mysql e o ngix.
PHP 8.3;
Mysql;
Nginx;


###  ✔Como rodar a aplicação

é necessário ter Docker desktop instalado caso esteja usando Windows

```
# Clone este repositório
git clone https://github.com/vieitesmarcus/zenitech.git

# Acesse a pasta do projeto no terminal/cmd
cd zenitech

#copie o .env.example para .env e pode deixar assim mesmo
```

```

# Agora insira o comando abaixo:

docker-compose up -d

docker-compose exec php-fpm bash 
composer i
exit
```

```
# agora escreva no cmd 
docker-compose exec php-fpm chown -R www-data:www-data /application/public

```


```
# faça as migrations

docker-compose exec php-fpm bash
php CreateDataBase.php

#para sair do bash só escrever exit

# escolha a opção 1
# aperte s
# aperte enter para entrar no menu novamente
# aperte 3
# isso vai gerar os dados dos usuários para testar
# todos sql encontram-se na pasta Database

# isso vai ajudar a criar a tabela e rodar a seed
# poder ser usado para apagar também

# caso queiram alimentar com mais dados para testar
# pode retirar o campo Unique do sql que está na pasta 
# Database/UsersCreateTable.sql
# isso pode ajudar a testar para ver como se comporta a paginação 
# com mais dados e fazer o teste de pesquisa
# fora isso manter o campo unique para não ocorrer exceções
```


```
# agora é só rodar no navegador localhost/users

#para parar o container 
docker-compose down
```

