-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 26, 2023 at 10:08 AM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onlineorderingsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `delivery_date` date NOT NULL,
  `status` enum('pending','paid','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `delivery_date`, `status`, `total`) VALUES
(1, 1, '2023-12-16 11:40:46', '0000-00-00', 'delivered', '79.97'),
(2, 1, '2023-12-16 11:49:36', '0000-00-00', 'pending', '9.99'),
(3, 4, '2023-12-21 07:55:09', '0000-00-00', 'pending', '9.99'),
(4, 4, '2023-12-21 08:21:49', '0000-00-00', 'shipped', '0.00'),
(5, 4, '2023-12-21 08:22:30', '0000-00-00', 'shipped', '19.99'),
(6, 4, '2023-12-21 08:36:47', '0000-00-00', 'shipped', '19.99'),
(7, 4, '2023-12-21 08:37:09', '0000-00-00', 'shipped', '0.00'),
(8, 4, '2023-12-21 21:54:30', '0000-00-00', 'shipped', '160.00'),
(9, 4, '2023-12-22 10:50:45', '0000-00-00', 'delivered', '0.00'),
(10, 4, '2023-12-22 10:51:40', '0000-00-00', 'delivered', '9.99'),
(11, 4, '2023-12-22 15:07:46', '0000-00-00', 'delivered', '9.99'),
(12, 4, '2023-12-22 15:18:10', '0000-00-00', 'pending', '0.00'),
(13, 4, '2023-12-22 15:18:54', '0000-00-00', 'pending', '9.99'),
(14, 4, '2023-12-22 15:31:00', '0000-00-00', 'pending', '40.01'),
(15, 4, '2023-12-23 07:11:38', '0000-00-00', 'shipped', '59.99'),
(16, 4, '2023-12-23 10:57:25', '2023-12-28', 'shipped', '30.01'),
(17, 4, '2023-12-23 11:03:27', '2023-12-28', 'shipped', '30.01'),
(18, 4, '2023-12-23 11:03:34', '2023-12-28', 'shipped', '0.00'),
(22, 4, '2023-12-23 14:59:54', '2024-01-03', 'delivered', '0.00'),
(24, 4, '2023-12-23 15:03:43', '2024-01-07', 'delivered', '0.00'),
(28, 4, '2023-12-23 15:24:20', '2023-12-29', 'delivered', '29.98'),
(29, 4, '2023-12-23 19:01:24', '2024-01-04', 'pending', '0.00'),
(30, 4, '2023-12-23 19:35:25', '2023-12-29', 'pending', '160.00'),
(31, 6, '2023-12-23 21:13:39', '2024-01-05', 'pending', '89.99'),
(32, 6, '2023-12-23 23:02:28', '2024-01-03', 'pending', '89.99'),
(33, 4, '2023-12-25 21:02:08', '2024-01-04', 'pending', '9.99');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `order_details_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_details_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_details_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 2, 1, '19.99'),
(2, 1, 3, 2, '29.99'),
(3, 2, 1, 1, '9.99'),
(4, 3, 1, 1, '9.99'),
(5, 5, 2, 1, '19.99'),
(6, 6, 2, 1, '19.99'),
(7, 8, 8, 4, '40.00'),
(8, 10, 1, 1, '9.99'),
(9, 11, 1, 1, '9.99'),
(10, 13, 1, 1, '9.99'),
(11, 14, 6, 1, '20.02'),
(12, 14, 2, 1, '19.99'),
(13, 15, 8, 1, '40.00'),
(14, 15, 2, 1, '19.99'),
(15, 17, 1, 1, '9.99'),
(16, 17, 6, 1, '20.02'),
(26, 28, 1, 1, '9.99'),
(25, 28, 2, 1, '19.99'),
(27, 30, 9, 2, '80.00'),
(28, 31, 1, 1, '9.99'),
(29, 31, 9, 1, '80.00'),
(30, 32, 1, 1, '9.99'),
(31, 32, 9, 1, '80.00'),
(32, 33, 1, 1, '9.99');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `payment_type` enum('card','cash') NOT NULL,
  `card_holder_name` varchar(255) DEFAULT NULL,
  `card_number` varchar(19) DEFAULT NULL,
  `card_expiry` date DEFAULT NULL,
  `card_cvv` varchar(4) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_status` enum('processed','failed','pending') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`payment_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `payment_type`, `card_holder_name`, `card_number`, `card_expiry`, `card_cvv`, `payment_date`, `payment_status`) VALUES
(1, 1, 'cash', '', '', '0000-00-00', '', '2023-12-16 11:40:46', 'pending'),
(2, 2, 'cash', '', '', '0000-00-00', '', '2023-12-16 11:49:36', 'pending'),
(3, 3, 'cash', '', '', '0000-00-00', '', '2023-12-21 07:55:09', 'pending'),
(4, 4, 'cash', '', '', '0000-00-00', '', '2023-12-21 08:21:49', 'pending'),
(5, 5, 'cash', '', '', '0000-00-00', '', '2023-12-21 08:22:30', 'pending'),
(6, 6, 'cash', '', '', '0000-00-00', '', '2023-12-21 08:36:47', 'pending'),
(7, 7, 'cash', '', '', '0000-00-00', '', '2023-12-21 08:37:09', 'pending'),
(8, 8, 'cash', '', '', '0000-00-00', '', '2023-12-21 21:54:30', 'pending'),
(9, 9, 'cash', '', '', '0000-00-00', '', '2023-12-22 10:50:45', 'pending'),
(10, 10, 'cash', '', '', '0000-00-00', '', '2023-12-22 10:51:40', 'pending'),
(11, 11, 'cash', '', '', '0000-00-00', '', '2023-12-22 15:07:46', 'pending'),
(12, 12, 'cash', '', '', '0000-00-00', '', '2023-12-22 15:18:10', 'pending'),
(13, 13, 'cash', '', '', '0000-00-00', '', '2023-12-22 15:18:54', 'pending'),
(14, 14, 'cash', '', '', '0000-00-00', '', '2023-12-22 15:31:00', 'pending'),
(15, 15, 'card', 'sunitha', '1234567891234567', '0000-00-00', '112', '2023-12-23 07:11:38', 'pending'),
(16, 17, 'cash', '', '', '0000-00-00', '', '2023-12-23 11:03:27', 'pending'),
(17, 18, 'cash', '', '', '0000-00-00', '', '2023-12-23 11:03:34', 'pending'),
(18, 19, 'cash', '', '', '0000-00-00', '', '2023-12-23 11:04:49', 'pending'),
(19, 20, 'cash', '', '', '0000-00-00', '', '2023-12-23 13:37:30', 'pending'),
(20, 21, 'cash', '', '', '0000-00-00', '', '2023-12-23 14:48:17', 'pending'),
(21, 22, 'cash', '', '', '0000-00-00', '', '2023-12-23 14:59:54', 'pending'),
(22, 23, 'cash', '', '', '0000-00-00', '', '2023-12-23 15:00:55', 'pending'),
(23, 24, 'cash', '', '', '0000-00-00', '', '2023-12-23 15:03:43', 'pending'),
(24, 25, 'cash', '', '', '0000-00-00', '', '2023-12-23 15:04:29', 'pending'),
(25, 26, 'cash', '', '', '0000-00-00', '', '2023-12-23 15:06:53', 'pending'),
(26, 27, 'cash', '', '', '0000-00-00', '', '2023-12-23 15:21:55', 'pending'),
(27, 28, 'cash', '', '', '0000-00-00', '', '2023-12-23 15:24:20', 'pending'),
(28, 29, 'cash', '', '', '0000-00-00', '', '2023-12-23 19:01:24', 'pending'),
(29, 30, 'cash', '', '', '0000-00-00', '', '2023-12-23 19:35:25', 'pending'),
(30, 31, 'cash', '', '', '0000-00-00', '', '2023-12-23 21:13:39', 'pending'),
(31, 32, 'cash', '', '', '0000-00-00', '', '2023-12-23 23:02:28', 'pending'),
(32, 33, 'cash', '', '', '0000-00-00', '', '2023-12-25 21:02:08', 'pending'),
(33, 34, 'cash', '', '', '0000-00-00', '', '2023-12-26 09:21:49', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `stock_quantity` int DEFAULT '0',
  `category` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `image_path`, `stock_quantity`, `category`, `created_at`, `updated_at`) VALUES
(1, 'Musar', 'kashmir hand made Musar\r\n', '9.99', 'images/product1.png', 100, 'Musar', '2023-12-16 11:29:40', '2023-12-23 19:55:54'),
(2, 'Cap', 'Bangladish hand made', '19.00', 'images/product2.png', 150, 'Cap', '2023-12-16 11:29:40', '2023-12-23 19:56:45'),
(3, 'Musar', 'kashmir hand made Musar', '29.99', 'images/product3.png', 200, 'Musar', '2023-12-16 11:29:40', '2023-12-23 19:55:37'),
(6, 'cap', ' Oman hand made', '20.02', 'images/090f78046d9a9dca31ec3d5052bc0ab0.png', 100, 'Cap', '2023-12-21 21:32:05', '2023-12-23 19:57:47'),
(8, 'stick', 'stick with silver', '40.00', 'images/566e220001e1d1073cc8b6d0d534dd71.jpeg', 100, 'Stick', '2023-12-21 21:53:25', '2023-12-23 19:07:59'),
(9, 'Omani stick', 'stick with silver', '80.00', 'images/3c5fead1877667ed7803eaa89823fdd0.jpeg', 10, 'Stick', '2023-12-23 15:26:50', '2023-12-23 15:26:50');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`username`, `password`) VALUES
('admin', 'admin'),
('aaa', 'a11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `created_at`, `updated_at`) VALUES
(1, 'abd', '$2y$10$BKPXqxIKhQ.2JGdLAdosBu7/ENJxEFf24k9/8NAfU9ns26I597EBa', 'aayy@u', '2023-12-16 11:35:20', '2023-12-16 11:35:20'),
(2, 'aaa', '$2y$10$4JpRg25DOaBU.dn3A2R5M.Rz76DSfBukUZnq3zTVRxH3mdf4MuqwW', '16j21164@utas.stu.om', '2023-12-18 11:07:53', '2023-12-18 11:07:53'),
(3, 'aww', 'Aa123456', 'su@12', '2023-12-20 05:34:51', '2023-12-20 20:19:12'),
(4, 'aw', '$2y$10$YgbzcvR4g0R48ZOa1rWngerWtbmpb/UDLexjzxB1K.NBolxMvPubW', '16j21164@utas', '2023-12-20 12:56:53', '2023-12-20 12:56:53'),
(6, 'Abdullah', '$2y$10$7Af2smPrF9rYY1Dan4uXoOoqz4A15M2gsGf2Mz0eYgu70HTCE4fde', '5000@gmail.com', '2023-12-23 20:59:15', '2023-12-23 20:59:15'),
(7, 'Razan', '$2y$10$CQ8gpbx2J5miK7YQOlDOXebpEhFFNcjj0DBOIVdSLonLPkOg99pEy', 'razan@gmail.comS', '2023-12-24 09:28:37', '2023-12-24 09:28:37'),
(8, 'Rzan', '$2y$10$fsTTCqiFjT06rXr9nsrB8uvQtAa3u6OQRaogioy86iMsIuzmbYU4y', 'razan@gmail.com', '2023-12-24 09:30:54', '2023-12-24 09:30:54');

-- --------------------------------------------------------

--
-- Table structure for table `user_bank`
--

DROP TABLE IF EXISTS `user_bank`;
CREATE TABLE IF NOT EXISTS `user_bank` (
  `user_id` int NOT NULL,
  `bank_value` decimal(10,2) NOT NULL DEFAULT '10000.00',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_bank`
--

INSERT INTO `user_bank` (`user_id`, `bank_value`) VALUES
(1, '9910.04'),
(2, '10000.00'),
(3, '10000.00'),
(4, '8030.03'),
(6, '9460.06'),
(7, '10000.00'),
(8, '10000.00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
