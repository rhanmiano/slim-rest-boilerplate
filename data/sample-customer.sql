CREATE DATABASE IF NOT EXISTS `slim_sample_db`; -- Define your database here

USE `slim_sample_db`;

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` varchar(32) NOT NULL,
  `age` int(3) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;