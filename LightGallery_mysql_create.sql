CREATE TABLE `Users` (
	`idUser` bigint NOT NULL AUTO_INCREMENT,
	`username` varchar(256) NOT NULL,
	`email` varchar(256) NOT NULL,
	`password` varchar(128) NOT NULL,
	PRIMARY KEY (`idUser`)
);

CREATE TABLE `Files` (
	`idFile` bigint NOT NULL AUTO_INCREMENT,
	`idUser` bigint NOT NULL,
	`filename` varchar(1024) NOT NULL,
	`path` varchar(1024) NOT NULL,
	`mimeType` varchar(256) NOT NULL,
	`rating` int NOT NULL DEFAULT '0',
	`uploadedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`idFile`)
);

CREATE TABLE `Rating` (
	`idUser` bigint,
	`idFile` bigint,
	`ipAddress` varchar(16) NOT NULL UNIQUE
);

CREATE TABLE `Tags` (
	`idTag` bigint NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`code` varchar(255) NOT NULL,
	`color` varchar(6) NOT NULL DEFAULT 'dbdbdb',
	PRIMARY KEY (`idTag`)
);

CREATE TABLE `FileTags` (
	`idFile` bigint NOT NULL,
	`idTag` bigint NOT NULL
);

CREATE TABLE `Roles` (
	`idRole` bigint NOT NULL AUTO_INCREMENT,
	`name` varchar(64) NOT NULL,
	PRIMARY KEY (`idRole`)
);

CREATE TABLE `UserRoles` (
	`idUser` bigint NOT NULL,
	`idRole` bigint NOT NULL
);

ALTER TABLE `Files` ADD CONSTRAINT `Files_fk0` FOREIGN KEY (`idUser`) REFERENCES `Users`(`idUser`);

ALTER TABLE `Rating` ADD CONSTRAINT `Rating_fk0` FOREIGN KEY (`idUser`) REFERENCES `Users`(`idUser`);

ALTER TABLE `Rating` ADD CONSTRAINT `Rating_fk1` FOREIGN KEY (`idFile`) REFERENCES `Files`(`idFile`);

ALTER TABLE `FileTags` ADD CONSTRAINT `FileTags_fk0` FOREIGN KEY (`idFile`) REFERENCES `Files`(`idFile`);

ALTER TABLE `FileTags` ADD CONSTRAINT `FileTags_fk1` FOREIGN KEY (`idTag`) REFERENCES `Tags`(`idTag`);

ALTER TABLE `UserRoles` ADD CONSTRAINT `UserRoles_fk0` FOREIGN KEY (`idUser`) REFERENCES `Users`(`idUser`);

ALTER TABLE `UserRoles` ADD CONSTRAINT `UserRoles_fk1` FOREIGN KEY (`idRole`) REFERENCES `Roles`(`idRole`);

--MY Constragns
ALTER TABLE `Rating` ADD UNIQUE `Rating_unique_1`(`idUser`, `idFile`);
ALTER TABLE `Rating` ADD UNIQUE `Rating_unique_2`(`idUser`, `ipAddress`);

ALTER TABLE `FileTags` ADD UNIQUE `Rating_unique_2`(`idFile`, `idTag`);

ALTER TABLE `Roles` ADD UNIQUE `Roles_unique`(`name`);

ALTER TABLE `UserRoles` ADD UNIQUE `UserRoles_unique`(`idUser`, `idRole`);

ALTER TABLE `Users` ADD UNIQUE `User_unique`(`username`);
ALTER TABLE `Users` ADD UNIQUE `User_unique_2`(`email`);

ALTER TABLE `Tags` ADD UNIQUE `Tags_unique_1`(`code`);
ALTER TABLE `Tags` ADD UNIQUE `Tags_unique_2`(`name`);

ALTER TABLE `Files` ADD UNIQUE `Files_unique`(`path`);




