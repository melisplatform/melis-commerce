-- -----------------------------------------------------
-- Table `melis_ecom_stock_email_alert`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_stock_email_alert` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_stock_email_alert` (
  `sea_id` INT NOT NULL AUTO_INCREMENT,
  `sea_stock_level_alert` INT NULL,
  `sea_email` VARCHAR(255) NULL,
  `sea_prd_id` INT NULL,
  `sea_user_id` INT NULL,
  PRIMARY KEY (`sea_id`),
  INDEX `fk_melis_ecom_stock_email_alert_melis_ecom_product1_idx` (`sea_prd_id` ASC))
ENGINE = InnoDB;