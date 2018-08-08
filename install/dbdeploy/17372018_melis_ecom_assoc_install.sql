-- -----------------------------------------------------
-- Table `melis_ecom_assoc_variants_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_assoc_variants_type` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_assoc_variants_type` (
  `avart_id` INT NOT NULL AUTO_INCREMENT,
  `avart_code` VARCHAR(45) NOT NULL,
  `avart_name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`avart_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `melis_ecom_assoc_variant`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_assoc_variant` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_assoc_variant` (
  `avar_id` INT NOT NULL AUTO_INCREMENT,
  `avar_one` INT NOT NULL,
  `avar_two` INT NOT NULL,
  `avar_type_id` INT NULL,
  PRIMARY KEY (`avar_id`),
  INDEX `var_id_idx` (`avar_one` ASC),
  INDEX `var_id_idx1` (`avar_two` ASC),
  INDEX `avart_id_idx` (`avar_type_id` ASC))
ENGINE = InnoDB;
