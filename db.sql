CREATE DATABASE IF NOT EXISTS `test_api` 
USE `test_api`;

CREATE TABLE IF NOT EXISTS `issue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '0',
  `date_create` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `user` varchar(50) DEFAULT '0',
  `comment` varchar(100) DEFAULT '0',
  KEY `Индекс 1` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

DELETE FROM `issue`;
INSERT INTO `issue` (`id`, `name`, `date_create`, `date_update`, `user`, `comment`) VALUES
	(6, 'test2', '2020-01-16 08:25:23', '2020-01-16 10:43:02', 'user2', 'Comment1'),
	(7, 'test3', '2020-01-16 10:37:23', '2020-01-16 10:42:52', 'user1', 'Comment1'),
	(8, 'test5', '2020-01-17 12:15:17', '2020-01-17 12:15:17', 'user1', 'Comment1');