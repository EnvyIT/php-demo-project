-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2020 at 01:31 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fh_2020_scm4_s1810307008`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--
/* DROP Database*/
DROP DATABASE IF EXISTS fh_2020_scm4_S1810307008;
COMMIT;

/* Create Database */
CREATE DATABASE IF NOT EXISTS fh_2020_scm4_S1810307008 CHARACTER SET utf8;
COMMIT;


USE fh_2020_scm4_S1810307008;

CREATE TABLE `article`
(
    `id`               int(11)        NOT NULL,
    `shopping_list_id` int(11) DEFAULT NULL,
    `name`             varchar(250)   NOT NULL,
    `max_price`        decimal(19, 4) NOT NULL CHECK (`max_price` > 0.0),
    `quantity`         int(11)        NOT NULL CHECK (`quantity` > 0),
    `checked`          tinyint(1)     NOT NULL,
    `deleted`          tinyint(1)     NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `shopping_list_id`, `name`, `max_price`, `quantity`, `checked`, `deleted`)
VALUES (1, 1, 'Beef 500 ', '25.6000', 2, 0, 0),
       (2, 1, 'Freistädter Bier - Kiste', '40.0000', 2, 0, 0),
       (3, 1, 'Kartoffel - 5kg Sack', '5.7500', 1, 0, 0),
       (4, 1, 'Coke - 6er Tragerl', '25.6800', 4, 0, 0),
       (5, 1, 'Maiskolben - 2er Packung', '17.4500', 5, 0, 0),
       (6, 2, 'Milka Schokolade 500g Traube-Nuss', '4.5000', 2, 1, 0),
       (7, 2, 'Haribo Goldb&auml;rchen ', '7.6000', 4, 1, 0),
       (8, 2, 'Mannerwafferl', '6.0000', 4, 1, 0),
       (9, 2, 'Kinder Pingui - 4er Packung', '4.6500', 2, 1, 0),
       (10, 3, 'Deo Super Dry', '4.5000', 2, 0, 0),
       (11, 3, 'Duschgel Megaman', '8.7500', 4, 0, 0),
       (12, 3, 'Hühneraugenpflaster ', '3.7500', 1, 0, 0),
       (13, 3, 'One Billion - Parfum 250ml', '45.5000', 1, 0, 0),
       (14, 4, 'Fa - Deo', '2.7500', 2, 0, 0),
       (15, 4, 'Schaumbad ', '13.5000', 4, 0, 0),
       (16, 4, 'Lippenstift - rot', '3.7500', 1, 0, 0),
       (17, 4, 'Binden', '5.0500', 1, 0, 0),
       (18, 4, 'Trockenshampoo', '7.5000', 3, 0, 0),
       (19, 5, 'Äpfel in der Tasse ', '7.6000', 2, 0, 0),
       (20, 5, 'Bananen bio', '4.8900', 6, 0, 0),
       (21, 5, 'Rote Rüben', '3.5000', 2, 0, 0),
       (22, 5, 'Kartoffeln - 10k Sack', '7.4000', 1, 0, 0),
       (23, 6, 'Schinken - 20dag', '4.5000', 1, 1, 0),
       (24, 6, 'Krakauer - 15dag', '5.0000', 1, 0, 0),
       (25, 6, 'Gemischtes Faschiertes 350g', '9.9000', 2, 1, 0),
       (26, 7, 'Cevapcici - 500g', '15.7000', 2, 0, 0),
       (27, 7, 'Hüftsteak vom Rind', '50.0000', 5, 0, 0),
       (28, 7, 'Maiskolben', '14.0000', 8, 0, 0),
       (29, 7, 'Kräuterbutter', '3.7500', 2, 0, 0),
       (30, 7, 'Kartoffeln - 10kg Sack', '8.9000', 1, 0, 0),
       (31, 7, 'Grüner Blattsalat', '2.6000', 4, 0, 0),
       (32, 7, 'Kräuterbaguette', '4.0000', 4, 0, 0),
       (33, 7, 'Knoblauchbaguette', '5.2500', 4, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role`
(
    `id`      int(11)                                 NOT NULL,
    `name`    enum ('Admin','HelpSeeker','Volunteer') NOT NULL,
    `code`    bit(7)                                  NOT NULL,
    `deleted` tinyint(1)                              NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `code`, `deleted`)
VALUES (1, 'Admin', b'1111111', 0),
       (2, 'HelpSeeker', b'0000001', 0),
       (3, 'Volunteer', b'0000010', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shoppinglist`
--

CREATE TABLE `shoppinglist`
(
    `id`           int(11)                                         NOT NULL,
    `owner_id`     int(11)        DEFAULT NULL,
    `volunteer_id` int(11)        DEFAULT NULL,
    `name`         varchar(250)   DEFAULT NULL,
    `total`        decimal(19, 4) DEFAULT NULL CHECK (`total` > 0.0),
    `due_date`     date           DEFAULT NULL,
    `state`        enum ('unpublished','new','in progress','done') NOT NULL,
    `deleted`      tinyint(1)                                      NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Dumping data for table `shoppinglist`
--

INSERT INTO `shoppinglist` (`id`, `owner_id`, `volunteer_id`, `name`, `total`, `due_date`, `state`, `deleted`)
VALUES (1, 2, NULL, 'Warren`s BBQ List', NULL, NOW() + INTERVAL 7 DAY, 'new', 0),
       (2, 2, 5, 'Sweets ', '21.3500', NOW() + INTERVAL 3 DAY , 'done', 0),
       (3, 2, NULL, 'DM Einkaufsliste - Warren', NULL, NOW() + INTERVAL 5 DAY, 'unpublished', 0),
       (4, 3, NULL, 'Drogerie Liste', NULL, NOW() + INTERVAL 10 DAY, 'unpublished', 0),
       (5, 3, NULL, 'Obst &amp; Gem&uuml;seliste', NULL, NOW() + INTERVAL 14 DAY, 'new', 0),
       (6, 3, 4, 'Aufschnitt', NULL, NOW() + INTERVAL 2 DAY, 'in progress', 0),
       (7, 3, NULL, 'Grillereiliste', NULL, NOW() + INTERVAL 1 DAY, 'new', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user`
(
    `id`            int(11)      NOT NULL,
    `role_id`       int(11) DEFAULT NULL,
    `first_name`    varchar(250) NOT NULL,
    `last_name`     varchar(250) NOT NULL,
    `user_name`     varchar(250) NOT NULL,
    `password`      varchar(250) NOT NULL,
    `creation_date` date         NOT NULL,
    `deleted`       tinyint(1)   NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `role_id`, `first_name`, `last_name`, `user_name`, `password`, `creation_date`, `deleted`)
VALUES (1, 3, 'Michael', 'Eder', 'meder',
        'bab1067a333c163bf71e9cd0b3e5d64f82a714a307244a6746b02f21b3c0830c272dec0699a0f075a485fe5250a025c8ea9fa692f49778cabaaa1e17f6506008',
        '2020-04-11', 0),
       (2, 2, 'Warren', 'Ferro', 'wferro0',
        '99c77d79948c03e93810bfad9b1c2305a0c62c0c8a2b96809d4c5dd0171885b24d21176884d0b8f6f1bf1ed86d9a3273b6123fdc3040ec6addc208344e768c33',
        '2020-04-11', 0),
       (3, 2, 'Arlena', 'McGroarty', 'amcgroarty1',
        '6077cef8d52f25784cce41d53409d857eb00b810da3fd160f319b50ce02316753a0e68366ded1647b3515a6cc194e58788969824945fb52c97933990dbada27b',
        '2020-04-11', 0),
       (4, 3, 'Roz', 'McCreagh', 'rmccreagh2',
        'c5f8db3618f9ca10b3ce296f5afe1c9d8b6a0f3768a2baff8739166b3776536008b01e158f58cdf33854e645705d963a175fc44e9dc4401d44b9f731938f6526',
        '2020-04-11', 0),
       (5, 3, 'Crissie', 'Pendrey', 'cpendrey3',
        '2b7656e83fe08aa9a026f81f18adaec88b212fcd55a08c0b46a9adbe5e5ccbbad4ab183296931078dc9d2c9d88516053f0843ed05b118fac517a65e2a8eafad3',
        '2020-04-11', 0),
       (6, 2, 'Barry', 'Moore', 'bmoore',
        '8dbe6483c9d236bb810c1aa795867707669bd2934a78c10aa0162670c8558b5ba6afc8faee4c38eb8b37123196856cdc5e8c9fab4b5735f9d2c279806c1baf74',
        '2020-04-11', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article`
--
ALTER TABLE `article`
    ADD PRIMARY KEY (`id`),
    ADD KEY `shopping_list_id` (`shopping_list_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `name` (`name`),
    ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `shoppinglist`
--
ALTER TABLE `shoppinglist`
    ADD PRIMARY KEY (`id`),
    ADD KEY `owner_id` (`owner_id`),
    ADD KEY `volunteer_id` (`volunteer_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `user_name` (`user_name`),
    ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 34;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 4;

--
-- AUTO_INCREMENT for table `shoppinglist`
--
ALTER TABLE `shoppinglist`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `article`
--
ALTER TABLE `article`
    ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`shopping_list_id`) REFERENCES `shoppinglist` (`id`);

--
-- Constraints for table `shoppinglist`
--
ALTER TABLE `shoppinglist`
    ADD CONSTRAINT `shoppinglist_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`),
    ADD CONSTRAINT `shoppinglist_ibfk_2` FOREIGN KEY (`volunteer_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
    ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
