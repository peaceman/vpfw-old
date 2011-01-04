# --------------------------------------------------------
# Host:                         127.0.0.1
# Server version:               5.1.37-community
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-01-04 17:27:14
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping structure for table facejudgement.pictures
DROP TABLE IF EXISTS `pictures`;
CREATE TABLE IF NOT EXISTS `pictures` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Md5` binary(16) NOT NULL COMMENT 'MD5 Hash des Bildes. Mit diesem Hash wird überprüft, ob sich das Bild bereits auf der Plattform existiert.',
  `Gender` tinyint(4) unsigned NOT NULL COMMENT 'Geschlecht der auf dem Bild befindlichen Person. 0 = Männlich 1 = Weiblich',
  `SessionId` int(10) unsigned NOT NULL COMMENT 'Session Id der Sitzung, in der dieses Bild hochgeladen wurde.',
  `UploadTime` int(10) unsigned NOT NULL,
  `SiteHits` int(10) unsigned NOT NULL,
  `PositiveRating` int(10) unsigned NOT NULL,
  `NegativeRating` int(10) unsigned NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `Md5` (`Md5`),
  KEY `SessionId` (`SessionId`),
  CONSTRAINT `FK_pictures_session` FOREIGN KEY (`SessionId`) REFERENCES `session` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table facejudgement.session
DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Ip` int(10) unsigned NOT NULL COMMENT 'IPv6 ?!',
  `StartTime` int(10) unsigned NOT NULL,
  `LastRequest` int(10) unsigned NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.


# Dumping structure for table facejudgement.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Username` varchar(64) NOT NULL,
  `Passhash` varchar(32) NOT NULL COMMENT 'Begrenzung auf 32 Zeichen, da es sich um einen MD5 Hash handelt.',
  `Email` varchar(64) NOT NULL,
  `CreationTime` int(10) unsigned NOT NULL,
  `CreationIp` int(10) unsigned NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Data exporting was unselected.
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
