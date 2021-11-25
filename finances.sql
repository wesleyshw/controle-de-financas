CREATE TABLE usuario (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(220) NOT  NULL,
    email VARCHAR(220) NOT NULL UNIQUE,
    senha VARCHAR(120) NOT NULL
);

CREATE TABLE dividas (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_user INT(11) NOT NULL,
    nome VARCHAR(220) NOT NULL,
    due_data VARCHAR(20) NOT NULL,
    valor VARCHAR(13) NOT NULL,
    situacao VARCHAR(10) NOT NULL,
    descricao TEXT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES usuario(id) ON DELETE CASCADE
);