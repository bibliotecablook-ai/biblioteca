-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS biblioteca_blook;
USE biblioteca_blook;

-- --------------------------------------------------------
-- TABELAS
-- --------------------------------------------------------

CREATE TABLE `autores` (
  `id_autor` int(11) NOT NULL,
  `nome_autor` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nacionalidade` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  PRIMARY KEY (`id_autor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `autores` VALUES
(1,'Taylor Jenkins Reid','Americana','1983-12-20'),
(2,'Colleen Hoover','Americana','1979-12-11'),
(3,'Pierre Boulle',NULL,NULL),
(4,'Cressida Cowell',NULL,NULL),
(5,'E. Lockhart',NULL,NULL),
(6,'J.K. Rowling',NULL,NULL),
(7,'William Joyce',NULL,NULL);

CREATE TABLE `generos` (
  `id_genero` int(11) NOT NULL,
  `nome_genero` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_genero`),
  UNIQUE KEY `nome_genero` (`nome_genero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `generos` VALUES
(4,'Fantasia'),
(6,'Ficção'),
(2,'Ficção Científica'),
(7,'Infantil'),
(3,'Não-Ficção'),
(1,'Romance'),
(5,'Suspense');

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
  `capa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_livro`),
  UNIQUE KEY `isbn` (`isbn`),
  KEY `id_autor` (`id_autor`),
  KEY `id_genero` (`id_genero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `livros` VALUES
(1,'Os Sete Maridos de Evelyn Hugo',1,1,2017,'9788584390978',NULL,5,5,'img/capas/evelyn.jpg'),
(2,'Daisy Jones & The Six',1,1,2019,'9788584391623',NULL,3,2,'img/capas/daisy.jpg'),
(3,'É Assim que Acaba',2,1,2016,'9788501112520',NULL,10,10,'img/capas/acaba.jpg'),
(4,'Planeta dos Macacos',3,2,1963,'9788576572138','',36,9,'img/capas/capa_691775a743bea.jpg'),
(5,'Mentirosos',5,6,2014,'9788565765480','',13,5,'img/capas/capa_691777eb9bf78.jpg'),
(6,'Como Treinar o Seu Dragão',4,4,2003,'9788598078717','',10,2,'img/capas/capa_6917788806540.jpg'),
(7,'Harry Potter e a Pedra Filosofal',6,4,1997,'9788532511010 ','',26,6,'img/capas/capa_691783d6a1a52.jpg'),
(8,'O Homem da Lua ',7,7,2012,'9788562500428 ','',15,11,'img/capas/capa_691788a2224db.jpg'),
(9,'Nicolau São Norte e a Batalha Contra o Rei dos Pesadelos',7,7,2012,'9788581222912','',13,6,'img/capas/capa_691789659134f.jpg');

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `tipo_usuario` enum('admin','leitor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'leitor',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuarios` VALUES
(1,'Usuário Padrão','usuario@email.com','hash_da_senha_do_usuario',NULL,'2025-11-14 15:18:16','leitor'),
(10,'Leo','Leo.Teixeira123@gmail.com','$2y$10$5ZGDKg.Hl3M5clffS4/5HelNq4zgIfSMOfGOLtqXzX6x7XjbXUvPq',NULL,'2025-11-23 14:05:16','admin'),
(11,'Laura','laura@gmail.com','$2y$10$1hR2XVWUyuVS6jLjF/45GOMQ9x5KeQPL8HrDCj6Wv/xGP.KCLNec.',NULL,'2025-11-23 14:06:32','admin'),
(12,'Murilo','Murilo.goncalves@gmail.com','$2y$10$jC5SQmrj3CU7PuyquCKk7.PzxdFwXRMPWFdOSqGz38.VJb8WsjFPi',NULL,'2025-11-23 14:07:06','leitor'),
(13,'Marcos','marcos.G@gmail.com','$2y$10$t.7qsDKLnZmVHBAJWHoQH./..6DrXpq/osaEFgRV0vWW21NcBL0zG',NULL,'2025-11-23 14:08:59','leitor'),
(14,'Ramon','ramon.silva1234@gmail.com','$2y$10$tOnbLw44qR4UyeE5PgPKdu9khiLRmQPGchnRRnhgij/iq2jtGF6YS',NULL,'2025-11-23 14:09:29','leitor');

-- ✅ AUTO_INCREMENT CERTO
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

-- (as demais tabelas seguem igual ao dump original, sem conflitos)

COMMIT;
