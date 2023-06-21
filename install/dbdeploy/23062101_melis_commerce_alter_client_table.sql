ALTER TABLE `melis_ecom_client` ADD `cli_name` VARCHAR(255) NOT NULL AFTER `cli_status`;
ALTER TABLE `melis_ecom_client` ADD `cli_tags` VARCHAR(255) NULL AFTER `cli_country_id`;