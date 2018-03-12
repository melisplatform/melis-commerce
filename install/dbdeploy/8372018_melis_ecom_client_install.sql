-- -----------------------------------------------------
-- Table `melis_ecom_client`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_client` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_client` (
  `cli_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Client Id',
  `cli_status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Active / Not active',
  `cli_country_id` INT NOT NULL COMMENT 'Country Id of the client',
  `cli_date_creation` DATETIME NOT NULL COMMENT 'Creation date of the client',
  `cli_date_edit` DATETIME NULL COMMENT 'Last edit date of the client',
  PRIMARY KEY (`cli_id`),
  INDEX `country_idx` (`cli_country_id` ASC))
ENGINE = InnoDB
COMMENT = 'This table stores the clients\' account';

-- -----------------------------------------------------
-- Table `melis_ecom_client_person`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_client_person` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_client_person` (
  `cper_id` INT NOT NULL AUTO_INCREMENT COMMENT 'client person Id, as more than one person can be affected to a client account',
  `cper_client_id` INT NOT NULL COMMENT 'client Id',
  `cper_lang_id` INT NOT NULL COMMENT 'Lang Id',
  `cper_status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Active / Not active',
  `cper_is_main_person` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Main contact of the client account',
  `cper_email` VARCHAR(255) NOT NULL COMMENT 'Email / Login of the person',
  `cper_password` VARCHAR(255) NOT NULL COMMENT 'Password of the person',
  `cper_password_recovery_key` VARCHAR(255) NULL COMMENT 'Hash key used for password recovery',
  `cper_civility` INT NULL DEFAULT NULL COMMENT 'Civility id',
  `cper_name` VARCHAR(255) NOT NULL COMMENT 'Name of person',
  `cper_middle_name` VARCHAR(255) NULL COMMENT 'Middle name of person',
  `cper_firstname` VARCHAR(255) NOT NULL COMMENT 'Firstname of person',
  `cper_job_title` VARCHAR(80) NULL COMMENT 'Job title in the company',
  `cper_job_service` VARCHAR(80) NULL COMMENT 'Job service where person works',
  `cper_tel_mobile` VARCHAR(45) NULL COMMENT 'Mobile phone number',
  `cper_tel_landline` VARCHAR(45) NULL COMMENT 'Landline phone number',
  `cper_date_creation` DATETIME NOT NULL COMMENT 'Creation date of the person',
  `cper_date_edit` DATETIME NULL COMMENT 'Last edit date of the person',
  PRIMARY KEY (`cper_id`),
  INDEX `client_idx` (`cper_client_id` ASC),
  INDEX `lang_idx` (`cper_lang_id` ASC),
  INDEX `civility_idx` (`cper_civility` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the persons linked to a client account';

-- -----------------------------------------------------
-- Table `melis_ecom_client_company`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_client_company` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_client_company` (
  `ccomp_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Company Id',
  `ccomp_client_id` INT NOT NULL COMMENT 'Client Id whom the company is linked to',
  `ccomp_name` VARCHAR(100) NOT NULL COMMENT 'Company name',
  `ccomp_number_id` VARCHAR(150) NULL COMMENT 'Official administration number of the company',
  `ccomp_vat_number` VARCHAR(150) NULL COMMENT 'Official VAT number of the company',
  `ccomp_group` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Group of the company',
  `ccomp_date_creation` DATETIME NOT NULL COMMENT 'Creation date of the company',
  `ccomp_date_edit` DATETIME NULL COMMENT 'Last edit date of the company',
  PRIMARY KEY (`ccomp_id`),
  INDEX `client_idx` (`ccomp_client_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores datas about the company if need be';

-- -----------------------------------------------------
-- Table `melis_ecom_client_address_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_client_address_type` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_client_address_type` (
  `catype_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Type id of address',
  `catype_code` VARCHAR(10) NULL DEFAULT NULL COMMENT 'Code if needed',
  PRIMARY KEY (`catype_id`))
  ENGINE = InnoDB
  COMMENT = 'This table stores the address types';

-- -----------------------------------------------------
-- Table `melis_ecom_client_address`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_client_address` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_client_address` (
  `cadd_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Address Id',
  `cadd_client_id` INT NOT NULL COMMENT 'Client Id if the address is linked directly to the client account',
  `cadd_client_person` INT NULL COMMENT 'Client Person Id if the address is linked to a person',
  `cadd_type` INT NULL COMMENT 'Type of address, delivery, billing, ...',
  `cadd_address_name` VARCHAR(45) NOT NULL COMMENT 'Name of this address given by the client',
  `cadd_civility` INT NULL DEFAULT NULL COMMENT 'Civility Id',
  `cadd_name` VARCHAR(255) NOT NULL COMMENT 'Name',
  `cadd_middle_name` VARCHAR(255) NULL COMMENT 'Middle Name',
  `cadd_firstname` VARCHAR(255) NOT NULL COMMENT 'Firstname',
  `cadd_num` VARCHAR(10) NULL COMMENT 'Number in street',
  `cadd_stairs` VARCHAR(10) NULL COMMENT 'Stairs if needed',
  `cadd_building_name` VARCHAR(45) NULL COMMENT 'Building name if needed',
  `cadd_company` VARCHAR(100) NULL COMMENT 'Company if needed',
  `cadd_street` VARCHAR(255) NULL COMMENT 'Street name',
  `cadd_zipcode` VARCHAR(15) NULL COMMENT 'Zipcode',
  `cadd_city` VARCHAR(100) NULL COMMENT 'City',
  `cadd_state` VARCHAR(50) NULL COMMENT 'State',
  `cadd_country` VARCHAR(50) NULL COMMENT 'Country',
  `cadd_complementary` VARCHAR(255) NULL COMMENT 'Optional informations',
  `cadd_phone_mobile` VARCHAR(45) NULL COMMENT 'Mobile phone number',
  `cadd_phone_landline` VARCHAR(45) NULL COMMENT 'Landline phone number',
  `cadd_creation_date` DATETIME NOT NULL COMMENT 'Creation date of this address',
  PRIMARY KEY (`cadd_id`),
  INDEX `client_idx` (`cadd_client_id` ASC),
  INDEX `clientperson_idx` (`cadd_client_person` ASC),
  INDEX `civility_idx` (`cadd_civility` ASC),
  INDEX `addresstype_idx` (`cadd_type` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the addresses of the clients';

-- -----------------------------------------------------
-- Table `melis_ecom_client_address_type_trans`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_client_address_type_trans` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_client_address_type_trans` (
  `catypt_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Type translation id',
  `catypt_type_id` INT NOT NULL COMMENT 'Type id of address',
  `catypt_lang_id` INT NOT NULL COMMENT 'Lang id for translation',
  `catypt_name` VARCHAR(100) NOT NULL COMMENT 'Type\'s name, ex \'Delivery\'',
  PRIMARY KEY (`catypt_id`),
  INDEX `type_idx` (`catypt_type_id` ASC),
  INDEX `lang_idx` (`catypt_lang_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the translation of the addresses types';

-- -----------------------------------------------------
-- Data for table `melis_ecom_client_address_type`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_client_address_type` (`catype_id`, `catype_code`) VALUES (1, 'BIL');
INSERT INTO `melis_ecom_client_address_type` (`catype_id`, `catype_code`) VALUES (2, 'DEL');

COMMIT;

-- -----------------------------------------------------
-- Data for table `melis_ecom_client_address_type_trans`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_client_address_type_trans` (`catypt_id`, `catypt_type_id`, `catypt_lang_id`, `catypt_name`) VALUES (1, 1, 1, 'Billing');
INSERT INTO `melis_ecom_client_address_type_trans` (`catypt_id`, `catypt_type_id`, `catypt_lang_id`, `catypt_name`) VALUES (2, 1, 2, 'Facturation');
INSERT INTO `melis_ecom_client_address_type_trans` (`catypt_id`, `catypt_type_id`, `catypt_lang_id`, `catypt_name`) VALUES (3, 2, 1, 'Delivery');
INSERT INTO `melis_ecom_client_address_type_trans` (`catypt_id`, `catypt_type_id`, `catypt_lang_id`, `catypt_name`) VALUES (4, 2, 2, 'Livraison');

COMMIT;