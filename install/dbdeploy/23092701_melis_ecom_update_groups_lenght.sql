ALTER TABLE `melis_ecom_client_groups` CHANGE `cgroup_name` `cgroup_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Name of the group';
ALTER TABLE `melis_ecom_client_company` CHANGE `ccomp_group` `ccomp_group` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'Group of the company';