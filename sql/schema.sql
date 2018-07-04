-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 30, 2018 at 09:02 PM
-- Server version: 5.6.32-78.1
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `hierarchy` (
  `source` varchar(191) NOT NULL,
  `type` varchar(25) NOT NULL,
  `subtype` varchar(25) NOT NULL,
  `target` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

CREATE TABLE `relations_cooc` (
  `source` varchar(191) NOT NULL,
  `count` int(11) NOT NULL,
  `mi` float NOT NULL,
  `target` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

CREATE TABLE `relations_term` (
  `doc` varchar(191) NOT NULL,
  `rel` varchar(191) NOT NULL,
  `source` varchar(191) NOT NULL,
  `target` varchar(191) NOT NULL,
  `context` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

CREATE TABLE `technologies` (
  `name` varchar(191) NOT NULL,
  `tscore` float NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

ALTER TABLE `hierarchy`
  ADD PRIMARY KEY (`source`);

ALTER TABLE `relations_cooc`
  ADD KEY `source` (`source`);

ALTER TABLE `relations_term`
  ADD KEY `source` (`source`);

ALTER TABLE `relations_term`
  ADD KEY `target` (`source`);

ALTER TABLE `technologies`
  ADD PRIMARY KEY (`name`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
