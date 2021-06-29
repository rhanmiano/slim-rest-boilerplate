-- ---
-- Globals
-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET FOREIGN_KEY_CHECKS=0;

-- ---
-- Table 'user'
-- 
-- ---

DROP TABLE IF EXISTS `user`;
		
CREATE TABLE `user` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `username` VARCHAR(64) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'profile'
-- 
-- ---

DROP TABLE IF EXISTS `profile`;
		
CREATE TABLE `profile` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `user_id` INTEGER(11) NOT NULL,
  `fname` VARCHAR(100) NULL DEFAULT NULL,
  `lname` VARCHAR(100) NULL DEFAULT NULL,
  `gender` VARCHAR(5) NULL DEFAULT NULL COMMENT 'm=male,f=female',
  `phone_no` INTEGER NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'user_verification'
-- 
-- ---

DROP TABLE IF EXISTS `user_verification`;
		
CREATE TABLE `user_verification` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `user_id` INTEGER(11) NOT NULL,
  `access_token` MEDIUMTEXT NOT NULL,
  `is_logged_out` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1=true,0=false',
  `logged_out_at` DATETIME NULL DEFAULT NULL,
  `ip` VARCHAR(50) NULL DEFAULT NULL,
  `user_agent` MEDIUMTEXT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'role'
-- 
-- ---

DROP TABLE IF EXISTS `role`;
		
CREATE TABLE `role` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'user_role'
-- 
-- ---

DROP TABLE IF EXISTS `user_role`;
		
CREATE TABLE `user_role` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `user_id` INTEGER(11) NOT NULL,
  `role_id` INTEGER(11) NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
);

-- ---
-- Foreign Keys 
-- ---

ALTER TABLE `profile` ADD FOREIGN KEY (user_id) REFERENCES `user` (`id`);
ALTER TABLE `user_verification` ADD FOREIGN KEY (user_id) REFERENCES `user` (`id`);
ALTER TABLE `user_role` ADD FOREIGN KEY (user_id) REFERENCES `user` (`id`);
ALTER TABLE `user_role` ADD FOREIGN KEY (role_id) REFERENCES `role` (`id`);

-- ---
-- Table Properties
-- ---

-- ALTER TABLE `user` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `profile` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `user_verification` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `role` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `user_role` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Test Data
-- ---

-- INSERT INTO `user` (`id`,`uuid`,`username`,`password`,`created_at`,`updated_at`,`deleted_at`,`enabled`) VALUES
-- ('','','','','','','','');
-- INSERT INTO `profile` (`id`,`user_id`,`fname`,`lname`,`gender`,`phone_no`,`created_at`,`updated_at`,`deleted_at`) VALUES
-- ('','','','','','','','','');
-- INSERT INTO `user_verification` (`id`,`user_id`,`access_token`,`is_logged_out`,`logged_out_at`,`ip`,`user_agent`,`created_at`,`updated_at`) VALUES
-- ('','','','','','','','','');
-- INSERT INTO `role` (`id`,`uuid`,`name`,`created_at`,`updated_at`,`deleted_at`,`enabled`) VALUES
-- ('','','','','','','');
-- INSERT INTO `user_role` (`id`,`user_id`,`role_id`,`created_at`,`updated_at`,`deleted_at`,`enabled`) VALUES
-- ('','','','','','','');