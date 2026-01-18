-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2026 at 01:56 AM
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
-- Database: `bibliotheque`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `available` tinyint(4) NOT NULL,
  `return_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id`, `title`, `author`, `description`, `available`, `return_date`) VALUES
(1, 'Le Petit Prince', 'Antoine de Saint-Exupéry', 'Un jeune prince visite différentes planètes.', 0, NULL),
(2, '1984', 'George Orwell', 'Un monde sous surveillance totale.', 0, NULL),
(3, 'L\'Étranger', 'Albert Camus', 'L\'histoire de Meursault.', 1, NULL),
(4, 'Harry Potter', 'J.K. Rowling', 'Un jeune sorcier à Poudlard.', 1, NULL),
(5, 'Le Seigneur des Anneaux', 'J.R.R. Tolkien', 'Une quête pour détruire un anneau maléfique.', 1, NULL),
(6, 'Le Petit Prince', 'Antoine de Saint-Exupéry', 'Un jeune prince visite différentes planètes.', 1, NULL),
(7, '1984', 'George Orwell', 'Un monde sous surveillance totale.', 1, NULL),
(8, 'L\'Étranger', 'Albert Camus', 'L\'histoire de Meursault.', 1, NULL),
(9, 'Harry Potter à l\'école des sorciers', 'J.K. Rowling', 'Un jeune sorcier découvre la magie.', 1, NULL),
(10, 'Le Seigneur des Anneaux', 'J.R.R. Tolkien', 'Une quête pour détruire un anneau maléfique.', 1, NULL),
(11, 'Les Misérables', 'Victor Hugo', 'L\'histoire de Jean Valjean en France.', 1, NULL),
(12, 'Da Vinci Code', 'Dan Brown', 'Un thriller mystérieux.', 1, NULL),
(13, 'Le Nom de la Rose', 'Umberto Eco', 'Meurtre dans une abbaye médiévale.', 1, NULL),
(14, 'Germinal', 'Émile Zola', 'La vie des mineurs au 19ème siècle.', 1, NULL),
(15, 'Orgueil et Préjugés', 'Jane Austen', 'Histoire d\'amour dans l\'aristocratie anglaise.', 1, NULL),
(16, 'Le Petit Prince', 'Antoine de Saint-Exupéry', 'Un jeune prince visite différentes planètes.', 1, NULL),
(17, '1984', 'George Orwell', 'Un monde sous surveillance totale.', 1, NULL),
(18, 'L\'Étranger', 'Albert Camus', 'L\'histoire de Meursault.', 1, NULL),
(19, 'Harry Potter à l\'école des sorciers', 'J.K. Rowling', 'Un jeune sorcier découvre la magie.', 1, NULL),
(20, 'Le Seigneur des Anneaux', 'J.R.R. Tolkien', 'Une quête pour détruire un anneau maléfique.', 1, NULL),
(21, 'Les Misérables', 'Victor Hugo', 'L\'histoire de Jean Valjean en France.', 1, NULL),
(22, 'Da Vinci Code', 'Dan Brown', 'Un thriller mystérieux.', 1, NULL),
(23, 'Le Nom de la Rose', 'Umberto Eco', 'Meurtre dans une abbaye médiévale.', 1, NULL),
(24, 'Germinal', 'Émile Zola', 'La vie des mineurs au 19ème siècle.', 1, NULL),
(25, 'Orgueil et Préjugés', 'Jane Austen', 'Histoire d\'amour dans l\'aristocratie anglaise.', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `request_date` datetime NOT NULL,
  `return_date` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `payment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan`
--

INSERT INTO `loan` (`id`, `status`, `request_date`, `return_date`, `user_id`, `book_id`, `payment_id`) VALUES
(1, 'refuse', '2026-01-15 23:52:21', NULL, 2, 2, NULL),
(2, 'refuse', '2026-01-15 23:53:37', NULL, 3, 1, NULL),
(3, 'valide', '2026-01-15 23:53:37', '2026-01-22 23:53:37', 3, 2, NULL),
(4, 'refuse', '2026-01-15 23:53:37', NULL, 3, 3, NULL),
(5, 'pending_payment', '2026-01-16 00:10:34', NULL, 2, 2, 1),
(6, 'refuse', '2026-01-16 00:22:09', NULL, 2, 1, NULL),
(7, 'pending_payment', '2026-01-16 00:28:37', NULL, 2, 2, 8),
(8, 'pending_payment', '2026-01-16 00:28:38', NULL, 2, 3, 9),
(9, 'pending_payment', '2026-01-16 00:30:17', NULL, 2, 1, 10),
(10, 'refuse', '2026-01-16 00:34:13', NULL, 1, 1, NULL),
(11, 'refuse', '2026-01-16 00:51:45', NULL, 2, 2, NULL),
(12, 'pending_payment', '2026-01-16 00:51:48', NULL, 2, 3, 19),
(13, 'pending_payment', '2026-01-16 01:00:23', NULL, 2, 2, 24),
(14, 'refuse', '2026-01-16 01:26:19', NULL, 2, 2, NULL),
(15, 'pending_payment', '2026-01-16 01:35:38', NULL, 2, 2, NULL),
(16, 'pending_payment', '2026-01-16 01:51:03', NULL, 2, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `message`, `link`, `is_read`, `created_at`, `user_id`) VALUES
(1, 'Votre demande pour \'1984\' est approuvée. Merci de régler les 5Dh.', '/payment/29', 0, '2026-01-16 01:36:01', 2),
(2, 'Votre demande pour \'Le Petit Prince\' est approuvée. Merci de régler les 5Dh.', '/payment/34', 0, '2026-01-16 01:51:28', 2);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `currency` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `stripe_payment_id` varchar(255) DEFAULT NULL,
  `stripe_session_id` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `paid_at` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `amount`, `currency`, `status`, `stripe_payment_id`, `stripe_session_id`, `created_at`, `paid_at`, `user_id`, `loan_id`) VALUES
(1, 5, 'eur', 'pending', NULL, 'cs_test_a1HAHeqpAyiSU8rbY2WT0Sitd3SQbNvb4No2Pg95HXi1EExtRspoDI2XsA', '2026-01-16 00:11:34', NULL, 2, 5),
(2, 5, 'eur', 'pending', NULL, NULL, '2026-01-16 00:23:32', NULL, 2, 6),
(8, 5, 'eur', 'pending', NULL, 'cs_test_a1U9lHp0ru34VNrL2zVMAwDSbn38QAsTYuN1lENhtAm6CKbrD6RsC2dFHy', '2026-01-16 00:28:51', NULL, 2, 7),
(9, 5, 'eur', 'pending', NULL, 'cs_test_a1qORhedBO1rnhFJqxqNXsdaLV2VlSEmIzJYxZlwUdnjshcbirxWRyJg6A', '2026-01-16 00:28:52', NULL, 2, 8),
(10, 5, 'eur', 'cancelled', NULL, 'cs_test_a1ah0Rtjt7lndqT840QwFqewgWA2WqzDUhHb9Kc7jTdARAWbH7yr2KrFi2', '2026-01-16 00:30:34', NULL, 2, 9),
(11, 5, 'eur', 'pending', NULL, 'cs_test_a1VZUdiCtgXsif6QTUUcOnIl99RFkRZFWYtvpQia9I6vX0uSthEwvFsQOc', '2026-01-16 00:51:24', NULL, 1, 10),
(13, 5, 'eur', 'pending', NULL, 'cs_test_a1Ra0bUQ6zR0TM6jjxd6W7fb5finDXjpyZwcHFdhhO2FLVdPUF4Tbfg6DL', '2026-01-16 00:51:59', NULL, 2, 11),
(19, 5, 'eur', 'pending', NULL, 'cs_test_a1bIBkgl494997ehzC7wFpaIQmWckxXeQsR1U7IjXZOjUdFNGkNWNK7sIh', '2026-01-16 00:58:58', NULL, 2, 12),
(24, 5, 'eur', 'pending', NULL, 'cs_test_a1PZHBmKETLsD5bzXVOr68qCrl5tE2Exl9xCa8SR3VdrcJ0zJosmg7hQxP', '2026-01-16 01:00:46', NULL, 2, 13),
(25, 5, 'eur', 'pending', NULL, NULL, '2026-01-16 01:32:17', NULL, 2, 14),
(29, 5, 'eur', 'cancelled', NULL, NULL, '2026-01-16 01:36:01', NULL, 2, 15),
(34, 5, 'eur', 'pending', NULL, 'cs_test_a1OXg36cGw0FGSUv0MYbaX9irlDBWm5PMfUTAeXEJHGO8ckzU9ceaHh5Oc', '2026-01-16 01:51:28', NULL, 2, 16);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `action_type`, `status`, `created_at`, `book_id`, `user_id`) VALUES
(1, 'refus_emprunt', 'refuse', '2026-01-15 23:59:56', 2, 2),
(2, 'refus_emprunt', 'refuse', '2026-01-15 23:59:57', 1, 3),
(3, 'creation_paiement', 'en_attente', '2026-01-16 00:11:39', 2, 2),
(4, 'refus_emprunt', 'refuse', '2026-01-16 00:28:19', 1, 2),
(5, 'creation_paiement', 'en_attente', '2026-01-16 00:28:52', 2, 2),
(6, 'creation_paiement', 'en_attente', '2026-01-16 00:28:53', 3, 2),
(7, 'creation_paiement', 'en_attente', '2026-01-16 00:30:34', 1, 2),
(8, 'refus_emprunt', 'refuse', '2026-01-16 00:51:30', 1, 1),
(9, 'creation_paiement', 'en_attente', '2026-01-16 00:58:59', 3, 2),
(10, 'refus_emprunt', 'refuse', '2026-01-16 00:59:44', 2, 2),
(11, 'creation_paiement', 'en_attente', '2026-01-16 01:00:47', 2, 2),
(12, 'refus_emprunt', 'refuse', '2026-01-16 01:35:21', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
(1, 'admin@emsi.ma', '[\"ROLE_ADMIN\"]', '$2y$13$kTB/bJeRuQWu85sXt35Sze5V4jPYLTrYX5JssJz.qHLEjhuZg7.Hi'),
(2, 'adam@emsi.ma', '[\"ROLE_USER\"]', '$2y$13$2yhOdYnSkxE83YxCUe0lAOqxyHSDwTo8KQMQcsQ/JLvz5R0kRJeza'),
(3, 'alice@emsi.ma', '[\"ROLE_USER\"]', '$2y$13$abcdefghijklmnopqrstuv'),
(4, 'bob@emsi.ma', '[\"ROLE_USER\"]', '$2y$13$abcdefghijklmnopqrstuv'),
(5, 'charlie@emsi.ma', '[\"ROLE_USER\"]', '$2y$13$abcdefghijklmnopqrstuv');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C5D30D034C3A3BB` (`payment_id`),
  ADD KEY `IDX_C5D30D03A76ED395` (`user_id`),
  ADD KEY `IDX_C5D30D0316A2B381` (`book_id`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750` (`queue_name`,`available_at`,`delivered_at`,`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BF5476CAA76ED395` (`user_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_6D28840DCE73868F` (`loan_id`),
  ADD KEY `IDX_6D28840DA76ED395` (`user_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_723705D116A2B381` (`book_id`),
  ADD KEY `IDX_723705D1A76ED395` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `FK_C5D30D0316A2B381` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`),
  ADD CONSTRAINT `FK_C5D30D034C3A3BB` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`id`),
  ADD CONSTRAINT `FK_C5D30D03A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `FK_BF5476CAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `FK_6D28840DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_6D28840DCE73868F` FOREIGN KEY (`loan_id`) REFERENCES `loan` (`id`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `FK_723705D116A2B381` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`),
  ADD CONSTRAINT `FK_723705D1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
