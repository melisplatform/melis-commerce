-- -----------------------------------------------------
-- Table `melis_ecom_order_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_order_status` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_order_status` (
  `osta_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Order status Id',
  `osta_color_code` VARCHAR(45) NOT NULL COMMENT 'Color associated to this status',
  `osta_status` TINYINT(1) NULL DEFAULT 1,
  PRIMARY KEY (`osta_id`))
ENGINE = InnoDB
COMMENT = 'This table stores the different status available for an order';

-- -----------------------------------------------------
-- Table `melis_ecom_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_order` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_order` (
  `ord_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Order Id',
  `ord_client_id` INT NOT NULL COMMENT 'Client Id',
  `ord_client_person_id` INT NOT NULL COMMENT 'Person who made the order',
  `ord_status` INT NOT NULL COMMENT 'Order status Id',
  `ord_country_id` INT NOT NULL COMMENT 'Country where the checkout based',
  `ord_reference` VARCHAR(100) NOT NULL COMMENT 'Order reference',
  `ord_billing_address` INT NOT NULL COMMENT 'Order address Id for billing',
  `ord_delivery_address` INT NOT NULL COMMENT 'Order address Id for delivery',
  `ord_date_creation` DATETIME NOT NULL COMMENT 'Creation date of the order',
  PRIMARY KEY (`ord_id`),
  INDEX `status_idx` (`ord_status` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the orders made by clients';

-- -----------------------------------------------------
-- Table `melis_ecom_order_status_trans`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_order_status_trans` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_order_status_trans` (
  `ostt_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Order status translation Id',
  `ostt_status_id` INT NOT NULL COMMENT 'Order Status Id',
  `ostt_lang_id` INT NOT NULL COMMENT 'Lang Id of the translation',
  `ostt_status_name` VARCHAR(50) NOT NULL COMMENT 'Name of the order status',
  PRIMARY KEY (`ostt_id`),
  INDEX `status_idx` (`ostt_status_id` ASC),
  INDEX `lang_idx` (`ostt_lang_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the different status translations available for an order';

-- -----------------------------------------------------
-- Table `melis_ecom_order_basket`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_order_basket` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_order_basket` (
  `obas_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Order basket Id',
  `obas_order_id` INT NOT NULL COMMENT 'Order Id',
  `obas_variant_id` INT NOT NULL COMMENT 'Variant Id',
  `obas_product_name` VARCHAR(255) NOT NULL COMMENT 'Name of the product',
  `obas_quantity` INT NOT NULL COMMENT 'Quantity of this variant ordered',
  `obas_sent` INT NOT NULL DEFAULT 0 COMMENT 'Item(s) sent',
  `obas_sku` VARCHAR(100) NOT NULL COMMENT 'Sku of the variant',
  `obas_attributes` TEXT NULL COMMENT 'Attributes list of the variant',
  `obas_category_id` INT NULL COMMENT 'Category where the variant was affected',
  `obas_category_name` VARCHAR(255) NULL COMMENT 'Name of the category where the variant was affected',
  `obas_currency` INT NOT NULL COMMENT 'Currency id of the price of the product',
  `obas_price_net` FLOAT NOT NULL COMMENT 'Net price of the product',
  `obas_price_gross` FLOAT NULL COMMENT 'Gross price of the product',
  `obas_price_vat` FLOAT NULL COMMENT 'VAT price of the product',
  `obas_price_other_tax` FLOAT NULL COMMENT 'Other taxes price of the product',
  PRIMARY KEY (`obas_id`),
  INDEX `order_idx` (`obas_order_id` ASC),
  INDEX `variant_idx` (`obas_variant_id` ASC),
  INDEX `currency_idx` (`obas_currency` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the basket of the order';

-- -----------------------------------------------------
-- Table `melis_ecom_order_address`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_order_address` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_order_address` (
  `oadd_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Order address Id',
  `oadd_order_id` INT NOT NULL COMMENT 'Order Id',
  `oadd_type` INT NULL COMMENT 'Type of address, delivery or billing',
  `oadd_civility` INT NULL COMMENT 'Civility Id',
  `oadd_name` VARCHAR(255) NOT NULL COMMENT 'Name',
  `oadd_middle_name` VARCHAR(255) NULL COMMENT 'Middle name',
  `oadd_firstname` VARCHAR(255) NOT NULL COMMENT 'Firstname',
  `oadd_num` VARCHAR(10) NULL COMMENT 'Number in the street',
  `oadd_stairs` VARCHAR(10) NULL COMMENT 'Stairs',
  `oadd_building_name` VARCHAR(45) NULL COMMENT 'Building Name',
  `oadd_company` VARCHAR(100) NULL COMMENT 'Company',
  `oadd_street` VARCHAR(255) NULL COMMENT 'Street',
  `oadd_zipcode` VARCHAR(15) NULL COMMENT 'Zipcode',
  `oadd_city` VARCHAR(100) NULL COMMENT 'City',
  `oadd_state` VARCHAR(50) NULL COMMENT 'State',
  `oadd_country` VARCHAR(50) NULL COMMENT 'Country',
  `oadd_complementary` VARCHAR(255) NULL COMMENT 'Optional informations',
  `oadd_phone_mobile` VARCHAR(45) NULL COMMENT 'Mobile phone number',
  `oadd_phone_landline` VARCHAR(45) NULL COMMENT 'Landline phone number',
  `oadd_creation_date` DATETIME NOT NULL COMMENT 'Creation date of the order address',
  PRIMARY KEY (`oadd_id`),
  INDEX `civility_idx` (`oadd_civility` ASC),
  INDEX `order_idx` (`oadd_order_id` ASC),
  INDEX `type_idx` (`oadd_type` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the addresses defined for the order';

-- -----------------------------------------------------
-- Table `melis_ecom_order_payment_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_order_payment_type` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_order_payment_type` (
  `opty_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Payment Type Id',
  `opty_status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Active / Not active',
  `opty_code` VARCHAR(10) NOT NULL COMMENT 'Code of payment, ex \'VISA\'',
  `opty_name` VARCHAR(50) NOT NULL COMMENT 'Payment name',
  PRIMARY KEY (`opty_id`))
  ENGINE = InnoDB
  COMMENT = 'This table stores the different types of payment allowed';

-- -----------------------------------------------------
-- Table `melis_ecom_order_payment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_order_payment` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_order_payment` (
  `opay_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Order Payment Id',
  `opay_order_id` INT NOT NULL COMMENT 'Order Id',
  `opay_price_total` FLOAT NOT NULL COMMENT 'Total price of the order',
  `opay_price_order` FLOAT NOT NULL COMMENT 'Price of the basket only',
  `opay_price_shipping` FLOAT NOT NULL DEFAULT 0 COMMENT 'Price of the shipping',
  `opay_currency_id` INT NOT NULL COMMENT 'Payment currency',
  `opay_payment_type_id` INT NOT NULL COMMENT 'Type of payment Id',
  `opay_transac_id` VARCHAR(100) NOT NULL COMMENT 'Transaction return Id',
  `opay_transac_return_value` VARCHAR(10) NOT NULL COMMENT 'Transaction return value',
  `opay_transac_price_paid_confirm` FLOAT NOT NULL COMMENT 'Transaction confirmed price  paid',
  `opay_transac_raw_response` TEXT NOT NULL COMMENT 'Raw transaction received undecoded',
  `opay_date_payment` DATETIME NOT NULL COMMENT 'Datetime of payment',
  PRIMARY KEY (`opay_id`),
  INDEX `order_idx` (`opay_order_id` ASC),
  INDEX `cuurency_idx` (`opay_currency_id` ASC),
  INDEX `paymentype_idx` (`opay_payment_type_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the payment datas about the order';

-- -----------------------------------------------------
-- Table `melis_ecom_order_shipping`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_order_shipping` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_order_shipping` (
  `oship_id` INT NOT NULL AUTO_INCREMENT,
  `oship_order_id` INT NOT NULL,
  `oship_tracking_code` VARCHAR(100) NOT NULL,
  `oship_content` TEXT NOT NULL,
  `oship_date_sent` DATETIME NOT NULL,
  PRIMARY KEY (`oship_id`),
  INDEX `order_idx` (`oship_order_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores the shipment made for orders';

-- -----------------------------------------------------
-- Table `melis_ecom_order_message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_order_message` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_order_message` (
  `omsg_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Order message Id',
  `omsg_order_id` INT NOT NULL COMMENT 'Order Id ',
  `omsg_client_id` INT NOT NULL COMMENT 'Client id',
  `omsg_client_person_id` INT NULL COMMENT 'Client Person talking',
  `omsg_user_id` INT NULL DEFAULT NULL COMMENT 'BO user Id who is talking',
  `omsg_message` TEXT NOT NULL COMMENT 'Message',
  `omsg_date_creation` DATETIME NOT NULL COMMENT 'Creation date of the message',
  PRIMARY KEY (`omsg_id`),
  INDEX `order_idx` (`omsg_order_id` ASC),
  INDEX `client_idx` (`omsg_client_id` ASC),
  INDEX `clientperson_idx` (`omsg_client_person_id` ASC))
  ENGINE = InnoDB
  COMMENT = 'This table stores messages exchanged by the client and the shop about orders';

-- -----------------------------------------------------
-- Data for table `melis_ecom_order_status`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_order_status` (`osta_id`, `osta_color_code`, `osta_status`) VALUES (1, '#0696cb', NULL);
INSERT INTO `melis_ecom_order_status` (`osta_id`, `osta_color_code`, `osta_status`) VALUES (2, '#f08c17', NULL);
INSERT INTO `melis_ecom_order_status` (`osta_id`, `osta_color_code`, `osta_status`) VALUES (3, '#06cb65', NULL);
INSERT INTO `melis_ecom_order_status` (`osta_id`, `osta_color_code`, `osta_status`) VALUES (4, '#06cb65', NULL);
INSERT INTO `melis_ecom_order_status` (`osta_id`, `osta_color_code`, `osta_status`) VALUES (5, '#ababab', NULL);
INSERT INTO `melis_ecom_order_status` (`osta_id`, `osta_color_code`, `osta_status`) VALUES (6, '#ff3300', NULL);
INSERT INTO `melis_ecom_order_status` (`osta_id`, `osta_color_code`, `osta_status`) VALUES (-1, '#00ccff', NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `melis_ecom_order_status_trans`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (1, 1, 1, 'New order');
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (2, 1, 2, 'Nouvelle commande');
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (3, 2, 1, 'Order on hold');
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (4, 2, 2, 'Commande en attente');
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (5, 3, 1, 'Order shipped');
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (6, 3, 2, 'Commande envoyée');
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (7, 4, 1, 'Order delivered');
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (8, 4, 2, 'Commande livrée');
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (9, 5, 1, 'Order canceled');
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (10, 5, 2, 'Commande annulée');
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (11, 6, 1, 'Error payment');
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (12, 6, 2, 'Erreur paiement');
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (13, -1, 1, 'Temporary');
INSERT INTO `melis_ecom_order_status_trans` (`ostt_id`, `ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES (14, -1, 2, 'Temporary');

COMMIT;

-- -----------------------------------------------------
-- Data for table `melis_ecom_order_payment_type`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_order_payment_type` (`opty_id`, `opty_status`, `opty_code`, `opty_name`) VALUES (1, 1, 'VISA', 'Visa');
INSERT INTO `melis_ecom_order_payment_type` (`opty_id`, `opty_status`, `opty_code`, `opty_name`) VALUES (2, 1, 'MASTERCARD', 'Mastercard');
INSERT INTO `melis_ecom_order_payment_type` (`opty_id`, `opty_status`, `opty_code`, `opty_name`) VALUES (3, 0, 'AMEX', 'American Express');
INSERT INTO `melis_ecom_order_payment_type` (`opty_id`, `opty_status`, `opty_code`, `opty_name`) VALUES (4, 0, 'PAYPAL', 'PayPal');
INSERT INTO `melis_ecom_order_payment_type` (`opty_id`, `opty_status`, `opty_code`, `opty_name`) VALUES (5, 0, 'TRANSFERT', 'Account Transfert');
INSERT INTO `melis_ecom_order_payment_type` (`opty_id`, `opty_status`, `opty_code`, `opty_name`) VALUES (6, 0, 'CB', 'CB');

COMMIT;