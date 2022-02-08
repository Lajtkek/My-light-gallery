SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `Files` (
  `idFile` int(10) UNSIGNED NOT NULL,
  `idUser` int(10) UNSIGNED NOT NULL,
  `filename` varchar(1024) COLLATE utf8_czech_ci NOT NULL,
  `permalink` varchar(120) COLLATE utf8_czech_ci NOT NULL,
  `mimeType` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `extension` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `size` decimal(10,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8_czech_ci NOT NULL,
  `rating` int(11) NOT NULL DEFAULT '0',
  `uploadedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `FileTags` (
  `idFile` int(10) UNSIGNED NOT NULL,
  `idTag` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `Rating` (
  `idUser` int(10) UNSIGNED DEFAULT NULL,
  `idFile` int(10) UNSIGNED DEFAULT NULL,
  `ipAddress` varchar(16) COLLATE utf8_czech_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `Roles` (
  `idRole` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8_czech_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `Tags` (
  `idTag` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `color` varchar(6) COLLATE utf8_czech_ci NOT NULL DEFAULT 'dbdbdb',
  `isPublic` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `UserRoles` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `idRole` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `Users` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `username` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `isApproved` bit(1) NOT NULL DEFAULT b'0',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


ALTER TABLE `Files`
  ADD PRIMARY KEY (`idFile`),
  ADD UNIQUE KEY `Files_unique` (`permalink`),
  ADD KEY `Files_fk0` (`idUser`);

ALTER TABLE `FileTags`
  ADD UNIQUE KEY `Rating_unique_2` (`idFile`,`idTag`),
  ADD KEY `FileTags_fk1` (`idTag`);

ALTER TABLE `Rating`
  ADD UNIQUE KEY `ipAddress` (`ipAddress`),
  ADD UNIQUE KEY `Rating_unique_1` (`idUser`,`idFile`),
  ADD UNIQUE KEY `Rating_unique_2` (`idUser`,`ipAddress`),
  ADD KEY `Rating_fk1` (`idFile`);

ALTER TABLE `Roles`
  ADD PRIMARY KEY (`idRole`),
  ADD UNIQUE KEY `Roles_unique` (`name`);

ALTER TABLE `Tags`
  ADD PRIMARY KEY (`idTag`),
  ADD UNIQUE KEY `Tags_unique_1` (`code`);

ALTER TABLE `UserRoles`
  ADD UNIQUE KEY `UserRoles_unique` (`idUser`,`idRole`),
  ADD KEY `UserRoles_fk1` (`idRole`);

ALTER TABLE `Users`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `User_unique` (`username`),
  ADD UNIQUE KEY `User_unique_2` (`email`);


ALTER TABLE `Files`
  MODIFY `idFile` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `Roles`
  MODIFY `idRole` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `Tags`
  MODIFY `idTag` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `Users`
  MODIFY `idUser` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
