-- MySQL Script generated by MySQL Workbench
-- Wed Oct 11 22:45:17 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Table `melis_messenger_msg`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_messenger_msg` ;

CREATE TABLE IF NOT EXISTS `melis_messenger_msg` (
  `msgr_msg_id` INT NOT NULL AUTO_INCREMENT,
  `msgr_msg_creator_id` INT NOT NULL,
  `msgr_msg_date_created` DATETIME NOT NULL,
  PRIMARY KEY (`msgr_msg_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `melis_messenger_msg_content`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_messenger_msg_content` ;

CREATE TABLE IF NOT EXISTS `melis_messenger_msg_content` (
  `msgr_msg_cont_id` INT NOT NULL AUTO_INCREMENT,
  `msgr_msg_id` INT NOT NULL,
  `msgr_msg_cont_sender_id` INT NOT NULL,
  `msgr_msg_cont_message` LONGTEXT NOT NULL,
  `msgr_msg_cont_date` DATETIME NOT NULL,
  `msgr_msg_cont_status` INT NULL,
  PRIMARY KEY (`msgr_msg_cont_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `melis_messenger_msg_members`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_messenger_msg_members` ;

CREATE TABLE IF NOT EXISTS `melis_messenger_msg_members` (
  `msgr_msg_mbr_id` INT NOT NULL AUTO_INCREMENT,
  `msgr_msg_id` INT NOT NULL,
  `msgr_msg_mbr_usr_id` INT NOT NULL,
  PRIMARY KEY (`msgr_msg_mbr_id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
