SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `grcenter_db` ;
CREATE SCHEMA IF NOT EXISTS `grcenter_db` DEFAULT CHARACTER SET latin1 ;
USE `grcenter_db` ;
 
-- -----------------------------------------------------
-- Table `grcenter_db`.`gr_gmaps`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grcenter_db`.`gr_gmaps` ;

CREATE  TABLE IF NOT EXISTS `grcenter_db`.`gr_gmaps` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key ' ,
  `name` VARCHAR(100) NOT NULL DEFAULT 'Untitled' COMMENT 'The name of GMap' ,
  `image_path` VARCHAR(256) NOT NULL DEFAULT '' COMMENT 'The path of Image file ' ,
  `deletedYN` VARCHAR(1) NOT NULL DEFAULT 'Y' COMMENT 'This map is deleted or not N: exist, Y: deleted' ,
  `created` VARCHAR(50) NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The date and time of this map created' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Google Map Table';


-- -----------------------------------------------------
-- Table `grcenter_db`.`gr_gmapitem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grcenter_db`.`gr_gmapitem` ;

CREATE  TABLE IF NOT EXISTS `grcenter_db`.`gr_gmapitem` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `videoid` BIGINT(20) NOT NULL ,
  `posx` INT(11) NOT NULL DEFAULT '0' ,
  `posy` INT(11) NOT NULL DEFAULT '0' ,
  `deletedYN` VARCHAR(1) NOT NULL DEFAULT 'Y' COMMENT 'This item is deleted or not' ,
  `created` VARCHAR(50) NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'When this item created' ,
  `locationId` BIGINT(20) NOT NULL ,
  `gr_gmaps_id` BIGINT(20) NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_gr_gmapitem_gr_gmaps`
    FOREIGN KEY (`gr_gmaps_id` )
    REFERENCES `grcenter_db`.`gr_gmaps` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'GMap Items table';


-- -----------------------------------------------------
-- Table `grcenter_db`.`gr_permissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grcenter_db`.`gr_permissions` ;

CREATE  TABLE IF NOT EXISTS `grcenter_db`.`gr_permissions` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `accessLocations` TEXT NULL DEFAULT NULL ,
  `accessCameras` TEXT NULL DEFAULT NULL ,
  `emailAddress` TEXT NULL DEFAULT NULL ,
  `enableMap` INT(11) NOT NULL DEFAULT '0' ,
  `enableMapPTZ` INT(11) NOT NULL DEFAULT '0' ,
  `enableLive` INT(11) NOT NULL DEFAULT '0' ,
  `enableLivePTZ` INT(11) NOT NULL DEFAULT '0' ,
  `enableSearch` INT(11) NOT NULL DEFAULT '0' ,
  `enableSearchSearch` INT(11) NOT NULL DEFAULT '0' ,
  `enableSearchSearchExport` INT(11) NOT NULL DEFAULT '0' ,
  `enableSearchThumb` INT(11) NOT NULL DEFAULT '0' ,
  `enableSearchThumbExport` INT(11) NOT NULL DEFAULT '0' ,
  `enableSearchLibrary` INT(11) NOT NULL DEFAULT '0' ,
  `enableSearchLibraryExport` INT(11) NOT NULL DEFAULT '0' ,
  `enableAlarm` INT(11) NOT NULL DEFAULT '0' ,
  `enableAlarmMotion` INT(11) NOT NULL DEFAULT '0' ,
  `enableAlarmMotionEmail` INT(11) NOT NULL DEFAULT '0' ,
  `enableAlarmAlert` INT(11) NOT NULL DEFAULT '0' ,
  `enableAlarmAlertEmail` INT(11) NOT NULL DEFAULT '0' ,
  `enableAlarmError` INT(11) NOT NULL DEFAULT '0' ,
  `enableAlarmErrorEmail` INT(11) NOT NULL DEFAULT '0' ,
  `enableAdminReporting` INT(11) NOT NULL DEFAULT '0' ,
  `enableAdminReportingChange` INT(11) NOT NULL DEFAULT '0' ,
  `enableAdminDefaults` INT(11) NOT NULL DEFAULT '0' ,
  `enableAdminDefaultsChange` INT(11) NOT NULL DEFAULT '0' ,
  `enableAdminDevices` INT(11) NOT NULL DEFAULT '0' ,
  `enableAdminDevicesChange` INT(11) NOT NULL DEFAULT '0' ,
  `enableAdminGroups` INT(11) NOT NULL DEFAULT '0' ,
  `enableAdminGroupsChange` INT(11) NOT NULL DEFAULT '0' ,
  `deletedYN` VARCHAR(1) NOT NULL DEFAULT 'N' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `grcenter_db`.`gr_groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grcenter_db`.`gr_groups` ;

CREATE  TABLE IF NOT EXISTS `grcenter_db`.`gr_groups` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Default primary key' ,
  `name` VARCHAR(50) NOT NULL DEFAULT '' ,
  `deletedYN` VARCHAR(1) NULL DEFAULT 'N' ,
  `isdefault` VARCHAR(1) NULL DEFAULT 'N' COMMENT 'Y: Default group, N: General group' ,
  `emailAddress` VARCHAR(255) NOT NULL DEFAULT '' ,
  `gr_permissions_id` BIGINT(20) NOT NULL ,
  PRIMARY KEY (`id`, `gr_permissions_id`) ,
  CONSTRAINT `fk_gr_groups_gr_permissions1`
    FOREIGN KEY (`gr_permissions_id` )
    REFERENCES `grcenter_db`.`gr_permissions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `grcenter_db`.`gr_locations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grcenter_db`.`gr_locations` ;

CREATE  TABLE IF NOT EXISTS `grcenter_db`.`gr_locations` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NOT NULL DEFAULT '' ,
  `ipaddress` VARCHAR(45) NOT NULL DEFAULT '000.000.000.000' ,
  `webport` INT(11) NOT NULL DEFAULT '7001' ,
  `rtmpport` INT(11) NOT NULL DEFAULT '1935' ,
  `deletedYN` VARCHAR(1) NOT NULL DEFAULT 'N' ,
  `parentId` BIGINT(20) NOT NULL DEFAULT '-1' ,
  `mapId` BIGINT NOT NULL DEFAULT -1 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `grcenter_db`.`gr_setting`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grcenter_db`.`gr_setting` ;

CREATE  TABLE IF NOT EXISTS `grcenter_db`.`gr_setting` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Default primary key' ,
  `name` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Setting Key' ,
  `value` VARCHAR(256) NOT NULL DEFAULT '' COMMENT 'Setting value' ,
  `deletedYN` VARCHAR(1) NOT NULL DEFAULT 'N' COMMENT 'This setting is deleted or not N: not deleted, Y: deleted' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Settings of global system';


-- -----------------------------------------------------
-- Table `grcenter_db`.`gr_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grcenter_db`.`gr_users` ;

CREATE  TABLE IF NOT EXISTS `grcenter_db`.`gr_users` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `user_name` VARCHAR(60) NOT NULL DEFAULT '' ,
  `password` VARCHAR(255) NOT NULL DEFAULT '' ,
  `first_name` VARCHAR(30) NOT NULL DEFAULT '' ,
  `last_name` VARCHAR(30) NOT NULL DEFAULT '' ,
  `email` VARCHAR(255) NOT NULL DEFAULT '' ,
  `signin_date` VARCHAR(45) NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `signup_date` VARCHAR(45) NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `changepwd_date` VARCHAR(45) NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `phone_mobile` VARCHAR(50) NOT NULL DEFAULT '0000000000' ,
  `address_street` VARCHAR(150) NOT NULL DEFAULT '' ,
  `address_city` VARCHAR(100) NOT NULL DEFAULT '' ,
  `address_state` VARCHAR(100) NOT NULL DEFAULT '' ,
  `address_country` VARCHAR(100) NOT NULL DEFAULT '' ,
  `address_postcode` VARCHAR(20) NOT NULL DEFAULT '' ,
  `deletedYN` VARCHAR(1) NOT NULL DEFAULT 'N' COMMENT 'N: Not deleted, Y: Deleted' ,
  `status` VARCHAR(1) NOT NULL DEFAULT 'A' COMMENT 'A: Active, B: Blocked' ,
  `isadmin` INT(11) NOT NULL DEFAULT '0' COMMENT '0: General User, 1: Administrator, 2: Super Administrator' ,
  `gr_groups_id` BIGINT(20) NOT NULL ,
  PRIMARY KEY (`id`, `gr_groups_id`) ,
  CONSTRAINT `fk_gr_users_gr_groups1`
    FOREIGN KEY (`gr_groups_id` )
    REFERENCES `grcenter_db`.`gr_groups` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Users table';


DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -----------------------------------------------------
-- Table `grcenter_db`.`gr_videoins`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grcenter_db`.`gr_videoins` ;

CREATE  TABLE IF NOT EXISTS `grcenter_db`.`gr_videoins` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `videoInIndex` BIGINT(20) NOT NULL ,
  `videoInName` VARCHAR(300) NOT NULL DEFAULT '' COMMENT 'The name of video ins' ,
  `videoInEnable` TINYINT(4) NOT NULL DEFAULT '0' ,
  `videoURL` VARCHAR(1024) NOT NULL DEFAULT '' ,
  `deletedYN` VARCHAR(1) NOT NULL DEFAULT 'Y' ,
  `gr_locations_id` BIGINT(20) NOT NULL ,
  PRIMARY KEY (`id`, `gr_locations_id`) ,
  CONSTRAINT `fk_gr_videoins_gr_locations1`
    FOREIGN KEY (`gr_locations_id` )
    REFERENCES `grcenter_db`.`gr_locations` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'The video ins table ';



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
