-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2025 at 12:51 AM
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
-- Database: `manajemen_inventaris_toko`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `clock_in` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `clock_out` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `user_id`, `clock_in`, `clock_out`, `created_at`, `updated_at`) VALUES
(9, 1, '2025-07-03 20:43:01', '2025-07-03 20:43:01', '2025-07-03 13:42:50', '2025-07-03 13:43:01'),
(10, 1, '2025-07-03 20:54:14', '2025-07-03 20:54:14', '2025-07-03 13:54:09', '2025-07-03 13:54:14'),
(11, 1, '2025-07-03 20:54:27', '2025-07-03 20:54:27', '2025-07-03 13:54:23', '2025-07-03 13:54:27'),
(12, 1, '2025-07-03 20:54:34', '2025-07-03 20:54:34', '2025-07-03 13:54:31', '2025-07-03 13:54:34'),
(13, 1, '2025-07-03 20:54:43', '2025-07-03 20:54:43', '2025-07-03 13:54:40', '2025-07-03 13:54:43'),
(14, 1, '2025-07-03 20:54:52', '2025-07-03 20:54:52', '2025-07-03 13:54:48', '2025-07-03 13:54:52'),
(15, 1, '2025-07-03 20:56:54', '2025-07-03 20:56:54', '2025-07-03 13:56:50', '2025-07-03 13:56:54'),
(16, 1, '2025-07-03 20:57:02', '2025-07-03 20:57:02', '2025-07-03 13:56:58', '2025-07-03 13:57:02'),
(17, 1, '2025-07-03 20:57:17', '2025-07-03 20:57:17', '2025-07-03 13:57:06', '2025-07-03 13:57:17'),
(18, 1, '2025-07-03 20:57:24', '2025-07-03 20:57:24', '2025-07-03 13:57:21', '2025-07-03 13:57:24'),
(19, 1, '2025-07-03 20:57:32', '2025-07-03 20:57:32', '2025-07-03 13:57:28', '2025-07-03 13:57:32');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Kopi Panas'),
(2, 'Kopi Dingin'),
(3, 'Makanan Berat'),
(4, 'Makanan Ringan'),
(5, 'Minuman Non-Kopi'),
(6, 'Juice');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_amount` bigint(20) NOT NULL,
  `amount_paid` bigint(20) NOT NULL,
  `change` bigint(20) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cash',
  `status` varchar(255) NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `invoice_number`, `user_id`, `total_amount`, `amount_paid`, `change`, `payment_method`, `status`, `created_at`, `updated_at`) VALUES
(1, 'INV-1751301377-VELL', 1, 46000, 46000, 0, 'QRIS', 'Completed', '2025-06-30 09:36:17', '2025-06-30 09:36:17'),
(2, 'INV-1751302293-BBY7', 1, 20700, 20700, 0, 'EDC BRI', 'Completed', '2025-06-30 09:51:33', '2025-06-30 09:51:33'),
(3, 'INV-1751302308-V5RP', 1, 9200, 9200, 0, 'Cash', 'Completed', '2025-06-30 09:51:48', '2025-06-30 09:51:48'),
(4, 'INV-1751302470-JTAP', 1, 28750, 300000, 271250, 'Cash', 'Completed', '2025-06-30 09:54:30', '2025-06-30 09:54:30'),
(5, 'INV-1751386865-WQD9', 1, 9200, 9200, 0, 'Qris BRI', 'Completed', '2025-07-01 09:21:05', '2025-07-01 09:21:05'),
(6, 'INV-1751399490-CKI7', 1, 58650, 60000, 1350, 'Cash', 'Completed', '2025-07-01 12:51:30', '2025-07-01 12:51:30'),
(7, 'INV-1751437877-RZEY', 1, 78200, 80000, 1800, 'Cash', 'Completed', '2025-07-01 23:31:17', '2025-07-01 23:31:17'),
(8, 'INV-1751484087-GKFK', 1, 23000, 200000, 177000, 'Cash', 'Completed', '2025-07-02 12:21:27', '2025-07-02 12:21:27'),
(9, 'INV-1751484170-PMQC', 1, 23000, 23000, 0, 'Qris BRI', 'Completed', '2025-07-02 12:22:50', '2025-07-02 12:22:50'),
(10, 'INV-1751525484-ALVW', 1, 74750, 80000, 5250, 'Cash', 'Completed', '2025-07-02 23:51:24', '2025-07-02 23:51:24'),
(11, 'INV-1751577947-Y6RA', 2, 32200, 32200, 0, 'Qris BCA', 'Completed', '2025-07-03 14:25:47', '2025-07-03 14:25:47'),
(12, 'INV-1751580736-95NT', 2, 32200, 32200, 0, 'Qris BRI', 'Completed', '2025-07-03 15:12:16', '2025-07-03 15:12:16'),
(13, 'INV-1751581170-KQTZ', 2, 34500, 34500, 0, 'Qris MANDIRI', 'Completed', '2025-07-03 15:19:30', '2025-07-03 15:19:30');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `subtotal` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `subtotal`) VALUES
(1, 1, 2, 1, 22000, 22000),
(2, 1, 4, 1, 18000, 18000),
(3, 2, 4, 1, 18000, 18000),
(4, 3, 5, 1, 8000, 8000),
(5, 4, 3, 1, 25000, 25000),
(6, 5, 5, 1, 8000, 8000),
(7, 6, 3, 1, 25000, 25000),
(8, 6, 4, 1, 18000, 18000),
(9, 6, 5, 1, 8000, 8000),
(10, 7, 3, 2, 25000, 50000),
(11, 7, 4, 1, 18000, 18000),
(12, 8, 6, 1, 20000, 20000),
(13, 9, 6, 1, 20000, 20000),
(14, 10, 2, 1, 22000, 22000),
(15, 10, 3, 1, 25000, 25000),
(16, 10, 4, 1, 18000, 18000),
(17, 11, 5, 1, 8000, 8000),
(18, 11, 6, 1, 20000, 20000),
(19, 12, 5, 1, 8000, 8000),
(20, 12, 6, 1, 20000, 20000),
(21, 13, 2, 1, 22000, 22000),
(22, 13, 5, 1, 8000, 8000);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `category_id`, `supplier_id`, `price`, `stock`) VALUES
(1, 'Espresso', 1, 1, 15000, 100),
(2, 'Ice Coffee Latte', 2, 4, 22000, 77),
(3, 'Nasi Goreng Ayam', 3, 2, 25000, 45),
(4, 'Kentang Goreng', 4, 2, 18000, 115),
(5, 'Es Teh Manis', 5, 5, 8000, 194),
(6, 'Machiato', 2, 6, 20000, 46),
(12, 'Avocado Juice', 6, 7, 18000, 40),
(17, 'Nasi Goreng ayam', 3, 2, 20000, 20),
(18, 'Nasi Goreng sapi', 3, 2, 23000, 20),
(19, 'Coffe Late', 1, 6, 22000, 80),
(20, 'Kombucha Pear', 5, 7, 18000, 30);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('nNwOAA4ihrvd6Fw1e2TyyFkgMj2lG13L3kf7yuVS', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRzJQQko3YWxkTml5M0pwRkNyb0c0MVlKb0VzVWJxNTFJMkRTY2E1cSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1751581183),
('OOc6gZ0EuN6AsucAvlM4Bye9vmZsOe0gsHHpRLVo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZTg1TWU2Y2s4QkJ3S3h0aEJYVHVsVHFkak85UTZqaXhrcnBzT1lSVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1751578197);

-- --------------------------------------------------------

--
-- Table structure for table `stock_ins`
--

CREATE TABLE `stock_ins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_ins`
--

INSERT INTO `stock_ins` (`id`, `product_id`, `supplier_id`, `user_id`, `quantity`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 12, 7, 1, 20, 'tambah stock jus alpukat', '2025-07-02 14:13:17', '2025-07-02 14:13:17');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `phone`, `address`) VALUES
(1, 'PT. Santos Jaya Abadi', '081234567890', 'Jl. Kopi No. 1, Jakarta'),
(2, 'CV. Sumber Pangan', '081234567891', 'Jl. Pangan No. 2, Bandung'),
(3, 'Toko Plastik Bahagia', '081234567892', 'Jl. Gelas No. 3, Surabaya'),
(4, 'Peternak Susu Segar Lembang', '081234567893', 'Jl. Sapi No. 4, Lembang'),
(5, 'Pemasok Teh Nusantara', '081234567894', 'Jl. Teh No. 5, Yogyakarta'),
(6, 'Cofe sejahtera', '082567382660', 'JL.Wonokromo.Surabaya'),
(7, 'Kombucha Djava', '082567555443', 'Kota Baru Yogyakarta');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ardha', 'ardhacandidate@gmail.com', NULL, '$2y$12$Hv9eduPRivWrWZfYzhFH5.GIkXTZW9DhFKN9b46wcdmwhjdXMgVAK', NULL, '2025-06-28 09:22:01', '2025-06-28 09:22:01'),
(2, 'jojo', 'jojo@gmail.com', NULL, '$2y$12$88i7urA83.HHnPJf1GTI5.643yNvgyj0PK5lwOUQcjXJwYZxVuAzC', NULL, '2025-07-03 14:12:28', '2025-07-03 14:12:28'),
(3, 'SuperAdmin', 'superadmin@gmail.com', NULL, '$2y$12$AJIJn3zk8xi5DppHddzV3eL9TXVrN9GNAeuIN3ENCB6PiN.lEjnKK', NULL, '2025-07-03 15:06:49', '2025-07-03 15:06:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_ins`
--
ALTER TABLE `stock_ins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_ins_product_id_foreign` (`product_id`),
  ADD KEY `stock_ins_supplier_id_foreign` (`supplier_id`),
  ADD KEY `stock_ins_user_id_foreign` (`user_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `stock_ins`
--
ALTER TABLE `stock_ins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_ins`
--
ALTER TABLE `stock_ins`
  ADD CONSTRAINT `stock_ins_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_ins_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_ins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
