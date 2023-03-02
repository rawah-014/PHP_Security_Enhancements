-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2023 at 08:47 AM
-- Server version: 8.0.31
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phploginapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempt_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `attempt_time`) VALUES
(49, '::1', '2023-02-27 13:38:54'),
(50, '::1', '2023-02-27 20:20:53'),
(51, '::1', '2023-02-28 12:21:17'),
(52, '::1', '2023-02-28 12:28:04'),
(53, '::1', '2023-02-28 20:08:58');

-- --------------------------------------------------------

--
-- Table structure for table `login_log`
--

CREATE TABLE `login_log` (
  `id` int NOT NULL,
  `ip_address` varchar(30) NOT NULL,
  `try_time` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `login_log`
--

INSERT INTO `login_log` (`id`, `ip_address`, `try_time`) VALUES
(37, '::1', 1673185025);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'rawah', 'rawah.014@gmail.com', '$2y$10$ojVRi7db93z2ylNSZmCcFuti.RGPy/vWhEYkSrBU7xcPo50oKjni.', '2022-10-22 01:04:58'),
(2, 'rawah', 'rawah.01@gmail.com', '$2y$10$k673OHiI2FQV3VwbeCavNuUUn59pcTlyMH/tsTPFevnTgRqgZdqeO', '2022-10-22 02:53:45'),
(3, 'test', 'test@hh.kk', '$2y$10$YDBSwpi37.9kGN/iQesGPOk./7aUpndt/lXseS2EnK5BZR8aArUZa', '2022-10-22 22:23:26'),
(4, 'test', 'test2@hh.kk', '$2y$10$AxJ.gmf8kHhnVh1Q9E/5o.S3i5bkXI.Qfk.Gs.e4HB1YyTAx2wxoS', '2022-10-22 22:26:59'),
(5, 'test', 'test3@hh.kk', 'Ploto7#1', '2022-10-22 22:32:23'),
(6, 'r', '', 'Car#1234', '2022-11-20 06:45:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_log`
--
ALTER TABLE `login_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `login_log`
--
ALTER TABLE `login_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
