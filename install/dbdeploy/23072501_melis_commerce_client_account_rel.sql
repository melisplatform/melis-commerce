-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2023 at 06:13 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.4.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `democommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `melis_ecom_client_account_rel`
--

DROP TABLE IF EXISTS `melis_ecom_client_account_rel`;
CREATE TABLE `melis_ecom_client_account_rel` (
  `car_id` int(11) NOT NULL,
  `car_client_id` int(11) NOT NULL,
  `car_client_person_id` int(11) NOT NULL,
  `car_default_person` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Relationship between client and contact';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_ecom_client_account_rel`
--
ALTER TABLE `melis_ecom_client_account_rel`
  ADD PRIMARY KEY (`car_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_ecom_client_account_rel`
--
ALTER TABLE `melis_ecom_client_account_rel`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
