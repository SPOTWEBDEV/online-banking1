-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2026 at 11:33 AM
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
-- Database: `zentra_bank`
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
-- Table structure for table `bank_list`
--

CREATE TABLE `bank_list` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank_list`
--

INSERT INTO `bank_list` (`id`, `name`) VALUES
(5, 'Accion Opportunity Fund'),
(6, 'Ally Bank'),
(7, 'American Express National Bank'),
(8, 'Bank of America'),
(9, 'BBVA USA'),
(10, 'Capital One'),
(11, 'Charles Schwab Bank'),
(12, 'Chase Bank'),
(13, 'Citibank'),
(14, 'Citizens Bank'),
(15, 'Fifth Third Bank'),
(16, 'First Citizens Bank'),
(17, 'First Horizon Bank'),
(18, 'Goldman Sachs'),
(19, 'Grameen America'),
(20, 'Huntington National Bank'),
(21, 'Justine Petersen'),
(22, 'KeyBank'),
(23, 'M&T Bank'),
(24, 'Morgan Stanley'),
(25, 'Pacific Community Ventures'),
(26, 'PNC Bank'),
(27, 'Regions Bank'),
(28, 'Santander Bank'),
(29, 'TD Bank'),
(30, 'Truist Bank'),
(31, 'U.S. Bank'),
(32, 'Union Bank'),
(33, 'Wells Fargo'),
(34, 'Zions Bank'),
(36, 'Firstclass Bank');

-- --------------------------------------------------------

--
-- Table structure for table `bank_transfers`
--

CREATE TABLE `bank_transfers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `receiver_account_number` varchar(30) NOT NULL,
  `receiver_bank` varchar(150) NOT NULL,
  `receiver_name` varchar(255) NOT NULL,
  `routing_number` varchar(50) DEFAULT NULL,
  `swift_code` varchar(20) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `otp_code` varchar(10) NOT NULL,
  `otp_expires_at` datetime NOT NULL,
  `status` enum('pending','reversed','completed','failed','declined') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `narration` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank_transfers`
--

INSERT INTO `bank_transfers` (`id`, `user_id`, `receiver_account_number`, `receiver_bank`, `receiver_name`, `routing_number`, `swift_code`, `amount`, `otp_code`, `otp_expires_at`, `status`, `created_at`, `updated_at`, `narration`) VALUES
(4, 3, '48858589595', '', 'Ally Bank', '12345678909', '5895949', 50.00, '578355', '2026-01-21 13:37:49', 'completed', '2026-01-21 12:32:49', '2026-01-22 09:51:57', 'he ask for it'),
(5, 2, '48858589595', '', 'Bank of America', '12345678909', '5895949', 1000.00, '978348', '2026-01-22 07:11:51', 'completed', '2026-01-22 06:06:51', '2026-01-22 06:14:20', 'he ask for it'),
(6, 2, '48858589595', 'American Express National Bank', 'Ezea Ugochukwu micheal', '12345678909', '5895949', 300.00, '344672', '2026-01-22 10:12:33', 'pending', '2026-01-22 09:07:33', '2026-01-22 09:07:33', 'he ask for it...');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','declined','failed') DEFAULT 'pending',
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deposits`
--

INSERT INTO `deposits` (`id`, `user_id`, `type_id`, `amount`, `status`, `date`) VALUES
(19, 1, 1, 100.00, 'declined', '2026-01-18 23:00:00'),
(20, 3, 2, 100.00, 'pending', '2023-06-20 23:00:00'),
(21, 3, 2, 223.00, 'declined', '2026-01-21 15:01:20');

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_id` varchar(100) NOT NULL,
  `amount_invested` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed','declined') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`id`, `user_id`, `plan_id`, `amount_invested`, `created_at`, `status`) VALUES
(12, 2, '1', 200.00, '2026-01-22 10:03:41', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `investment_plans`
--

CREATE TABLE `investment_plans` (
  `id` int(11) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `duration` int(11) NOT NULL,
  `profit_per_day` decimal(10,2) NOT NULL,
  `total_profit` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investment_plans`
--

INSERT INTO `investment_plans` (`id`, `plan_name`, `duration`, `profit_per_day`, `total_profit`, `created_at`) VALUES
(1, 'GOLD PLAN', 100, 100.00, 10000.00, '2026-01-21 00:38:05');

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
  `paid` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected','paid') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_requests`
--

INSERT INTO `loan_requests` (`id`, `user_id`, `loan_amount`, `loan_duration`, `loan_reason`, `monthly_income`, `employment_status`, `bank_name`, `account_number`, `interest_rate`, `total_payable`, `paid`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 500.00, 3, 'rooot', 5000.00, 'self-employed', 'opay', '7019855552', 5.00, 575.00, '', 'pending', '2026-01-16 11:38:33', '2026-01-16 11:38:33'),
(2, 1, 500.00, 3, 'rooot', 5000.00, 'self-employed', 'opay', '7019855552', 5.00, 575.00, '', 'pending', '2026-01-16 11:40:15', '2026-01-16 11:40:15'),
(3, 1, 500.00, 3, 'rooot', 5000.00, 'self-employed', 'opay', '7019855552', 5.00, 575.00, '', 'pending', '2026-01-16 11:42:02', '2026-01-16 11:42:02'),
(4, 1, 500.00, 3, 'rooot', 5000.00, 'self-employed', 'opay', '7019855552', 5.00, 575.00, '', 'pending', '2026-01-16 14:04:26', '2026-01-16 14:04:26'),
(5, 2, 4000.00, 3, 'school fees', 30000.00, 'self-employed', 'Growth Bank', '22669056778', 5.00, 4600.00, '', 'pending', '2026-01-21 00:57:15', '2026-01-21 00:57:15');

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
  `virtual_card_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `limits` varchar(255) NOT NULL DEFAULT '5000',
  `status` enum('pending','suspended','active') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `user_profile`, `created_at`, `balance`, `loan_balance`, `crypto_balance`, `virtual_card_balance`, `limits`, `status`) VALUES
(1, 'Ayogu Chimezie', 'ayoguchimezie00@gmail.com', '$2y$10$3TDQcP9cgdC812dL4L89P.Ih6KRnDso5o27O.ufH5mE2/zThcN1si', '/images/avatar/profile_696a3fcc9a25d0.85272167.jpeg', '2026-01-15 13:11:50', 0.00, 0.00, 0.00, 0.00, '5000', 'active'),
(2, 'Ezea Ugochukwu micheal', 'spotwebdev.com@gmail.com', '$2y$10$.XTST3H2SnvIc8gGMGTL3.dDKh1Mnd0uInDm.9K.f.wd9/rZBe29y', NULL, '2026-01-20 23:15:35', 1000.00, 200.00, 0.00, 0.00, '5000', 'active'),
(3, 'jenny rose', 'jennyrose@gmail.com', '$2y$10$Ky0ZxlH/cppRIhUquEsomuUsrU1vpO1XmBQhuhWaOsfz2fpEtNvIa', NULL, '2026-01-21 11:26:02', 20.00, 0.00, 0.00, 0.00, '5000', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `which_account` varchar(100) NOT NULL,
  `status` enum('pending','approved','failed') DEFAULT 'pending',
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `account_number` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`id`, `user_id`, `amount`, `which_account`, `status`, `date`, `account_number`, `account_name`, `bank_name`) VALUES
(1, 1, 300.00, 'BTC', 'pending', '2026-01-18 00:06:23', '', '', ''),
(2, 3, 14.00, 'balance', 'pending', '2026-01-21 13:47:54', '22669056778', 'ugochukwu micheal', 'Growth Bank'),
(3, 3, 2.00, 'balance', 'approved', '2026-01-21 13:48:46', '8108833188', 'ugochukwu micheal', 'Opay'),
(4, 2, 100.00, 'loan_balance', 'failed', '2026-01-22 06:43:28', '22669056778', 'ugochukwu micheal', 'Growth Bank');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_list`
--
ALTER TABLE `bank_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_transfers`
--
ALTER TABLE `bank_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investment_plans`
--
ALTER TABLE `investment_plans`
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
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_list`
--
ALTER TABLE `bank_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `bank_transfers`
--
ALTER TABLE `bank_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `investment_plans`
--
ALTER TABLE `investment_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `loan_requests`
--
ALTER TABLE `loan_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payment_account`
--
ALTER TABLE `payment_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
