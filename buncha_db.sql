-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 08:27 AM
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
-- Database: `buncha_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,0) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `order_date`, `status`) VALUES
(1, 1, 155000, '2025-05-20 04:51:32', 'pending'),
(2, 1, 120000, '2025-05-20 04:53:42', 'pending'),
(3, 2, 40000, '2025-05-20 04:59:15', 'pending'),
(4, 1, 175000, '2025-05-20 05:13:21', 'pending'),
(5, 1, 125000, '2025-05-20 05:51:40', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_order` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price_at_order`) VALUES
(1, 1, 1, 3, 50000),
(2, 1, 4, 1, 5000),
(3, 2, 1, 1, 50000),
(4, 2, 2, 1, 30000),
(5, 2, 3, 1, 40000),
(6, 3, 3, 1, 40000),
(7, 4, 1, 2, 50000),
(8, 4, 2, 1, 30000),
(9, 4, 3, 1, 40000),
(10, 4, 4, 1, 5000),
(11, 5, 1, 1, 50000),
(12, 5, 2, 1, 30000),
(13, 5, 3, 1, 40000),
(14, 5, 4, 1, 5000);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `created_at`) VALUES
(1, 'Bún chả đặc biệt', 'Bún chả đặc biệt với nhiều thịt nướng và nem rán giòn tan.', 50000, 'https://th.bing.com/th/id/OIP.I8njRMp9M6I25q8ewus5BQHaE8?rs=1&pid=ImgDetMain', '2025-05-16 10:43:50'),
(2, 'Nem rán', 'Nem rán truyền thống, thơm ngon, nóng hổi.', 30000, 'https://s3.amazonaws.com/images.ecwid.com/images/28464427/1401515995.jpg', '2025-05-16 10:43:50'),
(3, 'Bún chả thường', 'Bún chả thường với thịt nướng vừa ăn.', 40000, 'https://static-images.vnncdn.net/files/publish/bun-cha-o-ha-noi-co-dac-diem-thuong-dong-khach-vao-buoi-trua-nhat-la-khoang-11h-den-13h-93864d73ebdd43aab5f0580281f23a31.jpg', '2025-05-16 10:43:50'),
(4, 'Trà đá', 'Trà đá mát lạnh, giải khát.', 5000, 'https://i.pinimg.com/originals/62/b9/a7/62b9a7ae61e3da88f7d99490cf950223.jpg', '2025-05-16 10:43:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `username`, `password`, `is_admin`, `created_at`) VALUES
(1, 'Nguyen Duc Thanh', 'thanhnguyen.tnnn25@gmail.com', 'CWIP', '$2y$10$7w821AMSOqqQniuZKmRxie5w0srgQhEObK8F9DNsREHkxWCOMMNb2', 0, '2025-05-20 04:43:05'),
(2, 'Duc Thanh', 'giang@gmail.com', 'admin', '$2y$10$jUdZ/0vD3qe6OohEvtRQxuXvh0Ny8WfLm8Gx3odUomw/K1EeOYbhy', 1, '2025-05-20 04:57:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

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
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
