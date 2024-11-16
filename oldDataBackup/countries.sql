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
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `phone_code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `short_name`, `phone_code`, `created_at`, `updated_at`) VALUES
(1, 'Afghanistan', 'AF', '93', NULL, NULL),
(2, 'Albania', 'AL', '355', NULL, NULL),
(3, 'Algeria', 'DZ', '213', NULL, NULL),
(4, 'American Samoa', 'AS', '1684', NULL, NULL),
(5, 'Andorra', 'AD', '376', NULL, NULL),
(6, 'Angola', 'AO', '244', NULL, NULL),
(7, 'Anguilla', 'AI', '1264', NULL, NULL),
(8, 'Antarctica', 'AQ', '0', NULL, NULL),
(9, 'Antigua And Barbuda', 'AG', '1268', NULL, NULL),
(10, 'Argentina', 'AR', '54', NULL, NULL),
(11, 'Armenia', 'AM', '374', NULL, NULL),
(12, 'Aruba', 'AW', '297', NULL, NULL),
(13, 'Australia', 'AU', '61', NULL, NULL),
(14, 'Austria', 'AT', '43', NULL, NULL),
(15, 'Azerbaijan', 'AZ', '994', NULL, NULL),
(16, 'Bahamas The', 'BS', '1242', NULL, NULL),
(17, 'Bahrain', 'BH', '973', NULL, NULL),
(18, 'Bangladesh', 'BD', '880', NULL, NULL),
(19, 'Barbados', 'BB', '1246', NULL, NULL),
(20, 'Belarus', 'BY', '375', NULL, NULL),
(21, 'Belgium', 'BE', '32', NULL, NULL),
(22, 'Belize', 'BZ', '501', NULL, NULL),
(23, 'Benin', 'BJ', '229', NULL, NULL),
(24, 'Bermuda', 'BM', '1441', NULL, NULL),
(25, 'Bhutan', 'BT', '975', NULL, NULL),
(26, 'Bolivia', 'BO', '591', NULL, NULL),
(27, 'Bosnia and Herzegovina', 'BA', '387', NULL, NULL),
(28, 'Botswana', 'BW', '267', NULL, NULL),
(29, 'Bouvet Island', 'BV', '0', NULL, NULL),
(30, 'Brazil', 'BR', '55', NULL, NULL),
(31, 'British Indian Ocean Territory', 'IO', '246', NULL, NULL),
(32, 'Brunei', 'BN', '673', NULL, NULL),
(33, 'Bulgaria', 'BG', '359', NULL, NULL),
(34, 'Burkina Faso', 'BF', '226', NULL, NULL),
(35, 'Burundi', 'BI', '257', NULL, NULL),
(36, 'Cambodia', 'KH', '855', NULL, NULL),
(37, 'Cameroon', 'CM', '237', NULL, NULL),
(38, 'Canada', 'CA', '1', NULL, NULL),
(39, 'Cape Verde', 'CV', '238', NULL, NULL),
(40, 'Cayman Islands', 'KY', '1345', NULL, NULL),
(41, 'Central African Republic', 'CF', '236', NULL, NULL),
(42, 'Chad', 'TD', '235', NULL, NULL),
(43, 'Chile', 'CL', '56', NULL, NULL),
(44, 'China', 'CN', '86', NULL, NULL),
(45, 'Christmas Island', 'CX', '61', NULL, NULL),
(46, 'Cocos (Keeling) Islands', 'CC', '672', NULL, NULL),
(47, 'Colombia', 'CO', '57', NULL, NULL),
(48, 'Comoros', 'KM', '269', NULL, NULL),
(49, 'Republic Of The Congo', 'CG', '242', NULL, NULL),
(50, 'Democratic Republic Of The Congo', 'CD', '242', NULL, NULL),
(51, 'Cook Islands', 'CK', '682', NULL, NULL),
(52, 'Costa Rica', 'CR', '506', NULL, NULL),
(53, 'Cote D\'Ivoire (Ivory Coast)', 'CI', '225', NULL, NULL),
(54, 'Croatia', 'HR', '385', NULL, NULL),
(55, 'Cuba', 'CU', '53', NULL, NULL),
(56, 'Cyprus', 'CY', '357', NULL, NULL),
(57, 'Czech Republic', 'CZ', '420', NULL, NULL),
(58, 'Denmark', 'DK', '45', NULL, NULL),
(59, 'Djibouti', 'DJ', '253', NULL, NULL),
(60, 'Dominica', 'DM', '1767', NULL, NULL),
(61, 'Dominican Republic', 'DO', '1809', NULL, NULL),
(62, 'East Timor', 'TP', '670', NULL, NULL),
(63, 'Ecuador', 'EC', '593', NULL, NULL),
(64, 'Egypt', 'EG', '20', NULL, NULL),
(65, 'El Salvador', 'SV', '503', NULL, NULL),
(66, 'Equatorial Guinea', 'GQ', '240', NULL, NULL),
(67, 'Eritrea', 'ER', '291', NULL, NULL),
(68, 'Estonia', 'EE', '372', NULL, NULL),
(69, 'Ethiopia', 'ET', '251', NULL, NULL),
(70, 'External Territories of Australia', 'XA', '61', NULL, NULL),
(71, 'Falkland Islands', 'FK', '500', NULL, NULL),
(72, 'Faroe Islands', 'FO', '298', NULL, NULL),
(73, 'Fiji Islands', 'FJ', '679', NULL, NULL),
(74, 'Finland', 'FI', '358', NULL, NULL),
(75, 'France', 'FR', '33', NULL, NULL),
(76, 'French Guiana', 'GF', '594', NULL, NULL),
(77, 'French Polynesia', 'PF', '689', NULL, NULL),
(78, 'French Southern Territories', 'TF', '0', NULL, NULL),
(79, 'Gabon', 'GA', '241', NULL, NULL),
(80, 'Gambia The', 'GM', '220', NULL, NULL),
(81, 'Georgia', 'GE', '995', NULL, NULL),
(82, 'Germany', 'DE', '49', NULL, NULL),
(83, 'Ghana', 'GH', '233', NULL, NULL),
(84, 'Gibraltar', 'GI', '350', NULL, NULL),
(85, 'Greece', 'GR', '30', NULL, NULL),
(86, 'Greenland', 'GL', '299', NULL, NULL),
(87, 'Grenada', 'GD', '1473', NULL, NULL),
(88, 'Guadeloupe', 'GP', '590', NULL, NULL),
(89, 'Guam', 'GU', '1671', NULL, NULL),
(90, 'Guatemala', 'GT', '502', NULL, NULL),
(91, 'Guernsey and Alderney', 'XU', '44', NULL, NULL),
(92, 'Guinea', 'GN', '224', NULL, NULL),
(93, 'Guinea-Bissau', 'GW', '245', NULL, NULL),
(94, 'Guyana', 'GY', '592', NULL, NULL),
(95, 'Haiti', 'HT', '509', NULL, NULL),
(96, 'Heard and McDonald Islands', 'HM', '0', NULL, NULL),
(97, 'Honduras', 'HN', '504', NULL, NULL),
(98, 'Hong Kong S.A.R.', 'HK', '852', NULL, NULL),
(99, 'Hungary', 'HU', '36', NULL, NULL),
(100, 'Iceland', 'IS', '354', NULL, NULL),
(101, 'India', 'IN', '91', NULL, NULL),
(102, 'Indonesia', 'ID', '62', NULL, NULL),
(103, 'Iran', 'IR', '98', NULL, NULL),
(104, 'Iraq', 'IQ', '964', NULL, NULL),
(105, 'Ireland', 'IE', '353', NULL, NULL),
(106, 'Israel', 'IL', '972', NULL, NULL),
(107, 'Italy', 'IT', '39', NULL, NULL),
(108, 'Jamaica', 'JM', '1876', NULL, NULL),
(109, 'Japan', 'JP', '81', NULL, NULL),
(110, 'Jersey', 'XJ', '44', NULL, NULL),
(111, 'Jordan', 'JO', '962', NULL, NULL),
(112, 'Kazakhstan', 'KZ', '7', NULL, NULL),
(113, 'Kenya', 'KE', '254', NULL, NULL),
(114, 'Kiribati', 'KI', '686', NULL, NULL),
(115, 'Korea North', 'KP', '850', NULL, NULL),
(116, 'Korea South', 'KR', '82', NULL, NULL),
(117, 'Kuwait', 'KW', '965', NULL, NULL),
(118, 'Kyrgyzstan', 'KG', '996', NULL, NULL),
(119, 'Laos', 'LA', '856', NULL, NULL),
(120, 'Latvia', 'LV', '371', NULL, NULL),
(121, 'Lebanon', 'LB', '961', NULL, NULL),
(122, 'Lesotho', 'LS', '266', NULL, NULL),
(123, 'Liberia', 'LR', '231', NULL, NULL),
(124, 'Libya', 'LY', '218', NULL, NULL),
(125, 'Liechtenstein', 'LI', '423', NULL, NULL),
(126, 'Lithuania', 'LT', '370', NULL, NULL),
(127, 'Luxembourg', 'LU', '352', NULL, NULL),
(128, 'Macau S.A.R.', 'MO', '853', NULL, NULL),
(129, 'Macedonia', 'MK', '389', NULL, NULL),
(130, 'Madagascar', 'MG', '261', NULL, NULL),
(131, 'Malawi', 'MW', '265', NULL, NULL),
(132, 'Malaysia', 'MY', '60', NULL, NULL),
(133, 'Maldives', 'MV', '960', NULL, NULL),
(134, 'Mali', 'ML', '223', NULL, NULL),
(135, 'Malta', 'MT', '356', NULL, NULL),
(136, 'Man (Isle of)', 'XM', '44', NULL, NULL),
(137, 'Marshall Islands', 'MH', '692', NULL, NULL),
(138, 'Martinique', 'MQ', '596', NULL, NULL),
(139, 'Mauritania', 'MR', '222', NULL, NULL),
(140, 'Mauritius', 'MU', '230', NULL, NULL),
(141, 'Mayotte', 'YT', '269', NULL, NULL),
(142, 'Mexico', 'MX', '52', NULL, NULL),
(143, 'Micronesia', 'FM', '691', NULL, NULL),
(144, 'Moldova', 'MD', '373', NULL, NULL),
(145, 'Monaco', 'MC', '377', NULL, NULL),
(146, 'Mongolia', 'MN', '976', NULL, NULL),
(147, 'Montserrat', 'MS', '1664', NULL, NULL),
(148, 'Morocco', 'MA', '212', NULL, NULL),
(149, 'Mozambique', 'MZ', '258', NULL, NULL),
(150, 'Myanmar', 'MM', '95', NULL, NULL),
(151, 'Namibia', 'NA', '264', NULL, NULL),
(152, 'Nauru', 'NR', '674', NULL, NULL),
(153, 'Nepal', 'NP', '977', NULL, NULL),
(154, 'Netherlands Antilles', 'AN', '599', NULL, NULL),
(155, 'Netherlands The', 'NL', '31', NULL, NULL),
(156, 'New Caledonia', 'NC', '687', NULL, NULL),
(157, 'New Zealand', 'NZ', '64', NULL, NULL),
(158, 'Nicaragua', 'NI', '505', NULL, NULL),
(159, 'Niger', 'NE', '227', NULL, NULL),
(160, 'Nigeria', 'NG', '234', NULL, NULL),
(161, 'Niue', 'NU', '683', NULL, NULL),
(162, 'Norfolk Island', 'NF', '672', NULL, NULL),
(163, 'Northern Mariana Islands', 'MP', '1670', NULL, NULL),
(164, 'Norway', 'NO', '47', NULL, NULL),
(165, 'Oman', 'OM', '968', NULL, NULL),
(166, 'Pakistan', 'PK', '92', NULL, NULL),
(167, 'Palau', 'PW', '680', NULL, NULL),
(168, 'Palestinian Territory Occupied', 'PS', '970', NULL, NULL),
(169, 'Panama', 'PA', '507', NULL, NULL),
(170, 'Papua new Guinea', 'PG', '675', NULL, NULL),
(171, 'Paraguay', 'PY', '595', NULL, NULL),
(172, 'Peru', 'PE', '51', NULL, NULL),
(173, 'Philippines', 'PH', '63', NULL, NULL),
(174, 'Pitcairn Island', 'PN', '0', NULL, NULL),
(175, 'Poland', 'PL', '48', NULL, NULL),
(176, 'Portugal', 'PT', '351', NULL, NULL),
(177, 'Puerto Rico', 'PR', '1787', NULL, NULL),
(178, 'Qatar', 'QA', '974', NULL, NULL),
(179, 'Reunion', 'RE', '262', NULL, NULL),
(180, 'Romania', 'RO', '40', NULL, NULL),
(181, 'Russia', 'RU', '70', NULL, NULL),
(182, 'Rwanda', 'RW', '250', NULL, NULL),
(183, 'Saint Helena', 'SH', '290', NULL, NULL),
(184, 'Saint Kitts And Nevis', 'KN', '1869', NULL, NULL),
(185, 'Saint Lucia', 'LC', '1758', NULL, NULL),
(186, 'Saint Pierre and Miquelon', 'PM', '508', NULL, NULL),
(187, 'Saint Vincent And The Grenadines', 'VC', '1784', NULL, NULL),
(188, 'Samoa', 'WS', '684', NULL, NULL),
(189, 'San Marino', 'SM', '378', NULL, NULL),
(190, 'Sao Tome and Principe', 'ST', '239', NULL, NULL),
(191, 'Saudi Arabia', 'SA', '966', NULL, NULL),
(192, 'Senegal', 'SN', '221', NULL, NULL),
(193, 'Serbia', 'RS', '381', NULL, NULL),
(194, 'Seychelles', 'SC', '248', NULL, NULL),
(195, 'Sierra Leone', 'SL', '232', NULL, NULL),
(196, 'Singapore', 'SG', '65', NULL, NULL),
(197, 'Slovakia', 'SK', '421', NULL, NULL),
(198, 'Slovenia', 'SI', '386', NULL, NULL),
(199, 'Smaller Territories of the UK', 'XG', '44', NULL, NULL),
(200, 'Solomon Islands', 'SB', '677', NULL, NULL),
(201, 'Somalia', 'SO', '252', NULL, NULL),
(202, 'South Africa', 'ZA', '27', NULL, NULL),
(203, 'South Georgia', 'GS', '0', NULL, NULL),
(204, 'South Sudan', 'SS', '211', NULL, NULL),
(205, 'Spain', 'ES', '34', NULL, NULL),
(206, 'Sri Lanka', 'LK', '94', NULL, NULL),
(207, 'Sudan', 'SD', '249', NULL, NULL),
(208, 'Suriname', 'SR', '597', NULL, NULL),
(209, 'Svalbard And Jan Mayen Islands', 'SJ', '47', NULL, NULL),
(210, 'Swaziland', 'SZ', '268', NULL, NULL),
(211, 'Sweden', 'SE', '46', NULL, NULL),
(212, 'Switzerland', 'CH', '41', NULL, NULL),
(213, 'Syria', 'SY', '963', NULL, NULL),
(214, 'Taiwan', 'TW', '886', NULL, NULL),
(215, 'Tajikistan', 'TJ', '992', NULL, NULL),
(216, 'Tanzania', 'TZ', '255', NULL, NULL),
(217, 'Thailand', 'TH', '66', NULL, NULL),
(218, 'Togo', 'TG', '228', NULL, NULL),
(219, 'Tokelau', 'TK', '690', NULL, NULL),
(220, 'Tonga', 'TO', '676', NULL, NULL),
(221, 'Trinidad And Tobago', 'TT', '1868', NULL, NULL),
(222, 'Tunisia', 'TN', '216', NULL, NULL),
(223, 'Turkey', 'TR', '90', NULL, NULL),
(224, 'Turkmenistan', 'TM', '7370', NULL, NULL),
(225, 'Turks And Caicos Islands', 'TC', '1649', NULL, NULL),
(226, 'Tuvalu', 'TV', '688', NULL, NULL),
(227, 'Uganda', 'UG', '256', NULL, NULL),
(228, 'Ukraine', 'UA', '380', NULL, NULL),
(229, 'United Arab Emirates', 'AE', '971', NULL, NULL),
(230, 'United Kingdom', 'GB', '44', NULL, NULL),
(231, 'United States', 'US', '1', NULL, NULL),
(232, 'United States Minor Outlying Islands', 'UM', '1', NULL, NULL),
(233, 'Uruguay', 'UY', '598', NULL, NULL),
(234, 'Uzbekistan', 'UZ', '998', NULL, NULL),
(235, 'Vanuatu', 'VU', '678', NULL, NULL),
(236, 'Vatican City State (Holy See)', 'VA', '39', NULL, NULL),
(237, 'Venezuela', 'VE', '58', NULL, NULL),
(238, 'Vietnam', 'VN', '84', NULL, NULL),
(239, 'Virgin Islands (British)', 'VG', '1284', NULL, NULL),
(240, 'Virgin Islands (US)', 'VI', '1340', NULL, NULL),
(241, 'Wallis And Futuna Islands', 'WF', '681', NULL, NULL),
(242, 'Western Sahara', 'EH', '212', NULL, NULL),
(243, 'Yemen', 'YE', '967', NULL, NULL),
(244, 'Yugoslavia', 'YU', '38', NULL, NULL),
(245, 'Zambia', 'ZM', '260', NULL, NULL),
(246, 'Zimbabwe', 'ZW', '263', NULL, NULL),
(248, 'UAE', '', '0', NULL, NULL),
(249, 'test', '', '0', NULL, NULL),
(250, 'Dubai', '', '0', NULL, NULL),
(251, 'test444', '', '0', NULL, NULL),
(252, 'Carpeting - Brand New Synthetic', '', '0', NULL, NULL),
(253, 'Bali', '', '0', NULL, NULL),
(254, 'Europe', 'BT', '975', NULL, NULL),
(255, 'Reunion Island', 'BD', '880', NULL, NULL),
(256, 'Langkawi', 'AL', '355', NULL, NULL),
(259, 'Invalid', 'NA', '00', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;