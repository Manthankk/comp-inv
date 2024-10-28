-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2024 at 07:49 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventorymanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventroy`
--

CREATE TABLE `inventroy` (
  `item_name` varchar(30) NOT NULL,
  `quantity_available` int(11) NOT NULL,
  `issued` int(11) NOT NULL,
  `total_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inventroy`
--

INSERT INTO `inventroy` (`item_name`, `quantity_available`, `issued`, `total_quantity`) VALUES
('Cable cab 6', 0, 0, 0),
('CAT-6 networking cab', 0, 0, 0),
('CPU Fan', 0, 0, 0),
('crimping tool', 0, 0, 0),
('D Link Switch', 0, 0, 0),
('Desktop', 0, 0, 0),
('Dlink 16port Switch', 0, 0, 0),
('Dlink 24port Switch', 0, 0, 0),
('Dlink 5port Switch', 0, 0, 0),
('HDMI Cable', 0, 0, 0),
('HDMI Switch', 0, 0, 0),
('Keyboard', 0, 0, 0),
('Keyboard+mouse(combo)', 0, 0, 0),
('LAN Tester', 0, 0, 0),
('Monitor', 0, 0, 0),
('Motherboard', 0, 0, 0),
('Mouse', 8, 2, 10),
('RJ 45 Connector', 0, 0, 0),
('SMPS', 0, 0, 0),
('Speakers', 0, 0, 0),
('Tech Rmte 24 port sw', 0, 0, 0),
('TP Link Router', 0, 0, 0),
('TP Link Switch', 0, 0, 0),
('UPS 1kv APC', 0, 0, 0),
('VGA Cable', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `issue`
--

CREATE TABLE `issue` (
  `id` int(20) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `item_name` varchar(20) NOT NULL,
  `department` varchar(40) NOT NULL,
  `room` varchar(10) NOT NULL,
  `desktop_ID` varchar(20) NOT NULL,
  `quantity` int(10) NOT NULL,
  `receiver` varchar(10) DEFAULT NULL,
  `issued_by` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `issue`
--

INSERT INTO `issue` (`id`, `date`, `item_name`, `department`, `room`, `desktop_ID`, `quantity`, `receiver`, `issued_by`) VALUES
(12, '2024-01-29', 'Mouse', 'Computer Engineering', '', '', 1, 'Mr k.Ratho', 'Mr D.Patel'),
(13, '2024-01-29', 'Mouse', 'EXTC', 'A02', '', 1, 'Mr k.Ratho', 'Mr D.Patel');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `company_name` varchar(15) NOT NULL,
  `specification` varchar(50) NOT NULL,
  `quantity` int(10) NOT NULL,
  `price` int(10) NOT NULL,
  `invoice_img` varchar(200) NOT NULL,
  `date_added` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_name`, `company_name`, `specification`, `quantity`, `price`, `invoice_img`, `date_added`) VALUES
(16, 'Mouse', '', '', 10, 4000, '', '2024-01-29 16:26:18');

--
-- Triggers `items`
--
DELIMITER $$
CREATE TRIGGER `update_inventroy_after_insert` AFTER INSERT ON `items` FOR EACH ROW BEGIN
    -- Update the corresponding row in the inventory table
    -- Increase quantity_available by the newly added quantity
    -- Set total_quantity as quantity_available + issued
    UPDATE inventroy
    SET
        quantity_available = quantity_available + NEW.quantity,
        total_quantity = quantity_available + issued
    WHERE item_name = NEW.item_name;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `scrap`
--

CREATE TABLE `scrap` (
  `id` int(20) NOT NULL,
  `item_name` varchar(30) NOT NULL,
  `department` varchar(20) NOT NULL,
  `room` varchar(20) NOT NULL,
  `desktop_ID` varchar(20) NOT NULL,
  `identification_no` varchar(20) NOT NULL,
  `verifyed_by` varchar(20) NOT NULL,
  `Specification` varchar(40) NOT NULL,
  `timedate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Triggers `scrap`
--
DELIMITER $$
CREATE TRIGGER `after_scrap_insert` AFTER INSERT ON `scrap` FOR EACH ROW BEGIN
    UPDATE inventroy
    SET issued = issued - 1
    WHERE item_name = NEW.item_name;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp(),
  `branch` varchar(20) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `reg_date`, `branch`, `role`) VALUES
(10, 'akashmane907', 'akashmane91111@gmail.com', '$2y$10$39MjVLCi3dBop/SCOaYDMuGWIJiEyGTG684QswX9Xga6jyq3J2X2u', '2024-01-31 23:57:53', '', 'admin'),
(11, 'admin97', 'admin1@gmail.com', '$2y$10$IxRdIVqmxC1XzsMr2LQw4Oos1Eb7kJJmB2odb4225qNC3uZrciW2K', '2024-02-01 00:24:31', 'Computer Engineering', ''),
(12, 'omk', 'omk@gmail.com', '$2y$10$2GRtabO6EYMQRsmHRJD85ugS4W6zhN/8t932VeewIgesTnQU2Gumu', '2024-02-01 18:47:55', 'IT', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventroy`
--
ALTER TABLE `inventroy`
  ADD PRIMARY KEY (`item_name`);

--
-- Indexes for table `issue`
--
ALTER TABLE `issue`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scrap`
--
ALTER TABLE `scrap`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_scrap_item_name` (`item_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `issue`
--
ALTER TABLE `issue`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `scrap`
--
ALTER TABLE `scrap`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `scrap`
--
ALTER TABLE `scrap`
  ADD CONSTRAINT `fk_scrap_item_name` FOREIGN KEY (`item_name`) REFERENCES `inventroy` (`item_name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
