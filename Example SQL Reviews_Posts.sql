SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

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

ALTER TABLE `Reviews_Posts`
  ADD PRIMARY KEY (`post_id`),
  ADD UNIQUE KEY `friendly_name` (`friendly_name`),
  ADD UNIQUE KEY `title` (`title`);

ALTER TABLE `Reviews_Posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

--
-- Table structure for table `Reviews_Categories`
--

CREATE TABLE `Reviews_Categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `friendly_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

ALTER TABLE `Reviews_Categories`
  ADD PRIMARY KEY (`category_id`);

ALTER TABLE `Reviews_Categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

--
-- Table structure for table `Blog_Users`
--

CREATE TABLE `Blog_Users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` text DEFAULT NULL,
  `password_salted` tinyint(1) NOT NULL,
  `role` int(11) NOT NULL,
  `login_attempts` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

ALTER TABLE `Blog_Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `Blog_Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
