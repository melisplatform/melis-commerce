ALTER TABLE `melis_ecom_client_company`
ADD `ccomp_comp_creation_date` DATE NULL AFTER `ccomp_group`,
ADD `ccomp_employee_nb` INT NULL AFTER `ccomp_comp_creation_date`,
ADD `ccomp_add_number` VARCHAR(10) NULL AFTER `ccomp_employee_nb`,
ADD `ccomp_add_street` VARCHAR(255) NULL AFTER `ccomp_add_number`,
ADD `ccomp_add_building` VARCHAR(45) NULL AFTER `ccomp_add_street`,
ADD `ccomp_add_floor` VARCHAR(10) NULL AFTER `ccomp_add_building`,
ADD `ccomp_add_zipcode` VARCHAR(15) NULL AFTER `ccomp_add_floor`,
ADD `ccomp_add_city` VARCHAR(100) NULL AFTER `ccomp_add_zipcode`,
ADD `ccomp_add_state` VARCHAR(50) NULL AFTER `ccomp_add_city`,
ADD `ccomp_add_country` VARCHAR(50) NULL AFTER `ccomp_add_state`,
ADD `ccomp_logo` BLOB NULL AFTER `ccomp_add_country`;