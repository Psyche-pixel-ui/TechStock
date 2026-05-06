-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2026 at 02:07 PM
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
-- Database: `techstock_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Product_ID` int(11) NOT NULL,
  `Product_Name` varchar(200) NOT NULL,
  `Category` enum('CPU','GPU','RAM','Storage','Motherboard','Peripheral','Other') NOT NULL,
  `Price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `Stock_Quantity` int(11) NOT NULL DEFAULT 0,
  `Min_Stock_Level` int(11) NOT NULL DEFAULT 1,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp(),
  `Updated_At` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Product_ID`, `Product_Name`, `Category`, `Price`, `Stock_Quantity`, `Min_Stock_Level`, `Created_At`, `Updated_At`) VALUES
(1, 'Intel Core i5-12400F', 'CPU', 8500.00, 11, 3, '2026-05-04 10:42:16', '2026-05-04 16:32:44'),
(2, 'AMD Ryzen 5 5600X', 'CPU', 9200.00, 2, 3, '2026-05-04 10:42:16', '2026-05-04 10:42:16'),
(3, 'MSI B550 Tomahawk', 'Motherboard', 7800.00, 5, 2, '2026-05-04 10:42:16', '2026-05-04 10:42:16'),
(4, 'Corsair 16GB DDR4', 'RAM', 2800.00, 4, 4, '2026-05-04 10:42:16', '2026-05-04 16:32:18'),
(5, 'Samsung 970 EVO 1TB', 'Storage', 4500.00, 8, 3, '2026-05-04 10:42:16', '2026-05-04 10:42:16'),
(6, 'Logitech G102', 'Peripheral', 850.00, 15, 5, '2026-05-04 10:42:16', '2026-05-04 10:42:16'),
(7, 'ASUS RTX 3060 12GB', 'GPU', 18500.00, 3, 2, '2026-05-04 10:42:16', '2026-05-04 10:42:16'),
(8, 'Kingston A400 SSD 480GB', 'Storage', 1800.00, 1, 3, '2026-05-04 10:42:16', '2026-05-04 10:42:16');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transaction`
--

CREATE TABLE `stock_transaction` (
  `Transaction_ID` int(11) NOT NULL,
  `Product_ID` int(11) NOT NULL,
  `Supplier_ID` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `Transaction_Date` datetime NOT NULL,
  `Type` enum('Stock In','Stock Out') NOT NULL,
  `Remarks` varchar(255) DEFAULT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `stock_transaction`
--

INSERT INTO `stock_transaction` (`Transaction_ID`, `Product_ID`, `Supplier_ID`, `Quantity`, `Transaction_Date`, `Type`, `Remarks`, `Created_At`) VALUES
(1, 1, 1, 5, '2026-04-20 09:00:00', 'Stock In', 'Regular restock', '2026-05-04 10:42:16'),
(2, 4, 2, 10, '2026-04-21 10:30:00', 'Stock In', NULL, '2026-05-04 10:42:16'),
(3, 6, NULL, 3, '2026-04-22 11:00:00', 'Stock Out', 'Sold', '2026-05-04 10:42:16'),
(4, 2, NULL, 2, '2026-04-23 14:15:00', 'Stock Out', 'Sold', '2026-05-04 10:42:16'),
(5, 8, 3, 4, '2026-04-24 09:45:00', 'Stock In', NULL, '2026-05-04 10:42:16'),
(6, 4, 3, 4, '2026-05-04 00:00:00', 'Stock In', '', '2026-05-04 16:32:18'),
(7, 1, NULL, 1, '2026-05-04 00:00:00', 'Stock Out', '', '2026-05-04 16:32:44');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `Supplier_ID` int(11) NOT NULL,
  `Supplier_Name` varchar(150) NOT NULL COMMENT '\r\n',
  `Contact_Number` varchar(20) NOT NULL COMMENT '\r\n',
  `Email_Address` varchar(150) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Saves the contact details of all suppliers';

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`Supplier_ID`, `Supplier_Name`, `Contact_Number`, `Email_Address`, `Address`, `Created_At`) VALUES
(1, 'PC Express', '0917-111-2222', 'info@pcx.com', 'Davao City', '2026-05-04 10:42:16'),
(2, 'Dynaquest PC', '0918-333-4444', 'sales@dynaquest.com', 'Davao City', '2026-05-04 10:42:16'),
(3, 'EasyPC Trading', '0919-555-6666', 'orders@easypc.com.ph', 'Davao City', '2026-05-04 10:42:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Product_ID`);

--
-- Indexes for table `stock_transaction`
--
ALTER TABLE `stock_transaction`
  ADD PRIMARY KEY (`Transaction_ID`),
  ADD KEY `fk_txn_product` (`Product_ID`),
  ADD KEY `fk_txn_supplier` (`Supplier_ID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`Supplier_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Product_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transaction`
--
ALTER TABLE `stock_transaction`
  MODIFY `Transaction_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `Supplier_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stock_transaction`
--
ALTER TABLE `stock_transaction`
  ADD CONSTRAINT `fk_txn_product` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`Product_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_txn_supplier` FOREIGN KEY (`Supplier_ID`) REFERENCES `supplier` (`Supplier_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
