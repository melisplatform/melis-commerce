<<<<<<< HEAD
-- RENAME TABLE `melisv2`.`melis_ecom_atribute_value_trans` TO `melisv2`.`melis_ecom_attribute_value_trans`;
-- ALTER TABLE `melis_ecom_product_text_type` ADD `ptt_field_type` INT NULL AFTER `ptt_name`;
-- ALTER TABLE `melis_ecom_product` CHANGE `prd_date_creation` `prd_date_creation` DATETIME NULL COMMENT 'Creation date of this product';
-- ALTER TABLE `melis_ecom_product` CHANGE `prd_user_id_creation` `prd_user_id_creation` INT(11) NULL COMMENT 'BO user who created this product';
--
-- ALTER TABLE `melis_ecom_civility_trans` CHANGE `civt_min name` `civt_min_name` VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Abbreviation of the civility, ex ''M''';
-- ALTER TABLE `melis_ecom_order_status` ADD `osta_status` BOOLEAN NULL AFTER `osta_color_code`;
--
-- RENAME TABLE `melisv2`.`melis_ecom_address_type` TO `melisv2`.`melis_ecom_client_address_type`;
-- RENAME TABLE `melisv2`.`melis_ecom_address_type_trans` TO `melisv2`.`melis_ecom_client_address_type_trans`;
--
-- ALTER TABLE `melis_ecom_client_address_type` CHANGE `atype_id` `catype_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Type id of address',
-- CHANGE `atype_code` `catype_code` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Code if needed';
--
-- ALTER TABLE `melis_ecom_client_address_type_trans` CHANGE `atypt_id` `catypt_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Type translation id',
-- CHANGE `atypt_type_id` `catypt_type_id` INT(11) NOT NULL COMMENT 'Type id of address',
-- CHANGE `atypt_lang_id` `catypt_lang_id` INT(11) NOT NULL COMMENT 'Lang id for translation',
-- CHANGE `atypt_name` `catypt_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Type''s name, ex ''Delivery''';
--
-- ALTER TABLE `melis_ecom_seo` ADD `eseo_variant_id` INT NULL AFTER `eseo_product_id`;
-- ALTER TABLE `melis_ecom_seo` ADD `eseo_lang_id` INT NULL AFTER `eseo_variant_id`;
-- ALTER TABLE `melis_ecom_seo` ADD `eseo_url_301` VARCHAR(255) NULL AFTER `eseo_url`;
-- ALTER TABLE `melis_ecom_seo` ADD `eseo_url_redirect` VARCHAR(255) NULL AFTER `eseo_url`;
-- ALTER TABLE `melis_ecom_seo` ADD `eseo_page_id` INT NOT NULL AFTER `eseo_id`;
--
-- RENAME TABLE `melisv2`.`melis_ecom_company` TO `melisv2`.`melis_ecom_client_company`;
--
-- ALTER TABLE `melis_ecom_order_message` CHANGE `omsg_user_id` `omsg_user_id` INT(11) NULL COMMENT 'BO user Id who is talking';
--
-- ALTER TABLE `melis_ecom_client_person` CHANGE `cper_civility` `cper_civility` INT(11) NULL COMMENT 'Civility id';
-- ALTER TABLE `melis_ecom_client_address` CHANGE `cadd_civility` `cadd_civility` INT(11) NULL COMMENT 'Civility Id';
--
-- ALTER TABLE `melis_ecom_coupon` CHANGE `coup_max_use_number` `coup_max_use_number` INT(11) NULL DEFAULT NULL COMMENT 'Max number of use, unilimited if null';
--
-- INSERT INTO `melisv2`.`melis_ecom_order_status` (`osta_id`, `osta_color_code`, `osta_status`) VALUES (-1, '#00ccff', NULL);
-- INSERT INTO `melisv2`.`melis_ecom_order_status` (`osta_id`, `osta_color_code`, `osta_status`) VALUES (6, '#ff3300', NULL);
--
-- INSERT INTO `melisv2`.`melis_ecom_order_status_trans` (`ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES ('-1', '1', 'Temporary');
-- INSERT INTO `melisv2`.`melis_ecom_order_status_trans` (`ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES ('-1', '2', 'Temporary');
-- INSERT INTO `melisv2`.`melis_ecom_order_status_trans` (`ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES ('6', '1', 'Error payment');
-- INSERT INTO `melisv2`.`melis_ecom_order_status_trans` (`ostt_status_id`, `ostt_lang_id`, `ostt_status_name`) VALUES ('6', '2', 'Erreur paiement');
--
-- ALTER TABLE `melis_ecom_client_company` ADD `ccomp_group` VARCHAR(100) NULL COMMENT 'Group of the company' AFTER `ccomp_vat_number`;
--
--
-- RENAME TABLE `melisv2`.`melis_ecom_order_messages` TO `melisv2`.`melis_ecom_order_message`;
--
-- UPDATE `melisv2`.`melis_ecom_order_status_trans` SET `ostt_status_name` = 'New' WHERE `melis_ecom_order_status_trans`.`ostt_id` = 1;
-- UPDATE `melisv2`.`melis_ecom_order_status_trans` SET `ostt_status_name` = 'Nouvelle' WHERE `melis_ecom_order_status_trans`.`ostt_id` = 2;
-- UPDATE `melisv2`.`melis_ecom_order_status_trans` SET `ostt_status_name` = 'On hold' WHERE `melis_ecom_order_status_trans`.`ostt_id` = 3;
-- UPDATE `melisv2`.`melis_ecom_order_status_trans` SET `ostt_status_name` = 'En attente' WHERE `melis_ecom_order_status_trans`.`ostt_id` = 4;
-- UPDATE `melisv2`.`melis_ecom_order_status_trans` SET `ostt_status_name` = 'Shipped' WHERE `melis_ecom_order_status_trans`.`ostt_id` = 5;
-- UPDATE `melisv2`.`melis_ecom_order_status_trans` SET `ostt_status_name` = 'Envoyée' WHERE `melis_ecom_order_status_trans`.`ostt_id` = 6;
-- UPDATE `melisv2`.`melis_ecom_order_status_trans` SET `ostt_status_name` = 'Delivered' WHERE `melis_ecom_order_status_trans`.`ostt_id` = 7;
-- UPDATE `melisv2`.`melis_ecom_order_status_trans` SET `ostt_status_name` = 'Livrée' WHERE `melis_ecom_order_status_trans`.`ostt_id` = 8;
-- UPDATE `melisv2`.`melis_ecom_order_status_trans` SET `ostt_status_name` = 'Canceled' WHERE `melis_ecom_order_status_trans`.`ostt_id` = 9;
-- UPDATE `melisv2`.`melis_ecom_order_status_trans` SET `ostt_status_name` = 'Annulée' WHERE `melis_ecom_order_status_trans`.`ostt_id` = 10;
-- UPDATE `melisv2`.`melis_ecom_order_status_trans` SET `ostt_status_name` = 'Error payment' WHERE `melis_ecom_order_status_trans`.`ostt_id` = 11;
-- UPDATE `melisv2`.`melis_ecom_order_status_trans` SET `ostt_status_name` = 'Erreur paiement' WHERE `melis_ecom_order_status_trans`.`ostt_id` = 12;

ALTER TABLE `melis_ecom_country` DROP `ctry_order`;
ALTER TABLE `melis_ecom_country` ADD `ctry_status` BOOLEAN NULL AFTER `ctry_currency_id`;

ALTER TABLE `melis_ecom_currency` ADD `cur_status` BOOLEAN NULL AFTER `cur_symbol`;

ALTER TABLE `melis_ecom_lang` ADD `elang_status` BOOLEAN NULL AFTER `elang_name`;
UPDATE `melisv2`.`melis_ecom_lang` SET `elang_status` = '1'

ALTER TABLE `melis_ecom_currency` ADD `cur_default` BOOLEAN NULL DEFAULT FALSE COMMENT 'Default currency' AFTER `cur_id`;
UPDATE `melisv2`.`melis_ecom_currency` SET `cur_default` = '1' WHERE `melis_ecom_currency`.`cur_id` = 1;

UPDATE `melisv2`.`melis_ecom_order_status_trans` SET `ostt_status_name` = 'Non finalisée' WHERE `melis_ecom_order_status_trans`.`ostt_id` = 12;

ALTER TABLE `melis_ecom_country` ADD `ctry_flag` BLOB NULL DEFAULT NULL AFTER `ctry_status`;
ALTER TABLE `melis_ecom_country` CHANGE `ctry_flag` `ctry_flag` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `melis_ecom_assoc_variant` CHANGE `avar_to` `avar_one` INT(11) NOT NULL, CHANGE `avar_from` `avar_two` INT(11) NOT NULL;
ALTER TABLE `melis_ecom_lang` ADD `elang_flag` LONGTEXT NULL AFTER `elang_status`;

ALTER TABLE `melis_ecom_order` ADD `ord_country_id` INT NOT NULL COMMENT 'Country where the checkout based' AFTER `ord_status`;
