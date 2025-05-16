-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 09:25 AM
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
-- Database: `moodboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `mood_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `mood_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moods`
--

CREATE TABLE `moods` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `mood_emoji` varchar(10) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moods`
--

INSERT INTO `moods` (`id`, `user_id`, `mood_emoji`, `message`, `image`, `created_at`) VALUES
(1, 1, '😎', 'i am feeling cool . ', '1747130792_data_backup_img.jpg', '2025-05-13 15:36:32'),
(2, 2, '😄', 'i am feeling happy . ', '', '2025-05-13 15:53:17'),
(3, 3, '😴', 'i am feeling tired.', '', '2025-05-13 16:09:30'),
(4, 3, '😡', 'i am not feeling good', '', '2025-05-14 12:45:20'),
(5, 4, '🤔', 'i think some funny thinks and feel good.', '1747214269_67ed2c8cbc5ea_home lets.avif', '2025-05-14 14:47:49'),
(6, 4, '😢', 'jflkajdlfjdljfdljflkd', '1747377479_ganesh g.jpg', '2025-05-16 12:07:59');

-- --------------------------------------------------------

--
-- Table structure for table `moods_comments`
--

CREATE TABLE `moods_comments` (
  `id` int(11) NOT NULL,
  `mood_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moods_comments`
--

INSERT INTO `moods_comments` (`id`, `mood_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 1, 1, 'its cool post', '2025-05-13 15:47:33'),
(2, 1, 2, 'its not a cool post', '2025-05-13 15:48:10'),
(3, 2, 1, 'its not a cool post', '2025-05-13 16:07:37'),
(4, 3, 2, 'hello', '2025-05-14 12:18:39'),
(5, 1, 3, 'its cool post', '2025-05-14 12:22:23'),
(6, 4, 1, 'why ', '2025-05-14 15:13:26'),
(7, 6, 1, 'hello', '2025-05-16 12:30:43');

-- --------------------------------------------------------

--
-- Table structure for table `moods_likes`
--

CREATE TABLE `moods_likes` (
  `id` int(11) NOT NULL,
  `mood_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moods_likes`
--

INSERT INTO `moods_likes` (`id`, `mood_id`, `user_id`, `created_at`) VALUES
(4, 3, 3, '2025-05-13 18:00:19'),
(6, 1, 1, '2025-05-14 12:14:25'),
(7, 3, 2, '2025-05-14 12:18:28'),
(8, 1, 3, '2025-05-14 12:22:15'),
(9, 3, 1, '2025-05-14 12:40:51'),
(10, 1, 4, '2025-05-14 14:37:04'),
(12, 2, 1, '2025-05-14 15:15:29'),
(13, 4, 1, '2025-05-14 15:15:34');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `mood_id` int(11) NOT NULL,
  `type` enum('like','comment') NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `recipient_id`, `sender_id`, `mood_id`, `type`, `is_read`, `created_at`) VALUES
(1, 2, 1, 2, 'like', 1, '2025-05-14 12:14:08'),
(2, 1, 2, 1, 'like', 1, '2025-05-14 12:18:20'),
(3, 3, 2, 3, 'like', 1, '2025-05-14 12:18:28'),
(4, 1, 3, 1, 'like', 1, '2025-05-14 12:22:15'),
(5, 2, 1, 2, 'like', 0, '2025-05-14 12:40:49'),
(6, 3, 1, 3, 'like', 1, '2025-05-14 12:40:51'),
(7, 1, 4, 1, 'like', 1, '2025-05-14 14:37:04'),
(8, 4, 1, 5, 'like', 1, '2025-05-14 15:14:29'),
(9, 2, 1, 2, 'like', 0, '2025-05-14 15:15:29'),
(10, 3, 1, 4, 'like', 0, '2025-05-14 15:15:34'),
(11, 4, 1, 5, 'like', 1, '2025-05-14 17:06:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `profile`, `bio`, `dob`) VALUES
(1, 'naman', 'namanm0021@gmail.com', '$2y$10$ETUMfbClMhNyhUcvJa3lyOB6eTUmaa7TnebXf9pJbJBY.eWcBvvzy', NULL, NULL, NULL),
(2, 'anil', 'anil@gmail.com', '$2y$10$faKHhKzRn1cLq5oldnUDYORU5e.a87M7yHiRyDHSTUymEVPkpUYT.', NULL, NULL, NULL),
(3, 'joyti', 'joyti@gmail.com', '$2y$10$yn5lOyRYGnG4psqxpSJDZudseoLYzw4T9JX4Fi0jJyZdEahNWKjRC', NULL, NULL, NULL),
(4, 'amrita ', 'amrita@gmail.com', '$2y$10$uhTf6ak8wjeoSi6yhsEQRuG6MGSMdrbPyI8q/qwhE8eHlfbHibIWO', '68244567c41b5_profile_11505641.png', 'cool and funny', '2003-01-01'),
(5, 'Rupal ', 'rupal@gmail.com', '$2y$10$XkEpI8VZvVRfrTCg1t8oYuny2x./QrFppMHX4PXW43PBPMGYTsSQO', '682448f398e4d_profile_11505641.png', 'funny person.', '2001-02-23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mood_id` (`mood_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mood_id` (`mood_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `moods`
--
ALTER TABLE `moods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `moods_comments`
--
ALTER TABLE `moods_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mood_id` (`mood_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `moods_likes`
--
ALTER TABLE `moods_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mood_id` (`mood_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipient_id` (`recipient_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `mood_id` (`mood_id`);

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
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `moods`
--
ALTER TABLE `moods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `moods_comments`
--
ALTER TABLE `moods_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `moods_likes`
--
ALTER TABLE `moods_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`mood_id`) REFERENCES `moods` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`mood_id`) REFERENCES `moods` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `moods`
--
ALTER TABLE `moods`
  ADD CONSTRAINT `moods_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `moods_comments`
--
ALTER TABLE `moods_comments`
  ADD CONSTRAINT `moods_comments_ibfk_1` FOREIGN KEY (`mood_id`) REFERENCES `moods` (`id`),
  ADD CONSTRAINT `moods_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `moods_likes`
--
ALTER TABLE `moods_likes`
  ADD CONSTRAINT `moods_likes_ibfk_1` FOREIGN KEY (`mood_id`) REFERENCES `moods` (`id`),
  ADD CONSTRAINT `moods_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`mood_id`) REFERENCES `moods` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
