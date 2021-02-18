-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2021 at 04:17 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `commerce-dev2`
--

-- --------------------------------------------------------

--
-- Table structure for table `melis_ecom_client_person_emails`
--

CREATE TABLE `melis_ecom_client_person_emails` (
`cpmail_id` int(11) NOT NULL,
`cpmail_cper_id` int(11) NOT NULL,
`cpmail_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `melis_ecom_client_person_emails`
--
ALTER TABLE `melis_ecom_client_person_emails`
    ADD PRIMARY KEY (`cpmail_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `melis_ecom_client_person_emails`
--
ALTER TABLE `melis_ecom_client_person_emails`
    MODIFY `cpmail_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
