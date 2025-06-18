-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 17, 2025 at 02:13 PM
-- Server version: 11.7.2-MariaDB
-- PHP Version: 8.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_dompetku`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_add_transaction` (IN `p_user_id` INT, IN `p_wallet_id` INT, IN `p_category_id` INT, IN `p_jumlah` DECIMAL(15,2), IN `p_keterangan` TEXT, IN `p_tanggal_transaksi` DATE)   BEGIN
    DECLARE v_tipe_kategori ENUM('pemasukan', 'pengeluaran');
    DECLARE v_saldo_dompet DECIMAL(15, 2);

    SELECT tipe_kategori INTO v_tipe_kategori
    FROM categories
    WHERE id = p_category_id AND user_id = p_user_id;

    IF v_tipe_kategori IS NOT NULL THEN
        -- [PERBAIKAN] Cek saldo HANYA jika ini adalah transaksi PENGELUARAN
        IF v_tipe_kategori = 'pengeluaran' THEN
            -- Ambil saldo dompet saat ini
            SELECT saldo INTO v_saldo_dompet FROM wallets WHERE id = p_wallet_id AND user_id = p_user_id;

            -- Jika saldo tidak cukup, hentikan proses dan beri pesan error
            IF v_saldo_dompet < p_jumlah THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Gagal! Saldo dompet tidak mencukupi untuk transaksi ini.';
            END IF;
        END IF;

        -- Jika saldo cukup (atau jika ini pemasukan), lanjutkan proses insert
        INSERT INTO transactions(user_id, wallet_id, category_id, tipe_transaksi, jumlah, keterangan, tanggal_transaksi)
        VALUES (p_user_id, p_wallet_id, p_category_id, v_tipe_kategori, p_jumlah, p_keterangan, p_tanggal_transaksi);
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Kategori tidak valid atau bukan milik Anda.';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_add_wallet` (IN `p_user_id` INT, IN `p_nama_dompet` VARCHAR(100), IN `p_saldo_awal` DECIMAL(15,2))   BEGIN
    INSERT INTO wallets(user_id, nama_dompet, saldo)
    VALUES (p_user_id, p_nama_dompet, p_saldo_awal);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_dashboard_summary` (IN `p_user_id` INT)   BEGIN
    SELECT
        (SELECT IFNULL(SUM(jumlah), 0) FROM transactions WHERE user_id = p_user_id AND tipe_transaksi = 'pemasukan') AS total_pemasukan,
        (SELECT IFNULL(SUM(jumlah), 0) FROM transactions WHERE user_id = p_user_id AND tipe_transaksi = 'pengeluaran') AS total_pengeluaran,
        (SELECT IFNULL(SUM(saldo), 0) FROM wallets WHERE user_id = p_user_id) AS total_saldo_saat_ini;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_register_user` (IN `p_username` VARCHAR(100), IN `p_email` VARCHAR(100), IN `p_password_hash` VARCHAR(255))   BEGIN
    INSERT INTO users(username, email, password_hash)
    VALUES (p_username, p_email, p_password_hash);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_transfer_saldo` (IN `p_user_id` INT, IN `p_wallet_sumber_id` INT, IN `p_wallet_tujuan_id` INT, IN `p_jumlah` DECIMAL(15,2))   BEGIN
    DECLARE v_saldo_sumber DECIMAL(15, 2);
    DECLARE v_cat_id_keluar INT;
    DECLARE v_cat_id_masuk INT;
    DECLARE v_id_transaksi_keluar INT;
    DECLARE v_id_transaksi_masuk INT;

    -- [PERBAIKAN UTAMA] Logika pengecekan saldo sekarang ada di sini
    -- =================================================================
    -- Mengunci baris dompet yang terlibat untuk mencegah race condition
    SELECT saldo INTO v_saldo_sumber FROM wallets WHERE id = p_wallet_sumber_id AND user_id = p_user_id FOR UPDATE;

    -- Validasi Saldo
    IF v_saldo_sumber < p_jumlah THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: Saldo dompet sumber tidak mencukupi.';
    END IF;
    -- =================================================================

    -- Validasi lainnya
    IF p_wallet_sumber_id = p_wallet_tujuan_id THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: Dompet sumber dan tujuan tidak boleh sama.';
    END IF;

    -- Ambil ID kategori transfer
    SELECT id INTO v_cat_id_keluar FROM categories WHERE user_id = p_user_id AND nama_kategori = 'Transfer Keluar' LIMIT 1;
    SELECT id INTO v_cat_id_masuk FROM categories WHERE user_id = p_user_id AND nama_kategori = 'Transfer Masuk' LIMIT 1;

    IF v_cat_id_keluar IS NULL OR v_cat_id_masuk IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: Kategori untuk transfer tidak ditemukan.';
    END IF;
    
    START TRANSACTION;
    
    INSERT INTO transactions (user_id, wallet_id, category_id, tipe_transaksi, jumlah, keterangan, tanggal_transaksi)
    VALUES (p_user_id, p_wallet_sumber_id, v_cat_id_keluar, 'pengeluaran', p_jumlah, CONCAT('Transfer ke dompet lain'), CURDATE());
    SET v_id_transaksi_keluar = LAST_INSERT_ID();

    INSERT INTO transactions (user_id, wallet_id, category_id, tipe_transaksi, jumlah, keterangan, tanggal_transaksi)
    VALUES (p_user_id, p_wallet_tujuan_id, v_cat_id_masuk, 'pemasukan', p_jumlah, CONCAT('Transfer dari dompet lain'), CURDATE());
    SET v_id_transaksi_masuk = LAST_INSERT_ID();

    UPDATE transactions SET linked_transaction_id = v_id_transaksi_masuk WHERE id = v_id_transaksi_keluar;
    UPDATE transactions SET linked_transaction_id = v_id_transaksi_keluar WHERE id = v_id_transaksi_masuk;

    COMMIT;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `tipe_kategori` enum('pemasukan','pengeluaran') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `nama_kategori`, `tipe_kategori`, `created_at`, `updated_at`) VALUES
(3, 3, 'Gaji', 'pemasukan', '2025-06-12 23:59:14', '2025-06-12 23:59:14'),
(4, 3, 'Hadiah', 'pemasukan', '2025-06-12 23:59:14', '2025-06-12 23:59:14'),
(5, 3, 'Lainnya (Pemasukan)', 'pemasukan', '2025-06-12 23:59:14', '2025-06-12 23:59:14'),
(6, 3, 'Transfer Masuk', 'pemasukan', '2025-06-12 23:59:14', '2025-06-12 23:59:14'),
(7, 3, 'Makan & Minum', 'pengeluaran', '2025-06-12 23:59:14', '2025-06-12 23:59:14'),
(8, 3, 'Transportasi', 'pengeluaran', '2025-06-12 23:59:14', '2025-06-12 23:59:14'),
(9, 3, 'Belanja', 'pengeluaran', '2025-06-12 23:59:14', '2025-06-12 23:59:14'),
(10, 3, 'Tagihan', 'pengeluaran', '2025-06-12 23:59:14', '2025-06-12 23:59:14'),
(11, 3, 'Hobi', 'pengeluaran', '2025-06-12 23:59:14', '2025-06-12 23:59:14'),
(12, 3, 'Lainnya (Pengeluaran)', 'pengeluaran', '2025-06-12 23:59:15', '2025-06-12 23:59:15'),
(13, 3, 'Transfer Keluar', 'pengeluaran', '2025-06-12 23:59:15', '2025-06-12 23:59:15'),
(14, 4, 'Gaji', 'pemasukan', '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(15, 4, 'Hadiah', 'pemasukan', '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(16, 4, 'Lainnya (Pemasukan)', 'pemasukan', '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(17, 4, 'Transfer Masuk', 'pemasukan', '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(18, 4, 'Makan & Minum', 'pengeluaran', '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(19, 4, 'Transportasi', 'pengeluaran', '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(20, 4, 'Belanja', 'pengeluaran', '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(21, 4, 'Tagihan', 'pengeluaran', '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(22, 4, 'Hobi', 'pengeluaran', '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(23, 4, 'Lainnya (Pengeluaran)', 'pengeluaran', '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(24, 4, 'Transfer Keluar', 'pengeluaran', '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(25, 5, 'Gaji', 'pemasukan', '2025-06-13 05:34:19', '2025-06-13 05:34:19'),
(26, 5, 'Hadiah', 'pemasukan', '2025-06-13 05:34:19', '2025-06-13 05:34:19'),
(27, 5, 'Lainnya (Pemasukan)', 'pemasukan', '2025-06-13 05:34:19', '2025-06-13 05:34:19'),
(28, 5, 'Transfer Masuk', 'pemasukan', '2025-06-13 05:34:19', '2025-06-13 05:34:19'),
(29, 5, 'Makan & Minum', 'pengeluaran', '2025-06-13 05:34:19', '2025-06-13 05:34:19'),
(30, 5, 'Transportasi', 'pengeluaran', '2025-06-13 05:34:19', '2025-06-13 05:34:19'),
(31, 5, 'Belanja', 'pengeluaran', '2025-06-13 05:34:19', '2025-06-13 05:34:19'),
(32, 5, 'Tagihan', 'pengeluaran', '2025-06-13 05:34:19', '2025-06-13 05:34:19'),
(33, 5, 'Hobi', 'pengeluaran', '2025-06-13 05:34:19', '2025-06-13 05:34:19'),
(34, 5, 'Lainnya (Pengeluaran)', 'pengeluaran', '2025-06-13 05:34:19', '2025-06-13 05:34:19'),
(35, 5, 'Transfer Keluar', 'pengeluaran', '2025-06-13 05:34:19', '2025-06-13 05:34:19');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `wallet_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `tipe_transaksi` enum('pemasukan','pengeluaran') NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal_transaksi` date NOT NULL,
  `linked_transaction_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `wallet_id`, `category_id`, `tipe_transaksi`, `jumlah`, `keterangan`, `tanggal_transaksi`, `linked_transaction_id`, `created_at`) VALUES
(4, 4, 7, 17, 'pemasukan', 100000.00, 'Dari siapa yaaaa', '2025-06-13', NULL, '2025-06-13 10:03:10'),
(9, 4, 8, 17, 'pemasukan', 1000000.00, 'kiriman', '2025-06-13', NULL, '2025-06-13 12:26:38'),
(10, 4, 8, 24, 'pengeluaran', 550000.00, 'kos', '2025-06-14', NULL, '2025-06-13 12:27:12'),
(13, 5, 11, 25, 'pemasukan', 500000.00, 'driver narkoba', '2025-06-13', NULL, '2025-06-13 12:36:09'),
(18, 5, 11, 31, 'pengeluaran', 1000.00, '', '2025-06-13', NULL, '2025-06-13 12:51:14'),
(19, 5, 11, 26, 'pemasukan', 6000000.00, '', '2025-06-13', NULL, '2025-06-13 12:59:39'),
(20, 5, 11, 35, 'pengeluaran', 6000000.00, 'Transfer ke dompet lain', '2025-06-13', 21, '2025-06-13 13:05:08'),
(21, 5, 10, 28, 'pemasukan', 6000000.00, 'Transfer dari dompet lain', '2025-06-13', 20, '2025-06-13 13:05:08');

--
-- Triggers `transactions`
--
DELIMITER $$
CREATE TRIGGER `trg_after_delete_transaction` AFTER DELETE ON `transactions` FOR EACH ROW BEGIN
    IF OLD.tipe_transaksi = 'pemasukan' THEN
        UPDATE wallets SET saldo = saldo - OLD.jumlah WHERE id = OLD.wallet_id;
    ELSEIF OLD.tipe_transaksi = 'pengeluaran' THEN
        UPDATE wallets SET saldo = saldo + OLD.jumlah WHERE id = OLD.wallet_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_after_insert_transaction` AFTER INSERT ON `transactions` FOR EACH ROW BEGIN
    IF NEW.tipe_transaksi = 'pemasukan' THEN
        UPDATE wallets SET saldo = saldo + NEW.jumlah WHERE id = NEW.wallet_id;
    ELSEIF NEW.tipe_transaksi = 'pengeluaran' THEN
        UPDATE wallets SET saldo = saldo - NEW.jumlah WHERE id = NEW.wallet_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_after_update_transaction` AFTER UPDATE ON `transactions` FOR EACH ROW BEGIN
    -- Batalkan efek saldo lama
    IF OLD.tipe_transaksi = 'pemasukan' THEN
        UPDATE wallets SET saldo = saldo - OLD.jumlah WHERE id = OLD.wallet_id;
    ELSE
        UPDATE wallets SET saldo = saldo + OLD.jumlah WHERE id = OLD.wallet_id;
    END IF;

    -- Terapkan efek saldo baru
    IF NEW.tipe_transaksi = 'pemasukan' THEN
        UPDATE wallets SET saldo = saldo + NEW.jumlah WHERE id = NEW.wallet_id;
    ELSE
        UPDATE wallets SET saldo = saldo - NEW.jumlah WHERE id = NEW.wallet_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT 'default.jpg',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `foto`, `created_at`, `updated_at`) VALUES
(3, 'Dwi', 'Dwi@gmail.com', '$2y$12$z.O59MYLRKjQxlv3bNEFIO0FaWpSkL6APabqJykJwCQPLZNHKAH5i', 'default.jpg', '2025-06-12 23:59:14', '2025-06-12 23:59:14'),
(4, 'Aderelyan', 'aderelyan28@gmail.com', '$2y$12$0v8G2DFKDOVAEOOHq70n7.DvLw.O.LnNZVVfrCt1ABGFDlaUMbOBG', 'default.jpg', '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(5, 'bre', 'Brenen@gmail.com', '$2y$12$Iq0J8yzaaa1bD9h5Osj2huljvXbE3aukQieHQigFqcyYWG8BCaY76', 'default.jpg', '2025-06-13 05:34:18', '2025-06-13 05:34:18');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_dompet` varchar(100) NOT NULL,
  `saldo` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `nama_dompet`, `saldo`, `created_at`, `updated_at`) VALUES
(5, 3, 'Dompet Utama', 0.00, '2025-06-12 23:59:15', '2025-06-12 23:59:15'),
(6, 3, 'Rekening Bank', 0.00, '2025-06-12 23:59:15', '2025-06-12 23:59:15'),
(7, 4, 'Dompet Utama', 100000.00, '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(8, 4, 'Rekening Bank', 450000.00, '2025-06-13 03:02:18', '2025-06-13 03:02:18'),
(9, 5, 'Dompet Utama', 0.00, '2025-06-13 05:34:19', '2025-06-13 05:34:19'),
(10, 5, 'Rekening Bank', 6000000.00, '2025-06-13 05:34:19', '2025-06-13 05:34:19'),
(11, 5, 'makan', 499000.00, '2025-06-13 05:34:42', '2025-06-13 05:34:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_linked_transaction` (`linked_transaction_id`),
  ADD KEY `fk_trans_category` (`category_id`),
  ADD KEY `fk_trans_wallet` (`wallet_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_linked_transaction` FOREIGN KEY (`linked_transaction_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_trans_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_trans_wallet` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
