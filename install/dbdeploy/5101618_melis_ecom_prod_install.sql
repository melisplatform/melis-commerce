-- -----------------------------------------------------
-- Table `melis_ecom_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_product` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_product` (
  `prd_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Product Id',
  `prd_reference` VARCHAR(45) NULL COMMENT 'Reference for this product',
  `prd_status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Active / Not acitve',
  `prd_stock_low` INT NULL DEFAULT NULL,
  `prd_date_creation` DATETIME NULL DEFAULT NULL COMMENT 'Creation date of this product',
  `prd_user_id_creation` INT NULL DEFAULT NULL COMMENT 'BO user who created this product',
  `prd_date_edit` DATETIME NULL COMMENT 'Last edit date of this product',
  `prd_user_id_edit` INT NULL COMMENT 'Last BO User who edited this product',
  PRIMARY KEY (`prd_id`))
ENGINE = InnoDB
COMMENT = 'This table stores the products';

-- -----------------------------------------------------
-- Table `melis_ecom_product_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_product_category` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_product_category` (
  `pcat_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Relation product category Id',
  `pcat_prd_id` INT NOT NULL COMMENT 'Product Id',
  `pcat_cat_id` INT NOT NULL COMMENT 'Category Id',
  `pcat_order` INT NOT NULL COMMENT 'Number for displaying product in a specific order',
  PRIMARY KEY (`pcat_id`),
  INDEX `product_idx` (`pcat_prd_id` ASC),
  INDEX `category_idx` (`pcat_cat_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the relation between categories ans products';

-- -----------------------------------------------------
-- Table `melis_ecom_product_text_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_product_text_type` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_product_text_type` (
  `ptt_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Text\'s type Id',
  `ptt_code` VARCHAR(45) NOT NULL COMMENT 'Code for the type, ex \'TITLE\' or \'DESCRIPTION\'',
  `ptt_name` VARCHAR(255) NOT NULL COMMENT 'Name of the type',
  `ptt_field_type` INT NULL COMMENT '1 for short text, 2 for long text',
  PRIMARY KEY (`ptt_id`))
  ENGINE = InnoDB
  COMMENT = 'This table stores the type of product text';

-- -----------------------------------------------------
-- Table `melis_ecom_product_text`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_product_text` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_product_text` (
  `ptxt_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Product\'s translation Id',
  `ptxt_prd_id` INT NOT NULL COMMENT 'Product Id',
  `ptxt_lang_id` INT NOT NULL COMMENT 'Lang Id',
  `ptxt_type` INT NOT NULL COMMENT 'Type of the text. The type is used to define different text zones and be able to find them easily',
  `ptxt_field_short` VARCHAR(255) NULL COMMENT 'Varchar field to be used if text is short',
  `ptxt_field_long` TEXT NULL COMMENT 'Text field to be used if text is long',
  PRIMARY KEY (`ptxt_id`),
  INDEX `product_idx` (`ptxt_prd_id` ASC),
  INDEX `lang_idx` (`ptxt_lang_id` ASC),
  INDEX `type_idx` (`ptxt_type` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the texts associated with the products';

-- -----------------------------------------------------
-- Table `melis_ecom_product_attribute`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_product_attribute` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_product_attribute` (
  `patt_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Relation product Attribute Id',
  `patt_product_id` INT NOT NULL COMMENT 'Product id',
  `patt_attribute_id` INT NOT NULL COMMENT 'Attribute Id',
  PRIMARY KEY (`patt_id`),
  INDEX `product_idx` (`patt_product_id` ASC),
  INDEX `attribute_idx` (`patt_attribute_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the relation between a product and its attributes';

-- -----------------------------------------------------
-- Data for table `melis_ecom_product_text_type`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_product_text_type` (`ptt_id`, `ptt_code`, `ptt_name`, `ptt_field_type`) VALUES (1, 'TITLE', 'Title', 1);

COMMIT;