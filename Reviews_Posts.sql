-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 06, 2025 at 11:44 AM
-- Server version: 10.11.13-MariaDB-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jamiebalfour.scot_Blogs`
--

-- --------------------------------------------------------

--
-- Table structure for table `Reviews_Posts`
--

CREATE TABLE `Reviews_Posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `introduction` text NOT NULL,
  `banner` text NOT NULL,
  `content` longtext NOT NULL,
  `date` datetime NOT NULL,
  `category` int(11) NOT NULL,
  `poster` int(11) NOT NULL,
  `tags` text NOT NULL,
  `classes` text NOT NULL,
  `friendly_name` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Reviews_Posts`
--
ALTER TABLE `Reviews_Posts`
  ADD PRIMARY KEY (`post_id`),
  ADD UNIQUE KEY `friendly_name` (`friendly_name`),
  ADD UNIQUE KEY `title` (`title`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Reviews_Posts`
--
ALTER TABLE `Reviews_Posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
