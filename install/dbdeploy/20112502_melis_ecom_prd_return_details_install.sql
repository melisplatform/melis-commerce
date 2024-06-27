-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2020 at 08:28 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `melis_ecom_order_product_return_details`
--

CREATE TABLE `melis_ecom_order_product_return_details` (
  `pretd_id` int(11) NOT NULL,
  `pretd_pret_id` int(11) NOT NULL COMMENT 'Product return id',
  `pretd_variant_id` int(11) NOT NULL COMMENT 'Variant id of the returned product',
  `pretd_sku` varchar(100) DEFAULT NULL COMMENT 'SKU of the variant',
  `pretd_quantity` int(11) NOT NULL COMMENT 'Quantity returned for the variant'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_ecom_order_product_return_details`
--
ALTER TABLE `melis_ecom_order_product_return_details`
  ADD PRIMARY KEY (`pretd_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_ecom_order_product_return_details`
--
ALTER TABLE `melis_ecom_order_product_return_details`
  MODIFY `pretd_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
