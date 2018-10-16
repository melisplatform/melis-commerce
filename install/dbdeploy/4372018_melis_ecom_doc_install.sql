-- -----------------------------------------------------
-- Table `melis_ecom_doc_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_doc_type` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_doc_type` (
  `dtype_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Document type Id',
  `dtype_code` VARCHAR(10) NOT NULL COMMENT 'Document code Id, \'IMG\', or \'LARGE\' for a subtype',
  `dtype_name` VARCHAR(45) NOT NULL COMMENT 'Name of the document type, ex \'Image\'',
  `dtype_parent_type_id` INT NULL DEFAULT 0,
  PRIMARY KEY (`dtype_id`))
ENGINE = InnoDB
COMMENT = 'This table stores the different type sof documents that can be saved\n';

-- -----------------------------------------------------
-- Table `melis_ecom_document`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_document` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_document` (
  `doc_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Document Id',
  `doc_name` VARCHAR(255) NOT NULL COMMENT 'Document name',
  `doc_path` VARCHAR(255) NOT NULL COMMENT 'Document Path',
  `doc_type_id` INT NOT NULL COMMENT 'Document type Id, ex \'IMG\' or \'PDF\'',
  `doc_subtype_id` INT NULL COMMENT 'Document subtype Id, ex \'LARGE\' for \'IMG\' type',
  PRIMARY KEY (`doc_id`),
  INDEX `type_idx` (`doc_type_id` ASC),
  INDEX `type2_idx` (`doc_subtype_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the images used in the ecommerce';


-- -----------------------------------------------------
-- Table `melis_ecom_doc_relations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_doc_relations` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_doc_relations` (
  `rdoc_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Relation Document Id',
  `rdoc_doc_id` INT NOT NULL COMMENT 'Document id',
  `rdoc_category_id` INT NULL COMMENT 'Category Id',
  `rdoc_product_id` INT NULL COMMENT 'Product Id',
  `rdoc_variant_id` INT NULL COMMENT 'Variant Id',
  `rdoc_order_id` INT NULL COMMENT 'Order Id',
  `rdoc_country_id` INT NULL COMMENT 'Country Id',
  PRIMARY KEY (`rdoc_id`),
  INDEX `document_idx` (`rdoc_doc_id` ASC),
  INDEX `category_idx` (`rdoc_category_id` ASC),
  INDEX `product_idx` (`rdoc_product_id` ASC),
  INDEX `variant_idx` (`rdoc_variant_id` ASC),
  INDEX `order_idx` (`rdoc_order_id` ASC),
  INDEX `country_idx` (`rdoc_country_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the relations between the documents and products, variants, categories, countries and orders';

-- -----------------------------------------------------
-- Data for table `melis_ecom_doc_type`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_doc_type` (`dtype_id`, `dtype_code`, `dtype_name`, `dtype_parent_type_id`) VALUES (1, 'IMG', 'Image', NULL);
INSERT INTO `melis_ecom_doc_type` (`dtype_id`, `dtype_code`, `dtype_name`, `dtype_parent_type_id`) VALUES (2, 'FILE', 'File', NULL);
INSERT INTO `melis_ecom_doc_type` (`dtype_id`, `dtype_code`, `dtype_name`, `dtype_parent_type_id`) VALUES (3, 'DEFAULT', 'Default', 1);
INSERT INTO `melis_ecom_doc_type` (`dtype_id`, `dtype_code`, `dtype_name`, `dtype_parent_type_id`) VALUES (4, 'SMALL', 'Small', 1);
INSERT INTO `melis_ecom_doc_type` (`dtype_id`, `dtype_code`, `dtype_name`, `dtype_parent_type_id`) VALUES (5, 'BIG', 'Big', 1);

COMMIT;