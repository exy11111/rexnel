-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2025 at 01:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `houseoflocal_db`
--
CREATE DATABASE IF NOT EXISTS `houseoflocal_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `houseoflocal_db`;

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

DROP TABLE IF EXISTS `branch`;
CREATE TABLE IF NOT EXISTS `branch` (
  `branch_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `opening_time` time NOT NULL,
  `closing_time` time NOT NULL,
  PRIMARY KEY (`branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branch_id`, `branch_name`, `location`, `opening_time`, `closing_time`) VALUES
(1, 'Pagsanjan Branch', '62 JP Rizal St. Poblacion II, near McDonalds & Pagsanjan Stone Arch, beside Ilaya Bakery', '10:30:00', '19:00:00'),
(2, 'Los Baños Branch', '7690 San Antonio National Highway, MR & Sons Bldg, near Crossing, in front of KingLu Hardware. ', '10:00:00', '20:00:00'),
(3, 'Sta. Cruz Branch', 'Sitio Sampaguita, Brgy. Bubukal, beside Main Gate of LSPU, near Sambat Intersection. ', '10:00:00', '19:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) NOT NULL,
  `brand_description` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `brand_description`, `branch_id`, `created_at`, `updated_at`) VALUES
(12, 'Dbtk', 'Dbtk', 1, '2025-04-20 04:53:15', '2025-04-20 06:28:48');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `branch_id`) VALUES
(16, 'T-Shirt', 1);

-- --------------------------------------------------------

--
-- Table structure for table `db_status`
--

DROP TABLE IF EXISTS `db_status`;
CREATE TABLE IF NOT EXISTS `db_status` (
  `db_status_id` int(11) NOT NULL,
  `db_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `barcode`, `category_id`, `brand_id`, `supplier_id`, `size_id`, `price`, `stock`, `branch_id`) VALUES
(31, 'DBtk', '001', 16, 12, 9, 11, 1000.00, 86, 1),
(32, 'DBtk', '002', 16, 12, 9, 12, 1700.00, 989, 1),
(33, 'Brigade', '003', 16, 12, 9, 11, 900.00, 997, 1),
(34, 'Highmind', '004', 16, 12, 9, 11, 900.00, 998, 1),
(35, 'Highmind', '005', 16, 12, 9, 12, 1000.00, 989, 1),
(36, 'Unfirend', '006', 16, 12, 9, 11, 900.00, 998, 1),
(37, 'offhigh', '007', 16, 12, 9, 11, 900.00, 989, 1),
(38, 'at your pace', '008', 16, 12, 9, 11, 900.00, 994, 1),
(39, 'Coziest', '009', 16, 12, 9, 11, 900.00, 995, 1),
(40, 'Undrafted', '010', 16, 12, 9, 12, 1000.00, 987, 1),
(41, 'HoL ', '011', 16, 12, 9, 9, 300.00, 987, 1),
(42, 'Degraded', '012', 16, 12, 9, 11, 850.00, 995, 1),
(43, 'Classic', '013', 16, 12, 9, 11, 900.00, 987, 1),
(44, 'Thrilling', '014', 16, 12, 9, 9, 450.00, 986, 1),
(45, 'Daily Flight', '015', 16, 12, 9, 10, 600.00, 998, 1),
(46, 'gnarly', '018', 16, 12, 9, 11, 1250.00, 992, 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seen` tinyint(1) DEFAULT 0,
  `icon` varchar(50) DEFAULT 'bi-info-circle',
  `target_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `message`, `user_id`, `created_at`, `seen`, `icon`, `target_url`) VALUES
(4, 'Stock for classic short has been updated: 1 added.aaa aaaaaaaaaaaa aaaaaaaaaaaaaaaa aaa aaa aaa aaa aaa', 17, '2024-04-07 19:58:50', 1, 'bi-bag-check', 'stock.php'),
(5, 'Stock for classic short has been updated: 1 added.', 19, '2025-04-07 20:58:50', 0, 'bi-bag-check', 'stock.php'),
(6, 'Stock for classic short has been updated: 1 added.', 20, '2025-04-07 20:58:50', 1, 'bi-bag-check', 'stock.php'),
(7, 'admin added an item: good items (1 pcs, ₱1)', 17, '2025-04-07 22:26:45', 1, 'bi-plus-circle', 'stock.php'),
(8, 'admin added an item: good items (1 pcs, ₱1)', 19, '2025-04-07 22:26:45', 0, 'bi-plus-circle', 'stock.php'),
(9, 'admin added an item: good items (1 pcs, ₱1)', 20, '2025-04-07 22:26:45', 0, 'bi-plus-circle', 'stock.php'),
(10, '<strong>admin</strong> updated stock for <strong>Kuya Wil Jacket</strong>: <strong>55</strong> added.', 17, '2025-04-07 23:36:32', 1, 'bi-bag-check', 'stock.php'),
(11, '<strong>admin</strong> updated stock for <strong>Kuya Wil Jacket</strong>: <strong>55</strong> added.', 19, '2025-04-07 23:36:32', 0, 'bi-bag-check', 'stock.php'),
(12, '<strong>admin</strong> updated stock for <strong>Kuya Wil Jacket</strong>: <strong>55</strong> added.', 20, '2025-04-07 23:36:32', 0, 'bi-bag-check', 'stock.php'),
(13, '<strong>admin</strong> updated stock for <strong>DBTK Shorts</strong>: <strong>12</strong> added.', 17, '2025-04-09 05:07:03', 1, 'bi-bag-check', 'stock.php'),
(14, '<strong>admin</strong> updated stock for <strong>DBTK Shorts</strong>: <strong>12</strong> added.', 19, '2025-04-09 05:07:03', 0, 'bi-bag-check', 'stock.php'),
(15, '<strong>admin</strong> updated stock for <strong>DBTK Shorts</strong>: <strong>12</strong> added.', 20, '2025-04-09 05:07:03', 0, 'bi-bag-check', 'stock.php'),
(16, '<strong></strong> updated stock for <strong>Kuya Wil Jacket</strong>: <strong>199</strong> added.', 17, '2025-04-09 21:45:52', 0, 'bi-bag-check', 'stock.php'),
(17, '<strong></strong> updated stock for <strong>Kuya Wil Jacket</strong>: <strong>199</strong> added.', 19, '2025-04-09 21:45:52', 0, 'bi-bag-check', 'stock.php'),
(18, '<strong></strong> updated stock for <strong>Kuya Wil Jacket</strong>: <strong>199</strong> added.', 20, '2025-04-09 21:45:52', 0, 'bi-bag-check', 'stock.php'),
(19, '<strong></strong> updated stock for <strong>good items</strong>: <strong>6</strong> added.', 17, '2025-04-09 22:21:23', 0, 'bi-bag-check', 'stock.php'),
(20, '<strong></strong> updated stock for <strong>good items</strong>: <strong>6</strong> added.', 19, '2025-04-09 22:21:23', 0, 'bi-bag-check', 'stock.php'),
(21, '<strong></strong> updated stock for <strong>good items</strong>: <strong>6</strong> added.', 20, '2025-04-09 22:21:23', 0, 'bi-bag-check', 'stock.php'),
(22, ' added an item: Dbtk (1000 pcs, ₱1000.00)', 17, '2025-04-20 04:54:21', 0, 'bi-plus-circle', 'stock.php'),
(23, ' added an item: Dbtk (1000 pcs, ₱1000.00)', 19, '2025-04-20 04:54:21', 0, 'bi-plus-circle', 'stock.php'),
(24, ' added an item: Dbtk (1000 pcs, ₱1000.00)', 20, '2025-04-20 04:54:21', 0, 'bi-plus-circle', 'stock.php'),
(25, ' added an item: DBtk (1000 pcs, ₱1000.00)', 17, '2025-04-20 04:55:10', 0, 'bi-plus-circle', 'stock.php'),
(26, ' added an item: DBtk (1000 pcs, ₱1000.00)', 19, '2025-04-20 04:55:10', 0, 'bi-plus-circle', 'stock.php'),
(27, ' added an item: DBtk (1000 pcs, ₱1000.00)', 20, '2025-04-20 04:55:10', 0, 'bi-plus-circle', 'stock.php'),
(28, ' added an item: DBtk (1000 pcs, ₱1700.00)', 17, '2025-04-20 04:55:25', 0, 'bi-plus-circle', 'stock.php'),
(29, ' added an item: DBtk (1000 pcs, ₱1700.00)', 19, '2025-04-20 04:55:25', 0, 'bi-plus-circle', 'stock.php'),
(30, ' added an item: DBtk (1000 pcs, ₱1700.00)', 20, '2025-04-20 04:55:25', 0, 'bi-plus-circle', 'stock.php'),
(31, ' added an item: Brigade (1000 pcs, ₱900.00)', 19, '2025-04-20 05:15:02', 0, 'bi-plus-circle', 'stock.php'),
(32, ' added an item: Brigade (1000 pcs, ₱900.00)', 20, '2025-04-20 05:15:02', 0, 'bi-plus-circle', 'stock.php'),
(33, ' added an item: Highmind (1000 pcs, ₱900.00)', 19, '2025-04-20 05:18:52', 0, 'bi-plus-circle', 'stock.php'),
(34, ' added an item: Highmind (1000 pcs, ₱900.00)', 20, '2025-04-20 05:18:52', 0, 'bi-plus-circle', 'stock.php'),
(35, ' added an item: Highmind (1000 pcs, ₱1000.00)', 19, '2025-04-20 05:19:21', 0, 'bi-plus-circle', 'stock.php'),
(36, ' added an item: Highmind (1000 pcs, ₱1000.00)', 20, '2025-04-20 05:19:21', 0, 'bi-plus-circle', 'stock.php'),
(37, ' added an item: Unfirend (1000 pcs, ₱900.00)', 19, '2025-04-20 05:20:18', 0, 'bi-plus-circle', 'stock.php'),
(38, ' added an item: Unfirend (1000 pcs, ₱900.00)', 20, '2025-04-20 05:20:18', 0, 'bi-plus-circle', 'stock.php'),
(39, ' added an item: offhigh (1000 pcs, ₱900.00)', 19, '2025-04-20 05:20:43', 0, 'bi-plus-circle', 'stock.php'),
(40, ' added an item: offhigh (1000 pcs, ₱900.00)', 20, '2025-04-20 05:20:43', 0, 'bi-plus-circle', 'stock.php'),
(41, ' added an item: at your pace (1000 pcs, ₱900.00)', 19, '2025-04-20 05:21:07', 0, 'bi-plus-circle', 'stock.php'),
(42, ' added an item: at your pace (1000 pcs, ₱900.00)', 20, '2025-04-20 05:21:07', 0, 'bi-plus-circle', 'stock.php'),
(43, ' added an item: Coziest (1000 pcs, ₱900.00)', 19, '2025-04-20 05:21:40', 0, 'bi-plus-circle', 'stock.php'),
(44, ' added an item: Coziest (1000 pcs, ₱900.00)', 20, '2025-04-20 05:21:40', 0, 'bi-plus-circle', 'stock.php'),
(45, ' added an item: Undrafted (1000 pcs, ₱1000.00)', 19, '2025-04-20 05:22:07', 0, 'bi-plus-circle', 'stock.php'),
(46, ' added an item: Undrafted (1000 pcs, ₱1000.00)', 20, '2025-04-20 05:22:07', 0, 'bi-plus-circle', 'stock.php'),
(47, ' added an item: HoL  (1000 pcs, ₱300.00)', 19, '2025-04-20 05:22:39', 0, 'bi-plus-circle', 'stock.php'),
(48, ' added an item: HoL  (1000 pcs, ₱300.00)', 20, '2025-04-20 05:22:39', 0, 'bi-plus-circle', 'stock.php'),
(49, ' added an item: Degraded (1000 pcs, ₱850.00)', 19, '2025-04-20 05:23:05', 0, 'bi-plus-circle', 'stock.php'),
(50, ' added an item: Degraded (1000 pcs, ₱850.00)', 20, '2025-04-20 05:23:05', 0, 'bi-plus-circle', 'stock.php'),
(51, ' added an item: Classic (1000 pcs, ₱900.00)', 19, '2025-04-20 05:23:34', 0, 'bi-plus-circle', 'stock.php'),
(52, ' added an item: Classic (1000 pcs, ₱900.00)', 20, '2025-04-20 05:23:34', 0, 'bi-plus-circle', 'stock.php'),
(53, ' added an item: Thrilling (1000 pcs, ₱450.00)', 19, '2025-04-20 05:23:58', 0, 'bi-plus-circle', 'stock.php'),
(54, ' added an item: Thrilling (1000 pcs, ₱450.00)', 20, '2025-04-20 05:23:58', 0, 'bi-plus-circle', 'stock.php'),
(55, ' added an item: Daily Flight (1000 pcs, ₱600.00)', 19, '2025-04-20 05:24:27', 0, 'bi-plus-circle', 'stock.php'),
(56, ' added an item: Daily Flight (1000 pcs, ₱600.00)', 20, '2025-04-20 05:24:27', 0, 'bi-plus-circle', 'stock.php'),
(57, ' added an item: gnarly (1000 pcs, ₱1250.00)', 19, '2025-04-20 22:37:55', 0, 'bi-plus-circle', 'stock.php'),
(58, ' added an item: gnarly (1000 pcs, ₱1250.00)', 20, '2025-04-20 22:37:55', 0, 'bi-plus-circle', 'stock.php');

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

DROP TABLE IF EXISTS `payment_method`;
CREATE TABLE IF NOT EXISTS `payment_method` (
  `pm_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_method` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`pm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`pm_id`, `payment_method`, `branch_id`) VALUES
(1, 'Cash', 1),
(2, 'GCash', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
CREATE TABLE IF NOT EXISTS `purchases` (
  `purchase_id` int(11) NOT NULL AUTO_INCREMENT,
  `price` decimal(10,2) NOT NULL,
  `pm_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`purchase_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`purchase_id`, `price`, `pm_id`, `date`, `branch_id`) VALUES
(23, 2000.00, 1, '2025-03-01 07:56:57', 1),
(24, 1000.00, 1, '2025-03-01 07:56:57', 1),
(25, 2000.00, 1, '2025-03-01 07:56:57', 1),
(26, 600.00, 1, '2025-03-01 07:56:57', 1),
(27, 900.00, 1, '2025-03-01 07:56:57', 1),
(28, 1000.00, 1, '2025-03-01 07:56:57', 1),
(29, 1800.00, 1, '2025-03-01 07:56:57', 1),
(30, 1250.00, 1, '2025-03-01 07:56:57', 1),
(31, 900.00, 1, '2025-03-01 07:56:57', 1),
(32, 900.00, 1, '2025-03-01 07:56:57', 1),
(33, 11600.00, 1, '2025-03-01 22:42:17', 1),
(34, 6900.00, 1, '2025-03-02 22:44:24', 1),
(35, 4100.00, 1, '2025-03-02 22:45:19', 1),
(36, 12950.00, 1, '2025-03-03 22:47:22', 1),
(37, 13950.00, 1, '2025-03-04 22:49:39', 1),
(38, 13850.00, 1, '2025-03-05 22:52:50', 1),
(39, 10150.00, 1, '2025-03-06 22:54:03', 1),
(40, 12300.00, 1, '2025-03-07 22:55:52', 1),
(41, 10600.00, 1, '2025-03-08 22:57:39', 1),
(42, 11400.00, 1, '2025-04-20 22:59:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

DROP TABLE IF EXISTS `purchase_items`;
CREATE TABLE IF NOT EXISTS `purchase_items` (
  `pi_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`pi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`pi_id`, `purchase_id`, `item_id`, `quantity`) VALUES
(20, 23, 31, 2),
(21, 24, 31, 1),
(22, 25, 35, 2),
(23, 26, 41, 2),
(24, 27, 43, 1),
(25, 28, 40, 1),
(26, 29, 37, 2),
(27, 30, 46, 1),
(28, 31, 39, 1),
(29, 32, 36, 1),
(30, 33, 44, 4),
(31, 33, 45, 1),
(32, 33, 31, 1),
(33, 33, 32, 2),
(34, 33, 35, 2),
(35, 33, 33, 1),
(36, 33, 40, 1),
(37, 33, 43, 1),
(38, 34, 35, 1),
(39, 34, 43, 2),
(40, 34, 32, 1),
(41, 34, 41, 2),
(42, 34, 38, 1),
(43, 34, 37, 1),
(44, 35, 42, 1),
(45, 35, 44, 1),
(46, 35, 31, 1),
(47, 35, 39, 1),
(48, 35, 36, 1),
(49, 36, 31, 2),
(50, 36, 42, 1),
(51, 36, 44, 1),
(52, 36, 40, 1),
(53, 36, 39, 1),
(54, 36, 35, 2),
(55, 36, 46, 1),
(56, 36, 37, 2),
(57, 36, 43, 2),
(58, 36, 38, 1),
(59, 37, 31, 1),
(60, 37, 39, 2),
(61, 37, 41, 2),
(62, 37, 43, 1),
(63, 37, 42, 1),
(64, 37, 32, 3),
(65, 37, 40, 1),
(66, 37, 44, 2),
(67, 37, 33, 1),
(68, 37, 37, 1),
(69, 38, 32, 1),
(70, 38, 31, 2),
(71, 38, 35, 2),
(72, 38, 41, 1),
(73, 38, 43, 2),
(74, 38, 40, 3),
(75, 38, 37, 1),
(76, 38, 46, 1),
(77, 38, 38, 1),
(78, 39, 32, 1),
(79, 39, 31, 1),
(80, 39, 34, 1),
(81, 39, 41, 2),
(82, 39, 43, 1),
(83, 39, 40, 2),
(84, 39, 37, 1),
(85, 39, 46, 1),
(86, 39, 38, 1),
(87, 40, 32, 1),
(88, 40, 31, 1),
(89, 40, 34, 1),
(90, 40, 41, 2),
(91, 40, 43, 1),
(92, 40, 40, 2),
(93, 40, 37, 2),
(94, 40, 46, 2),
(95, 40, 38, 1),
(96, 41, 44, 4),
(97, 41, 45, 1),
(98, 41, 32, 2),
(99, 41, 35, 2),
(100, 41, 33, 1),
(101, 41, 40, 1),
(102, 41, 43, 1),
(103, 42, 44, 2),
(104, 42, 37, 1),
(105, 42, 40, 1),
(106, 42, 46, 2),
(107, 42, 38, 1),
(108, 42, 41, 2),
(109, 42, 31, 2),
(110, 42, 43, 1),
(111, 42, 42, 2);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Super Admin'),
(2, 'Branch Admin');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

DROP TABLE IF EXISTS `sizes`;
CREATE TABLE IF NOT EXISTS `sizes` (
  `size_id` int(11) NOT NULL AUTO_INCREMENT,
  `size_name` varchar(255) NOT NULL,
  `size_description` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`size_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`size_id`, `size_name`, `size_description`, `branch_id`) VALUES
(9, 'S', 'Small', 1),
(10, 'M', 'Medium', 1),
(11, 'L', 'Large', 1),
(12, 'XL', 'Extra Large', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
CREATE TABLE IF NOT EXISTS `stock` (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(255) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `contact_name`, `email`, `phone`, `address`, `branch_id`) VALUES
(9, 'Dbtk', 'Rexnel', 'rexnel@gmail.com', '09123456789', 'jan lang', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `transaction_type` enum('increase','decrease') DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `before_quantity` int(11) DEFAULT NULL,
  `after_quantity` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userdetails`
--

DROP TABLE IF EXISTS `userdetails`;
CREATE TABLE IF NOT EXISTS `userdetails` (
  `userdetails_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  PRIMARY KEY (`userdetails_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userdetails`
--

INSERT INTO `userdetails` (`userdetails_id`, `user_id`, `email`, `firstname`, `lastname`) VALUES
(16, 17, 'admin@gmail.com', 'Rexnels', 'Ulic-Ulic'),
(18, 19, 'exy@gmail.com', 'Exy', 'F'),
(19, 20, 'mikyla@gmail.com', 'Mikyla', 'Fernandezz'),
(20, 21, 'vj@gmail.com', 'Victor', 'Azucena');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `branch_id`, `created_at`) VALUES
(17, 'admin', '$2y$10$9.uk1x5UYAXfYGPHM3GKDOgGRCf231bOVUXdOI59DH8VBJIQjiZii', 0, '2025-03-13 14:40:04'),
(19, 'exy', '$2y$10$ITp8OblRl/j.SnHtFVejX.RBfoxf84eQcylfG.IlLf8jJq.x0Z2RK', 1, '2025-03-26 03:41:00'),
(20, 'mikyla', '$2y$10$rdJaAo35jwwC68UasthmwezHSpi1el4Bkeasm9DC9Z1T.gDB.WdqS', 1, '2025-03-26 04:56:43'),
(21, 'vj', '$2y$10$itUrIxsLASMxQl.A6Y8fAegbtGx6xaz09P3msY2LdQ2k3bPvIsvFK', 2, '2025-04-20 07:33:16');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
