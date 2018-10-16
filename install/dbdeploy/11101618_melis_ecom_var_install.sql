-- -----------------------------------------------------
-- Table `melis_ecom_variant`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_variant` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_variant` (
  `var_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Variant Id, final product which is really sold',
  `var_prd_id` INT NOT NULL COMMENT 'Product Id of the variant',
  `var_status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Active / Not active',
  `var_sku` VARCHAR(100) NULL COMMENT 'SKU of the variant, unique selling code',
  `var_main_variant` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Determines if the variant is the main one for pushing it on top or using its image in display',
  `var_date_creation` DATETIME NOT NULL COMMENT 'Creation date of the variant',
  `var_user_id_creation` INT NOT NULL COMMENT 'BO user Id who created the variant',
  `var_date_edit` DATETIME NULL COMMENT 'Last edit date of the variant',
  `var_user_id_edit` INT NULL COMMENT 'Last BO user who edited the variant',
  PRIMARY KEY (`var_id`),
  INDEX `product_idx` (`var_prd_id` ASC))
ENGINE = InnoDB
COMMENT = 'This table stores the variants';

-- -----------------------------------------------------
-- Table `melis_ecom_variant_stock`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_variant_stock` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_variant_stock` (
  `stock_id` INT NOT NULL AUTO_INCREMENT,
  `stock_var_id` INT NOT NULL,
  `stock_country_id` INT NOT NULL DEFAULT 0,
  `stock_quantity` INT NULL DEFAULT 0,
  `stock_low` INT NULL DEFAULT NULL,
  `stock_qty_email_sent` SMALLINT NULL DEFAULT 0,
  `stock_next_fill_up` DATETIME NULL,
  PRIMARY KEY (`stock_id`),
  INDEX `variant_idx` (`stock_var_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stocks the stocks of a variant';

-- -----------------------------------------------------
-- Table `melis_ecom_variant_attribute_value`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_variant_attribute_value` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_variant_attribute_value` (
  `vatv_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Relation attribute value variant Id',
  `vatv_variant_id` INT NOT NULL COMMENT 'Variant Id',
  `vatv_attribute_value_id` INT NOT NULL COMMENT 'Attribute Value Id',
  PRIMARY KEY (`vatv_id`),
  INDEX `variant_idx` (`vatv_variant_id` ASC),
  INDEX `attributevalue_idx` (`vatv_attribute_value_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the the values of the attribute for variants';
