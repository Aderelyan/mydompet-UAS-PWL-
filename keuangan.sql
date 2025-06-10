-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2025 at 03:56 PM
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
-- Database: `keuangan`
--

-- --------------------------------------------------------

--
-- Table structure for table `catatan_keuangan`
--

CREATE TABLE `catatan_keuangan` (
  `id` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `jenis` enum('pemasukan','pengeluaran') NOT NULL,
  `tanggal` date NOT NULL,
  `users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `catatan_keuangan`
--

INSERT INTO `catatan_keuangan` (`id`, `keterangan`, `jumlah`, `jenis`, `tanggal`, `users_id`) VALUES
(6, 'thr', 500000, 'pemasukan', '2025-03-24', 0),
(7, 'pulsa', 300000, 'pengeluaran', '2025-03-25', 0),
(8, 'print', 50, 'pengeluaran', '2025-04-16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'user1', 'brem@gmail.com', '$2y$10$LwQR3O4GTf0N3YzchfnH9O50AjJPoDUqj9WMu66qd20OejwP6KVyi', '2025-03-18 05:09:51'),
(3, 'Brenendra', 'Brenendra77@gmail.com', '$2y$10$umN/tyv9AwmKrPHwqM2heOZX04BHhuWKQjYMC5UyXFmD0AqBAwR5i', '2025-03-21 02:04:58'),
(4, 'budi', 'budi@gmail.com', '$2y$10$KnGMFsxOhhdS9peJxeCVA..hTsSOOq4K1H8vH4hzUmlIerjpBFhIW', '2025-03-23 01:20:36'),
(7, 'a@gmail.com', 'a@gmail.com', '$2y$10$TKKXM5hB2wRyLWp4RX7mneuTQ91QVOJS9m7l4T2lHdp2nOyf9NH4O', '2025-04-15 04:29:02'),
(10, 'user', 'user10@gmail.com', '$2y$10$yGoGltWtQSxgWm0kOT2QSenzVSiK46dGjN5WgvbiNM3C5ecTWWqqS', '2025-04-16 01:03:01'),
(11, 'user1', 'user1@gmail.com', '$2y$10$My6RwqRsIWiZLZb/6iImr.p1IwSAEhCof1VkcvkYBdX3vhegGbc8W', '2025-04-16 01:22:15'),
(12, '2', '2@gmail.com', '$2y$10$Drtk5TGtC/1fhWPDtUIvYuYGmNO9ym4qV9n3R2OorKsTsd251i0oO', '2025-04-16 03:52:59'),
(14, 'guest', 'guest@sistem.com', '$2y$10$pvdxJBXkEIlEpXhUXAWtBuvSZpsOYPr8wIWoNGxDO6dbXROvx3oxi', '2025-06-06 12:05:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `catatan_keuangan`
--
ALTER TABLE `catatan_keuangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_id` (`users_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `catatan_keuangan`
--
ALTER TABLE `catatan_keuangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
