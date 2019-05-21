-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 13, 2018 at 11:36 AM
-- Server version: 10.0.28-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cpsc471`
--

-- --------------------------------------------------------

--
-- Table structure for table `Components`
--

CREATE TABLE `Components` (
  `part_no` int(11) NOT NULL,
  `reorder_qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Components`
--

INSERT INTO `Components` (`part_no`, `reorder_qty`) VALUES
(789, 30),
(1234, 2),
(1267, 700),
(12312, 800),
(43722, 0),
(54387, 300),
(438678, 2500),
(899889, 3),
(3434324, 6500),
(93485094, 800);

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
(1, 'Brandon', 'Bisson', '14 Avenue NW', 'Calgary', 'AB', 'T7G2S1', 'Canada', 'stareagle@gmail.com', '403-234-5837', 'Star Eagle'),
(2, 'Tamara', 'Austin', '17 MacLeod Street SW', 'Calgary', 'AB', 'M2N5G2', 'Canada', 'taustin@reddeercasino.com', '587-983-2384', 'Red Deer Casino'),
(3, 'Leila', 'Mehmetoglu', '8745 Whiting Drive NW', 'Toronto', 'ON', 'G9D3S2', 'Canada', 'lmehmet@positive.ca', '614-383-3483', 'Positive'),
(4, 'Jack', 'Russell', '4857', 'calgary', 'ab', 't4tyy6h', 'canada', 'jack@ucalgary.ca', '4057787780', 'University of Calgary'),
(5, 'Hasssan', 'Chaudh', '3289', 'calgary', 'ab', 't3l3e3', 'canada', 'hachaud@gmail.com', '40344444444', 'Babyboy'),
(6, 'sladana', 'tall', '987', 'calgary', 'ab', 't4hy65', 'canada', 'sladnahi@gmai.com', '5857777777', 'SerbiaLand'),
(7, 'Jason', 'De', '765', 'calgary', 'ab', 't3uj7u', 'canada', 'jadsonBn@gmail.com', '899898998', 'VacationHomes'),
(8, 'Kashfia', 'K', '237', 'calgary', 'ab', 't5y7u8', 'canada', 'kashfia@gmail.com', '457888888', 'DataQueen'),
(9, 'Wish', 'Bakshi', '234', 'calgary', 'ab', 't5yh77', 'canada', 'wishwashy@gmail.com', '309483998', 'WishUponAStar');

-- --------------------------------------------------------

--
-- Table structure for table `employee_info`
--

CREATE TABLE `employee_info` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_info`
--

INSERT INTO `employee_info` (`id`, `uid`, `start_date`, `ft_pt`, `hourly_salary`, `compensation`, `position`, `division`, `pay_increase_date`, `sin`, `dob`, `td1`, `td1ab`, `home_phone`, `home_cell`, `home_email`, `street`, `city`, `province`, `postal_code`, `work_email`, `work_phone`, `work_cell`, `drivers_license`, `expiry`, `emergency_contact`, `emerg_number`, `notes`, `status`, `supervisor`) VALUES
(56, 46, '0000-00-00', '\"Part', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', ''),
(57, 2, '0000-00-00', 'Full Time', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', 'Notice: Undefined index: ', '', 'Active', ''),
(58, 47, '0000-00-00', '\"Part', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', ''),
(59, 48, '0000-00-00', 'Full Time', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', ''),
(60, 49, '2018-04-02', 'Full Time', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', 'Notice: Undefined index: ', '', 'Active', 'cpsc471'),
(61, 50, '0000-00-00', 'Full Time', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', ''),
(62, 51, '0000-00-00', 'Full Time', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', ''),
(63, 52, '0000-00-00', 'Full Time', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', ''),
(64, 53, '0000-00-00', 'Full Time', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Inactive', ''),
(65, 54, '0000-00-00', 'Full Time', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', ''),
(66, 55, '0000-00-00', 'Full Time', 'Hourly', 0, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', ''),
(67, 56, '2018-04-09', 'Full Time', 'Salary', 80000, 'Sales', '', '0000-00-00', 123123123, '0000-00-00', 0, 0, '4035555555', '', '', '123', 'Calgary', 'Ab', 't2t5t5', '', '', '', '', '0000-00-00', '', '', '', 'Active', 'cpsc471'),
(69, 58, '2018-04-02', 'Full Time', 'Hourly', 90000, 'Engineer Rep', 'Engineer', '0000-00-00', 888347324, '2018-04-01', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', 'cpsc471'),
(70, 59, '2009-01-14', 'Full Time', 'Hourly', 200000, 'Lead Engineer', 'Engineer', '0000-00-00', 847548574, '1948-04-23', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', 'cpsc471'),
(71, 60, '2009-11-21', 'Full Time', 'Hourly', 25, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', 'cpsc471'),
(72, 61, '2018-04-27', 'Full Time', 'Hourly', 90, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', 'JDoe'),
(73, 62, '2010-01-30', 'Part Time', 'Salary', 20000, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', 'JDoe'),
(74, 63, '2018-04-09', 'Full Time', 'Hourly', 90, '', '', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', 'JDoe'),
(75, 64, '2018-04-12', 'Full Time', 'Salary', 100000, 'sales', 'Sales', '0000-00-00', 0, '0000-00-00', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '', '', 'Active', 'Gijoe');

-- --------------------------------------------------------

--
-- Table structure for table `Fixtures`
--

CREATE TABLE `Fixtures` (
  `PartNo` int(20) NOT NULL,
  `FixtureName` varchar(50) CHARACTER SET utf16le DEFAULT NULL,
  `FixtureType` varchar(50) CHARACTER SET utf16le DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Fixtures`
--

INSERT INTO `Fixtures` (`PartNo`, `FixtureName`, `FixtureType`) VALUES
(12345, 'MR LED luminaire', 'Outdoor'),
(12346, '75-250 Watt HID', 'Outdoor'),
(23432, 'cbx 777', 'Outdoor'),
(24111, 'CL-45W', 'Indoor'),
(98453, 'H67', 'Indoor'),
(99889, '120W, SIB', 'Light Panel'),
(98453459, 'H67', 'Indoor'),
(342353453, 'kl8887', 'Outdoor'),
(1232984732, 'rdd', 'Controller');

-- --------------------------------------------------------

--
-- Table structure for table `Fixture_Components`
--

CREATE TABLE `Fixture_Components` (
  `id` int(11) NOT NULL,
  `fixture_no` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `component_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Fixture_Components`
--

INSERT INTO `Fixture_Components` (`id`, `fixture_no`, `qty`, `component_no`) VALUES
(4, 24111, 8, 43722);

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
(1, 12345, 34, 'MR LED luminaire Fixture ', 528, 739),
(2, 0, 43, '', 32, 43),
(3, 12346, 63, '75-250 Watt HID fixture', 337, 529),
(4, 12347, 25, 'Multi-functional LED luminaire fixture', 528, 799),
(5, 43722, 184, 'Lightning Rod Pole', 1376, 1799),
(6, 54387, 217, 'Controls', 324, 527),
(7, 438678, 945, 'Ballast', 58, 99),
(8, 99889, 4, '120 Watt, Screw In Bulb', 40, 60),
(9, 1234, 2, 'rod', 14.5, 24.5),
(14, 24111, 8, 'Cool Light', 10, 35),
(15, 98453459, 5, 'LED h67 ', 200, 170),
(17, 98453, 5, 'LED h67 ', 200, 170),
(19, 342353453, 223, 'bulb 989', 900, 800),
(20, 23432, 23, 'cbx 777 bulb', 650, 250),
(21, 3434324, 8000, 'CBC h - bulb', 790, 70),
(22, 93485094, 6700, 'HLD pro fix. unit', 9000, 7800),
(23, 12312, 10000, 'Bridge bulb anti freeze', 800, 600),
(24, 1267, 900, 'BDX bike led ', 800, 670),
(25, 899889, 7, 'Road led BBHX-900', 10000, 9000),
(26, 789, 80, 'Hospital overnight CBHT - 9000', 4000, 2900),
(27, 1232984732, 12, 'rdd 1234', 300, 150);

-- --------------------------------------------------------

--
-- Table structure for table `Items`
--

CREATE TABLE `Items` (
  `id` int(11) NOT NULL,
  `QuoteNo` int(11) NOT NULL,
  `Item_type` varchar(30) CHARACTER SET utf16le DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `part_no` int(11) NOT NULL,
  `Price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Items`
--

INSERT INTO `Items` (`id`, `QuoteNo`, `Item_type`, `Quantity`, `part_no`, `Price`) VALUES
(21, 3, NULL, 5, 0, 500),
(22, 4, NULL, 25, 12345, 200),
(23, 4, NULL, 5, 12346, 10),
(24, 4, NULL, 5, 438678, 10),
(25, 5, NULL, 34999, 99889, 250),
(27, 1, NULL, 0, 0, 0),
(28, 6, NULL, 2, 98453459, 500),
(29, 6, NULL, 2, 43722, 230),
(30, 7, NULL, 0, 0, 0),
(32, 8, NULL, 12, 1267, 900);

-- --------------------------------------------------------

--
-- Table structure for table `Personal_Data`
--

CREATE TABLE `Personal_Data` (
  `uid` int(11) NOT NULL,
  `SIN` int(11) NOT NULL,
  `first_name` varchar(30) CHARACTER SET utf16le DEFAULT NULL,
  `middle_name` varchar(30) CHARACTER SET utf16le DEFAULT NULL,
  `last_name` varchar(30) CHARACTER SET utf16le DEFAULT NULL,
  `street` varchar(100) CHARACTER SET utf16le DEFAULT NULL,
  `city` varchar(50) CHARACTER SET utf16le DEFAULT NULL,
  `postal_code` varchar(6) CHARACTER SET utf16le DEFAULT NULL,
  `province` varchar(30) CHARACTER SET utf16le DEFAULT NULL,
  `country` varchar(30) CHARACTER SET utf16le DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf16le DEFAULT NULL,
  `home_phone` varchar(25) CHARACTER SET utf16le DEFAULT NULL,
  `cell` varchar(25) CHARACTER SET utf16le DEFAULT NULL,
  `start_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `phpauthent_groups`
--

CREATE TABLE `phpauthent_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phpauthent_groups`
--

INSERT INTO `phpauthent_groups` (`id`, `name`, `description`) VALUES
(1, 'admin', ''),
(2, 'employee', 'Employees'),
(3, 'accounting', 'Payroll'),
(5, 'supervisor', 'Supervisor'),
(6, 'operations', 'operations'),
(7, 'engineering', 'engineering'),
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phpauthent_relation`
--

INSERT INTO `phpauthent_relation` (`id`, `user_id`, `group_id`) VALUES
(387, 1, 1),
(388, 2, 1),
(389, 47, 1),
(390, 46, 1),
(391, 48, 1),
(409, 48, 3),
(410, 2, 3),
(424, 50, 6),
(429, 54, 7),
(430, 50, 7),
(431, 2, 5),
(432, 55, 5),
(433, 59, 5),
(434, 60, 8),
(435, 48, 2),
(436, 2, 2),
(437, 47, 2),
(438, 46, 2),
(439, 50, 2),
(440, 56, 9),
(441, 52, 9),
(442, 49, 9),
(443, 64, 9);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phpauthent_users`
--

INSERT INTO `phpauthent_users` (`id`, `username`, `password`, `realname`, `email`, `lastlogin`, `creation`, `numlogins`) VALUES
(1, 'administrator', 'cad6c0c3e5a9e1afc4de', NULL, NULL, '2014-06-17 12:55:50', NULL, 1),
(2, 'jdeboer', 'ced3c3d6d9a3e0', 'Jason De Boer', 'jason@testcompany.ca', '2017-07-26 08:32:51', '2014-06-17', 380),
(46, 'test1', 'ced3c3d6d9a3e0', 'test1', 'test@testing.com', '2018-03-25 06:21:39', '2018-01-11', 40),
(47, 'root', 'ced3c3d6d9a3e0', 'root', 'root@testcompany.com', NULL, '2018-01-11', 0),
(48, 'cpsc471', 'ced3c3d6d9a3e0', 'cpsc471', 'cpsc471@email.com', '2018-04-12 20:22:50', '2018-03-22', 39),
(49, 'salesguy', 'cdcfbcc7e3', 'salesguy', 'sales@company.com', '2018-04-05 09:44:48', '2018-04-02', 2),
(50, '471', '8ea581', 'salesperson', 'hello@ucalgary.ca', '2018-04-12 13:55:44', '2018-04-04', 5),
(51, '', '', '', '', NULL, '2018-04-04', 0),
(52, 'NewUserTEST', 'cae0bfccd598edadc6ddd4668b95', 'New User TEST Change', 'newusertest@project.ca', '2018-04-06 00:50:35', '2018-04-05', 4),
(53, 'delete', 'bed3bcc7e49a', 'to delete', 'delete', NULL, '2018-04-05', 0),
(54, 'engineer1', 'bfdcb7cbde9adebc87', 'engineer1', 'engineer@company.com', '2018-04-05 09:45:19', '2018-04-05', 1),
(55, 'supervisor1', 'cde3c0c7e2abe2bdc5dca2', 'supervisor1', 'supervisor@company.com', NULL, '2018-04-05', 0),
(56, 'JDoe ', '8ba08396', 'Jane Doe', 'jdoe@gmail.com', NULL, '2018-04-12', 0),
(58, 'log123', '8ba08396', 'Logan', 'log@gmail.com', NULL, '2018-04-12', 0),
(59, 'trump1', '8ba08396', 'Donald Trump', 'trumpLord@gmail.com', NULL, '2018-04-12', 0),
(60, 'Gijoe', '8ba08396', 'GiJoe', 'gijoe@hotness.com', NULL, '2018-04-12', 0),
(61, 'CCM', '8ba08396', 'CashCashMoney', 'ccm@company.com', NULL, '2018-04-12', 0),
(62, 'hw', '8ba08396', 'helloWorld', 'hw@company.com', NULL, '2018-04-12', 0),
(63, 'jm', '8ba08396', 'Jumanji Man', 'jm@company.com', NULL, '2018-04-12', 0),
(64, 'dt', '8ba08396', 'Donald', 'dt@company.com', '2018-04-12 15:36:37', '2018-04-12', 2);

-- --------------------------------------------------------

--
-- Table structure for table `PO_Components`
--

CREATE TABLE `PO_Components` (
  `id` int(11) NOT NULL,
  `po_number` int(11) NOT NULL,
  `part_no` int(11) NOT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `UnitCost` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PO_Components`
--

INSERT INTO `PO_Components` (`id`, `po_number`, `part_no`, `Quantity`, `UnitCost`) VALUES
(9, 17, 4, 1, 20),
(10, 18, 4, 2, 10),
(11, 19, 4, 2, 10),
(13, 21, 1, 1, 180),
(14, 22, 12345, 1, 20),
(15, 22, 12347, 1, 20),
(19, 20, 0, 1, 180),
(22, 12, 1234, 1, 10),
(23, 12, 438678, 1, 10),
(24, 23, 99889, 1500, 25),
(25, 23, 43722, 1, 856),
(26, 13, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Project`
--

CREATE TABLE `Project` (
  `ProjectNo` int(11) NOT NULL,
  `Description` varchar(255) CHARACTER SET utf16le NOT NULL,
  `Status` varchar(32) CHARACTER SET utf16le NOT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `notes` text,
  `SupervisorID` int(11) DEFAULT NULL,
  `filepath` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Project`
--

INSERT INTO `Project` (`ProjectNo`, `Description`, `Status`, `StartDate`, `EndDate`, `notes`, `SupervisorID`, `filepath`) VALUES
(2, 'Controller High Efficiency', 'On Hold', '2018-04-03', '2018-04-24', 'The first job in the database', 48, NULL),
(4, 'Foothills Cancer Centre', 'In Progress', '2018-04-12', '2018-04-12', NULL, 56, NULL),
(5, 'Batman ', 'On Hold', '2018-04-12', '2018-07-31', 'Batman costume ', 2, NULL),
(6, 'CIA ', 'Complete', '2018-04-12', '2018-04-30', NULL, 47, NULL),
(7, 'Cambridge Analytica', 'In Progress', '2018-04-12', '2018-04-30', NULL, 2, NULL);

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
  `ordered_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Purchase_Order`
--

INSERT INTO `Purchase_Order` (`po_number`, `status`, `date_ordered`, `est_ship_date`, `amount`, `acknowledged`, `require_qc`, `vendor_id`, `payment_terms`, `description`, `notes`, `shipper_id`, `tracking_no`, `ordered_by`) VALUES
(12, 'Complete', '2018-04-04', '2018-04-04', 500, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 48),
(13, 'Shipped', '2018-04-04', '2018-04-04', 20, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, 48),
(14, 'Ordered', '2018-04-04', '2018-04-04', 40, NULL, NULL, 1, 'Account', NULL, NULL, 1, 123456, 48),
(15, 'Ordered', '2018-04-04', '2018-04-04', 40, NULL, NULL, 1, 'Account', NULL, NULL, 1, 123456, 48),
(17, 'Ordered', '2018-04-04', '2018-04-04', 40, NULL, NULL, 1, 'Account', NULL, NULL, 1, 123456, 48),
(18, 'Ordered', '2018-04-04', '2018-04-04', 20, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 48),
(19, 'Ordered', '2018-04-04', '2018-04-04', 20, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 48),
(20, 'Shipped', '2018-04-04', '2018-04-04', 200, NULL, NULL, 1, 'Account', NULL, NULL, NULL, NULL, 48),
(21, NULL, '2018-04-04', '2018-04-04', 200, NULL, NULL, 1, 'Account', NULL, NULL, NULL, NULL, 48),
(22, NULL, '2018-04-04', '2018-04-04', 40, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 48),
(23, 'Ordered', '2018-04-02', '2018-04-06', 4500, NULL, 1, 2, 'Credit Card', NULL, NULL, 3, 2147483647, 48);

-- --------------------------------------------------------

--
-- Table structure for table `Quote`
--

CREATE TABLE `Quote` (
  `CustomerID` int(11) NOT NULL,
  `QuoteNo` int(11) NOT NULL,
  `est_ship_date` date DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  `freight_charge` float DEFAULT NULL,
  `Amount` float DEFAULT NULL,
  `Notes` text,
  `Terms` varchar(32) DEFAULT NULL,
  `Description` varchar(512) DEFAULT NULL,
  `SalesPersonID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Quote`
--

INSERT INTO `Quote` (`CustomerID`, `QuoteNo`, `est_ship_date`, `date_created`, `freight_charge`, `Amount`, `Notes`, `Terms`, `Description`, `SalesPersonID`) VALUES
(2, 1, '2018-04-05', '2018-04-02', NULL, 50000, NULL, NULL, 'none', NULL),
(2, 3, '2018-04-29', '2018-03-29', NULL, 2500, 'Please note that ....', 'Account', 'Outside fixtures - needed for parking lot', 49),
(3, 4, '2018-04-02', '2018-04-02', NULL, 357, 'The shipment is needed ASAP.', 'Credit Card', 'Indoor fixture', 49),
(2, 5, '2018-04-30', '2018-04-12', NULL, 50000, 'feedack required', 'Net 45', 'building material', 49),
(3, 6, '2018-04-24', '2018-04-12', NULL, 4500, NULL, 'Credit Card', 'bulb replacement', 55),
(2, 7, '2018-04-25', '2018-04-12', NULL, 5000, NULL, NULL, 'poles needed ', NULL),
(8, 8, '2018-04-27', '2018-04-03', NULL, 120, NULL, 'Cash', 'poles lightinng', 64);

-- --------------------------------------------------------

--
-- Table structure for table `Sales_Order`
--

CREATE TABLE `Sales_Order` (
  `uid` int(11) DEFAULT NULL,
  `SalesOrderNo` int(11) NOT NULL,
  `QuoteNo` int(11) NOT NULL,
  `shipper_id` int(11) DEFAULT NULL,
  `date_received` date DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `freight_charge` float DEFAULT NULL,
  `est_ship_date` date DEFAULT NULL,
  `tracking_no` int(11) DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  `status` varchar(64) NOT NULL,
  `Description` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Sales_Order`
--

INSERT INTO `Sales_Order` (`uid`, `SalesOrderNo`, `QuoteNo`, `shipper_id`, `date_received`, `amount`, `freight_charge`, `est_ship_date`, `tracking_no`, `date_created`, `status`, `Description`) VALUES
(1, 43248, 1, 1, '2018-04-02', 357, 97, '2018-04-02', 2147483647, '2018-03-21', 'On Order', NULL),
(NULL, 43249, 4, NULL, '2018-04-04', NULL, NULL, '2018-04-04', NULL, '2018-04-04', 'On Order', NULL),
(NULL, 43251, 1, 3, '2018-04-04', 250, 20, '2018-04-04', 441165444, '2018-04-04', 'On Order', NULL),
(NULL, 43252, 1, 1, '2018-04-04', 400, 20, '2018-04-04', 1010404050, '2018-04-04', 'Shipped', NULL),
(NULL, 43253, 3, 2, '2018-04-28', 45450, 250, '2018-04-12', 2147483647, '2018-04-12', 'Complete', NULL),
(NULL, 43254, 4, 1, '2018-04-29', 435540, 435, '2018-04-12', 2147483647, '2018-04-12', 'Received', NULL),
(NULL, 43255, 5, 3, '2018-04-29', 9000, 780, '2018-04-12', 2147483647, '2018-04-12', 'Back Ordered', NULL),
(NULL, 43256, 5, 1, '2018-04-30', 6780, 540, '2018-04-12', 2147483647, '2018-04-12', 'Complete', NULL),
(NULL, 43257, 3, 1, '2018-04-12', 8790, 350, '2018-04-27', 2147483647, '2018-04-12', 'Paid', NULL),
(NULL, 43258, 1, 1, '2018-04-26', 50050, 450, '2018-04-12', 2147483647, '2018-04-12', 'Shipped', NULL),
(NULL, 43259, 4, 3, '2018-04-24', 1230, 90, '2018-04-12', 2147483647, '2018-04-12', 'Back Ordered', NULL),
(NULL, 43260, 3, 3, '2018-04-26', 3390, 900, '2018-04-12', 2147483647, '2018-04-12', 'Back Ordered', NULL),
(NULL, 43261, 4, 4, '2018-04-24', 900, 80, '2018-04-12', 2147483647, '2018-04-12', 'Shipped', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Shipper`
--

CREATE TABLE `Shipper` (
  `ShipperID` int(11) NOT NULL,
  `Shname` varchar(64) CHARACTER SET utf16le NOT NULL,
  `Ship_contact_rep` varchar(64) CHARACTER SET utf16le NOT NULL,
  `Shphone` varchar(16) CHARACTER SET utf16le DEFAULT NULL,
  `Shemail` varchar(255) CHARACTER SET utf16le DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Shipper`
--

INSERT INTO `Shipper` (`ShipperID`, `Shname`, `Ship_contact_rep`, `Shphone`, `Shemail`) VALUES
(1, 'Fed Ex', 'Anita Braak', '403-298-2999', 'a@fex.com'),
(2, 'Delay and Loss', 'Brad', '123-098-6785', 'brad@delayandloss.ca'),
(3, 'DHL', 'Aaron Scott', '985-438-2348', 'ryan@dhl.ca'),
(4, 'Superman', 'Clark Kent', '457888888', 'superman@southpole.com'),
(5, 'JusticeLeague', 'JLA', '238999999', 'justice@america.com'),
(6, 'BritneySpears', 'Brit', '4578889900', 'brit@hollywoood.com');


-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE `temp` (
  `id` int(11) NOT NULL,
  `items` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `temp`
--

INSERT INTO `temp` (`id`, `items`) VALUES
(1, 'Item15'),
(2, 'Item2'),
(3, 'Item3'),
(4, 'Item4'),
(5, 'Item5'),
(6, 'Item1'),
(7, 'Item2'),
(8, 'Item3'),
(9, 'Item4'),
(12, 'Item27');

-- --------------------------------------------------------

--
-- Table structure for table `Timelog`
--

CREATE TABLE `Timelog` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `jobnumber` int(11) NOT NULL,
  `dateIn` int(11) NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `hours` float NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `uid` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Timelog`
--

INSERT INTO `Timelog` (`id`, `user_id`, `jobnumber`, `dateIn`, `time_in`, `time_out`, `hours`, `comment`, `uid`) VALUES
(108, 48, 3, 2018, '13:00:00', '14:00:00', 1, NULL, NULL),
(185, 48, 3, 2018, '09:00:00', '11:00:00', 2, 'Additional 15 hours will be required...', NULL),
(6531, 48, 2, 2018, '14:00:00', '16:00:00', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Vendor`
--

CREATE TABLE `Vendor` (
  `Vendor_ID` int(11) NOT NULL,
  `Name` varchar(64) NOT NULL,
  `Street` varchar(128) DEFAULT NULL,
  `City` varchar(64) DEFAULT NULL,
  `Province` varchar(64) DEFAULT NULL,
  `Postal_Code` varchar(16) DEFAULT NULL,
  `Contact` varchar(64) DEFAULT NULL,
  `Phone` varchar(16) DEFAULT NULL,
  `Email` varchar(64) DEFAULT NULL,
  `Notes` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Vendor`
--

INSERT INTO `Vendor` (`Vendor_ID`, `Name`, `Street`, `City`, `Province`, `Postal_Code`, `Contact`, `Phone`, `Email`, `Notes`) VALUES
(1, 'Favorite Vendor', '123 - 4 street', 'Calgary', 'AB', 't1a34r', 'Slick', '333-444-5555', 'Fav@mail.com', 'No Notes'),
(2, '2nd Best', '455, 6 ave', 'Edmonton', 'AB', 'T2T2T2', 'Lindsay Hood', '222-222-2222', '2@2.com', NULL),
(3, 'VendorVendor', '16 Avenue NW', 'Toronto', 'ON', 'R4E2W3', 'Ryan Austin', '985-437-2765', 'raustin@vendor.com', 'Vendor has seven days delivery policy'),
(4, 'NuclearTEch', '900', 'calgary', 'ab', '456hty', 'Mark Zuckerberg', '23400000', 'mzberg@facebook.com', NULL),
(5, 'Reuters', '65', 'calgary', 'ab', 't5h7uu', 'Babaganosh', '9874563', 'babaG@ret.com', NULL),
(6, 'LordofFlies', '3847', 'calgary', 'ab', 'y6yj8j', 'Lordy ', '76549000', 'lordy@lordypants.com', NULL),
(7, 'CBCPol', '8977', 'calgary', 'ab', 'r9rii9', 'Peter Mansbrdg', '87899999', 'petermans@gmot.com', NULL),
(8, 'Bash', '8987', 'calgary', 'ab', 't5yu76', 'jon', '89989890', 'jon@gmail.com', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Components`
--
ALTER TABLE `Components`
  ADD PRIMARY KEY (`part_no`),
  ADD UNIQUE KEY `part_no` (`part_no`);

--
-- Indexes for table `Customers`
--
ALTER TABLE `Customers`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `employee_info`
--
ALTER TABLE `employee_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `Fixtures`
--
ALTER TABLE `Fixtures`
  ADD PRIMARY KEY (`PartNo`),
  ADD UNIQUE KEY `PartNo` (`PartNo`);

--
-- Indexes for table `Fixture_Components`
--
ALTER TABLE `Fixture_Components`
  ADD PRIMARY KEY (`id`),
  ADD KEY `component_no` (`component_no`),
  ADD KEY `fixture_no` (`fixture_no`);

--
-- Indexes for table `Inventory`
--
ALTER TABLE `Inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `part_no` (`part_no`);

--
-- Indexes for table `Items`
--
ALTER TABLE `Items`
  ADD PRIMARY KEY (`id`,`QuoteNo`),
  ADD KEY `part_no` (`part_no`),
  ADD KEY `QuoteNo` (`QuoteNo`);

--
-- Indexes for table `Personal_Data`
--
ALTER TABLE `Personal_Data`
  ADD PRIMARY KEY (`uid`,`SIN`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `phpauthent_users`
--
ALTER TABLE `phpauthent_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `PO_Components`
--
ALTER TABLE `PO_Components`
  ADD PRIMARY KEY (`id`),
  ADD KEY `po_number` (`po_number`),
  ADD KEY `part_no` (`part_no`);

--
-- Indexes for table `Project`
--
ALTER TABLE `Project`
  ADD PRIMARY KEY (`ProjectNo`);

--
-- Indexes for table `Purchase_Order`
--
ALTER TABLE `Purchase_Order`
  ADD PRIMARY KEY (`po_number`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `shipper_id` (`shipper_id`),
  ADD KEY `ordered_by` (`ordered_by`);

--
-- Indexes for table `Quote`
--
ALTER TABLE `Quote`
  ADD PRIMARY KEY (`QuoteNo`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `Sales_Order`
--
ALTER TABLE `Sales_Order`
  ADD PRIMARY KEY (`SalesOrderNo`) USING BTREE,
  ADD UNIQUE KEY `SalesOrderNo` (`SalesOrderNo`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD KEY `shipper_id` (`shipper_id`),
  ADD KEY `QuoteNo` (`QuoteNo`);

--
-- Indexes for table `Shipper`
--
ALTER TABLE `Shipper`
  ADD PRIMARY KEY (`ShipperID`),
  ADD UNIQUE KEY `Shname` (`Shname`);

--
-- Indexes for table `temp`
--
ALTER TABLE `temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Timelog`
--
ALTER TABLE `Timelog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobnumber` (`jobnumber`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Vendor`
--
ALTER TABLE `Vendor`
  ADD PRIMARY KEY (`Vendor_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Customers`
--
ALTER TABLE `Customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `employee_info`
--
ALTER TABLE `employee_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
--
-- AUTO_INCREMENT for table `Fixture_Components`
--
ALTER TABLE `Fixture_Components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `Inventory`
--
ALTER TABLE `Inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `Items`
--
ALTER TABLE `Items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `Personal_Data`
--
ALTER TABLE `Personal_Data`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `phpauthent_groups`
--
ALTER TABLE `phpauthent_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `phpauthent_relation`
--
ALTER TABLE `phpauthent_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=444;
--
-- AUTO_INCREMENT for table `phpauthent_users`
--
ALTER TABLE `phpauthent_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT for table `PO_Components`
--
ALTER TABLE `PO_Components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `Project`
--
ALTER TABLE `Project`
  MODIFY `ProjectNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `Purchase_Order`
--
ALTER TABLE `Purchase_Order`
  MODIFY `po_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `Quote`
--
ALTER TABLE `Quote`
  MODIFY `QuoteNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `Sales_Order`
--
ALTER TABLE `Sales_Order`
  MODIFY `SalesOrderNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43262;
--
-- AUTO_INCREMENT for table `Shipper`
--
ALTER TABLE `Shipper`
  MODIFY `ShipperID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `temp`
--
ALTER TABLE `temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `Timelog`
--
ALTER TABLE `Timelog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6532;
--
-- AUTO_INCREMENT for table `Vendor`
--
ALTER TABLE `Vendor`
  MODIFY `Vendor_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Components`
--
ALTER TABLE `Components`
  ADD CONSTRAINT `Components_ibfk_1` FOREIGN KEY (`part_no`) REFERENCES `Inventory` (`part_no`);

--
-- Constraints for table `employee_info`
--
ALTER TABLE `employee_info`
  ADD CONSTRAINT `employee_info_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `phpauthent_users` (`id`);

--
-- Constraints for table `Fixtures`
--
ALTER TABLE `Fixtures`
  ADD CONSTRAINT `Fixtures_ibfk_1` FOREIGN KEY (`PartNo`) REFERENCES `Inventory` (`part_no`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Fixture_Components`
--
ALTER TABLE `Fixture_Components`
  ADD CONSTRAINT `Fixture_Components_ibfk_1` FOREIGN KEY (`component_no`) REFERENCES `Components` (`part_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Fixture_Components_ibfk_2` FOREIGN KEY (`fixture_no`) REFERENCES `Fixtures` (`PartNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Items`
--
ALTER TABLE `Items`
  ADD CONSTRAINT `Items_ibfk_1` FOREIGN KEY (`QuoteNo`) REFERENCES `Quote` (`QuoteNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `phpauthent_relation`
--
ALTER TABLE `phpauthent_relation`
  ADD CONSTRAINT `phpauthent_relation_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `phpauthent_groups` (`id`),
  ADD CONSTRAINT `phpauthent_relation_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `phpauthent_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `PO_Components`
--
ALTER TABLE `PO_Components`
  ADD CONSTRAINT `PO_Components_ibfk_1` FOREIGN KEY (`po_number`) REFERENCES `Purchase_Order` (`po_number`);

--
-- Constraints for table `Purchase_Order`
--
ALTER TABLE `Purchase_Order`
  ADD CONSTRAINT `Purchase_Order_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `Vendor` (`Vendor_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Purchase_Order_ibfk_2` FOREIGN KEY (`shipper_id`) REFERENCES `Shipper` (`ShipperID`),
  ADD CONSTRAINT `Purchase_Order_ibfk_3` FOREIGN KEY (`ordered_by`) REFERENCES `phpauthent_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Quote`
--
ALTER TABLE `Quote`
  ADD CONSTRAINT `Quote_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `Customers` (`CustomerID`);

--
-- Constraints for table `Sales_Order`
--
ALTER TABLE `Sales_Order`
  ADD CONSTRAINT `Sales_Order_ibfk_1` FOREIGN KEY (`shipper_id`) REFERENCES `Shipper` (`ShipperID`),
  ADD CONSTRAINT `Sales_Order_ibfk_2` FOREIGN KEY (`QuoteNo`) REFERENCES `Quote` (`QuoteNo`) ON DELETE CASCADE ON UPDATE CASCADE;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
