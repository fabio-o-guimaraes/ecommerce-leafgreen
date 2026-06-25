-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2026 at 10:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `leafgreen_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `encomendas`
--

CREATE TABLE `encomendas` (
  `id_encomenda` int(11) NOT NULL,
  `id_utilizador` int(11) DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `morada` text NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `data_encomenda` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendente','pago','enviado','cancelado') NOT NULL DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `encomendas`
--

INSERT INTO `encomendas` (`id_encomenda`, `id_utilizador`, `nome`, `email`, `data_nascimento`, `morada`, `total`, `data_encomenda`, `estado`) VALUES
(40, 18, 'cliente', 'cliente@email.com', '1991-10-25', 'Argoncilhe', 69.50, '2026-05-06 19:35:04', 'pendente');

-- --------------------------------------------------------

--
-- Table structure for table `encomenda_produtos`
--

CREATE TABLE `encomenda_produtos` (
  `id` int(11) NOT NULL,
  `id_encomenda` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `nome_produto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `encomenda_produtos`
--

INSERT INTO `encomenda_produtos` (`id`, `id_encomenda`, `id_produto`, `quantidade`, `preco_unitario`, `nome_produto`) VALUES
(50, 40, 8, 1, 58.00, 'Ficus ginseng'),
(51, 40, 7, 1, 11.50, 'Estrela-de-Natal');

-- --------------------------------------------------------

--
-- Table structure for table `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `imagem` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produtos`
--

INSERT INTO `produtos` (`id_produto`, `nome`, `preco`, `stock`, `imagem`, `ativo`) VALUES
(1, 'Espada-de-São-Jorge', 14.99, 10, 'product1.jpg', 1),
(2, 'Árvore-da-riqueza', 13.90, 10, 'product2.jpg', 1),
(3, 'Costela-de-adão', 15.90, 8, 'product3.jpg', 1),
(4, 'Palmeira-de-salão', 28.95, 18, 'product4.jpg', 1),
(5, 'Lírio-da-paz', 8.90, 17, 'product5.jpg', 1),
(6, 'Planta-mosaico', 2.50, 0, 'product6.jpg', 1),
(7, 'Estrela-de-Natal', 11.50, 23, 'product7.jpg', 1),
(8, 'Ficus ginseng', 58.00, 21, 'product8.jpg', 1),
(9, 'Iresine herbstii', 6.95, 22, 'product9.jpg', 1),
(10, 'Planta ZZ', 17.00, 20, 'product10.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id_utilizador` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `morada` text DEFAULT NULL,
  `tipo` enum('admin','cliente') DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizador`, `nome`, `email`, `password`, `data_nascimento`, `morada`, `tipo`) VALUES
(4, 'administrador', 'admin@email.com', '$2y$10$j9zkkUCxzzGnS490e1t3FumdYhoQsy5uFkw.6XAocUHzXXBNPXoG6', '1991-10-25', 'Argoncilhe', 'admin'),
(18, 'cliente', 'cliente@email.com', '$2y$10$ga67ffoLh/T1vStghRhUaO8MmsNPh43VhyjUTsTFiAdU54wcQVrAW', '1991-10-25', 'Argoncilhe', 'cliente');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `encomendas`
--
ALTER TABLE `encomendas`
  ADD PRIMARY KEY (`id_encomenda`),
  ADD KEY `id_utilizador` (`id_utilizador`);

--
-- Indexes for table `encomenda_produtos`
--
ALTER TABLE `encomenda_produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_encomenda` (`id_encomenda`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Indexes for table `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`);

--
-- Indexes for table `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id_utilizador`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `encomendas`
--
ALTER TABLE `encomendas`
  MODIFY `id_encomenda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `encomenda_produtos`
--
ALTER TABLE `encomenda_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id_utilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `encomendas`
--
ALTER TABLE `encomendas`
  ADD CONSTRAINT `encomendas_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE SET NULL;

--
-- Constraints for table `encomenda_produtos`
--
ALTER TABLE `encomenda_produtos`
  ADD CONSTRAINT `encomenda_produtos_ibfk_1` FOREIGN KEY (`id_encomenda`) REFERENCES `encomendas` (`id_encomenda`) ON DELETE CASCADE,
  ADD CONSTRAINT `encomenda_produtos_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
