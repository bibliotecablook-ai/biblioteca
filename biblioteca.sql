-- biblioteca.sql completo e autocontido
-- Cria o banco e todas as tabelas com FKs nomeadas e ON DELETE CASCADE onde apropriado

CREATE DATABASE IF NOT EXISTS `biblioteca_blook`
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
USE `biblioteca_blook`;

-- (Opcional) remover tabelas antigas para evitar conflitos ao importar várias vezes
DROP TABLE IF EXISTS Reservas;
DROP TABLE IF EXISTS Emprestimos;
DROP TABLE IF EXISTS Desejados;
DROP TABLE IF EXISTS Lidos;
DROP TABLE IF EXISTS Livros;
DROP TABLE IF EXISTS Usuarios;
DROP TABLE IF EXISTS Generos;
DROP TABLE IF EXISTS Autores;

-- ==============================================
-- TABELA: AUTORES
-- ==============================================
CREATE TABLE Autores (
    id_autor INT PRIMARY KEY AUTO_INCREMENT,
    nome_autor VARCHAR(100) NOT NULL,
    nacionalidade VARCHAR(50),
    data_nascimento DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABELA: GÊNEROS
-- ==============================================
CREATE TABLE Generos (
    id_genero INT PRIMARY KEY AUTO_INCREMENT,
    nome_genero VARCHAR(50) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABELA: USUÁRIOS
-- ==============================================
CREATE TABLE Usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    tipo_usuario ENUM('admin', 'leitor') NOT NULL DEFAULT 'leitor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABELA: LIVROS
-- ==============================================
CREATE TABLE Livros (
    id_livro INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    id_autor INT,
    id_genero INT,
    ano_publicacao INT,
    isbn VARCHAR(20) UNIQUE,
    edicao VARCHAR(50),
    quantidade_total INT NOT NULL DEFAULT 1,
    quantidade_disponivel INT NOT NULL DEFAULT 1,
    capa VARCHAR(255),
    CONSTRAINT fk_livros_autor FOREIGN KEY (id_autor) REFERENCES Autores(id_autor) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_livros_genero FOREIGN KEY (id_genero) REFERENCES Generos(id_genero) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABELA: LIVROS LIDOS (histórico de leitura)
-- ==============================================
CREATE TABLE Lidos (
    id_lido INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_livro INT NOT NULL,
    data_leitura DATE DEFAULT (CURRENT_DATE),
    avaliacao TINYINT,
    comentario TEXT,
    CONSTRAINT fk_lidos_usuario FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_lidos_livro FOREIGN KEY (id_livro) REFERENCES Livros(id_livro) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABELA: DESEJADOS (wish-list)
-- ==============================================
CREATE TABLE Desejados (
    id_desejado INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_livro INT NOT NULL,
    data_adicao DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_desejados_usuario FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_desejados_livro FOREIGN KEY (id_livro) REFERENCES Livros(id_livro) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uk_usuario_livro (id_usuario, id_livro)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABELA: EMPRÉSTIMOS
-- ==============================================
CREATE TABLE Emprestimos (
    id_emprestimo INT PRIMARY KEY AUTO_INCREMENT,
    id_livro INT NOT NULL,
    id_usuario INT NOT NULL,
    data_emprestimo DATE NOT NULL,
    data_prevista_devolucao DATE NOT NULL,
    data_devolucao DATE,
    status ENUM('emprestado','devolvido','atrasado') NOT NULL DEFAULT 'emprestado',
    CONSTRAINT fk_emprestimos_livro FOREIGN KEY (id_livro) REFERENCES Livros(id_livro) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_emprestimos_usuario FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- TABELA: RESERVAS
-- ==============================================
CREATE TABLE Reservas (
    id_reserva INT PRIMARY KEY AUTO_INCREMENT,
    id_livro INT NOT NULL,
    id_usuario INT NOT NULL,
    data_reserva DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('ativa','cancelada','atendida') NOT NULL DEFAULT 'ativa',
    CONSTRAINT fk_reservas_livro FOREIGN KEY (id_livro) REFERENCES Livros(id_livro) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_reservas_usuario FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uk_reserva (id_livro, id_usuario, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- DADOS INICIAIS (opcionais)
-- ==============================================
INSERT IGNORE INTO Generos (nome_genero) VALUES
('Romance'),
('Ficção Científica'),
('Não-Ficção'),
('Fantasia'),
('Suspense');

INSERT IGNORE INTO Autores (nome_autor, nacionalidade, data_nascimento) VALUES
('Taylor Jenkins Reid', 'Americana', '1983-12-20'),
('Colleen Hoover', 'Americana', '1979-12-11');

INSERT IGNORE INTO Livros (titulo, id_autor, id_genero, ano_publicacao, isbn, quantidade_total, quantidade_disponivel, capa) VALUES
('Os Sete Maridos de Evelyn Hugo', 1, 1, 2017, '9788584390978', 5, 5, 'img/capas/evelyn.jpg'),
('Daisy Jones & The Six', 1, 1, 2019, '9788584391623', 3, 2, 'img/capas/daisy.jpg'),
('É Assim que Acaba', 2, 1, 2016, '9788501112520', 10, 10, 'img/capas/acaba.jpg');

-- Observação: senha deve ser armazenada como hash; aqui deixamos um placeholder.
-- Recomendo gerar o hash em PHP com password_hash('SENHA', PASSWORD_DEFAULT) e então inserir o hash resultante.
INSERT IGNORE INTO Usuarios (id_usuario, nome, email, senha, tipo_usuario) VALUES
(1, 'Usuário Padrão', 'usuario@email.com', 'usersenha', 'leitor'),
(2, 'Admin Blook', 'admin@blook.com', 'adminsenha', 'admin');

-- Exemplo de empréstimo inicial (usuário 1 e livro 2 devem existir)
INSERT IGNORE INTO Emprestimos (id_livro, id_usuario, data_emprestimo, data_prevista_devolucao) VALUES
(2, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY));

-- Exemplo de lidos/desejados (opcionais)
INSERT IGNORE INTO Lidos (id_usuario, id_livro, data_leitura, avaliacao, comentario) VALUES
(1, 1, CURDATE(), 5, 'Ótimo livro');

INSERT IGNORE INTO Desejados (id_usuario, id_livro) VALUES
(1, 3);