ALTER TABLE `melis_ecom_order_message` ADD `omsg_type` ENUM('MSG','RETURN') NOT NULL DEFAULT 'MSG' AFTER `omsg_user_id`;