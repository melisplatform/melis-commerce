
-- -----------------------------------------------------
-- Table `melis_ecom_attribute_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_attribute_type` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_attribute_type` (
  `atype_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Attribute Type Id',
  `atype_name` VARCHAR(45) NOT NULL COMMENT 'Attribute name',
  `atype_column_value` VARCHAR(45) NOT NULL COMMENT 'Column used in values for saving the value of this attribute',
  PRIMARY KEY (`atype_id`))
ENGINE = InnoDB
COMMENT = 'This table stores the type of attribute, letting user know what type of field is used for the value';

-- -----------------------------------------------------
-- Table `melis_ecom_attribute`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_attribute` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_attribute` (
  `attr_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Attribute Id',
  `attr_type_id` INT NOT NULL COMMENT 'Type Id of the attribute',
  `attr_status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Active / Not active',
  `attr_reference` VARCHAR(45) NULL COMMENT 'REference of the attribute',
  `attr_visible` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Display or not the attribute',
  `attr_searchable` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Used to know if searches can use this attribute',
  PRIMARY KEY (`attr_id`),
  INDEX `type_idx` (`attr_type_id` ASC))
ENGINE = InnoDB
COMMENT = 'This table stores the attributes that can be used by the products';

-- -----------------------------------------------------
-- Table `melis_ecom_attribute_trans`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_attribute_trans` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_attribute_trans` (
  `atrans_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Attribute translation Id',
  `atrans_attribute_id` INT NOT NULL COMMENT 'Attribute Id',
  `atrans_lang_id` INT NOT NULL COMMENT 'Lang Id of the translation',
  `atrans_name` VARCHAR(100) NOT NULL COMMENT 'Name of the attribute',
  `atrans_description` VARCHAR(45) NULL COMMENT 'Description of the attribute',
  PRIMARY KEY (`atrans_id`),
  INDEX `attributeid_idx` (`atrans_attribute_id` ASC),
  INDEX `langid_idx` (`atrans_lang_id` ASC))
ENGINE = InnoDB
COMMENT = 'This table stores the translations of the attributes names';

-- -----------------------------------------------------
-- Table `melis_ecom_attribute_value`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_attribute_value` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_attribute_value` (
  `atval_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Attribute Value Id',
  `atval_attribute_id` INT NOT NULL COMMENT 'Attribute Id which the value is linked to',
  `atval_type_id` INT NOT NULL COMMENT 'Type of attribute',
  `atval_reference` VARCHAR(45) NULL COMMENT 'Reference of the value',
  PRIMARY KEY (`atval_id`),
  INDEX `attributeid_idx` (`atval_attribute_id` ASC))
ENGINE = InnoDB
COMMENT = 'This table stores the values of the attributes';

-- -----------------------------------------------------
-- Table `melis_ecom_attribute_value_trans`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_attribute_value_trans` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_attribute_value_trans` (
  `avt_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Attribute value translation Id',
  `av_attribute_value_id` INT NOT NULL COMMENT 'Attribute Value Id of the translation',
  `avt_lang_id` INT NOT NULL COMMENT 'Lang Id of the translation',
  `avt_v_int` INT NULL COMMENT 'Saving in here if type of attribute is INT',
  `avt_v_float` FLOAT NULL COMMENT 'Saving in here if type of attribute is FLOAT',
  `avt_v_bool` TINYINT(1) NULL COMMENT 'Saving in here if type of attribute is BOOLEAN',
  `avt_v_varchar` VARCHAR(255) NULL COMMENT 'Saving in here if type of attribute is short text - varchar',
  `avt_v_text` TEXT NULL COMMENT 'Saving in here if type of attribute is long text',
  `avt_v_datetime` DATETIME NULL COMMENT 'Saving in here if type of attribute is a date',
  `avt_v_binary` LONGBLOB NULL COMMENT 'Saving in here if type of attribute is binary',
  PRIMARY KEY (`avt_id`),
  INDEX `attributevalueid_idx` (`av_attribute_value_id` ASC),
  INDEX `langid_idx` (`avt_lang_id` ASC))
ENGINE = InnoDB
COMMENT = 'This table stores the values of the attributes';

-- -----------------------------------------------------
-- Data for table `melis_ecom_attribute_type`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_attribute_type` (`atype_id`, `atype_name`, `atype_column_value`) VALUES (1, 'Integer', 'int');
INSERT INTO `melis_ecom_attribute_type` (`atype_id`, `atype_name`, `atype_column_value`) VALUES (2, 'Decimal', 'float');
INSERT INTO `melis_ecom_attribute_type` (`atype_id`, `atype_name`, `atype_column_value`) VALUES (3, 'Boolean', 'bool');
INSERT INTO `melis_ecom_attribute_type` (`atype_id`, `atype_name`, `atype_column_value`) VALUES (4, 'Short string', 'varchar');
INSERT INTO `melis_ecom_attribute_type` (`atype_id`, `atype_name`, `atype_column_value`) VALUES (5, 'Text', 'text');
INSERT INTO `melis_ecom_attribute_type` (`atype_id`, `atype_name`, `atype_column_value`) VALUES (6, 'Date and time', 'datetime');
INSERT INTO `melis_ecom_attribute_type` (`atype_id`, `atype_name`, `atype_column_value`) VALUES (7, 'File', 'binary');

COMMIT;