CREATE TABLE
IF NOT EXISTS `melis_ecom_client_groups`
(
  `cgroup_id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID of the group',
  `cgroup_status` INT NOT NULL COMMENT 'Status of the group',
  `cgroup_name` VARCHAR
(50) NOT NULL COMMENT 'Name of the group',
  `cgroup_date_creation` DATETIME NOT NULL COMMENT 'Date creation of the group',
  PRIMARY KEY
(`cgroup_id`))
ENGINE = InnoDB;

INSERT INTO `melis_ecom_client_groups` (`
cgroup_id`,
`cgroup_status
`, `cgroup_name`, `cgroup_date_creation`) VALUES
(1, 1, 'General', NOW
());

ALTER TABLE `melis_ecom_client`
ADD `cli_group_id` INT NULL DEFAULT '1' AFTER `cli_status`;

ALTER TABLE `melis_ecom_price`
ADD `price_group_id` INT NULL AFTER `price_currency`; 