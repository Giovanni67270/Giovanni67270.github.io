CREATE DATABASE IF NOT EXISTS `ppe3` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ppe3`;

CREATE TABLE IF NOT EXISTS `admin_accounts` (
`id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_accounts` (
`id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `admin_accounts` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

INSERT INTO `user_accounts` (`id`, `username`, `password`) VALUES
(1, 'user', 'user');

CREATE TABLE IF NOT EXISTS `categories` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'General'),
(2, 'Problème technique'),
(3, 'Autre');

CREATE TABLE IF NOT EXISTS `tickets` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `msg` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('ouvert','ferme','resolu') NOT NULL DEFAULT 'ouvert',
  `priority` enum('bas','moyen','haut') NOT NULL DEFAULT 'bas',
  `category_id` int(1) NOT NULL DEFAULT '1',
  `private` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tickets_comments` (
`id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


ALTER TABLE `admin_accounts`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `user_accounts`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `categories`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `tickets`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `tickets_comments`
 ADD PRIMARY KEY (`id`);


ALTER TABLE `admin_accounts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `user_accounts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
ALTER TABLE `tickets`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
ALTER TABLE `tickets_comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
