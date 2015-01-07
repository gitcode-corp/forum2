-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2015 at 10:03 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `forum`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups_roles`
--

CREATE TABLE IF NOT EXISTS `groups_roles` (
`id` int(11) NOT NULL,
  `security_group_id` int(11) NOT NULL,
  `security_role_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `groups_roles`
--

INSERT INTO `groups_roles` (`id`, `security_group_id`, `security_role_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16),
(17, 1, 17),
(18, 2, 1),
(19, 2, 3),
(20, 2, 4),
(21, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
`id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text,
  `is_edited_by_admin` tinyint(4) DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `topic_id`, `user_id`, `content`, `is_edited_by_admin`, `created_on`) VALUES
(1, 1, 1, 'Jak w temacie.', 0, '2014-12-23 16:09:52'),
(7, 6, 9, 'jak w temacie?', 0, '2015-01-02 17:44:04'),
(13, 13, 1, 'poleci ktoś dobre książki?', 0, '2015-01-07 20:15:52'),
(14, 13, 1, '??', 0, '2015-01-07 20:16:26'),
(21, 20, 1, 'Zna ktos jakis dobry kurs?', 0, '2015-01-07 21:01:05'),
(22, 20, 10, 'nie znam', 0, '2015-01-07 21:01:40');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE IF NOT EXISTS `sections` (
`id` int(11) NOT NULL,
  `last_topic_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` tinytext,
  `amount_topics` smallint(6) DEFAULT '0',
  `is_closed` tinyint(4) NOT NULL DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `last_topic_id`, `user_id`, `name`, `description`, `amount_topics`, `is_closed`, `created_on`) VALUES
(1, 20, 1, 'Przedszkole', 'Raczkujesz w tematyce WWW (PHP, SQL, (X)HTML, CSS, JS)? Tutaj możesz stanąć na nogi.', 1, 0, '2015-01-07 21:00:40'),
(2, 13, 1, 'PHP', 'Zagadnienia dotyczące programowania w PHP.', 1, 0, '2015-01-07 21:02:45'),
(3, 6, 1, 'Gotowe rozwiązania ', 'Wyszukiwanie, instalacja i konfiguracja a także przydatne opinie na temat gotowych skryptów i bibliotek PHP', 1, 0, '2015-01-07 21:03:03'),
(4, NULL, 1, 'Bazy danych ', 'Serwery baz danych i język SQL.', 0, 0, '2015-01-07 21:00:01');

-- --------------------------------------------------------

--
-- Table structure for table `security_groups`
--

CREATE TABLE IF NOT EXISTS `security_groups` (
`id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `security_groups`
--

INSERT INTO `security_groups` (`id`, `name`) VALUES
(1, 'ADMIN'),
(2, 'USER');

-- --------------------------------------------------------

--
-- Table structure for table `security_roles`
--

CREATE TABLE IF NOT EXISTS `security_roles` (
`id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `security_roles`
--

INSERT INTO `security_roles` (`id`, `name`) VALUES
(3, 'ROLE_ADD_POST'),
(9, 'ROLE_ADD_SECTION'),
(4, 'ROLE_ADD_TOPIC'),
(14, 'ROLE_CHANGE_PASSWORD'),
(15, 'ROLE_CHANGE_TOPIC_STATUS'),
(7, 'ROLE_CLOSE_TOPIC'),
(2, 'ROLE_DELETE_POST'),
(11, 'ROLE_DELETE_SECTION'),
(6, 'ROLE_DELETE_TOPIC'),
(12, 'ROLE_DELETE_USER'),
(17, 'ROLE_EDIT_ALL_POSTS'),
(16, 'ROLE_EDIT_ALL_TOPICS'),
(1, 'ROLE_EDIT_POST'),
(10, 'ROLE_EDIT_SECTION'),
(5, 'ROLE_EDIT_TOPIC'),
(13, 'ROLE_EDIT_USER'),
(8, 'ROLE_OPEN_TOPIC');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
`id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `last_post_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` tinytext,
  `amount_posts` smallint(6) DEFAULT '0',
  `is_closed` tinyint(4) NOT NULL DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `section_id`, `last_post_id`, `user_id`, `name`, `description`, `amount_posts`, `is_closed`, `created_on`) VALUES
(1, 2, NULL, 0, 'Dziedziczenie w PHP', 'Jak to dziala?', 1, 0, '2014-12-23 16:09:12'),
(6, 3, 7, 9, 'Forum', 'posiada ktoś gotowy skrypt forum?', 1, 0, '2015-01-02 17:42:24'),
(13, 2, 14, 1, 'książki o php', '', 2, 0, '2015-01-07 20:15:29'),
(20, 1, 22, 1, 'Kurs dla początkujących', '', 2, 0, '2015-01-07 21:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(12) NOT NULL,
  `amount_posts` smallint(6) DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `salt`, `amount_posts`, `created_on`) VALUES
(1, 'admin', 'admin@email.com', '39c8a79fd1dc520d9e36d6c4edf2a55453fb16c0', '621fd2214074', 12, '2014-12-29 17:04:14'),
(2, 'johnny', 'johnny@email.com', '097702e9391503eab6c8f69d656ae71657bfc230', 'bbc2bd6e8263', 0, '2015-01-02 17:10:05'),
(4, 'lukasz', 'lukasz@email.com', '340e620be6cabd21931e6e3bfd6dbc3912d6de2d', '6fe2599dc199', 0, '2015-01-02 17:34:39'),
(9, 'php_master', 'master@email.com', 'f3a8de81addbb0ca7074e7307f4e0d74ea5fa7d6', '9d95c71d73df', 2, '2015-01-02 17:40:34'),
(10, 'tester', 'tester@email.com', '6410323f894588c502ca3f2c006f1faf46b4f4a7', '5bbfc9bcb5b2', 2, '2015-01-06 21:37:13');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `security_group_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `security_group_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(4, 4, 2),
(3, 9, 2),
(5, 10, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `groups_roles`
--
ALTER TABLE `groups_roles`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `groups_roles` (`security_group_id`,`security_role_id`), ADD KEY `fk_groups_roles_security_groups1_idx` (`security_group_id`), ADD KEY `fk_groups_roles_security_roles1_idx` (`security_role_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_posts_topics1_idx` (`topic_id`), ADD KEY `fk_posts_users1_idx` (`user_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_sections_topics1_idx` (`last_topic_id`), ADD KEY `fk_sections_users1_idx` (`user_id`);

--
-- Indexes for table `security_groups`
--
ALTER TABLE `security_groups`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indexes for table `security_roles`
--
ALTER TABLE `security_roles`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_topics_sections_idx` (`section_id`), ADD KEY `fk_topics_posts1_idx` (`last_post_id`), ADD KEY `fk_topics_users1_idx` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username_UNIQUE` (`username`), ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `users_groups` (`user_id`,`security_group_id`), ADD KEY `fk_users_groups_users1_idx` (`user_id`), ADD KEY `fk_users_groups_security_groups1_idx` (`security_group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groups_roles`
--
ALTER TABLE `groups_roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `security_groups`
--
ALTER TABLE `security_groups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `security_roles`
--
ALTER TABLE `security_roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `groups_roles`
--
ALTER TABLE `groups_roles`
ADD CONSTRAINT `fk_groups_roles_security_groups1` FOREIGN KEY (`security_group_id`) REFERENCES `security_groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_groups_roles_security_roles1` FOREIGN KEY (`security_role_id`) REFERENCES `security_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
ADD CONSTRAINT `fk_posts_topics1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_posts_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
ADD CONSTRAINT `fk_sections_topics1` FOREIGN KEY (`last_topic_id`) REFERENCES `topics` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_sections_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
