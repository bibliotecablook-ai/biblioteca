-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14-Nov-2025 às 20:18
-- Versão do servidor: 10.4.22-MariaDB
-- versão do PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `biblioteca_blook`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `autores`
--

CREATE TABLE `autores` (
  `id_autor` int(11) NOT NULL,
  `nome_autor` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nacionalidade` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `autores`
--

INSERT INTO `autores` (`id_autor`, `nome_autor`, `nacionalidade`, `data_nascimento`) VALUES
(1, 'Taylor Jenkins Reid', 'Americana', '1983-12-20'),
(2, 'Colleen Hoover', 'Americana', '1979-12-11'),
(3, 'Pierre Boulle', NULL, NULL),
(4, 'Cressida Cowell', NULL, NULL),
(5, 'E. Lockhart', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `desejados`
--

CREATE TABLE `desejados` (
  `id_desejado` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `data_adicao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `emprestimos`
--

CREATE TABLE `emprestimos` (
  `id_emprestimo` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_emprestimo` date NOT NULL,
  `data_prevista_devolucao` date NOT NULL,
  `data_devolucao` date DEFAULT NULL,
  `status` enum('emprestado','devolvido','atrasado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'emprestado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `emprestimos`
--

INSERT INTO `emprestimos` (`id_emprestimo`, `id_livro`, `id_usuario`, `data_emprestimo`, `data_prevista_devolucao`, `data_devolucao`, `status`) VALUES
(1, 2, 1, '2025-11-14', '2025-11-29', NULL, 'emprestado');

-- --------------------------------------------------------

--
-- Estrutura da tabela `generos`
--

CREATE TABLE `generos` (
  `id_genero` int(11) NOT NULL,
  `nome_genero` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `generos`
--

INSERT INTO `generos` (`id_genero`, `nome_genero`) VALUES
(4, 'Fantasia'),
(6, 'Ficção'),
(2, 'Ficção Científica'),
(3, 'Não-Ficção'),
(1, 'Romance'),
(5, 'Suspense');

-- --------------------------------------------------------

--
-- Estrutura da tabela `lidos`
--

CREATE TABLE `lidos` (
  `id_lido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `data_leitura` date DEFAULT curdate(),
  `avaliacao` tinyint(4) DEFAULT NULL,
  `comentario` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `livros`
--

CREATE TABLE `livros` (
  `id_livro` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_autor` int(11) DEFAULT NULL,
  `id_genero` int(11) DEFAULT NULL,
  `ano_publicacao` int(11) DEFAULT NULL,
  `isbn` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `edicao` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantidade_total` int(11) NOT NULL DEFAULT 1,
  `quantidade_disponivel` int(11) NOT NULL DEFAULT 1,
  `capa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `livros`
--

INSERT INTO `livros` (`id_livro`, `titulo`, `id_autor`, `id_genero`, `ano_publicacao`, `isbn`, `edicao`, `quantidade_total`, `quantidade_disponivel`, `capa`) VALUES
(1, 'Os Sete Maridos de Evelyn Hugo', 1, 1, 2017, '9788584390978', NULL, 5, 5, 'img/capas/evelyn.jpg'),
(2, 'Daisy Jones & The Six', 1, 1, 2019, '9788584391623', NULL, 3, 2, 'img/capas/daisy.jpg'),
(3, 'É Assim que Acaba', 2, 1, 2016, '9788501112520', NULL, 10, 10, 'img/capas/acaba.jpg'),
(4, 'Planeta dos Macacos', 3, 2, 1963, '9788576572138', '', 36, 9, 'img/capas/capa_691775a743bea.jpg'),
(9, 'Mentirosos', 5, 6, 2014, '9788565765480', '', 13, 5, 'img/capas/capa_691777eb9bf78.jpg'),
(10, 'Como Treinar o Seu Dragão', 4, 4, 2003, '9788598078717', '', 10, 2, 'img/capas/capa_6917788806540.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_reserva` datetime DEFAULT current_timestamp(),
  `status` enum('ativa','cancelada','atendida') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ativa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `tipo_usuario` enum('admin','leitor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'leitor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `senha`, `telefone`, `data_cadastro`, `tipo_usuario`) VALUES
(1, 'Usuário Padrão', 'usuario@email.com', 'hash_da_senha_do_usuario', NULL, '2025-11-14 15:18:16', 'leitor'),
(2, 'Admin Blook', 'admin@blook.com', 'hash_da_senha_do_admin', NULL, '2025-11-14 15:18:16', 'admin'),
(3, 'Ramon', 'ramon.silva1234@gmail.com', '$2y$10$kxM2iA0rA.EySPHmH8CNFujylhs/HkyIqmCXSzGQyZ4iqXiTW9Vku', NULL, '2025-11-14 15:20:25', 'leitor'),
(4, 'Leo', 'Leo.Teixeira123@gmail.com', '$2y$10$ID0svIh/fxiEeLYwpn5Viu/pUBALnrFb0ahR71RY69dkHIs/7xY/u', NULL, '2025-11-14 15:22:47', 'leitor'),
(5, 'Murilo', 'Murilo.goncalves@gmail.com', '$2y$10$pWL9Y4Zd012t9jnZ/qOK2.9oO6iOwMRzfABsvudsKx5o1Wulbk486', NULL, '2025-11-14 15:25:57', 'leitor'),
(6, 'Marcos', 'marcos.G@gmail.com', '$2y$10$Gbq6kKuYAmL0sb0hxMrrzuK1Pz9FWN6Gq0VwDWk7KZkvVEsTq5C5i', NULL, '2025-11-14 15:27:13', 'leitor'),
(9, 'laura', 'laura@gmail.com', '$2y$10$J..qwgZWYhUyxhwhAB.vRO83fY6vdwNvU2a3P7jVD2hkTbXEmaFzS', NULL, '2025-11-14 15:50:25', 'leitor');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `autores`
--
ALTER TABLE `autores`
  ADD PRIMARY KEY (`id_autor`);

--
-- Índices para tabela `desejados`
--
ALTER TABLE `desejados`
  ADD PRIMARY KEY (`id_desejado`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`,`id_livro`),
  ADD KEY `id_livro` (`id_livro`);

--
-- Índices para tabela `emprestimos`
--
ALTER TABLE `emprestimos`
  ADD PRIMARY KEY (`id_emprestimo`),
  ADD KEY `id_livro` (`id_livro`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices para tabela `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id_genero`),
  ADD UNIQUE KEY `nome_genero` (`nome_genero`);

--
-- Índices para tabela `lidos`
--
ALTER TABLE `lidos`
  ADD PRIMARY KEY (`id_lido`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_livro` (`id_livro`);

--
-- Índices para tabela `livros`
--
ALTER TABLE `livros`
  ADD PRIMARY KEY (`id_livro`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `id_autor` (`id_autor`),
  ADD KEY `id_genero` (`id_genero`);

--
-- Índices para tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD UNIQUE KEY `id_livro` (`id_livro`,`id_usuario`,`status`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `autores`
--
ALTER TABLE `autores`
  MODIFY `id_autor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `desejados`
--
ALTER TABLE `desejados`
  MODIFY `id_desejado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `emprestimos`
--
ALTER TABLE `emprestimos`
  MODIFY `id_emprestimo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `generos`
--
ALTER TABLE `generos`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `lidos`
--
ALTER TABLE `lidos`
  MODIFY `id_lido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `livros`
--
ALTER TABLE `livros`
  MODIFY `id_livro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `desejados`
--
ALTER TABLE `desejados`
  ADD CONSTRAINT `desejados_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `desejados_ibfk_2` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id_livro`);

--
-- Limitadores para a tabela `emprestimos`
--
ALTER TABLE `emprestimos`
  ADD CONSTRAINT `emprestimos_ibfk_1` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id_livro`),
  ADD CONSTRAINT `emprestimos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Limitadores para a tabela `lidos`
--
ALTER TABLE `lidos`
  ADD CONSTRAINT `lidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `lidos_ibfk_2` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id_livro`);

--
-- Limitadores para a tabela `livros`
--
ALTER TABLE `livros`
  ADD CONSTRAINT `livros_ibfk_1` FOREIGN KEY (`id_autor`) REFERENCES `autores` (`id_autor`),
  ADD CONSTRAINT `livros_ibfk_2` FOREIGN KEY (`id_genero`) REFERENCES `generos` (`id_genero`);

--
-- Limitadores para a tabela `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id_livro`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;