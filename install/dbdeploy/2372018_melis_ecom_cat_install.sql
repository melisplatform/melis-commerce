-- -----------------------------------------------------
-- Table `melis_ecom_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_category` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_category` (
  `cat_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Category ID',
  `cat_father_cat_id` INT NOT NULL DEFAULT -1 COMMENT 'The id of the parent\'s category. If first category, -1',
  `cat_order` INT NOT NULL DEFAULT 1 COMMENT 'Displaying order',
  `cat_status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Active / not active',
  `cat_reference` VARCHAR(45) NULL COMMENT 'Reference of the category',
  `cat_date_valid_start` DATETIME NULL COMMENT 'Starting datetime for showing the category',
  `cat_date_valid_end` DATETIME NULL COMMENT 'Ending datetime for showing the category',
  `cat_date_creation` DATETIME NOT NULL COMMENT 'Creation date of this category',
  `cat_user_id_creation` INT NOT NULL COMMENT 'BO user who created this category',
  `cat_date_edit` DATETIME NULL COMMENT 'Last edition date',
  `cat_user_id_edit` INT NULL COMMENT 'Last BO user who created this category',
  PRIMARY KEY (`cat_id`),
  INDEX `father_idx` (`cat_father_cat_id` ASC))
ENGINE = InnoDB
COMMENT = 'This table stores the categories of the ecommerce database';

-- -----------------------------------------------------
-- Table `melis_ecom_category_trans`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_category_trans` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_category_trans` (
  `catt_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Category translation Id',
  `catt_category_id` INT NOT NULL COMMENT 'Category Id',
  `catt_lang_id` INT NOT NULL COMMENT 'Lang Id',
  `catt_name` VARCHAR(100) NOT NULL COMMENT 'Name of the category in the given lang',
  `catt_description` TEXT NULL COMMENT 'Description of the category in the given lang',
  PRIMARY KEY (`catt_id`),
  INDEX `category_idx` (`catt_category_id` ASC),
  INDEX `lang_idx` (`catt_lang_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the translations of categories';

-- -----------------------------------------------------
-- Data for table `melis_ecom_category`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_category` (`cat_id`, `cat_father_cat_id`, `cat_order`, `cat_status`, `cat_reference`, `cat_date_valid_start`, `cat_date_valid_end`, `cat_date_creation`, `cat_user_id_creation`, `cat_date_edit`, `cat_user_id_edit`) VALUES (1, -1, 1, 1, NULL, NULL, NULL, '2016-12-01 00:00:01', 1, NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `melis_ecom_category_trans`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_category_trans` (`catt_id`, `catt_category_id`, `catt_lang_id`, `catt_name`, `catt_description`) VALUES (1, 1, 1, 'My catalog', NULL);
INSERT INTO `melis_ecom_category_trans` (`catt_id`, `catt_category_id`, `catt_lang_id`, `catt_name`, `catt_description`) VALUES (2, 1, 2, 'Mon catalogue', NULL);

COMMIT;
