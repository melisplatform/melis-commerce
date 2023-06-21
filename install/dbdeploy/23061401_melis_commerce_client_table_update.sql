ALTER TABLE `melis_ecom_client_person` CHANGE `cper_client_id` `cper_client_id` INT(11) NULL COMMENT 'client Id';
ALTER TABLE `melis_ecom_client_person` ADD `cper_type` VARCHAR(50) NULL AFTER `cper_lang_id`;
ALTER TABLE `melis_ecom_client_person` CHANGE `cper_name` `cper_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT 'Name of person';