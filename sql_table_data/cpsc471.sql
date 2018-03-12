-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 11, 2018 at 10:52 PM
-- Server version: 5.7.21-0ubuntu0.17.10.1
-- PHP Version: 7.1.11-0ubuntu0.17.10.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cpsc471`
--
CREATE DATABASE IF NOT EXISTS `cpsc471` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `cpsc471`;

-- --------------------------------------------------------

--
-- Table structure for table `Components`
--

CREATE TABLE `Components` (
  `id` int(11) NOT NULL,
  `part_no` int(11) NOT NULL,
  `reorder_qty` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Customers`
--

CREATE TABLE `Customers` (
  `CustomerID` int(11) NOT NULL,
  `CFname` varchar(64) NOT NULL,
  `CLname` varchar(64) NOT NULL,
  `Street` varchar(128) DEFAULT NULL,
  `City` varchar(64) DEFAULT NULL,
  `Province` varchar(32) DEFAULT NULL,
  `Postal_Code` varchar(16) DEFAULT NULL,
  `Country` varchar(64) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Phone` varchar(32) DEFAULT NULL,
  `CompanyName` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Customers`
--

INSERT INTO `Customers` (`CustomerID`, `CFname`, `CLname`, `Street`, `City`, `Province`, `Postal_Code`, `Country`, `Email`, `Phone`, `CompanyName`) VALUES
(1, 'first', 'last', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'second', 'second', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Second', 'Second', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'third', 'third', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_info`
--

CREATE TABLE `employee_info` (
  `id` int(11) NOT NULL,
  `uid` varchar(35) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `ft_pt` varchar(10) DEFAULT NULL,
  `hourly_salary` varchar(10) DEFAULT NULL,
  `compensation` float DEFAULT NULL,
  `position` varchar(35) DEFAULT NULL,
  `division` varchar(35) DEFAULT NULL,
  `pay_increase_date` date DEFAULT NULL,
  `sin` int(11) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `td1` float DEFAULT NULL,
  `td1ab` float DEFAULT NULL,
  `home_phone` varchar(25) DEFAULT NULL,
  `home_cell` varchar(25) DEFAULT NULL,
  `home_email` varchar(40) DEFAULT NULL,
  `street` varchar(35) DEFAULT NULL,
  `city` varchar(25) DEFAULT NULL,
  `province` varchar(25) DEFAULT NULL,
  `postal_code` varchar(16) DEFAULT NULL,
  `work_email` varchar(40) DEFAULT NULL,
  `work_phone` varchar(25) DEFAULT NULL,
  `work_cell` varchar(25) DEFAULT NULL,
  `drivers_license` varchar(25) DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `emergency_contact` varchar(35) DEFAULT NULL,
  `emerg_number` varchar(25) DEFAULT NULL,
  `notes` text,
  `status` varchar(25) DEFAULT NULL,
  `supervisor` varchar(35) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_info`
--

INSERT INTO `employee_info` (`id`, `uid`, `start_date`, `ft_pt`, `hourly_salary`, `compensation`, `position`, `division`, `pay_increase_date`, `sin`, `dob`, `td1`, `td1ab`, `home_phone`, `home_cell`, `home_email`, `street`, `city`, `province`, `postal_code`, `work_email`, `work_phone`, `work_cell`, `drivers_license`, `expiry`, `emergency_contact`, `emerg_number`, `notes`, `status`, `supervisor`) VALUES
(53, '44', '0000-00-00', '\"Part', '\"Salary\"', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', '\"Inactive\"', ''),
(54, '45', '0000-00-00', '\"Part', '\"Salary\"', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', '\"Inactive\"', ''),
(55, '0', '0000-00-00', '\"Part', '\"Salary\"', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', '\"Active\"', ''),
(56, '46', '0000-00-00', '\"Part', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', ''),
(57, '0', '0000-00-00', '\"Part', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', ''),
(58, '47', '0000-00-00', '\"Part', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', '');

-- --------------------------------------------------------

--
-- Table structure for table `Inventory`
--

CREATE TABLE `Inventory` (
  `id` int(11) NOT NULL,
  `part_no` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `unit_cost` float DEFAULT NULL,
  `retail_cost` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Inventory`
--

INSERT INTO `Inventory` (`id`, `part_no`, `quantity`, `description`, `unit_cost`, `retail_cost`) VALUES
(1, 1234, 6, 'Test Part', 10, 12),
(2, 5678, 4, 'Test Part 2', 20.2, 32.32);

-- --------------------------------------------------------

--
-- Table structure for table `phpauthent_groups`
--

CREATE TABLE `phpauthent_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(80) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phpauthent_groups`
--

INSERT INTO `phpauthent_groups` (`id`, `name`, `description`) VALUES
(1, 'admin', ''),
(2, 'employee', 'Employees'),
(3, 'accounting', 'Payroll'),
(7, 'engineering', 'engineering'),
(5, 'supervisor', 'Supervisor'),
(6, 'operations', 'operations'),
(8, 'r_and_d', 'r_and_d'),
(9, 'sales', 'sales');

-- --------------------------------------------------------

--
-- Table structure for table `phpauthent_relation`
--

CREATE TABLE `phpauthent_relation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phpauthent_relation`
--

INSERT INTO `phpauthent_relation` (`id`, `user_id`, `group_id`) VALUES
(359, 37, 3),
(358, 3, 3),
(378, 46, 1),
(354, 22, 5),
(353, 2, 5),
(352, 4, 5),
(357, 22, 3),
(382, 47, 2),
(356, 2, 3),
(377, 2, 1),
(376, 1, 1),
(379, 47, 1),
(381, 46, 2),
(380, 2, 2),
(355, 36, 5),
(360, 36, 3);

-- --------------------------------------------------------

--
-- Table structure for table `phpauthent_users`
--

CREATE TABLE `phpauthent_users` (
  `id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(64) DEFAULT NULL,
  `realname` varchar(80) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `creation` date DEFAULT NULL,
  `numlogins` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phpauthent_users`
--

INSERT INTO `phpauthent_users` (`id`, `username`, `password`, `realname`, `email`, `lastlogin`, `creation`, `numlogins`) VALUES
(1, 'administrator', 'cad6c0c3e5a9e1afc4de', NULL, NULL, '2014-06-17 12:55:50', NULL, 1),
(2, 'jdeboer', 'ced3c3d6d9a3e0', 'Jason De Boer', 'jason@testcompany.ca', '2017-07-26 08:32:51', '2014-06-17', 380),
(46, 'test1', 'ced3c3d6d9a3e0', 'test1', 'test@testing.com', '2018-03-11 21:56:04', '2018-01-11', 29),
(47, 'root', 'ced3c3d6d9a3e0', 'root', 'root@testcompany.com', NULL, '2018-01-11', 0);

-- --------------------------------------------------------

--
-- Table structure for table `PO_Components`
--

CREATE TABLE `PO_Components` (
  `id` int(11) NOT NULL,
  `po_number` int(11) NOT NULL,
  `part_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Purchase_Order`
--

CREATE TABLE `Purchase_Order` (
  `po_number` int(11) NOT NULL,
  `status` varchar(64) DEFAULT NULL,
  `date_ordered` date DEFAULT NULL,
  `est_ship_date` date DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `acknowledged` tinyint(1) DEFAULT NULL,
  `require_qc` tinyint(1) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL,
  `payment_terms` varchar(32) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `notes` text,
  `shipper_id` int(11) DEFAULT NULL,
  `tracking_no` int(11) DEFAULT NULL,
  `ordered_by` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Components`
--
ALTER TABLE `Components`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Customers`
--
ALTER TABLE `Customers`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `employee_info`
--
ALTER TABLE `employee_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Inventory`
--
ALTER TABLE `Inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phpauthent_groups`
--
ALTER TABLE `phpauthent_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `phpauthent_relation`
--
ALTER TABLE `phpauthent_relation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phpauthent_users`
--
ALTER TABLE `phpauthent_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `PO_Components`
--
ALTER TABLE `PO_Components`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Purchase_Order`
--
ALTER TABLE `Purchase_Order`
  ADD PRIMARY KEY (`po_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Components`
--
ALTER TABLE `Components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Customers`
--
ALTER TABLE `Customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `employee_info`
--
ALTER TABLE `employee_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
--
-- AUTO_INCREMENT for table `Inventory`
--
ALTER TABLE `Inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `phpauthent_groups`
--
ALTER TABLE `phpauthent_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `phpauthent_relation`
--
ALTER TABLE `phpauthent_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=383;
--
-- AUTO_INCREMENT for table `phpauthent_users`
--
ALTER TABLE `phpauthent_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `PO_Components`
--
ALTER TABLE `PO_Components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Purchase_Order`
--
ALTER TABLE `Purchase_Order`
  MODIFY `po_number` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
