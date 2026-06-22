-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2025 at 06:40 PM
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
-- Database: `job_finder`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('waiting','accepted','rejected') DEFAULT 'waiting'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `job_id`, `user_id`, `applied_at`, `status`) VALUES
(3, 2, 1, '2025-04-02 13:47:38', 'accepted'),
(6, 2, 6, '2025-04-05 14:08:07', 'accepted'),
(9, 2, 7, '2025-04-09 07:09:39', 'accepted'),
(10, 4, 7, '2025-04-09 09:39:07', 'rejected');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `posted_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `company`, `location`, `description`, `posted_by`, `created_at`) VALUES
(2, 'Manager', 'Romeo The Great', 'Dhaka', 'For nothing ', 2, '2025-04-02 13:46:50'),
(3, 'Cooking', 'Romeo The Great', 'Dhaka', 'Something', 2, '2025-04-05 14:10:16'),
(4, 'Teacher', 'IUB', 'Dhaka', 'Salary- 200K\r\njhfjhf', 8, '2025-04-09 09:37:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('job_seeker','job_giver','admin') NOT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `experience`, `education`, `company_name`, `position`, `profile_pic`, `created_at`) VALUES
(1, 'Afraim Romeo ', 'afraim@gmail.com', '$2y$10$aStbeRQYksR7XUNz/QCwmuTNlUiv1F8pgpBPDP/5DQxN/SpAfuFrS', 'job_seeker', '3 years in Ab group', 'MA ', NULL, NULL, '1743105063_fire.png', '2025-03-27 19:51:03'),
(2, 'Romeo', 'afraim19@gmail.com', '$2y$10$svLxxWXlUkWycaQTOVyjBuMed5Ukz6n0A2EM7V8.ShKp6MrbDrocq', 'job_giver', NULL, NULL, 'Romeo the Great ', 'OWNER', '1743497186_fire.png', '2025-04-01 08:46:26'),
(3, 'Shirin', 'shirin@gmail.com', '$2y$10$a41NraUdwSwy7rlD8yLFTun0WrT/sSzWNpQzr2rCCi0DMDnTZrsJ2', 'job_seeker', 'cooking for 20 years', 'HSC', NULL, NULL, '1743611320_bag.png', '2025-04-02 16:28:40'),
(4, 'Admin User', 'admin@example.com', '$2y$10$eW5pHQwLkA5hW/Y3z1sZ5eV/W.Yx0KHwMWx9FqTtBdcQu8bdRTl1e', 'admin', NULL, NULL, NULL, NULL, 'default.png', '2025-04-03 10:08:55'),
(5, 'Admin', 'admin@gmail.com', '$2y$10$.w8CASc/NzL88G6B184uW.IOucIhMrMfafM9HgfmnKiUPj0Wbly1C', 'admin', NULL, NULL, 'xyz', 'Admin', '1743680615_fire.png', '2025-04-03 11:43:35'),
(6, 'Shovon', 'shovon@gmail.com', '$2y$10$p3TKhFbK/jMc9x92k3Uu1e8vfvbFi0eYKltY4sxrjzI3mZRn8zSn6', 'job_seeker', '2 years in Ab group', 'MA ', NULL, NULL, '1743862007_bag.png', '2025-04-05 14:06:47'),
(7, 'maisha', 'maisha@gmail.com', '$2y$10$5rPurs62E26VP6g28HTBu.jJc9NesKDwA/O7QnqlzViItQXQ4Epsi', 'job_seeker', 'teaching for 2 years', 'BSc in computer science', NULL, NULL, 'default.png', '2025-04-09 07:07:11'),
(8, 'Liton', 'liton@gmail.com', '$2y$10$vtytUyXEvKn34j/4Pd7YJ.ojr2v0OjgVRJEiL2ROi98Ds3P50TWM.', 'job_giver', NULL, NULL, 'IUB', 'VC', 'default.png', '2025-04-09 09:35:05');
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `job_id`, `parent_id`, `user_name`, `comment_text`, `created_at`) VALUES
(3, 4, NULL, 'Sinthia Siddiqui', 'can i have more details?', '2025-05-02 21:10:23'),
(4, 4, 3, 'Sinthia Siddiqui', 'yes sure', '2025-05-02 21:15:54'),
(5, 4, NULL, 'Sinthia Siddiqui', 'Is it in BARIDHARA??', '2025-05-03 00:18:06'),
(6, 3, NULL, 'Sinthia Siddiqui', 'How much is the salary? What kind of cuisine?', '2025-05-03 10:23:05'),
(7, 2, NULL, 'Sinthia Siddiqui', 'Interested, need more details!', '2025-05-03 10:24:05'),
(8, 3, 6, 'Sinthia Siddiqui', 'Need more information', '2025-05-03 10:32:29'),
(9, 2, 7, 'Sinthia Siddiqui', 'The description is so vagueee!', '2025-05-03 10:35:57');

-- --------------------------------------------------------
--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posted_by` (`posted_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `parent_id` (`parent_id`);

-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
