-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 11:25 AM
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
-- Database: `crm2`
--

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `country_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 101, 'Andaman', NULL, NULL),
(2, 101, 'Kerala', NULL, NULL),
(3, 101, 'Goa', NULL, NULL),
(4, 101, 'Srinagar', NULL, NULL),
(5, 101, 'Rajasthan', NULL, NULL),
(6, 101, 'Leh Ladakh', NULL, NULL),
(7, 101, 'Uttarakhand', NULL, NULL),
(8, 101, 'Mcleodganj', NULL, NULL),
(9, 101, 'Coorg', NULL, NULL),
(10, 101, 'Kasol', NULL, NULL),
(11, 101, 'Rishikesh', NULL, NULL),
(12, 101, 'Shimla', NULL, NULL),
(13, 101, 'Manali', NULL, NULL),
(14, 101, 'Udaipur', NULL, NULL),
(15, 101, 'Jaisalmer', NULL, NULL),
(16, 101, 'Jodhpur', NULL, NULL),
(17, 101, 'Sikkim', NULL, NULL),
(18, 101, 'Agra', NULL, NULL),
(19, 101, 'Dalhousie', NULL, NULL),
(20, 101, 'Amritsar', NULL, NULL),
(21, 101, 'Ooty', NULL, NULL),
(22, 101, 'Darjeeling', NULL, NULL),
(23, 101, 'Mount Abu', NULL, NULL),
(24, 101, 'Nainital', NULL, NULL),
(25, 101, 'Lakshadweep', NULL, NULL),
(26, 101, 'Spiti', NULL, NULL),
(27, 248, 'Dubai', NULL, NULL),
(28, 133, 'Maldives', NULL, NULL),
(29, 102, 'Bali', NULL, NULL),
(30, 132, 'Malaysia', NULL, NULL),
(31, 196, 'Singapore', NULL, NULL),
(32, 254, 'Europe', NULL, NULL),
(33, 13, 'Australia', NULL, NULL),
(34, 157, 'New Zealand', NULL, NULL),
(35, 255, 'Reunion Island', NULL, NULL),
(36, 140, 'Mauritius', NULL, NULL),
(37, 64, 'Egypt', NULL, NULL),
(38, 202, 'South Africa', NULL, NULL),
(39, 85, 'Greece', NULL, NULL),
(40, 206, 'Sri lanka', NULL, NULL),
(41, 217, 'Thailand', NULL, NULL),
(42, 98, 'Hong Kong', NULL, NULL),
(43, 194, 'Seychelles', NULL, NULL),
(44, 256, 'Langkawi', NULL, NULL),
(45, 181, 'Russia', NULL, NULL),
(46, 101, 'Delhi', NULL, NULL),
(47, 132, 'Kuala Lumpur', NULL, NULL),
(48, 132, 'Penang', NULL, NULL),
(49, 107, 'Milan', NULL, NULL),
(50, 107, 'Venice', NULL, NULL),
(51, 75, 'Paris', NULL, NULL),
(52, 230, 'London', NULL, NULL),
(53, 217, 'Koh Samui', NULL, NULL),
(54, 238, 'Vietnam', NULL, NULL),
(55, 82, 'Frankfurt', NULL, NULL),
(60, 36, 'Cambodia', NULL, NULL),
(63, 238, 'HANOI', NULL, NULL),
(64, 82, 'HAMBURG', NULL, NULL),
(65, 57, 'Prague', NULL, NULL),
(66, 107, 'Rome', NULL, NULL),
(67, 107, 'Florence', NULL, NULL),
(68, 54, 'Zagreb', NULL, NULL),
(69, 54, 'Dubrovnik', NULL, NULL),
(70, 54, 'Split', NULL, NULL),
(71, 54, 'Opatija', NULL, NULL),
(72, 13, 'SYDNEY', NULL, NULL),
(73, 157, 'Auckland', NULL, NULL),
(74, 157, 'Rotorua', NULL, NULL),
(75, 157, 'Coromandel', NULL, NULL),
(76, 157, 'Bay of Islands', NULL, NULL),
(77, 157, 'Queenstown', NULL, NULL),
(78, 157, 'Christchurch', NULL, NULL),
(79, 99, 'Budapest', NULL, NULL),
(80, 133, 'HULHUMALE', NULL, NULL),
(81, 133, 'MAAFUSHI', NULL, NULL),
(82, 85, 'Athens', NULL, NULL),
(83, 85, 'Mykonos', NULL, NULL),
(84, 85, 'Santorini', NULL, NULL),
(85, 82, 'Munich', NULL, NULL),
(86, 82, 'Berlin', NULL, NULL),
(87, 13, 'MELBOURNE', NULL, NULL),
(88, 13, 'CAIRNS', NULL, NULL),
(89, 13, 'PERTH', NULL, NULL),
(90, 194, 'MAHE', NULL, NULL),
(91, 194, 'PRASLIN', NULL, NULL),
(92, 223, 'Antalya', NULL, NULL),
(93, 205, 'Barcelona', NULL, NULL),
(94, 223, 'Cappadocia', NULL, NULL),
(95, 223, 'Istanbul', NULL, NULL),
(96, 238, 'HO CHI MINH', NULL, NULL),
(97, 238, 'SAPA', NULL, NULL),
(98, 238, 'HOI AN', NULL, NULL),
(99, 238, 'DALAT', NULL, NULL),
(100, 101, 'Mumbai', NULL, NULL),
(101, 101, 'Bangalore', NULL, NULL),
(102, 101, 'Hyderabad', NULL, NULL),
(103, 101, 'Pune', NULL, NULL),
(104, 101, 'Gurgaon', NULL, NULL),
(105, 101, 'Chennai', NULL, NULL),
(106, 101, 'Ahmedabad', NULL, NULL),
(107, 101, 'Kochi', NULL, NULL),
(108, 106, 'Jerusalem ', NULL, NULL),
(109, 132, 'Genting', NULL, NULL),
(110, 14, 'VIENNA', NULL, NULL),
(111, 82, 'LAUF - WURZBURG - FRANKFURT', NULL, NULL),
(112, 212, 'ZURICH', NULL, NULL),
(113, 105, 'DUBLIN', NULL, NULL),
(114, 205, 'MADRID', NULL, NULL),
(115, 14, ' INNSBRUCK', NULL, NULL),
(116, 14, 'KITZBUHEL - SALZBURG - VIENNA', NULL, NULL),
(117, 155, 'Amsterdam', NULL, NULL),
(118, 176, 'LISBON', NULL, NULL),
(119, 164, 'OSLO', NULL, NULL),
(120, 181, 'ST. PETERSBURG', NULL, NULL),
(121, 58, 'Copenhagen', NULL, NULL),
(123, 259, 'Invalid', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `states_country_id_foreign` (`country_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `states`
--
ALTER TABLE `states`
  ADD CONSTRAINT `states_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
