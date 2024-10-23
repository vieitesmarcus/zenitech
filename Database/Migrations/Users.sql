DROP TABLE IF EXISTS zenitechdb.users;


CREATE TABLE zenitechdb.users (
    id INT AUTO_INCREMENT,
    nome varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL,
    foto varchar(255) NULL,
    criado_em timestamp DEFAULT CURRENT_TIMESTAMP,
    atualizado_em timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (id)
);

INSERT INTO zenitechdb.users (nome, email, data_nascimento, foto) VALUES
('João Silva', 'joao@example.com', '1990-05-15', 'foto_joao.jpg'),
('Maria Oliveira', 'maria@example.com', '1985-10-25', 'foto_maria.jpg'),
('Carlos Santos', 'carlos@example.com', '2000-01-30', 'foto_carlos.jpg'),
('Ana Costa', 'ana.costa@example.com', '1992-08-12', 'foto_ana.jpg'),
('Pedro Almeida', 'pedro.almeida@example.com', '1988-11-20', 'foto_pedro.jpg'),
('Lucas Ferreira', 'lucas.ferreira@example.com', '1995-03-05', 'foto_lucas.jpg'),
('Juliana Martins', 'juliana.martins@example.com', '1993-06-30', 'foto_juliana.jpg'),
('Roberto Lima', 'roberto.lima@example.com', '1980-01-15', 'foto_roberto.jpg'),
('Fernanda Souza', 'fernanda.souza@example.com', '1991-12-22', 'foto_fernanda.jpg'),
('Ricardo Pereira', 'ricardo.pereira@example.com', '1989-07-18', 'foto_ricardo.jpg'),
('Cláudia Rocha', 'claudia.rocha@example.com', '1994-04-03', 'foto_claudia.jpg'),
('Fernando Oliveira', 'fernando.oliveira@example.com', '1987-09-27', 'foto_fernando.jpg'),
('Tatiane Nascimento', 'tatiane.nascimento@example.com', '1996-02-17', 'foto_tatiane.jpg'),
('Diego Andrade', 'diego.andrade@example.com', '1994-10-10', 'foto_diego.jpg'),
('Mariana Ramos', 'mariana.ramos@example.com', '1990-05-30', 'foto_mariana.jpg'),
('Gabriel Silva', 'gabriel.silva@example.com', '1985-11-14', 'foto_gabriel.jpg'),
('Patrícia Gomes', 'patricia.gomes@example.com', '1993-08-19', 'foto_patricia.jpg'),
('Adriano Cruz', 'adriano.cruz@example.com', '1988-07-21', 'foto_adriano.jpg'),
('Tatiane Alves', 'tatiane.alves@example.com', '1991-03-12', 'foto_tatiane_alves.jpg'),
('Carlos Eduardo', 'carlos.eduardo@example.com', '1992-06-01', 'foto_carlos_eduardo.jpg'),
('Priscila Mendes', 'priscila.mendes@example.com', '1995-09-15', 'foto_priscila.jpg'),
('Rafael Martins', 'rafael.martins@example.com', '1989-12-31', 'foto_rafael.jpg'),
('Vinícius Lima', 'vinicius.lima@example.com', '1984-02-05', 'foto_vinicius.jpg'),
('Simone Ferreira', 'simone.ferreira@example.com', '1990-04-25', 'foto_simone.jpg'),
('Sérgio Rocha', 'sergio.rocha@example.com', '1997-11-11', 'foto_sergio.jpg'),
('Daniele Costa', 'daniele.costa@example.com', '1986-05-16', 'foto_daniele.jpg'),
('Leonardo Dias', 'leonardo.dias@example.com', '1993-12-18', 'foto_leonardo.jpg'),
('Karla Santos', 'karla.santos@example.com', '1995-03-27', 'foto_karla.jpg'),
('Jéssica Ribeiro', 'jessica.ribeiro@example.com', '1992-10-10', 'foto_jessica.jpg'),
('Eduardo Pinto', 'eduardo.pinto@example.com', '1991-01-08', 'foto_eduardo.jpg'),
('Aline Oliveira', 'aline.oliveira@example.com', '1994-07-22', 'foto_aline.jpg'),
('Bruno Martins', 'bruno.martins@example.com', '1990-11-30', 'foto_bruno.jpg');

UPDATE zenitechdb.users
SET nome = 'João Pedro Silva'
WHERE email = 'joao@example.com';

UPDATE zenitechdb.users
SET nome = 'Maria Clara Oliveira'
WHERE email = 'maria@example.com';