-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2023 at 01:28 PM
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
-- Table structure for table `melis_ecom_settings_account`
--

DROP TABLE IF EXISTS `melis_ecom_settings_account`;
CREATE TABLE `melis_ecom_settings_account` (
  `sa_id` int(11) NOT NULL,
  `sa_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_ecom_settings_account`
--
ALTER TABLE `melis_ecom_settings_account`
  ADD PRIMARY KEY (`sa_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_ecom_settings_account`
--
ALTER TABLE `melis_ecom_settings_account`
  MODIFY `sa_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
