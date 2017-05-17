ALTER TABLE `melis_ecom_coupon` ADD `coup_product_assign` INT NOT NULL AFTER `coup_type`;

CREATE TABLE IF NOT EXISTS `melis_ecom_coupon_product` (
  `cprod_id` INT NOT NULL AUTO_INCREMENT,
  `cprod_coupon_id` INT NOT NULL,
  `cprod_product_id` INT NOT NULL,
  PRIMARY KEY (`cprod_id`),
  INDEX `fk_melis_ecom_coupon_product_melis_ecom_coupon1_idx` (`cprod_coupon_id` ASC),
  INDEX `fk_melis_ecom_coupon_product_melis_ecom_product1_idx` (`cprod_product_id` ASC))
ENGINE = InnoDB;