ALTER TABLE `melis_ecom_order_basket` ADD `obas_price_log` INT NULL AFTER `obas_price_other_tax`; 

ALTER TABLE `melis_ecom_order_basket` ADD `obas_total_price` FLOAT NOT NULL COMMENT 'Total amount of the product in the basket' AFTER `obas_price_other_tax`; 

ALTER TABLE `melis_ecom_order_basket` ADD `obas_total_discount` FLOAT NOT NULL COMMENT ' Total discount of the product in the basket' AFTER `obas_price_other_tax`; 