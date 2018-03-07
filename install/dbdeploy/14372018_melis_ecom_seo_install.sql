-- -----------------------------------------------------
-- Table `melis_ecom_seo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_seo` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_seo` (
  `eseo_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Ecommerce SEO id',
  `eseo_page_id` INT NULL DEFAULT NULL COMMENT 'The page id to redirect to',
  `eseo_category_id` INT NULL COMMENT 'Category id if seo is linked to a category',
  `eseo_product_id` INT NULL COMMENT 'Product Id if SEO is linked to product',
  `eseo_variant_id` INT NULL COMMENT 'Variant Id if SEO is linked to variant',
  `eseo_lang_id` INT NOT NULL COMMENT 'Lang id if multiple pages for 1 item',
  `eseo_url` VARCHAR(255) NULL COMMENT 'URL',
  `eseo_url_redirect` VARCHAR(255) NULL COMMENT 'Url to redirect if needed',
  `eseo_url_301` VARCHAR(255) NULL COMMENT 'Url to redirect if item offline',
  `eseo_meta_title` VARCHAR(255) NULL COMMENT 'Meta title',
  `eseo_meta_description` VARCHAR(255) NULL COMMENT 'Meta description',
  PRIMARY KEY (`eseo_id`),
  INDEX `category_id_idx` (`eseo_category_id` ASC),
  INDEX `product_id_idx` (`eseo_product_id` ASC))
ENGINE = InnoDB;
