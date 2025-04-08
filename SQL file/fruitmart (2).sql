-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2024 at 11:39 AM
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
-- Database: `fruitmart`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$q/NoUl.zGIxALKjdOmeiCOXzhz1Dj2uWqZZDgkl1EnPrgb9N6/PjW');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'SHINDE AJINKYA YUVRAJ', 'ajinkyashinde6099@gmail.com', 'fgh', '2024-11-17 17:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`) VALUES
(2, 'pineapple', 120.00, 'E:\\xampp\\htdocs\\Fruit_Mart/uploads/pineapple.jpg'),
(4, 'kiwi', 100.00, 'E:\\xampp\\htdocs\\Fruit_Mart/uploads/strawberry.jpg'),
(6, 'Banana', 60.00, 'E:\\xampp\\htdocs\\Fruit_Mart/uploads/bananas.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `items` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `order_number`, `items`, `total_amount`, `date_time`) VALUES
(1, 'order_67383727c0b93', 'Kiwi (x2)', 4.00, '2024-11-16 07:09:43'),
(2, 'order_67387d92ba591', 'pineapple (x2), Kiwi (x1), strawberry  (x1)', 176.00, '2024-11-16 12:10:10'),
(3, 'order_673998c5be3b2', 'Banana (x1), Kiwi (x1)', 62.00, '2024-11-17 08:18:29'),
(4, 'order_6739b63be75cb', 'pineapple (x1)', 12.00, '2024-11-17 10:24:11'),
(5, 'order_6739c256b7854', 'kiwi (x1), Banana (x1), pineapple (x1)', 172.00, '2024-11-17 11:15:50'),
(6, 'order_6739c2ddcb7d3', 'kiwi (x1)', 100.00, '2024-11-17 11:18:05'),
(7, 'order_6739c7705e4c6', 'pineapple (x1), kiwi (x1)', 220.00, '2024-11-17 11:37:36'),
(8, 'order_6739d172f2ffd', 'kiwi (x1)', 100.00, '2024-11-17 12:20:18'),
(9, 'order_6739d2a41b9d5', 'Banana (x1)', 60.00, '2024-11-17 12:25:24'),
(10, 'order_6739e938d5014', 'Banana (x1)', 60.00, '2024-11-17 14:01:44'),
(11, 'order_673a04674334e', 'pineapple (x2)', 240.00, '2024-11-17 15:57:43'),
(12, 'order_673a15dd16c53', 'pineapple (x1)', 120.00, '2024-11-17 17:12:13'),
(13, 'order_673a1983a13af', 'kiwi (x2)', 200.00, '2024-11-17 17:27:47'),
(14, 'order_673a19df57d9f', 'kiwi (x1)', 100.00, '2024-11-17 17:29:19'),
(15, 'order_673a2c642e3fd', 'kiwi (x2)', 200.00, '2024-11-17 18:48:20');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `subscribed_at`) VALUES
(1, 'sotari555@gmail.com', '2024-11-16 05:55:45'),
(2, 'ajinkya@gmail.com', '2024-11-16 10:51:05'),
(3, 'asd@asd.com', '2024-11-16 11:14:13'),
(4, 'bhakti@gmail.com', '2024-11-17 14:55:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'sumit', '$2y$10$AkLanLNWxRPI4Kbp8JKGnOKpXp6iJNjNpCkPidR0MH/w0sjxNI/Eu'),
(3, 'xyz', '$2y$10$UJ3N1eAjth.L7b2G28N8JeLk9atHVpkJwyr1U1.mEnLdMXr6np.y2'),
(5, 'pranav', '$2y$10$qnP49j.gZhT4Jq5vErU.u.D7DOPMZW.FiitK6g3pBX5sGrUsiQ6rq'),
(6, 'pranavm', '$2y$10$80MumRtJDe9Qup15/P5EiOABs1mx4S4B.oiE3fKSknteJYp2tU4WS'),
(7, 'bhakti', '$2y$10$Q7XsqXMOgQEIRA8J8NQic.zT9gjI2sy.5IoLY4ipCmQrQhD1DZ9Z6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
