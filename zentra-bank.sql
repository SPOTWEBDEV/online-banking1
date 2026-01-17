-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2026 at 02:31 PM
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
-- Database: `zentra-bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'admin', 'admin@admin.com', 'admin@admin.com');

-- --------------------------------------------------------

--
-- Table structure for table `loan_requests`
--

CREATE TABLE `loan_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `loan_amount` decimal(15,2) NOT NULL,
  `loan_duration` int(11) NOT NULL COMMENT 'Duration in months',
  `loan_reason` text NOT NULL,
  `monthly_income` decimal(15,2) NOT NULL,
  `employment_status` varchar(50) NOT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(30) DEFAULT NULL,
  `interest_rate` decimal(5,2) DEFAULT 0.00,
  `total_payable` decimal(15,2) DEFAULT 0.00,
  `status` enum('pending','approved','rejected','paid') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_requests`
--

INSERT INTO `loan_requests` (`id`, `user_id`, `loan_amount`, `loan_duration`, `loan_reason`, `monthly_income`, `employment_status`, `bank_name`, `account_number`, `interest_rate`, `total_payable`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 500.00, 3, 'rooot', 5000.00, 'self-employed', 'opay', '7019855552', 5.00, 575.00, 'pending', '2026-01-16 11:38:33', '2026-01-16 11:38:33'),
(2, 1, 500.00, 3, 'rooot', 5000.00, 'self-employed', 'opay', '7019855552', 5.00, 575.00, 'pending', '2026-01-16 11:40:15', '2026-01-16 11:40:15'),
(3, 1, 500.00, 3, 'rooot', 5000.00, 'self-employed', 'opay', '7019855552', 5.00, 575.00, 'pending', '2026-01-16 11:42:02', '2026-01-16 11:42:02'),
(4, 1, 500.00, 3, 'rooot', 5000.00, 'self-employed', 'opay', '7019855552', 5.00, 575.00, 'pending', '2026-01-16 14:04:26', '2026-01-16 14:04:26');

-- --------------------------------------------------------

--
-- Table structure for table `payment_account`
--

CREATE TABLE `payment_account` (
  `id` int(11) NOT NULL,
  `type` enum('bank','crypto') NOT NULL,
  `routing_number` varchar(50) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `bank_name` varchar(255) NOT NULL,
  `fullname` varchar(150) DEFAULT NULL,
  `network` varchar(20) DEFAULT NULL,
  `wallet_address` text DEFAULT NULL,
  `label` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_account`
--

INSERT INTO `payment_account` (`id`, `type`, `routing_number`, `account_number`, `bank_name`, `fullname`, `network`, `wallet_address`, `label`, `created_at`) VALUES
(1, 'crypto', NULL, NULL, '', NULL, 'BTC', 'nfnfnnfjmfmfnndnr345678jnvfgjsmbd', 'My eth wallet', '2026-01-17 13:16:04'),
(2, 'bank', '12345678909', '22669056778', 'Growth Bank', 'Ezea Ugochukwu', NULL, NULL, NULL, '2026-01-17 13:20:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_profile` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `loan_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `crypto_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `virtual_card_balance` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `user_profile`, `created_at`, `balance`, `loan_balance`, `crypto_balance`, `virtual_card_balance`) VALUES
(1, 'Ayogu Chimezie', 'ayoguchimezie00@gmail.com', '$2y$10$3TDQcP9cgdC812dL4L89P.Ih6KRnDso5o27O.ufH5mE2/zThcN1si', '/images/avatar/profile_696a3fcc9a25d0.85272167.jpeg', '2026-01-15 13:11:50', 0.00, 0.00, 0.00, 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_requests`
--
ALTER TABLE `loan_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payment_account`
--
ALTER TABLE `payment_account`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `loan_requests`
--
ALTER TABLE `loan_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_account`
--
ALTER TABLE `payment_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loan_requests`
--
ALTER TABLE `loan_requests`
  ADD CONSTRAINT `loan_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
