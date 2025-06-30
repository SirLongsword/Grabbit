-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 06:31 PM
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
-- Database: `grabbit`
--

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `stock` int(11) DEFAULT 1,
  `category` enum('Sports','Electronics','Appliances','Furniture','Outdoors','Music','Books','Fashion') NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`id`, `title`, `location`, `price`, `description`, `stock`, `category`, `image_path`, `user_id`, `created_at`) VALUES
(1, 'Vintage Bicycle', 'New York', 150.00, 'Classic vintage bike.', 1, 'Sports', 'images/listings/bike1.jpg', 6, '2025-06-03 11:02:44'),
(2, 'Gaming Laptop', 'San Francisco', 1200.00, 'Gaming laptop with RTX 3070.', 1, 'Electronics', 'images/listings/laptop.jpg', 7, '2025-06-03 11:02:44'),
(3, 'Coffee Maker', 'Chicago', 75.50, 'Espresso coffee machine.', 3, 'Appliances', 'images/listings/coffeemaker.jpg', 8, '2025-06-03 11:02:44'),
(4, 'Office Chair', 'Los Angeles', 85.00, 'Ergonomic mesh chair.', 2, 'Furniture', 'images/listings/chair.jpg', 9, '2025-06-03 11:02:44'),
(5, 'Tent 4-Person', 'Denver', 180.00, 'Waterproof mountain tent.', 1, 'Outdoors', 'images/listings/tent.jpg', 10, '2025-06-03 11:02:44'),
(6, 'iPhone 13', 'Austin', 900.00, '128GB unlocked.', 5, 'Electronics', 'images/listings/iphone.jpg', 11, '2025-06-03 11:02:44'),
(7, 'Guitar', 'Nashville', 300.00, 'Electric guitar for beginners.', 1, 'Music', 'images/listings/guitar.jpg', 12, '2025-06-03 11:02:44'),
(8, 'Cookbook Set', 'Boston', 45.00, '10 cookbooks.', 2, 'Books', 'images/listings/cookbooks.jpg', 13, '2025-06-03 11:02:44'),
(9, 'Smart TV 50\"', 'Seattle', 700.00, '4K HDR smart TV.', 1, 'Electronics', 'images/listings/tv.jpg', 14, '2025-06-03 11:02:44'),
(10, 'Running Shoes', 'Portland', 60.00, 'Men size 10.', 2, 'Fashion', 'images/listings/shoes.jpg', 15, '2025-06-03 11:02:44');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `content`, `sent_at`) VALUES
(1, 6, 7, 'Is the laptop still available?', '2025-06-03 11:02:44'),
(2, 7, 6, 'Yes, it is.', '2025-06-03 11:02:44'),
(3, 8, 10, 'I’m interested in the tent.', '2025-06-03 11:02:44'),
(4, 10, 8, 'It’s still in great shape!', '2025-06-03 11:02:44'),
(5, 9, 11, 'What condition is the iPhone in?', '2025-06-03 11:02:44'),
(6, 11, 9, 'It’s nearly new.', '2025-06-03 11:02:44'),
(7, 12, 13, 'Is the guitar suitable for beginners?', '2025-06-03 11:02:44'),
(8, 13, 12, 'Definitely, very easy to use.', '2025-06-03 11:02:44'),
(9, 14, 15, 'Do you have more photos of the bookshelf?', '2025-06-03 11:02:44'),
(10, 15, 14, 'I’ll upload them tonight.', '2025-06-03 11:02:44'),
(11, 1, 2, 'test', '2025-06-03 15:39:26'),
(12, 1, 2, 'test', '2025-06-03 15:39:32'),
(13, 1, 2, 'test2', '2025-06-03 16:45:19'),
(14, 6, 7, 'test', '2025-06-26 10:25:47'),
(15, 6, 1, 'test1234', '2025-06-26 10:26:18'),
(16, 6, 1, 'test4321', '2025-06-26 10:26:27'),
(17, 1, 2, 'tester', '2025-06-26 10:43:38'),
(18, 1, 6, 'nice', '2025-06-26 10:43:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','moderator','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'admin1', 'admin1_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'admin', '2025-06-03 11:02:44'),
(2, 'admin2', 'admin2_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'admin', '2025-06-03 11:02:44'),
(3, 'mod1', 'mod1_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'moderator', '2025-06-03 11:02:44'),
(4, 'mod2', 'mod2_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'moderator', '2025-06-03 11:02:44'),
(5, 'mod3', 'mod3_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'moderator', '2025-06-03 11:02:44'),
(6, 'user1', 'user1_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'user', '2025-06-03 11:02:44'),
(7, 'user2', 'user2_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'user', '2025-06-03 11:02:44'),
(8, 'user3', 'user3_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'user', '2025-06-03 11:02:44'),
(9, 'user4', 'user4_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'user', '2025-06-03 11:02:44'),
(10, 'user5', 'user5_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'user', '2025-06-03 11:02:44'),
(11, 'user6', 'user6_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'user', '2025-06-03 11:02:44'),
(12, 'user7', 'user7_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'user', '2025-06-03 11:02:44'),
(13, 'user8', 'user8_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'user', '2025-06-03 11:02:44'),
(14, 'user9', 'user9_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'user', '2025-06-03 11:02:44'),
(15, 'user10', 'user10_test@example.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'user', '2025-06-03 11:02:44'),
(16, 'Willy', 'willgomwhite@gmail.com', '$2y$10$1IlVUiLewH6ADIa1/bhYxuOGW7Rv4AzVroxJk0L0MTl83nNxfI4de', 'user', '2025-06-03 12:33:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

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
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `listings`
--
ALTER TABLE `listings`
  ADD CONSTRAINT `listings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
