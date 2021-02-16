-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2020 at 08:26 AM
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
-- Table structure for table `melis_ecom_order_product_return`
--

CREATE TABLE `melis_ecom_order_product_return` (
  `pret_id` int(11) NOT NULL,
  `pret_order_id` int(11) NOT NULL COMMENT 'Order id concerned by the return',
  `pret_client_id` int(11) NOT NULL COMMENT 'Cliend id of the account',
  `pret_date_creation` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Date when the return was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_ecom_order_product_return`
--
ALTER TABLE `melis_ecom_order_product_return`
  ADD PRIMARY KEY (`pret_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_ecom_order_product_return`
--
ALTER TABLE `melis_ecom_order_product_return`
  MODIFY `pret_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
