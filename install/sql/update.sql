RENAME TABLE `melisv2`.`melis_ecom_atribute_value_trans` TO `melisv2`.`melis_ecom_attribute_value_trans`;
ALTER TABLE `melis_ecom_product_text_type` ADD `ptt_field_type` INT NULL AFTER `ptt_name`;
ALTER TABLE `melis_ecom_product` CHANGE `prd_date_creation` `prd_date_creation` DATETIME NULL COMMENT 'Creation date of this product';
ALTER TABLE `melis_ecom_product` CHANGE `prd_user_id_creation` `prd_user_id_creation` INT(11) NULL COMMENT 'BO user who created this product';

ALTER TABLE `melis_ecom_civility_trans` CHANGE `civt_min name` `civt_min_name` VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Abbreviation of the civility, ex ''M''';
ALTER TABLE `melis_ecom_order_status` ADD `osta_status` BOOLEAN NULL AFTER `osta_color_code`;

RENAME TABLE `melisv2`.`melis_ecom_address_type` TO `melisv2`.`melis_ecom_client_address_type`;
RENAME TABLE `melisv2`.`melis_ecom_address_type_trans` TO `melisv2`.`melis_ecom_client_address_type_trans`;

ALTER TABLE `melis_ecom_client_address_type` CHANGE `atype_id` `catype_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Type id of address', 
CHANGE `atype_code` `catype_code` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Code if needed';

ALTER TABLE `melis_ecom_client_address_type_trans` CHANGE `atypt_id` `catypt_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Type translation id', 
CHANGE `atypt_type_id` `catypt_type_id` INT(11) NOT NULL COMMENT 'Type id of address', 
CHANGE `atypt_lang_id` `catypt_lang_id` INT(11) NOT NULL COMMENT 'Lang id for translation', 
CHANGE `atypt_name` `catypt_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Type''s name, ex ''Delivery''';

ALTER TABLE `melis_ecom_seo` ADD `eseo_variant_id` INT NULL AFTER `eseo_product_id`;
ALTER TABLE `melis_ecom_seo` ADD `eseo_lang_id` INT NULL AFTER `eseo_variant_id`;
ALTER TABLE `melis_ecom_seo` ADD `eseo_url_301` VARCHAR(255) NULL AFTER `eseo_url`;
ALTER TABLE `melis_ecom_seo` ADD `eseo_url_redirect` VARCHAR(255) NULL AFTER `eseo_url`;
ALTER TABLE `melis_ecom_seo` ADD `eseo_page_id` INT NOT NULL AFTER `eseo_id`;

RENAME TABLE `melisv2`.`melis_ecom_company` TO `melisv2`.`melis_ecom_client_company`;

ALTER TABLE `melis_ecom_order_messages` CHANGE `omsg_user_id` `omsg_user_id` INT(11) NULL COMMENT 'BO user Id who is talking';


ALTER TABLE `melis_ecom_client_person` CHANGE `cper_civility` `cper_civility` INT(11) NULL COMMENT 'Civility id';
ALTER TABLE `melis_ecom_client_address` CHANGE `cadd_civility` `cadd_civility` INT(11) NULL COMMENT 'Civility Id';