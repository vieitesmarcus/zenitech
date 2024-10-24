CREATE TABLE users (
    id INT AUTO_INCREMENT,
    nome varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL,
    foto varchar(255) NULL,
    criado_em timestamp DEFAULT CURRENT_TIMESTAMP,
    atualizado_em timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (id)
);