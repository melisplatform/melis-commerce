ALTER TABLE `melis_ecom_order_basket` ADD `obas_price_log` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'The item prices logs'; 

ALTER TABLE `melis_ecom_order_basket` ADD `obas_total_price` FLOAT NOT NULL COMMENT 'Total amount of the product in the basket' AFTER `obas_price_other_tax`; 

ALTER TABLE `melis_ecom_order_basket` ADD `obas_total_discount` FLOAT NOT NULL COMMENT ' Total discount of the product in the basket' AFTER `obas_price_other_tax`;