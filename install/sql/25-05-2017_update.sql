

ALTER TABLE `melis_ecom_variant_stock` ADD `stock_low` INT NULL DEFAULT NULL AFTER `stock_quantity`;
ALTER TABLE `melis_ecom_variant_stock` ADD `stock_qty_email_sent` SMALLINT(1) NOT NULL DEFAULT '0' AFTER `stock_low`;
ALTER TABLE `melis_ecom_product` ADD `prd_stock_low` INT NULL AFTER `prd_status`;

ALTER TABLE `melis_ecom_coupon_order` ADD `cord_basket_id` INT NULL DEFAULT NULL AFTER `cord_order_id`;
ALTER TABLE `melis_ecom_coupon_order` ADD `cord_status` INT NULL DEFAULT NULL AFTER `cord_basket_id`;
ALTER TABLE `melis_ecom_coupon_order` ADD `cord_quantity_used` INT NOT NULL AFTER `cord_basket_id`;