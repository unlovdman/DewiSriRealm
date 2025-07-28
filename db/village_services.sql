-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 16, 2025 at 10:03 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `village_services`
--

-- --------------------------------------------------------

--
-- Table structure for table `letter_history`
--

CREATE TABLE `letter_history` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `letter_type` varchar(50) DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `letter_history`
--

INSERT INTO `letter_history` (`id`, `user_id`, `letter_type`, `nik`, `created_at`) VALUES
(13, 2, 'Surat Kuasa', '404', '2024-12-17 00:43:30'),
(14, 2, 'Surat Domisili', '43141', '2024-12-17 01:24:00'),
(15, 2, 'Surat Domisili', '41231', '2024-12-17 01:27:44'),
(16, 2, 'Surat Domisili', '41231', '2024-12-17 01:30:44'),
(17, 2, 'Surat Domisili', 'ewqeq', '2024-12-17 01:32:57'),
(18, 2, 'Surat Pengantar', '31423241', '2024-12-17 01:42:07'),
(19, 2, 'Surat Pengantar', '3242', '2024-12-17 01:42:58'),
(20, 2, 'Surat Pengantar', '3242', '2024-12-17 01:46:40'),
(21, 2, 'Surat Keterangan', 'eqwe', '2024-12-17 01:54:06'),
(22, 2, 'Surat Domisili', '3506224805020003', '2024-12-17 04:22:50'),
(23, 2, 'Surat Domisili', '3522065905030001', '2024-12-17 04:42:07'),
(24, 2, 'Surat Domisili', '3522065905030001', '2024-12-17 04:51:31'),
(25, 2, 'Surat Domisili', '3522065905030001', '2024-12-17 05:29:40'),
(26, 2, 'Surat Keterangan', '3522065905030001', '2024-12-17 05:31:27'),
(27, 2, 'Surat Domisili', '3522065705040004', '2024-12-18 05:27:13'),
(28, 2, 'Surat Keterangan', '3522065705040004', '2024-12-18 05:40:23'),
(29, 2, 'Surat Domisili', '3522065905030013', '2024-12-23 17:01:14'),
(30, 2, 'Surat Keterangan', '3522065905030010', '2024-12-23 17:05:51'),
(31, 2, 'Surat Kuasa', '31323', '2024-12-23 17:08:19'),
(32, 2, 'Surat Pengantar', '3522065905030001', '2024-12-23 17:10:38'),
(33, 2, 'Surat Domisili', '3522065905030013', '2024-12-24 05:32:37'),
(34, 2, 'Surat Keterangan', '3522065905030013', '2024-12-24 05:36:39'),
(35, 2, 'Surat Kuasa', '333333433', '2024-12-24 05:40:32'),
(38, 2, 'Surat Domisili', '3500852708175130', '2025-02-27 03:56:22'),
(39, 1, 'Surat Keterangan', '3522065905030013', '2025-04-25 06:50:13'),
(40, 2, 'Surat Domisili', '3522065705040004', '2025-05-05 13:06:31'),
(41, 2, 'Surat Domisili', '3522065705040004', '2025-05-05 13:10:22'),
(42, 2, 'Surat Pengantar', '3522065705040004', '2025-05-15 02:14:42'),
(43, 2, 'Surat Domisili', '3522065705040004', '2025-05-15 02:30:33'),
(44, 2, 'Surat Domisili', '3522065705040004', '2025-05-15 02:33:37'),
(45, 2, 'Surat Domisili', '3522065705040004', '2025-05-15 02:37:13'),
(46, 2, 'Surat Domisili', '3522065705040004', '2025-05-15 02:40:47'),
(47, 2, 'Surat Domisili', '3522065705040004', '2025-05-15 02:43:27'),
(48, 2, 'Surat Domisili', '3522065705040004', '2025-05-15 02:53:17'),
(49, 2, 'Surat Domisili', '3522065705040004', '2025-05-15 02:54:31'),
(50, 2, 'Surat Domisili', '3522065705040004', '2025-05-15 02:55:45'),
(51, 2, 'Surat Keterangan', 'AA', '2025-05-15 02:58:28'),
(52, 2, 'Surat Domisili', '3522065705040004', '2025-05-15 11:06:15');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `message` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `letter_type` enum('Surat Pengantar','Surat Domisili','Surat Keterangan') DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nik`, `username`, `password`, `role`, `created_at`) VALUES
(1, '3522065705040005', 'me', '$2y$10$/lJYfvZm6rrmwiJBGNvmo.9CCgmdpcCs/jAwuraPo3onZJo7SHCJ2', 'admin', '2024-12-16 11:00:33'),
(2, '3522065705040004', 'mel', '$2y$10$pbOmnmRlVqhVOO0S8jVe6O3w8xgNBN9zyr/4T7G.admNouOthD1OS', 'user', '2024-12-16 11:06:58'),
(3, '3547841926696606', 'puti', '$2y$10$guJmJ.W7btJw7U6nbTsvbe/YwifTT.ZiSH/yjfrjx21KFXQrTCbkq', 'user', '2024-12-17 03:14:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `letter_history`
--
ALTER TABLE `letter_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `letter_history`
--
ALTER TABLE `letter_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `letter_history`
--
ALTER TABLE `letter_history`
  ADD CONSTRAINT `letter_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `letter_history` ADD COLUMN `pdf_filename` VARCHAR(255) DEFAULT NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
