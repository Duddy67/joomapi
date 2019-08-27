-- -----------------------------------------------------
-- Table `#__joomapi_notification`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__joomapi_notification`;
CREATE TABLE `#__joomapi_notification` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(225) NOT NULL ,
  `alias` VARCHAR(255) NOT NULL ,
  `intro_text` MEDIUMTEXT NULL ,
  `full_text` MEDIUMTEXT NULL ,
  `published` TINYINT NOT NULL DEFAULT 0 ,
  `catid` INT UNSIGNED NOT NULL ,
  `checked_out` INT UNSIGNED NOT NULL DEFAULT 0 ,
  `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `ordering` INT NOT NULL DEFAULT 0 ,
  `asset_id` INT UNSIGNED NOT NULL DEFAULT 0 ,
  `access` TINYINT NOT NULL DEFAULT 0 ,
  `params` TEXT NOT NULL ,
  `metakey` TEXT NOT NULL ,
  `metadesc` TEXT NOT NULL ,
  `metadata` TEXT NOT NULL ,
  `xreference` VARCHAR(50) NOT NULL ,
  `hits` INT UNSIGNED NOT NULL DEFAULT 0 ,
  `publish_up` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `publish_down` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `created_by` INT UNSIGNED NOT NULL ,
  `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `modified_by` INT UNSIGNED NOT NULL ,
  `language` CHAR(7) NOT NULL,
  PRIMARY KEY (`id`) ,
  INDEX `idx_access` (`access` ASC) ,
  INDEX `idx_created_by` (`created_by` ASC) ,
  INDEX `idx_published` (`published` ASC) ,
  INDEX `idx_check_out` (`checked_out` ASC) )
ENGINE = MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table `#__joomapi_user_token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__joomapi_user_token`;
CREATE TABLE `#__joomapi_user_token` (
  `user_id` INT UNSIGNED NOT NULL ,
  `user_token` VARCHAR(225) NOT NULL ,
  `device_token` TEXT NOT NULL ,
  `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  INDEX `idx_user_id` (`user_id` ASC) ,
  INDEX `idx_user_token` (`user_token` ASC) )
ENGINE = MyISAM DEFAULT CHARSET=utf8;

