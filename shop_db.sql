-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2024 at 06:18 AM
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
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(255) NOT NULL,
  `pid` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(73, 1003, 28, 'Silk Ombre       ', '670', '1', 'Hippies.jpg'),
(74, 1003, 18, 'Pins', '100', '1', 'acc2.jpeg'),
(160, 0, 29, 'Hijab Clip Pin', '200', '1', 'acc.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(2, 1, 'sadia', 'sadia@email.com', '333', 'hi hello.'),
(4, 2, 'sadia', 'sadia@email.com', '333', 'hi hello.'),
(10, 0, 'Masnin', 'masninjuairia10@gmail.com', '', 'You need to update your collection'),
(11, 0, '', '', '', ''),
(12, 0, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `discount` float NOT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL,
  `product_ids` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('current','old') DEFAULT 'current',
  `curr_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `title`, `description`, `discount`, `valid_from`, `valid_to`, `product_ids`, `created_at`, `status`, `curr_date`) VALUES
(7, 'Summer Sale - 20% OFF', 'Save 20% for Next 10 Days and Enjoy Summer', 20, '2024-08-01', '2024-08-10', '29,15,30,19,31', '2024-07-31 16:22:48', 'old', '2024-08-08'),
(9, 'Winter Clearance - 40%', 'Get Our Winter Collection on 40% OFF for the Next SEVEN Days', 40, '2024-07-24', '2024-07-31', '28,12,31', '2024-07-31 17:47:01', 'old', '2024-08-08'),
(12, 'Buy One Get One Free', 'Save 50% OFF on Selected Products ', 50, '2024-07-25', '2024-09-05', '5,12,11', '2024-07-31 18:29:16', 'old', '2024-08-08'),
(20, 'Buy One Get One Free', '50%', 50, '2024-07-30', '2024-08-06', '12,5', '2024-08-07 15:56:56', 'old', '2024-08-08'),
(23, 'Sale on accessories', 'Get 10% Discount', 10, '2024-09-20', '2024-09-27', '19,29,18', '2024-09-20 15:39:55', 'current', '2024-09-20'),
(24, 'BOGO', 'Buy One Get One Free', 50, '2024-09-23', '2024-10-07', '5,11,12', '2024-09-23 16:43:29', 'current', '2024-09-23'),
(25, 'Winter Clearance - 40%', 'Collect Our winter Products', 40, '2024-09-17', '2024-10-01', '31,28,30,15', '2024-09-23 16:52:19', 'current', '2024-09-23');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `total_products` varchar(255) NOT NULL,
  `total_price` varchar(255) NOT NULL,
  `placed_on` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(1, 1, 'sadia', '23456789', 'sadia@email.com', 'paytm', 'mirpur 10', '3', '300', '1/1/24', 'pending'),
(2, 2, 'sadia', '23456789', 'sadia@email.com', 'paytm', 'mirpur 10', '3', '300', '1/1/24', 'pending'),
(3, 0, 'sadia', '2', 'sadia@gmail.com', 'bkash', 'mirpur 10', '1', '300', '1/1/24', 'pending'),
(8, 1, 'Sadia', '01234567890', 'sadiaislam13@gmail.com', '', 'flat no. ,,,,Bangladesh,', '', '0', '28-Jul-2024', 'pending'),
(10, 1, 'Sadia', '02801111111', 'sadiaislam13@gmail.com', '', 'flat no. ,,,,Bangladesh,', '', '0', '28-Jul-2024', ''),
(12, 1003, 'Masnin', '02801111111', 'masnin@gmail.com', '', 'flat no. ,,,,Bangladesh,', 'Silk hiijab  (1), hijab magnets (1), Georgette Hijab (1)', '1260', '28-Jul-2024', 'complete'),
(13, 1003, 'Masnin', '02801111111', 'masnin@gmail.com', '', 'flat no. ,,,,Bangladesh,', 'Hijab Clip Pin (10), Silk Ombre        (1)', '2670', '28-Jul-2024', 'pending'),
(14, 1008, 'Masnin', '02801111111', 'masninjuairia10@gmail.com', 'Cash on Delivery', 'flat no. ,Mirpur,Dhaka,,,', 'Silk Ombre        (1), Pins (3), Crepe Hiijab (2)', '1870', '01-Aug-2024', 'pending'),
(18, 1008, 'Masnin Juairia', '02801111111', 'masninjuairia10@gmail.com', 'Cash on Delivery', 'flat no. ,Mirpur,Dhaka,,Bangladesh,', 'Crepe Hiijab (1), Chiffon Laced Hijab (3), Silk hiijab  (2)', '2720', '01-Aug-2024', 'pending'),
(19, 1008, 'Masnin Juairia', '02801111111', 'masninjuairia10@gmail.com', '', 'flat no. ,Mirpur,Dhaka,,Bangladesh,', 'Silk Ombre        (4), Pins (2), Crepe Hiijab (1)', '3330', '01-Aug-2024', 'pending'),
(20, 1008, 'Masnin', '01234568903', 'masninjuairia10@gmail.com', '', 'flat no. 5B,Mirpur,Dhaka,,Bangladesh,221', 'Crepe Hiijab (1), hijab magnets (1), Chiffon Laced Hijab (1), Organza hiijab   (1)', '1660', '24-Sep-2024', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(255) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_detail` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `product_quantity`, `product_detail`, `image`) VALUES
(5, '   Georgette Hijab  ', 350, 30, 'Georgette & Comfy (Pink) ', 'images (1).jpg'),
(11, 'Georgette Hijab               ', 350, 32, 'Georgette (Mauve)  ', 'lavendar hijab.png'),
(12, 'Georgette Hijab ', 350, 15, 'Georgette (Blue)    ', 'skycluechiffon.jpg'),
(15, 'Cotton Ombre Hijab', 450, 0, 'Cotton (Ombre)', 'Ombre pashmina.jpg'),
(17, 'Organza hiijab  ', 460, 27, 'Organza Hijab ', 'product_17 .jpg'),
(18, 'Pins', 100, 20, 'Hijab pins accessory', 'acc2.jpeg'),
(19, 'hijab magnets', 300, 44, '6 pieces accessory magnets', 'acc1.jpeg'),
(28, 'Silk Ombre        ', 670, 29, 'Ombre Pink         ', 'Hippies.jpg'),
(29, 'Hijab Clip Pin', 200, 20, 'Accessory Pin Clip', 'acc.jpeg'),
(30, 'Crepe Hiijab', 450, 49, 'Brown Crepe Hijab', 'download.jpg'),
(31, 'Chiffon Laced Hijab', 450, 26, 'Gorgeous Chiffon Hijab', 'Lace Floral Hem Chiffon Scarf.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(1, 'Sadia Islam', 'sadiaislaam13@gmail.com', 'sadia', 'user'),
(1000, 'sadiaisraat', 'sadiaisrat@gmail.com', 'sad', 'admin'),
(1003, 'masnin', 'masnin@gmail.com', 'masnin', 'user'),
(1007, 'sadiaa', 'sadia.cse@gmail.com', '1234', 'user'),
(1008, 'Masnin Juairia', 'masninjuairia10@gmail.com', 'mas', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `pid` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `pid`, `name`, `price`, `image`) VALUES
(20, 1, 0, '', '', ''),
(21, 1, 0, '', '', ''),
(21, 1, 0, '', '', ''),
(21, 1, 0, '', '', ''),
(21, 1, 0, '', '', ''),
(17, 1003, 0, 'Silk hiijab ', '460', 'Hand Dyed Silk Chiffon Scarf in Purple Fuchsia Plum Blue by ZMFelt, £17_50.jpg'),
(19, 0, 0, 'hijab magnets', '270', 'acc1.jpeg'),
(30, 0, 0, 'Crepe Hiijab', '270', 'download.jpg'),
(11, 0, 0, 'Georgette Hijab               ', '175', 'lavendar hijab.png'),
(29, 0, 0, 'Hijab Clip Pin', '180', 'acc.jpeg'),
(28, 0, 0, 'Silk Ombre        ', '402', 'Hippies.jpg'),
(19, 1008, 0, 'hijab magnets', '270', 'acc1.jpeg'),
(30, 1008, 0, 'Crepe Hiijab', '270', 'download.jpg'),
(18, 1008, 0, 'Pins', '90', 'acc2.jpeg'),
(17, 1008, 0, 'Organza hiijab  ', '460', 'product_17 .jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1009;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
