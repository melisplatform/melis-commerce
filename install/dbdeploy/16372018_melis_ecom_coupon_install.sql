
-- -----------------------------------------------------
-- Table `melis_ecom_coupon`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_coupon` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_coupon` (
  `coup_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Coupon Id',
  `coup_code` VARCHAR(45) NOT NULL COMMENT 'Code of the coupon',
  `coup_status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Active / Not active',
  `coup_type` INT NOT NULL COMMENT 'Type of coupon: for all or affected to a client',
  `coup_product_assign` INT NOT NULL,
  `coup_percentage` FLOAT NULL COMMENT 'Percentage of discount',
  `coup_discount_value` FLOAT NULL COMMENT 'Value of discount',
  `coup_max_use_number` INT NULL DEFAULT NULL COMMENT 'Max number of use',
  `coup_current_use_number` INT NOT NULL DEFAULT 0 COMMENT 'Current number of use',
  `coup_date_valid_start` DATETIME NULL COMMENT 'Start datetime of validity',
  `coup_date_valid_end` DATETIME NULL COMMENT 'End datetime of validity',
  `coup_date_creation` DATETIME NOT NULL COMMENT 'Creation date of the coupon',
  `coup_user_id` INT NOT NULL COMMENT 'BO user id who created the coupon',
  PRIMARY KEY (`coup_id`))
ENGINE = InnoDB
COMMENT = 'This table stores the coupons which can be used in the shop	';


-- -----------------------------------------------------
-- Table `melis_ecom_coupon_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_coupon_order` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_coupon_order` (
  `cord_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Relation coupon order Id',
  `cord_coupon_id` INT NOT NULL COMMENT 'Coupon Id',
  `cord_order_id` INT NOT NULL COMMENT 'Order Id',
  `cord_basket_id` INT NULL DEFAULT NULL,
  `cord_status` INT NULL DEFAULT NULL,
  `cord_quantity_used` INT NOT NULL,
  PRIMARY KEY (`cord_id`),
  INDEX `coupon_idx` (`cord_coupon_id` ASC),
  INDEX `order_idx` (`cord_order_id` ASC))
ENGINE = InnoDB
COMMENT = 'This table stores the relation between used coupons and their order';


-- -----------------------------------------------------
-- Table `melis_ecom_coupon_client`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_coupon_client` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_coupon_client` (
  `ccli_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Relation coupon client Id',
  `ccli_coupon_id` INT NOT NULL COMMENT 'Coupon Id',
  `ccli_client_id` INT NOT NULL COMMENT 'Client Id',
  PRIMARY KEY (`ccli_id`),
  INDEX `coupon_idx` (`ccli_coupon_id` ASC),
  INDEX `client_idx` (`ccli_client_id` ASC))
ENGINE = InnoDB
COMMENT = 'This table stores the coupons that are affected to a client';

-- -----------------------------------------------------
-- Table `melis_ecom_coupon_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_coupon_product` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_coupon_product` (
  `cprod_id` INT NOT NULL AUTO_INCREMENT,
  `cprod_coupon_id` INT NOT NULL,
  `cprod_product_id` INT NOT NULL,
  PRIMARY KEY (`cprod_id`),
  INDEX `fk_melis_ecom_coupon_product_melis_ecom_coupon1_idx` (`cprod_coupon_id` ASC),
  INDEX `fk_melis_ecom_coupon_product_melis_ecom_product1_idx` (`cprod_product_id` ASC))
  ENGINE = InnoDB;
