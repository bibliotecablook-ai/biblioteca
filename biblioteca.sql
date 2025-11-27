-- Configurações iniciais de modo SQL e início da transação
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Configurações de charset (comandos especiais do MySQL)
 /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
 /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
 /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 /*!40101 SET NAMES utf8mb4 */;

-- Criação do banco, caso não exista
CREATE DATABASE IF NOT EXISTS `biblioteca_blook` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `biblioteca_blook`;

-- Tabela de autores
DROP TABLE IF EXISTS `autores`;
CREATE TABLE `autores` (
  `id_autor` int(11) NOT NULL,
  `nome_autor` varchar(100) NOT NULL,
  `nacionalidade` varchar(50) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados padrão dos autores cadastrados
INSERT INTO `autores` (`id_autor`, `nome_autor`, `nacionalidade`, `data_nascimento`) VALUES
(1, 'Taylor Jenkins Reid', 'Americana', '1983-12-20'),
(2, 'Colleen Hoover', 'Americana', '1979-12-11'),
(3, 'Pierre Boulle', NULL, NULL),
(4, 'Cressida Cowell', NULL, NULL),
(5, 'E. Lockhart', NULL, NULL),
(6, 'J.K. Rowling', NULL, NULL),
(7, 'William Joyce', NULL, NULL);

-- Tabela de livros desejados pelos usuários
DROP TABLE IF EXISTS `desejados`;
CREATE TABLE `desejados` (
  `id_desejado` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `data_adicao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela que armazena empréstimos realizados
DROP TABLE IF EXISTS `emprestimos`;
CREATE TABLE `emprestimos` (
  `id_emprestimo` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_emprestimo` date NOT NULL,
  `data_prevista_devolucao` date NOT NULL,
  `data_devolucao` date DEFAULT NULL,
  `status` enum('emprestado','devolvido','atrasado') NOT NULL DEFAULT 'emprestado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Empréstimos iniciais do sistema
INSERT INTO `emprestimos` (`id_emprestimo`, `id_livro`, `id_usuario`, `data_emprestimo`, `data_prevista_devolucao`, `data_devolucao`, `status`) VALUES
(1, 2, 1, '2025-11-14', '2025-11-29', NULL, 'emprestado');

-- Tabela dos gêneros literários
DROP TABLE IF EXISTS `generos`;
CREATE TABLE `generos` (
  `id_genero` int(11) NOT NULL,
  `nome_genero` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Gêneros padrão cadastrados
INSERT INTO `generos` (`id_genero`, `nome_genero`) VALUES
(4, 'Fantasia'),
(6, 'Ficção'),
(2, 'Ficção Científica'),
(7, 'Infantil'),
(3, 'Não-Ficção'),
(1, 'Romance'),
(5, 'Suspense');

-- Tabela de livros lidos pelos usuários
DROP TABLE IF EXISTS `lidos`;
CREATE TABLE `lidos` (
  `id_lido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `data_leitura` date DEFAULT curdate(),
  `avaliacao` tinyint(4) DEFAULT NULL,
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela principal dos livros cadastrados
DROP TABLE IF EXISTS `livros`;
CREATE TABLE `livros` (
  `id_livro` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `id_autor` int(11) DEFAULT NULL,
  `id_genero` int(11) DEFAULT NULL,
  `ano_publicacao` int(11) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `edicao` varchar(50) DEFAULT NULL,
  `quantidade_total` int(11) NOT NULL DEFAULT 1,
  `quantidade_disponivel` int(11) NOT NULL DEFAULT 1,
  `capa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Livros cadastrados inicialmente
INSERT INTO `livros` (`id_livro`, `titulo`, `id_autor`, `id_genero`, `ano_publicacao`, `isbn`, `edicao`, `quantidade_total`, `quantidade_disponivel`, `capa`) VALUES
(1, 'Os Sete Maridos de Evelyn Hugo', 1, 1, 2017, '9788584390978', '', 5, 5, '7maridos.jpg'),
(2, 'Daisy Jones & The Six', 1, 1, 2019, '9788584391623', '', 3, 2, 'daisyjones.jpg'),
(3, 'É Assim que Acaba', 2, 1, 2016, '9788501112520', '0', 10, 10, 'capa_69279d6454431.jpg'),
(4, 'Planeta dos Macacos', 3, 2, 1963, '9788576572138', '0', 36, 9, 'planetadosmacacos.jpg'),
(5, 'Mentirosos', 5, 6, 2014, '9788565765480', '0', 13, 5, 'mentirosos.jpg'),
(6, 'Como Treinar o Seu Dragão', 4, 4, 2003, '9788598078717', '', 10, 2, 'Como Treinar o Seu Dragão.jpg'),
(7, 'Harry Potter e a Pedra Filosofal', 6, 4, 1997, '9788532511010', '', 26, 6, 'capa_6923582f233a5.jpg'),
(8, 'O Homem da Lua', 7, 7, 2012, '9788562500428', '', 15, 11, 'capa_6923585bd75bb.jpg'),
(9, 'Nicolau São Norte e a Batalha Contra o Rei dos Pesadelos', 7, 7, 2012, '9788581222912', '', 13, 6, 'Nicolau São Norte e a Batalha Contra o Rei dos Pesadelos.jpg');


-- Tabela de reservas de livros
DROP TABLE IF EXISTS `reservas`;
CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_reserva` datetime DEFAULT current_timestamp(),
  `status` enum('ativa','cancelada','atendida') NOT NULL DEFAULT 'ativa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de usuários cadastrados no sistema
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `tipo_usuario` enum('admin','leitor') NOT NULL DEFAULT 'leitor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Usuários iniciais cadastrados
INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `senha`, `telefone`, `data_cadastro`, `tipo_usuario`) VALUES
(1, 'Usuário Padrão', 'usuario@email.com', 'hash_da_senha_do_usuario', NULL, '2025-11-14 15:18:16', 'leitor'),
(10, 'Leo', 'Leo.Teixeira123@gmail.com', '$2y$10$5ZGDKg.Hl3M5clffS4/5HelNq4zgIfSMOfGOLtqXzX6x7XjbXUvPq', NULL, '2025-11-23 14:05:16', 'admin'),
(11, 'Laura', 'laura@gmail.com', '$2y$10$1hR2XVWUyuVS6jLjF/45GOMQ9x5KeQPL8HrDCj6Wv/xGP.KCLNec.', NULL, '2025-11-23 14:06:32', 'admin'),
(12, 'Murilo', 'Murilo.goncalves@gmail.com', '$2y$10$jC5SQmrj3CU7PuyquCKk7.PzxdFwXRMPWFdOSqGz38.VJb8WsjFPi', NULL, '2025-11-23 14:07:06', 'leitor'),
(13, 'Marcos', 'marcos.G@gmail.com', '$2y$10$t.7qsDKLnZmVHBAJWHoQH./..6DrXpq/osaEFgRV0vWW21NcBL0zG', NULL, '2025-11-23 14:08:59', 'leitor'),
(14, 'Ramon', 'ramon.silva1234@gmail.com', '$2y$10$tOnbLw44qR4UyeE5PgPKdu9khiLRmQPGchnRRnhgij/iq2jtGF6YS', NULL, '2025-11-23 14:09:29', 'leitor'),
(15, 'Admin Blook', 'admin@blook.com', '$2y$10$PxtS2/cEesH2EdpovmnNyeujvgBd.iIyKxqUsHER3qMsqyYWxkasG', NULL, '2025-11-23 14:22:17', 'admin');

-- Configuração de chaves primárias e índices
ALTER TABLE `autores`
  ADD PRIMARY KEY (`id_autor`);

ALTER TABLE `desejados`
  ADD PRIMARY KEY (`id_desejado`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`,`id_livro`),
  ADD KEY `id_livro` (`id_livro`);

ALTER TABLE `emprestimos`
  ADD PRIMARY KEY (`id_emprestimo`),
  ADD KEY `id_livro` (`id_livro`),
  ADD KEY `id_usuario` (`id_usuario`);

ALTER TABLE `generos`
  ADD PRIMARY KEY (`id_genero`),
  ADD UNIQUE KEY `nome_genero` (`nome_genero`);

ALTER TABLE `lidos`
  ADD PRIMARY KEY (`id_lido`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_livro` (`id_livro`);

ALTER TABLE `livros`
  ADD PRIMARY KEY (`id_livro`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `id_autor` (`id_autor`),
  ADD KEY `id_genero` (`id_genero`);

ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD UNIQUE KEY `id_livro` (`id_livro`,`id_usuario`,`status`),
  ADD KEY `id_usuario` (`id_usuario`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

-- Torna as colunas auto_increment exatamente como estavam
ALTER TABLE `autores`
  MODIFY `id_autor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `desejados`
  MODIFY `id_desejado` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `emprestimos`
  MODIFY `id_emprestimo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `generos`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `lidos`
  MODIFY `id_lido` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `livros`
  MODIFY `id_livro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

-- Configuração das relações entre tabelas (chaves estrangeiras)
ALTER TABLE `desejados`
  ADD CONSTRAINT `desejados_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `desejados_ibfk_2` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id_livro`);

ALTER TABLE `emprestimos`
  ADD CONSTRAINT `emprestimos_ibfk_1` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id_livro`),
  ADD CONSTRAINT `emprestimos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

ALTER TABLE `lidos`
  ADD CONSTRAINT `lidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `lidos_ibfk_2` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id_livro`);

ALTER TABLE `livros`
  ADD CONSTRAINT `livros_ibfk_1` FOREIGN KEY (`id_autor`) REFERENCES `autores` (`id_autor`),
  ADD CONSTRAINT `livros_ibfk_2` FOREIGN KEY (`id_genero`) REFERENCES `generos` (`id_genero`);

ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id_livro`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

-- Finaliza a transação
COMMIT;

-- Restaura charsets antigos
 /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
 /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
 /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;