-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2025 at 02:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `building_designs`
--

-- --------------------------------------------------------

--
-- Table structure for table `adscontrol`
--

CREATE TABLE `adscontrol` (
  `banner` varchar(50) NOT NULL,
  `interstitial` varchar(50) NOT NULL,
  `rewarded` varchar(50) NOT NULL,
  `appOpen` varchar(50) NOT NULL,
  `apiKey` varchar(50) NOT NULL DEFAULT 'gjguwe92ugut&%&yU@79tut'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adscontrol`
--

INSERT INTO `adscontrol` (`banner`, `interstitial`, `rewarded`, `appOpen`, `apiKey`) VALUES
('meta', 'meta', 'meta', 'google', 'a34ab#%ghij0123456789');

-- --------------------------------------------------------

--
-- Table structure for table `buildingitem`
--

CREATE TABLE `buildingitem` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `bedRoom` int(11) NOT NULL,
  `floorNumber` int(11) NOT NULL,
  `basicInfo` text DEFAULT NULL,
  `viewCount` int(11) DEFAULT 0,
  `fontDesign` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buildingitem`
--

INSERT INTO `buildingitem` (`id`, `title`, `bedRoom`, `floorNumber`, `basicInfo`, `viewCount`, `fontDesign`, `status`) VALUES
(53, 'yyiyi', 1, 1, '', 0, 'uploads/font_designs/compressed_67769006e4be1_IMG_5376.jpg', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(100) NOT NULL,
  `userId` int(50) NOT NULL,
  `buildingId` int(50) NOT NULL,
  `text` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `floordesign`
--

CREATE TABLE `floordesign` (
  `id` int(11) NOT NULL,
  `buildingId` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `floordesign`
--

INSERT INTO `floordesign` (`id`, `buildingId`, `url`, `comment`) VALUES
(71, 53, 'uploads/floor_designs/677690070de95_IMG_5376.jpg', 'floor');

-- --------------------------------------------------------

--
-- Table structure for table `usertable`
--

CREATE TABLE `usertable` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` enum('male','female','other') DEFAULT 'other',
  `mobile` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `userRole` enum('normal','admin') DEFAULT 'normal',
  `otp` varchar(10) DEFAULT NULL,
  `totalView` varchar(10) NOT NULL DEFAULT '0',
  `totalDownload` varchar(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usertable`
--

INSERT INTO `usertable` (`id`, `email`, `password`, `name`, `gender`, `mobile`, `address`, `country`, `userRole`, `otp`, `totalView`, `totalDownload`) VALUES
(1, 'mdkamalhosennn@gmail.com', '31565989a52157a6931ec632275f9f53', 'Gm Kamal Hosen', 'male', '66425523', 'Luxmipur, Mudaforgonj', 'Qatar', 'admin', NULL, '0', '1'),
(2, 'kamalhosennn@gmail.com', 'mdkamalhosennn', 'Md Kamal Hosen', 'male', '01613400495', '', 'Qatar', 'normal', NULL, '0', '0'),
(4, 'hosennn@gmail.com', 'c299a18770bb98bac3895aa11a37d97d', 'Md Kamal', 'male', '01613400495', 'aaaaa', 'BD', 'normal', NULL, '0', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buildingitem`
--
ALTER TABLE `buildingitem`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `floordesign`
--
ALTER TABLE `floordesign`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buildingId` (`buildingId`);

--
-- Indexes for table `usertable`
--
ALTER TABLE `usertable`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buildingitem`
--
ALTER TABLE `buildingitem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `floordesign`
--
ALTER TABLE `floordesign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `usertable`
--
ALTER TABLE `usertable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `floordesign`
--
ALTER TABLE `floordesign`
  ADD CONSTRAINT `floordesign_ibfk_1` FOREIGN KEY (`buildingId`) REFERENCES `buildingitem` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
