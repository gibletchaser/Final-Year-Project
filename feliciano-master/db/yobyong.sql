-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2026 at 08:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yobyong`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(6, 'Add on'),
(5, 'Kuey Tiaw'),
(8, 'Lauk Sahaja'),
(3, 'Mi Kuning'),
(4, 'Mihun'),
(2, 'Minuman'),
(1, 'Nasi Goreng'),
(7, 'Sizzling');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `phone` int(255) NOT NULL,
  `password` varchar(256) DEFAULT NULL,
  `profilePic` varchar(255) DEFAULT 'images/default-user.png',
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`name`, `email`, `phone`, `password`, `profilePic`, `reset_token`, `reset_token_expires`) VALUES
('haziq', 'hazzziq412@gmail.com', 102102102, '12345678', 'images/default-user.png', NULL, NULL),
('kaijin', 'louislimkj10@gmail.com', 29238373, '102102102', 'images/default-user.png', NULL, NULL),
('shybroccoli', 'shynayip913@gmail.com', 12345678, 'de9587b4f621f8a5d2b1a0f815fd483589d83eeb40f4745eb211f870eeba4a42', 'uploads/1770778403_bg_2.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(65) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'img/tempMenu.jpg',
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `price`, `image`, `category_id`) VALUES
(5, 'nasi kerabu', 10, 'img/NasiKerabu.jpg', NULL),
(6, 'nasi goreng', 6, 'uploads/1770779641_nasiGoreng.jpg', 1),
(7, 'telur mata', 1, 'img/telurMata.jpg', 6),
(8, 'air kosong ', 1, 'img/airKosong.jpg', 2),
(11, 'teh ais', 3, 'img/tehAis.jpg', 2),
(14, 'Mi Ladna', 8, 'img/miLadna.jpg', 3),
(19, 'telur dadar', 1, 'img/telurDadar.jpg', 6),
(21, 'nasi goreng pattaya', 7, 'img/tempMenu.jpg', 1),
(27, 'nasi ayam ', 6, 'uploads/1770779760_NasiAyam.webp', 8),
(28, 'Tomyam Campur', 10, 'uploads/1772074297_TomyamCampur.jpg', 8),
(29, 'Mi Kuning', 8, 'uploads/1772076137_download (10).jpg', NULL),
(30, 'Mi Goreng Tomyam', 9, 'uploads/1772076303_download (10).jpg', 3),
(31, 'Mi Sup Ayam', 10, 'uploads/1772076186_download (10).jpg', NULL),
(32, 'Mi Tomyam Sup', 10, 'uploads/1772076207_download (10).jpg', NULL),
(33, 'Mi Goreng Seafood', 9, 'uploads/1772076233_download (10).jpg', NULL),
(34, 'Yi Mi Goreng', 9, 'uploads/1772076252_download (10).jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(120) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('card','fpx') NOT NULL,
  `stripe_session_id` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `phone`, `items`, `total_amount`, `payment_method`, `stripe_session_id`, `notes`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 'shyna', '12345678', '[{\"id\":\"nasi-goreng\",\"name\":\"nasi goreng\",\"price\":5,\"quantity\":2}]', 10.00, 'card', 'cs_test_a19hElNgAENHEkMtRQc9bnreCYZ3r0nGMVmhbz8r2kx7u8dzXfRqS7vf5x', '', 'pending', '2026-02-26 15:13:40', '2026-02-26 15:13:40'),
(2, 'shyna', '12345678', '[{\"id\":\"nasi-goreng\",\"name\":\"nasi goreng\",\"price\":5,\"quantity\":2}]', 10.00, 'card', 'cs_test_a1K9iBZA4V4Vv4yPQKo3n04ggF3XJJNQ5vPJeymgmzbKLe8l4Km3q5R60o', '', 'pending', '2026-02-26 15:13:40', '2026-02-26 15:13:40'),
(3, 'shyna', '12345678', NULL, 10.00, '', 'cs_test_a19hElNgAENHEkMtRQc9bnreCYZ3r0nGMVmhbz8r2kx7u8dzXfRqS7vf5x', '', 'pending', '2026-02-26 15:13:40', NULL),
(4, 'shyna', '12345678', NULL, 10.00, '', 'cs_test_a1K9iBZA4V4Vv4yPQKo3n04ggF3XJJNQ5vPJeymgmzbKLe8l4Km3q5R60o', '', 'pending', '2026-02-26 15:13:40', NULL),
(5, 'shyna', '12345678', '[{\"id\":\"nasi-goreng\",\"name\":\"nasi goreng\",\"price\":5,\"quantity\":2}]', 10.00, 'card', 'cs_test_a12g4srILSQlVfyAmt0sYSpEuZH9o8Mhg0lBXeohaSt6vt580btnAnuqcn', '', 'pending', '2026-02-26 15:15:23', '2026-02-26 15:15:23'),
(6, 'shyna', '12345678', '[{\"id\":\"nasi-goreng\",\"name\":\"nasi goreng\",\"price\":5,\"quantity\":2}]', 10.00, 'card', 'cs_test_a1HOL4v8tKJE7chQmEHITDujYb4DYcTy5NVzNKlOzdJFG8tKZEoWW0QnRe', '', 'pending', '2026-02-26 15:15:23', '2026-02-26 15:15:23'),
(7, 'shyna', '12345678', NULL, 10.00, '', 'cs_test_a1HOL4v8tKJE7chQmEHITDujYb4DYcTy5NVzNKlOzdJFG8tKZEoWW0QnRe', '', 'pending', '2026-02-26 15:15:23', NULL),
(8, 'shyna', '12345678', NULL, 10.00, '', 'cs_test_a12g4srILSQlVfyAmt0sYSpEuZH9o8Mhg0lBXeohaSt6vt580btnAnuqcn', '', 'pending', '2026-02-26 15:15:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `reviewer_name` varchar(255) NOT NULL,
  `reviewer_email` varchar(255) DEFAULT NULL,
  `rating` int(1) NOT NULL DEFAULT 5,
  `user_email` varchar(255) DEFAULT 'Guest',
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `delete_code` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `reviewer_name`, `reviewer_email`, `rating`, `user_email`, `comment`, `created_at`, `delete_code`) VALUES
(2, 'HUI', '', 5, 'Guest', 'The environment is also clean', '2026-01-31 06:29:50', NULL),
(29, 'shyna', NULL, 4, 'Guest', 'the food was good', '2026-02-11 02:54:05', 'f1555bbb8c'),
(30, 'shyna', NULL, 4, 'Guest', 'the food was good', '2026-02-11 02:54:09', '80d08d3c13'),
(32, 'shyna', NULL, 4, 'Guest', 'the food was good', '2026-02-11 02:54:33', '0976d88a76');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','','') NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `full_name`, `username`, `password`, `role`, `email`, `created_at`, `profile_image`) VALUES
(1, 'Ahmad', 'GibletChaser', '123Ahmad', 'admin', 'ahmad123@gmail.com', '2026-02-10 14:47:25', 'profile_698b44fdee22d1.80556718.jpg'),
(2, 'Ali', 'staff01', '123', 'staff', 'ali@company.com', '2026-01-11 17:43:19', NULL),
(3, 'Akmal Harith', 'maljauAdmin', '123Akmal', 'admin', 'akmal123@gmail.com', '2026-02-10 08:30:17', 'profile_698aec998b21b9.15317313.jpg'),
(4, 'Syed Azrul', 'SyedAzrul', 'roy', 'staff', 'azrul@company.com', '2026-02-11 03:17:59', 'profile_698bf49c62a8c3.77311945.jpg'),
(5, 'Shyna Yip How Yee', 'shynaStaff', 'shyna123', 'staff', 'shyna@company.com', '2026-02-11 03:19:21', 'profile_698bdc044fcad8.12497310.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_category_name` (`name`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`name`),
  ADD UNIQUE KEY `Date of Birth` (`name`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu_category` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stripe_session` (`stripe_session_id`),
  ADD KEY `idx_status` (`payment_status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_delete_code` (`delete_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
