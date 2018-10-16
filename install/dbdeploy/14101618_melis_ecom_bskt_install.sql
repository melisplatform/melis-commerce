-- -----------------------------------------------------
-- Table `melis_ecom_basket_anonymous`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_basket_anonymous` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_basket_anonymous` (
  `bano_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Anonymous Id of this basket',
  `bano_key` VARCHAR(100) NOT NULL COMMENT 'Cookie saved key for this basket',
  `bano_variant_id` INT NOT NULL COMMENT 'Variant Id in the basket',
  `bano_quantity` INT NOT NULL DEFAULT 1 COMMENT 'Quantity of this variant in the basket',
  `bano_date_added` DATETIME NULL COMMENT 'Datetime when added to the basket',
  PRIMARY KEY (`bano_id`),
  INDEX `variant_idx` (`bano_variant_id` ASC))
ENGINE = InnoDB
COMMENT = 'This table stores basket when no client is yet identified';

-- -----------------------------------------------------
-- Table `melis_ecom_basket_persistent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_basket_persistent` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_basket_persistent` (
  `bper_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Persistent Basket Id',
  `bper_client_id` INT NOT NULL COMMENT 'Client Id whom the basket belongs to',
  `bper_variant_id` INT NOT NULL COMMENT 'Variant Id in the basket',
  `bper_quantity` INT NOT NULL DEFAULT 1 COMMENT 'Quantity of this variant in the basket',
  `bper_date_added` DATETIME NULL COMMENT 'Datetime when this variant has been added to the basket',
  PRIMARY KEY (`bper_id`),
  INDEX `variant_idx` (`bper_variant_id` ASC),
  INDEX `client_idx` (`bper_client_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the basket of an identified client';