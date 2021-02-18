-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2020 at 08:11 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `melisdev`
--

-- --------------------------------------------------------

--
-- Table structure for table `melis_ecom_product_links`
--

CREATE TABLE `melis_ecom_product_links` (
  `plink_id` int(11) NOT NULL,
  `plink_product_id` int(11) NOT NULL,
  `plink_link_1` text DEFAULT NULL,
  `plink_link_2` text DEFAULT NULL,
  `plink_link_3` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_ecom_product_links`
--
ALTER TABLE `melis_ecom_product_links`
  ADD PRIMARY KEY (`plink_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_ecom_product_links`
--
ALTER TABLE `melis_ecom_product_links`
  MODIFY `plink_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
