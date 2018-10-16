-- -----------------------------------------------------
-- Table `melis_ecom_civility`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_civility` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_civility` (
  `civ_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Civility Id',
  PRIMARY KEY (`civ_id`))
ENGINE = InnoDB
COMMENT = 'This table stores the civilities per language and country';

-- -----------------------------------------------------
-- Table `melis_ecom_civility_trans`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_civility_trans` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_civility_trans` (
  `civt_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Civility translation Id',
  `civt_civ_id` INT NOT NULL COMMENT 'Civility Id',
  `civt_lang_id` INT NOT NULL COMMENT 'Lang Id for translation',
  `civt_min_name` VARCHAR(45) NOT NULL COMMENT 'Abbreviation of the civility, ex \'M\'',
  `civt_max_name` VARCHAR(45) NOT NULL COMMENT 'Full translation of the civility, ex \"Madame\"',
  PRIMARY KEY (`civt_id`),
  INDEX `lang_idx` (`civt_lang_id` ASC),
  INDEX `civility_idx` (`civt_civ_id` ASC))
ENGINE = InnoDB
COMMENT = 'This table stores the translations of the civilities';

-- -----------------------------------------------------
-- Data for table `melis_ecom_civility`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_civility` (`civ_id`) VALUES (1);
INSERT INTO `melis_ecom_civility` (`civ_id`) VALUES (2);
INSERT INTO `melis_ecom_civility` (`civ_id`) VALUES (3);

COMMIT;

-- -----------------------------------------------------
-- Data for table `melis_ecom_civility_trans`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_civility_trans` (`civt_id`, `civt_civ_id`, `civt_lang_id`, `civt_min_name`, `civt_max_name`) VALUES (1, 1, 1, 'Mr', 'Mister');
INSERT INTO `melis_ecom_civility_trans` (`civt_id`, `civt_civ_id`, `civt_lang_id`, `civt_min_name`, `civt_max_name`) VALUES (2, 1, 2, 'M', 'Monsieur');
INSERT INTO `melis_ecom_civility_trans` (`civt_id`, `civt_civ_id`, `civt_lang_id`, `civt_min_name`, `civt_max_name`) VALUES (3, 2, 1, 'Mrs', 'Madam');
INSERT INTO `melis_ecom_civility_trans` (`civt_id`, `civt_civ_id`, `civt_lang_id`, `civt_min_name`, `civt_max_name`) VALUES (4, 2, 2, 'Mme', 'Madame');
INSERT INTO `melis_ecom_civility_trans` (`civt_id`, `civt_civ_id`, `civt_lang_id`, `civt_min_name`, `civt_max_name`) VALUES (5, 3, 1, 'Miss', 'Miss');
INSERT INTO `melis_ecom_civility_trans` (`civt_id`, `civt_civ_id`, `civt_lang_id`, `civt_min_name`, `civt_max_name`) VALUES (6, 3, 2, 'Mlle', 'Mademoiselle');

COMMIT;