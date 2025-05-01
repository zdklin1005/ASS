-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2025 at 03:05 PM
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
-- Database: `graduation_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `created_at`) VALUES
(1, 2, 19.90, '2025-04-30 20:20:58'),
(2, 2, 59.80, '2025-04-30 20:25:09'),
(3, 2, 19.80, '2025-04-30 22:49:55'),
(4, 3, 59.80, '2025-04-30 22:55:40'),
(5, 7, 119.80, '2025-05-01 17:49:56'),
(6, 7, 39.80, '2025-05-01 18:00:09'),
(7, 7, 99.90, '2025-05-01 18:02:34'),
(8, 7, 39.80, '2025-05-01 18:07:22'),
(9, 6, 119.80, '2025-05-01 18:16:16'),
(10, 6, 69.80, '2025-05-01 18:19:00'),
(11, 6, 99.90, '2025-05-01 18:21:54');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 2, 3, 1, 39.90),
(2, 2, 4, 1, 19.90),
(3, 3, 5, 2, 9.90),
(4, 4, 4, 1, 19.90),
(5, 4, 3, 1, 39.90),
(6, 5, 4, 1, 19.90),
(7, 5, 1, 1, 99.90),
(8, 6, 4, 2, 19.90),
(9, 7, 1, 1, 99.90),
(10, 8, 5, 1, 9.90),
(11, 8, 2, 1, 29.90),
(12, 9, 1, 1, 99.90),
(13, 9, 4, 1, 19.90),
(14, 10, 3, 1, 39.90),
(15, 10, 2, 1, 29.90),
(16, 11, 1, 1, 99.90);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `order_id`, `payment_method`, `amount`, `payment_date`) VALUES
(1, 1, 'tng', 19.90, '2025-04-30 20:20:58'),
(2, 2, 'credit', 59.80, '2025-04-30 20:25:09'),
(3, 3, 'paypal', 19.80, '2025-04-30 22:49:55'),
(4, 4, 'tng', 59.80, '2025-04-30 22:55:40'),
(5, 5, 'tng', 119.80, '2025-05-01 17:49:56'),
(6, 6, 'paypal', 39.80, '2025-05-01 18:00:09'),
(7, 7, 'credit', 99.90, '2025-05-01 18:02:34'),
(8, 8, 'tng', 39.80, '2025-05-01 18:07:22'),
(9, 9, 'paypal', 119.80, '2025-05-01 18:16:16'),
(10, 10, 'paypal', 69.80, '2025-05-01 18:19:00'),
(11, 11, 'paypal', 99.90, '2025-05-01 18:21:54');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`) VALUES
(1, 'Graduation Gown', 'Premium quality graduation gown with TARUMT logo.', 99.90, 'gown.jpg'),
(2, 'Graduation Cap', 'Elegant black cap for your convocation.', 29.90, 'cap.jpg'),
(3, 'TARUMT T-Shirt', 'Comfortable cotton t-shirt with university branding.', 39.90, 'tshirt.jpg'),
(4, 'Photo Frame', 'Wooden frame for graduation photos.', 19.90, 'frame.jpg'),
(5, 'Keychain', 'Metal keychain with TARUMT emblem.', 9.90, 'keychain.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'zx', 'zx@gmail.com', '$2y$10$iWNyrk1jTYj8erlzlV8hmeCeCs5coDCDjAsYKPK7qo87XZeyj5clu'),
(2, 'yj', 'yj09@gmail.com', '$2y$10$XGgDryaW.UXjDYQYFYpJOOKxypxzeD7El5/FMLCLSSViT/LGiRyWe'),
(3, 'ws', 'ws@gmail.com', '$2y$10$bsXoE1rdgs33z03WdjE9J.nWlv7rv9VEbdB0s8xyDdjoD1wuWcLTC'),
(4, 'kenny', 'kenny@gmail.com', '$2y$10$vYgbw9p1lSklyNfHDf6BgeU4k8vxN7g.adFlB.7PvnmYg/cqulomy'),
(6, 'yjjj', 'yjjj@gmail.com', '$2y$10$eKt.ebHDYrDv8rJRijSfi.WgOS.z2bDpMfiFkZVU.xpDO8UWx8tui'),
(7, 'abc', 'abc@gmail.com', '$2y$10$PzH9CmdF7jNvmuhWlB2idOVuwEqy9r14nPvkUOk2IGVYfA7bg1/6e');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
