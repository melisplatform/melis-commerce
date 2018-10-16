-- -----------------------------------------------------
-- Table `melis_ecom_currency`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_currency` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_currency` (
  `cur_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Currency Id',
  `cur_default` TINYINT(1) NULL DEFAULT 0 COMMENT 'Default currency',
  `cur_name` VARCHAR(45) NOT NULL COMMENT 'Currency name',
  `cur_code` VARCHAR(8) NOT NULL COMMENT 'Currency code',
  `cur_symbol` VARCHAR(8) NOT NULL COMMENT 'Currency symbol',
  `cur_status` TINYINT(1) NULL,
  PRIMARY KEY (`cur_id`))
ENGINE = InnoDB
COMMENT = 'This tables stores the currency used in melis Ecommerce';

-- -----------------------------------------------------
-- Data for table `melis_ecom_currency`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_currency` (`cur_id`, `cur_default`, `cur_name`, `cur_code`, `cur_symbol`, `cur_status`) VALUES (1, 1, 'Euro', 'EUR', '€', 1);
INSERT INTO `melis_ecom_currency` (`cur_id`, `cur_default`, `cur_name`, `cur_code`, `cur_symbol`, `cur_status`) VALUES (2, 0, 'US Dollar', 'USD', '$', 0);
INSERT INTO `melis_ecom_currency` (`cur_id`, `cur_default`, `cur_name`, `cur_code`, `cur_symbol`, `cur_status`) VALUES (3, 0, 'Renminbi (Yuan)', 'RMB', '¥', 0);
INSERT INTO `melis_ecom_currency` (`cur_id`, `cur_default`, `cur_name`, `cur_code`, `cur_symbol`, `cur_status`) VALUES (4, 0, 'Pound Sterling', 'GBP', '£', 0);
INSERT INTO `melis_ecom_currency` (`cur_id`, `cur_default`, `cur_name`, `cur_code`, `cur_symbol`, `cur_status`) VALUES (5, 0, 'Yen', 'JPY', '¥', 0);
INSERT INTO `melis_ecom_currency` (`cur_id`, `cur_default`, `cur_name`, `cur_code`, `cur_symbol`, `cur_status`) VALUES (6, 0, 'Australian Dollar', 'AUD', 'A$', 0);
INSERT INTO `melis_ecom_currency` (`cur_id`, `cur_default`, `cur_name`, `cur_code`, `cur_symbol`, `cur_status`) VALUES (7, 0, 'Philippine Peso', 'PHP', '₱', 0);
INSERT INTO `melis_ecom_currency` (`cur_id`, `cur_default`, `cur_name`, `cur_code`, `cur_symbol`, `cur_status`) VALUES (8, 0, 'Russian Ruble', 'RUB', 'руб', 0);
INSERT INTO `melis_ecom_currency` (`cur_id`, `cur_default`, `cur_name`, `cur_code`, `cur_symbol`, `cur_status`) VALUES (9, 0, 'CFA Franc BCEAO', 'XOF', 'R', 0);
INSERT INTO `melis_ecom_currency` (`cur_id`, `cur_default`, `cur_name`, `cur_code`, `cur_symbol`, `cur_status`) VALUES (10, 0, 'Brazilian Real', 'BRL', 'R$', 0);
INSERT INTO `melis_ecom_currency` (`cur_id`, `cur_default`, `cur_name`, `cur_code`, `cur_symbol`, `cur_status`) VALUES (11, 0, 'Canadian Dollar', 'CAD', '$', 0);
INSERT INTO `melis_ecom_currency` (`cur_id`, `cur_default`, `cur_name`, `cur_code`, `cur_symbol`, `cur_status`) VALUES (12, 0, 'Indian Rupee', 'INR', 'INR', 0);
INSERT INTO `melis_ecom_currency` (`cur_id`, `cur_default`, `cur_name`, `cur_code`, `cur_symbol`, `cur_status`) VALUES (13, 0, 'Won', 'KRW', '₩', 0);

COMMIT;