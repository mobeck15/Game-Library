-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Generation Time: Nov 05, 2021 at 10:48 AM
-- Server version: 5.7.28-log
-- PHP Version: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sika_games`
--
CREATE DATABASE IF NOT EXISTS `sika_games` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sika_games`;

-- --------------------------------------------------------

--
-- Stand-in structure for view `cpi`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `cpi`;
CREATE TABLE `cpi` (
`year` int(11)
,`month` int(11)
,`cpi` decimal(10,4)
);

-- --------------------------------------------------------

--
-- Table structure for table `gl_cpi`
--

DROP TABLE IF EXISTS `gl_cpi`;
CREATE TABLE `gl_cpi` (
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `cpi` decimal(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_hardware`
--

DROP TABLE IF EXISTS `gl_hardware`;
CREATE TABLE `gl_hardware` (
  `HardwareID` int(11) NOT NULL,
  `Hardware` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `Paid` decimal(10,3) DEFAULT NULL,
  `Tax` decimal(10,3) DEFAULT NULL,
  `PurchaseDate` date DEFAULT NULL,
  `RetireDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_history`
--

DROP TABLE IF EXISTS `gl_history`;
CREATE TABLE `gl_history` (
  `HistoryID` int(11) NOT NULL,
  `Timestamp` datetime NOT NULL,
  `Game` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `System` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Data` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Time` decimal(10,4) DEFAULT NULL,
  `Notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `Achievements` int(11) DEFAULT NULL,
  `AchievementType` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Levels` int(11) DEFAULT NULL,
  `LevelType` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `KeywordsLegacy` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Review` int(11) DEFAULT NULL,
  `BaseGame` tinyint(1) DEFAULT '0',
  `RowType` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kwMinutes` tinyint(1) DEFAULT '0',
  `kwIdle` tinyint(1) DEFAULT '0',
  `kwCardFarming` tinyint(1) NOT NULL DEFAULT '0',
  `kwCheating` tinyint(1) NOT NULL DEFAULT '0',
  `kwBeatGame` tinyint(1) NOT NULL DEFAULT '0',
  `kwShare` tinyint(1) DEFAULT '0',
  `GameID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_items`
--

DROP TABLE IF EXISTS `gl_items`;
CREATE TABLE `gl_items` (
  `ItemID` int(11) NOT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `TransID` int(11) DEFAULT NULL,
  `ParentProductID` int(11) DEFAULT NULL,
  `Tier` int(11) DEFAULT NULL,
  `Notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `SizeMB` int(11) DEFAULT NULL,
  `ActivationKey` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Library` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DRM` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main` int(11) DEFAULT NULL,
  `launchstring` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OS` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DateAdded` date DEFAULT NULL,
  `Time Added` time DEFAULT NULL,
  `Sequence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_keywords`
--

DROP TABLE IF EXISTS `gl_keywords`;
CREATE TABLE `gl_keywords` (
  `KWid` int(10) UNSIGNED NOT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `KwType` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Keyword` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_products`
--

DROP TABLE IF EXISTS `gl_products`;
CREATE TABLE `gl_products` (
  `Game_ID` int(11) NOT NULL,
  `Title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Series` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `LaunchDate` date DEFAULT NULL,
  `LaunchPrice` decimal(10,3) DEFAULT NULL,
  `MSRP` decimal(10,3) DEFAULT NULL,
  `CurrentMSRP` decimal(10,3) DEFAULT NULL,
  `HistoricLow` decimal(10,3) DEFAULT NULL,
  `LowDate` date DEFAULT NULL,
  `SteamAchievements` int(11) DEFAULT NULL,
  `SteamCards` int(11) DEFAULT NULL,
  `TimeToBeat` decimal(10,2) DEFAULT NULL,
  `Metascore` int(11) DEFAULT NULL,
  `UserMetascore` int(11) DEFAULT NULL,
  `SteamRating` int(11) DEFAULT NULL,
  `SteamID` int(11) DEFAULT NULL,
  `GOGID` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isthereanydealID` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TimeToBeatID` int(11) DEFAULT NULL,
  `MetascoreID` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DateUpdated` date DEFAULT NULL,
  `Want` int(11) DEFAULT NULL,
  `Playable` tinyint(1) DEFAULT NULL,
  `Type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ParentGameID` int(11) DEFAULT NULL,
  `DesuraID` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ParentGame` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Developer` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Publisher` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_settings`
--

DROP TABLE IF EXISTS `gl_settings`;
CREATE TABLE `gl_settings` (
  `Setting` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SettingNum` decimal(10,4) DEFAULT NULL,
  `SettingDate` date DEFAULT NULL,
  `SettingText` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_status`
--

DROP TABLE IF EXISTS `gl_status`;
CREATE TABLE `gl_status` (
  `Status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Active` tinyint(1) DEFAULT NULL,
  `Count` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_transactions`
--

DROP TABLE IF EXISTS `gl_transactions`;
CREATE TABLE `gl_transactions` (
  `TransID` int(11) NOT NULL,
  `Title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Store` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BundleID` int(11) DEFAULT NULL,
  `Tier` int(11) DEFAULT NULL,
  `PurchaseDate` date DEFAULT NULL,
  `PurchaseTime` time DEFAULT NULL,
  `Sequence` int(11) DEFAULT NULL,
  `Price` decimal(10,3) DEFAULT NULL,
  `Fees` decimal(10,3) DEFAULT NULL,
  `Paid` decimal(10,3) DEFAULT NULL,
  `Credit Used` decimal(10,3) DEFAULT NULL,
  `Bundle Link` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `hardware`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `hardware`;
CREATE TABLE `hardware` (
`HardwareID` int(11)
,`Hardware` varchar(100)
,`Notes` mediumtext
,`Paid` decimal(10,3)
,`Tax` decimal(10,3)
,`PurchaseDate` date
,`RetireDate` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `history`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `history`;
CREATE TABLE `history` (
`HistoryID` int(11)
,`Timestamp` datetime
,`Game` varchar(100)
,`System` varchar(50)
,`Data` varchar(50)
,`Time` decimal(10,4)
,`Notes` mediumtext
,`Achievements` int(11)
,`AchievementType` varchar(20)
,`Levels` int(11)
,`LevelType` varchar(20)
,`KeywordsLegacy` varchar(100)
,`Status` varchar(50)
,`Review` int(11)
,`BaseGame` tinyint(1)
,`RowType` varchar(50)
,`kwMinutes` tinyint(1)
,`kwIdle` tinyint(1)
,`kwCardFarming` tinyint(1)
,`kwCheating` tinyint(1)
,`kwBeatGame` tinyint(1)
,`kwShare` tinyint(1)
,`GameID` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `items`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `items`;
CREATE TABLE `items` (
`ItemID` int(11)
,`ProductID` int(11)
,`TransID` int(11)
,`ParentProductID` int(11)
,`Tier` int(11)
,`Notes` mediumtext
,`SizeMB` int(11)
,`DRM` varchar(20)
,`OS` varchar(20)
,`ActivationKey` varchar(200)
,`DateAdded` date
,`Time Added` time
,`Sequence` int(11)
,`Library` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `keywords`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `keywords`;
CREATE TABLE `keywords` (
`KWid` int(10) unsigned
,`ProductID` int(11)
,`KwType` varchar(50)
,`Keyword` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `products`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `products`;
CREATE TABLE `products` (
`Game_ID` int(11)
,`Title` varchar(100)
,`Series` varchar(50)
,`LaunchDate` date
,`LaunchPrice` decimal(10,3)
,`MSRP` decimal(10,3)
,`CurrentMSRP` decimal(10,3)
,`HistoricLow` decimal(10,3)
,`LowDate` date
,`SteamAchievements` int(11)
,`SteamCards` int(11)
,`TimeToBeat` decimal(10,2)
,`Metascore` int(11)
,`UserMetascore` int(11)
,`SteamRating` int(11)
,`SteamID` int(11)
,`GOGID` varchar(50)
,`isthereanydealID` varchar(50)
,`TimeToBeatID` int(11)
,`MetascoreID` varchar(60)
,`DateUpdated` date
,`Want` int(11)
,`Playable` tinyint(1)
,`Type` varchar(10)
,`ParentGameID` int(11)
,`DesuraID` varchar(50)
,`ParentGame` varchar(100)
,`Developer` varchar(50)
,`Publisher` varchar(60)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `settings`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `settings`;
CREATE TABLE `settings` (
`Setting` varchar(10)
,`SettingNum` decimal(10,4)
,`SettingDate` date
,`SettingText` varchar(10)
,`description` mediumtext
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `status`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `status`;
CREATE TABLE `status` (
`Status` varchar(10)
,`Active` tinyint(1)
,`Count` tinyint(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `transactions`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `transactions`;
CREATE TABLE `transactions` (
`TransID` int(11)
,`Title` varchar(100)
,`Store` varchar(50)
,`BundleID` int(11)
,`Tier` int(11)
,`PurchaseDate` date
,`PurchaseTime` time
,`Sequence` int(11)
,`Price` decimal(10,3)
,`Fees` decimal(10,3)
,`Paid` decimal(10,3)
,`Credit Used` decimal(10,3)
,`Bundle Link` varchar(200)
);

-- --------------------------------------------------------

--
-- Structure for view `cpi`
--
DROP TABLE IF EXISTS `cpi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`isaacguerrero`@`66.33.192.0/255.255.224.0` SQL SECURITY DEFINER VIEW `cpi`  AS  select `gl_cpi`.`year` AS `year`,`gl_cpi`.`month` AS `month`,`gl_cpi`.`cpi` AS `cpi` from `gl_cpi` ;

-- --------------------------------------------------------

--
-- Structure for view `hardware`
--
DROP TABLE IF EXISTS `hardware`;

CREATE ALGORITHM=UNDEFINED DEFINER=`isaacguerrero`@`66.33.192.0/255.255.224.0` SQL SECURITY DEFINER VIEW `hardware`  AS  select `gl_hardware`.`HardwareID` AS `HardwareID`,`gl_hardware`.`Hardware` AS `Hardware`,`gl_hardware`.`Notes` AS `Notes`,`gl_hardware`.`Paid` AS `Paid`,`gl_hardware`.`Tax` AS `Tax`,`gl_hardware`.`PurchaseDate` AS `PurchaseDate`,`gl_hardware`.`RetireDate` AS `RetireDate` from `gl_hardware` ;

-- --------------------------------------------------------

--
-- Structure for view `history`
--
DROP TABLE IF EXISTS `history`;

CREATE ALGORITHM=UNDEFINED DEFINER=`isaacguerrero`@`66.33.192.0/255.255.224.0` SQL SECURITY DEFINER VIEW `history`  AS  select `gl_history`.`HistoryID` AS `HistoryID`,`gl_history`.`Timestamp` AS `Timestamp`,`gl_history`.`Game` AS `Game`,`gl_history`.`System` AS `System`,`gl_history`.`Data` AS `Data`,`gl_history`.`Time` AS `Time`,`gl_history`.`Notes` AS `Notes`,`gl_history`.`Achievements` AS `Achievements`,`gl_history`.`AchievementType` AS `AchievementType`,`gl_history`.`Levels` AS `Levels`,`gl_history`.`LevelType` AS `LevelType`,`gl_history`.`KeywordsLegacy` AS `KeywordsLegacy`,`gl_history`.`Status` AS `Status`,`gl_history`.`Review` AS `Review`,`gl_history`.`BaseGame` AS `BaseGame`,`gl_history`.`RowType` AS `RowType`,`gl_history`.`kwMinutes` AS `kwMinutes`,`gl_history`.`kwIdle` AS `kwIdle`,`gl_history`.`kwCardFarming` AS `kwCardFarming`,`gl_history`.`kwCheating` AS `kwCheating`,`gl_history`.`kwBeatGame` AS `kwBeatGame`,`gl_history`.`kwShare` AS `kwShare`,`gl_history`.`GameID` AS `GameID` from `gl_history` ;

-- --------------------------------------------------------

--
-- Structure for view `items`
--
DROP TABLE IF EXISTS `items`;

CREATE ALGORITHM=UNDEFINED DEFINER=`isaacguerrero`@`66.33.192.0/255.255.224.0` SQL SECURITY DEFINER VIEW `items`  AS  select `gl_items`.`ItemID` AS `ItemID`,`gl_items`.`ProductID` AS `ProductID`,`gl_items`.`TransID` AS `TransID`,`gl_items`.`ParentProductID` AS `ParentProductID`,`gl_items`.`Tier` AS `Tier`,`gl_items`.`Notes` AS `Notes`,`gl_items`.`SizeMB` AS `SizeMB`,`gl_items`.`DRM` AS `DRM`,`gl_items`.`OS` AS `OS`,`gl_items`.`ActivationKey` AS `ActivationKey`,`gl_items`.`DateAdded` AS `DateAdded`,`gl_items`.`Time Added` AS `Time Added`,`gl_items`.`Sequence` AS `Sequence`,`gl_items`.`Library` AS `Library` from `gl_items` ;

-- --------------------------------------------------------

--
-- Structure for view `keywords`
--
DROP TABLE IF EXISTS `keywords`;

CREATE ALGORITHM=UNDEFINED DEFINER=`isaacguerrero`@`66.33.192.0/255.255.224.0` SQL SECURITY DEFINER VIEW `keywords`  AS  select `gl_keywords`.`KWid` AS `KWid`,`gl_keywords`.`ProductID` AS `ProductID`,`gl_keywords`.`KwType` AS `KwType`,`gl_keywords`.`Keyword` AS `Keyword` from `gl_keywords` ;

-- --------------------------------------------------------

--
-- Structure for view `products`
--
DROP TABLE IF EXISTS `products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`isaacguerrero`@`66.33.192.0/255.255.224.0` SQL SECURITY DEFINER VIEW `products`  AS  select `gl_products`.`Game_ID` AS `Game_ID`,`gl_products`.`Title` AS `Title`,`gl_products`.`Series` AS `Series`,`gl_products`.`LaunchDate` AS `LaunchDate`,`gl_products`.`LaunchPrice` AS `LaunchPrice`,`gl_products`.`MSRP` AS `MSRP`,`gl_products`.`CurrentMSRP` AS `CurrentMSRP`,`gl_products`.`HistoricLow` AS `HistoricLow`,`gl_products`.`LowDate` AS `LowDate`,`gl_products`.`SteamAchievements` AS `SteamAchievements`,`gl_products`.`SteamCards` AS `SteamCards`,`gl_products`.`TimeToBeat` AS `TimeToBeat`,`gl_products`.`Metascore` AS `Metascore`,`gl_products`.`UserMetascore` AS `UserMetascore`,`gl_products`.`SteamRating` AS `SteamRating`,`gl_products`.`SteamID` AS `SteamID`,`gl_products`.`GOGID` AS `GOGID`,`gl_products`.`isthereanydealID` AS `isthereanydealID`,`gl_products`.`TimeToBeatID` AS `TimeToBeatID`,`gl_products`.`MetascoreID` AS `MetascoreID`,`gl_products`.`DateUpdated` AS `DateUpdated`,`gl_products`.`Want` AS `Want`,`gl_products`.`Playable` AS `Playable`,`gl_products`.`Type` AS `Type`,`gl_products`.`ParentGameID` AS `ParentGameID`,`gl_products`.`DesuraID` AS `DesuraID`,`gl_products`.`ParentGame` AS `ParentGame`,`gl_products`.`Developer` AS `Developer`,`gl_products`.`Publisher` AS `Publisher` from `gl_products` ;

-- --------------------------------------------------------

--
-- Structure for view `settings`
--
DROP TABLE IF EXISTS `settings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`isaacguerrero`@`66.33.192.0/255.255.224.0` SQL SECURITY DEFINER VIEW `settings`  AS  select `gl_settings`.`Setting` AS `Setting`,`gl_settings`.`SettingNum` AS `SettingNum`,`gl_settings`.`SettingDate` AS `SettingDate`,`gl_settings`.`SettingText` AS `SettingText`,`gl_settings`.`description` AS `description` from `gl_settings` ;

-- --------------------------------------------------------

--
-- Structure for view `status`
--
DROP TABLE IF EXISTS `status`;

CREATE ALGORITHM=UNDEFINED DEFINER=`isaacguerrero`@`66.33.192.0/255.255.224.0` SQL SECURITY DEFINER VIEW `status`  AS  select `gl_status`.`Status` AS `Status`,`gl_status`.`Active` AS `Active`,`gl_status`.`Count` AS `Count` from `gl_status` ;

-- --------------------------------------------------------

--
-- Structure for view `transactions`
--
DROP TABLE IF EXISTS `transactions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`isaacguerrero`@`66.33.192.0/255.255.224.0` SQL SECURITY DEFINER VIEW `transactions`  AS  select `gl_transactions`.`TransID` AS `TransID`,`gl_transactions`.`Title` AS `Title`,`gl_transactions`.`Store` AS `Store`,`gl_transactions`.`BundleID` AS `BundleID`,`gl_transactions`.`Tier` AS `Tier`,`gl_transactions`.`PurchaseDate` AS `PurchaseDate`,`gl_transactions`.`PurchaseTime` AS `PurchaseTime`,`gl_transactions`.`Sequence` AS `Sequence`,`gl_transactions`.`Price` AS `Price`,`gl_transactions`.`Fees` AS `Fees`,`gl_transactions`.`Paid` AS `Paid`,`gl_transactions`.`Credit Used` AS `Credit Used`,`gl_transactions`.`Bundle Link` AS `Bundle Link` from `gl_transactions` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gl_hardware`
--
ALTER TABLE `gl_hardware`
  ADD PRIMARY KEY (`HardwareID`);

--
-- Indexes for table `gl_history`
--
ALTER TABLE `gl_history`
  ADD PRIMARY KEY (`HistoryID`),
  ADD KEY `GameID` (`GameID`);

--
-- Indexes for table `gl_items`
--
ALTER TABLE `gl_items`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `ProductID` (`ProductID`),
  ADD KEY `ParentProductID` (`ParentProductID`),
  ADD KEY `TransID` (`TransID`);

--
-- Indexes for table `gl_keywords`
--
ALTER TABLE `gl_keywords`
  ADD PRIMARY KEY (`KWid`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `gl_products`
--
ALTER TABLE `gl_products`
  ADD PRIMARY KEY (`Game_ID`),
  ADD KEY `ParentGameID` (`ParentGameID`);

--
-- Indexes for table `gl_settings`
--
ALTER TABLE `gl_settings`
  ADD PRIMARY KEY (`Setting`);

--
-- Indexes for table `gl_status`
--
ALTER TABLE `gl_status`
  ADD PRIMARY KEY (`Status`);

--
-- Indexes for table `gl_transactions`
--
ALTER TABLE `gl_transactions`
  ADD PRIMARY KEY (`TransID`),
  ADD KEY `BundleID` (`BundleID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gl_keywords`
--
ALTER TABLE `gl_keywords`
  MODIFY `KWid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
