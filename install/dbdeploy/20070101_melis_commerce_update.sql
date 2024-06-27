ALTER TABLE `melis_ecom_order`
ADD `ord_delivery_method` VARCHAR
(15) NOT NULL AFTER `ord_delivery_address`;