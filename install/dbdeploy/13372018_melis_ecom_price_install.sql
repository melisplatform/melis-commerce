-- -----------------------------------------------------
-- Table `melis_ecom_price`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_price` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_price` (
  `price_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Price Id for the product',
  `price_prd_id` INT NULL COMMENT 'Product Id, if all variants have the same price',
  `price_var_id` INT NULL COMMENT 'Variant Id, if variants have different prices',
  `price_country_id` INT NOT NULL DEFAULT 0 COMMENT 'Country Id if the price is different per country',
  `price_currency` INT NOT NULL COMMENT 'Currency of the price',
  `price_net` DECIMAL(10,2) NULL COMMENT 'Net price, all-in',
  `price_gross` DECIMAL(10,2) NULL COMMENT 'Gross price, without taxes',
  `price_vat_percent` DECIMAL(10,2) NULL COMMENT 'Percentage of VAT for this product',
  `price_vat_price` DECIMAL(10,2) NULL COMMENT 'VAT price',
  `price_other_tax_price` DECIMAL(10,2) NULL COMMENT 'Any other taxes to add',
  PRIMARY KEY (`price_id`),
  INDEX `product_idx` (`price_prd_id` ASC),
  INDEX `variant_idx` (`price_var_id` ASC),
  INDEX `country_idx` (`price_country_id` ASC),
  INDEX `cuurency_idx` (`price_currency` ASC))
ENGINE = InnoDB
COMMENT = 'This table stores the price of the product or variant';