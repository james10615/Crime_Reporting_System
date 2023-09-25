-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2023 at 01:39 PM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `crimes`
--

CREATE TABLE `crimes` (
  `crime_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `crime` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `crime_date` datetime DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `crimes`
--

INSERT INTO `crimes` (`crime_id`, `user_id`, `crime`, `location`, `crime_date`, `description`, `status`) VALUES
(4, 12, 'vandalism', 'home', '2023-05-18 14:40:00', 'My home has been broken into', 'Closed'),
(5, 12, 'Theft', 'canaan estate', '2023-05-11 14:41:00', 'Somebody just stole my from from my car', 'Closed'),
(6, 12, 'Theft', 'canaan estate', '2023-05-11 14:42:00', 'Somebody just snatched a womans purse', 'Closed'),
(7, 12, 'Vandalism', 'canaan estate', '2023-05-16 14:48:00', 'Some goons are trying to break into the estate', 'In Progress'),
(25, 0, 'theft', 'canaan estate', '2023-05-04 09:42:00', 'ccdv', 'pending'),
(26, 138, 'vandalism', 'canaan estate', '2023-05-25 09:42:00', 'rgrtg', 'Closed'),
(27, 0, 'Theft', 'canaan estate', '2023-05-30 11:21:00', 'There is somebody breaking into a car', 'pending'),
(28, 139, 'vandalism', 'highrise', '2023-05-30 11:23:00', 'somebody broke into my home', 'In Progress');

-- --------------------------------------------------------

--
-- Table structure for table `forum_messages`
--

CREATE TABLE `forum_messages` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message_content` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `forum_messages`
--

INSERT INTO `forum_messages` (`message_id`, `user_id`, `title`, `message_content`, `timestamp`) VALUES
(6, 12, 'Meeting', 'There is a meeting tommorrow at the hall', '2023-05-20 09:14:57'),
(7, 12, 'Town Hall', 'The member of parliament has requested for a town hall tommorrow at 10.00 PM\r\n', '2023-05-20 09:37:25'),
(10, 12, 'Robery at canaan estate', 'if you live in canaan estate stay cautious\r\n', '2023-05-20 09:40:42'),
(11, 25, 'State of security in the estate', 'Lets discuss on how to improve security in our estates', '2023-05-22 11:44:42'),
(15, 138, 'meeting', 'town hal tommorrow', '2023-05-30 06:42:58'),
(16, 139, 'Town hall', 'You are requested to come for a town hall meeting at 11 tommorrow', '2023-05-30 08:25:18');

-- --------------------------------------------------------

--
-- Table structure for table `master_log_in`
--

CREATE TABLE `master_log_in` (
  `user_id` int(11) NOT NULL,
  `uname` varchar(50) NOT NULL,
  `upassword` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `master_log_in`
--

INSERT INTO `master_log_in` (`user_id`, `uname`, `upassword`) VALUES
(1, 'master', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `lpassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `lname`, `email`, `lpassword`) VALUES
(12, 'John', 'john@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4'),
(24, 'Simanto', 'simanto@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4'),
(25, 'Wetu', 'wetu@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4'),
(123, 'Wetu1', 'wetu1@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4'),
(124, 'Nelson', 'neslon@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4'),
(135, 'davih', 'davih@gmail.com', '756bc47cb5215dc3329ca7e1f7be33a2dad68990bb94b76d90aa07f4e44a233a'),
(139, 'james', 'jamessimanto1@gmail.com', '1234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `crimes`
--
ALTER TABLE `crimes`
  ADD PRIMARY KEY (`crime_id`);

--
-- Indexes for table `forum_messages`
--
ALTER TABLE `forum_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `master_log_in`
--
ALTER TABLE `master_log_in`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `crimes`
--
ALTER TABLE `crimes`
  MODIFY `crime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `forum_messages`
--
ALTER TABLE `forum_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `master_log_in`
--
ALTER TABLE `master_log_in`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
