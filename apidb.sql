-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.25-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for api
CREATE DATABASE IF NOT EXISTS `api` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `api`;

-- Dumping structure for table api.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table api.cart: ~0 rows (approximately)
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;

-- Dumping structure for table api.keys
CREATE TABLE IF NOT EXISTS `keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table api.keys: ~2 rows (approximately)
/*!40000 ALTER TABLE `keys` DISABLE KEYS */;
REPLACE INTO `keys` (`id`, `key`, `level`, `ignore_limits`, `date_created`) VALUES
	(1, 'w44o8s0o4w8scocg0koos0g4g488c8wgwokccogk', 1, 1, 1507713044),
	(2, 'kkc00w88c8400w4w4k00so4c88g8g8kwcwcc048s', 1, 1, 1507714339);
/*!40000 ALTER TABLE `keys` ENABLE KEYS */;

-- Dumping structure for table api.services
CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `value` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table api.services: ~5 rows (approximately)
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
REPLACE INTO `services` (`id`, `name`, `description`, `value`, `status`) VALUES
	(1, 'National ID application', 'national ID appication for the genereal public', 1000, 1),
	(2, 'Driving Licence', 'Driving licence for the appicant', 2500, 1),
	(3, 'Court Case', 'Show up to the next case', 10050, 1),
	(4, 'Land Rate', 'National housing coorporation', 40000, 1),
	(5, 'Marriage Certificate', 'The marriage certificate', 5000, 1);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;

-- Dumping structure for table api.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table api.transactions: ~0 rows (approximately)
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;

-- Dumping structure for table api.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `user_type_id` int(11) DEFAULT NULL,
  `firstLogin` int(11) DEFAULT '1',
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table api.users: ~1 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `name`, `email`, `password`, `phone`, `user_type_id`, `firstLogin`, `status`) VALUES
	(1, 'Joshua Bakasa', 'joshua.bakasa@strathmore.edu', 'ae8a97165a6cd640a149ec76965d51b7559d164d', '0725455925', 1, NULL, 1),
	(3, 'Joshua Bakasa', 'baksajoshua09@gmail.com', 'ae8a97165a6cd640a149ec76965d51b7559d164d', '0725455925', 2, NULL, 1),
	(5, 'Joshua Bakasa', 'bakasajoshua09@gmail.com', 'ae8a97165a6cd640a149ec76965d51b7559d164d', '0725455925', 2, 7563, 1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table api.user_types
CREATE TABLE IF NOT EXISTS `user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT '0',
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table api.user_types: ~2 rows (approximately)
/*!40000 ALTER TABLE `user_types` DISABLE KEYS */;
REPLACE INTO `user_types` (`id`, `name`, `status`) VALUES
	(1, 'admin', 1),
	(2, 'customer', 1);
/*!40000 ALTER TABLE `user_types` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
