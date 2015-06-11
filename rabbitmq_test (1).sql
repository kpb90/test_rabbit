-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 11, 2015 at 07:52 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rabbitmq_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `ACL`
--

CREATE TABLE IF NOT EXISTS `ACL` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `ACL`
--

INSERT INTO `ACL` (`id`, `title`) VALUES
(1, 'Уровень 1'),
(2, 'Уровень 2'),
(3, 'Уровень 3'),
(4, 'Уровень 4'),
(5, 'Уровень 5');

-- --------------------------------------------------------

--
-- Table structure for table `Author`
--

CREATE TABLE IF NOT EXISTS `Author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(400) NOT NULL,
  `phone` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `AuthorTemplateRID`
--

CREATE TABLE IF NOT EXISTS `AuthorTemplateRID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(400) NOT NULL,
  `idAuthor` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idAuthor` (`idAuthor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Author_RID`
--

CREATE TABLE IF NOT EXISTS `Author_RID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idRID` int(11) NOT NULL,
  `idAuthor` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idRID` (`idRID`),
  KEY `idAuthor` (`idAuthor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Branch`
--

CREATE TABLE IF NOT EXISTS `Branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `Branch`
--

INSERT INTO `Branch` (`id`, `title`) VALUES
(1, 'Судостроение'),
(2, 'Музыка'),
(3, 'Кино'),
(4, 'Наука');

-- --------------------------------------------------------

--
-- Table structure for table `Branch_RID`
--

CREATE TABLE IF NOT EXISTS `Branch_RID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idRID` int(11) NOT NULL,
  `idBranch` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idRID` (`idRID`),
  UNIQUE KEY `idBranch` (`idBranch`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `FieldRID`
--

CREATE TABLE IF NOT EXISTS `FieldRID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idTypeFieldRID` int(11) DEFAULT NULL,
  `idUnits` int(11) DEFAULT NULL,
  `idTypeValueFieldRID` int(11) DEFAULT NULL,
  `idTitleFieldRID` int(11) NOT NULL,
  `idACL` int(11) NOT NULL,
  `idRID` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTypeFieldRID` (`idTypeFieldRID`),
  KEY `idUnits` (`idUnits`),
  KEY `idTypeValueFieldRID` (`idTypeValueFieldRID`),
  KEY `idTitleFieldRID` (`idTitleFieldRID`),
  KEY `idACL` (`idACL`),
  KEY `idRID` (`idRID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=85 ;

--
-- Dumping data for table `FieldRID`
--

INSERT INTO `FieldRID` (`id`, `idTypeFieldRID`, `idUnits`, `idTypeValueFieldRID`, `idTitleFieldRID`, `idACL`, `idRID`) VALUES
(79, 1, 5, 1, 2, 2, 63),
(82, 1, 5, 1, 2, 4, 63),
(83, 1, 2, 1, 3, 5, 63),
(84, 1, 4, 2, 2, 4, 63);

-- --------------------------------------------------------

--
-- Table structure for table `inheritableRID`
--

CREATE TABLE IF NOT EXISTS `inheritableRID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idRID` int(11) NOT NULL,
  `idInheritableRID` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idRID` (`idRID`),
  KEY `idInheritableRID` (`idInheritableRID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `RelativeRID`
--

CREATE TABLE IF NOT EXISTS `RelativeRID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idRID` int(11) NOT NULL,
  `idRelativeRID` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_RID` (`idRID`),
  KEY `id_RID_2` (`idRID`),
  KEY `id_RelativeRID` (`idRelativeRID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `RID`
--

CREATE TABLE IF NOT EXISTS `RID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(300) NOT NULL,
  `short_descr` text NOT NULL,
  `idACL` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idACL` (`idACL`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

--
-- Dumping data for table `RID`
--

INSERT INTO `RID` (`id`, `title`, `short_descr`, `idACL`) VALUES
(63, 'Ляляz', 'ляляляsdf', 1);

-- --------------------------------------------------------

--
-- Table structure for table `TemplateFieldRID`
--

CREATE TABLE IF NOT EXISTS `TemplateFieldRID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idAuthorTemplateRID` int(11) NOT NULL,
  `idTypeFieldRID` int(11) NOT NULL,
  `idUnits` int(11) NOT NULL,
  `idTypeValueFieldRID` int(11) NOT NULL,
  `idTitleFieldRID` int(11) NOT NULL,
  `idACL` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTypeFieldRID` (`idTypeFieldRID`),
  KEY `idUnits` (`idUnits`),
  KEY `idTypeValueFieldRID` (`idTypeValueFieldRID`),
  KEY `idTitleFieldRID` (`idTitleFieldRID`),
  KEY `idACL` (`idACL`),
  KEY `idAuthorTemplateRID` (`idAuthorTemplateRID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `TitleFieldRID`
--

CREATE TABLE IF NOT EXISTS `TitleFieldRID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `TitleFieldRID`
--

INSERT INTO `TitleFieldRID` (`id`, `title`) VALUES
(1, 'Вязкость'),
(2, 'Вес'),
(3, 'Объем');

-- --------------------------------------------------------

--
-- Table structure for table `TitleFieldRID_Units`
--

CREATE TABLE IF NOT EXISTS `TitleFieldRID_Units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idTitleFieldRID` int(11) NOT NULL,
  `idUnits` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTitleFieldRID` (`idTitleFieldRID`),
  KEY `idUnits` (`idUnits`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `TitleFieldRID_Units`
--

INSERT INTO `TitleFieldRID_Units` (`id`, `idTitleFieldRID`, `idUnits`) VALUES
(1, 1, 2),
(3, 2, 3),
(4, 2, 4),
(5, 2, 5),
(6, 3, 1),
(7, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `TypeFieldRID`
--

CREATE TABLE IF NOT EXISTS `TypeFieldRID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(400) NOT NULL,
  `title` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `TypeFieldRID`
--

INSERT INTO `TypeFieldRID` (`id`, `key`, `title`) VALUES
(1, 'string', 'Строковый'),
(2, 'file', 'Файл'),
(3, 'text', 'Текстовый');

-- --------------------------------------------------------

--
-- Table structure for table `TypeValueFieldRID`
--

CREATE TABLE IF NOT EXISTS `TypeValueFieldRID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(300) NOT NULL,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `TypeValueFieldRID`
--

INSERT INTO `TypeValueFieldRID` (`id`, `key`, `value`) VALUES
(1, 'value', 'Значение'),
(2, 'intervalOfValues', 'Диапазон значений');

-- --------------------------------------------------------

--
-- Table structure for table `Units`
--

CREATE TABLE IF NOT EXISTS `Units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `Units`
--

INSERT INTO `Units` (`id`, `title`) VALUES
(1, 'м3'),
(2, 'см3'),
(3, 'кг'),
(4, 'граммы'),
(5, 'милиграммы');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(400) NOT NULL,
  `phone` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `User_RID`
--

CREATE TABLE IF NOT EXISTS `User_RID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `idRID` int(11) NOT NULL,
  `idACL` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idUser` (`idUser`),
  KEY `idRID` (`idRID`),
  KEY `idACL` (`idACL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ValueFieldRID`
--

CREATE TABLE IF NOT EXISTS `ValueFieldRID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idFieldRID` int(11) NOT NULL,
  `value` varchar(400) NOT NULL,
  `ord` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ValueFieldRID`
--

INSERT INTO `ValueFieldRID` (`id`, `idFieldRID`, `value`, `ord`) VALUES
(1, 82, 'fsdfsdfsd sdfsdf', 1),
(2, 83, '80', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `AuthorTemplateRID`
--
ALTER TABLE `AuthorTemplateRID`
  ADD CONSTRAINT `AuthorTemplateRID` FOREIGN KEY (`idAuthor`) REFERENCES `Author` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Author_RID`
--
ALTER TABLE `Author_RID`
  ADD CONSTRAINT `AuthorOfRID` FOREIGN KEY (`idAuthor`) REFERENCES `Author` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `RIDOfAuthor` FOREIGN KEY (`idRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Branch_RID`
--
ALTER TABLE `Branch_RID`
  ADD CONSTRAINT `BranchBranch_RID` FOREIGN KEY (`idBranch`) REFERENCES `Branch` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `RIDBranch_RID` FOREIGN KEY (`idRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `FieldRID`
--
ALTER TABLE `FieldRID`
  ADD CONSTRAINT `ACLinFieldRID` FOREIGN KEY (`idACL`) REFERENCES `ACL` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `RID_FieldRID` FOREIGN KEY (`idRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `TitleFieldRIDinFieldRID` FOREIGN KEY (`idTitleFieldRID`) REFERENCES `TitleFieldRID` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TypeFieldRIDinFieldRID` FOREIGN KEY (`idTypeFieldRID`) REFERENCES `TypeFieldRID` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TypeValueFieldRIDinFieldRID` FOREIGN KEY (`idTypeValueFieldRID`) REFERENCES `TypeValueFieldRID` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `UnitsRIDinFieldRID` FOREIGN KEY (`idUnits`) REFERENCES `Units` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `inheritableRID`
--
ALTER TABLE `inheritableRID`
  ADD CONSTRAINT `inheritableRID` FOREIGN KEY (`idInheritableRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mainInheritableRID` FOREIGN KEY (`idRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `RelativeRID`
--
ALTER TABLE `RelativeRID`
  ADD CONSTRAINT `mainRelativeRID` FOREIGN KEY (`idRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `relativeRID` FOREIGN KEY (`idRelativeRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `RID`
--
ALTER TABLE `RID`
  ADD CONSTRAINT `ACLRID` FOREIGN KEY (`idACL`) REFERENCES `ACL` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `TemplateFieldRID`
--
ALTER TABLE `TemplateFieldRID`
  ADD CONSTRAINT `ACLTemplateFieldRID` FOREIGN KEY (`idACL`) REFERENCES `ACL` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `AuthorTemplateRIDTemplateFieldRID` FOREIGN KEY (`idAuthorTemplateRID`) REFERENCES `AuthorTemplateRID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `TitleFieldRIDTemplateFieldRID` FOREIGN KEY (`idTitleFieldRID`) REFERENCES `TitleFieldRID` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TypeFieldRIDTemplateFieldRID` FOREIGN KEY (`idTypeFieldRID`) REFERENCES `TypeFieldRID` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TypeValueFieldRIDTemplateFieldRID` FOREIGN KEY (`idTypeValueFieldRID`) REFERENCES `TypeFieldRID` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `UnitsTemplateFieldRID` FOREIGN KEY (`idUnits`) REFERENCES `Units` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `TitleFieldRID_Units`
--
ALTER TABLE `TitleFieldRID_Units`
  ADD CONSTRAINT `TitleFieldRIDTitleFieldRID_Units` FOREIGN KEY (`idTitleFieldRID`) REFERENCES `TitleFieldRID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UnitsTitleFieldRID_Units` FOREIGN KEY (`idUnits`) REFERENCES `Units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `User_RID`
--
ALTER TABLE `User_RID`
  ADD CONSTRAINT `ACLUser_RID` FOREIGN KEY (`idACL`) REFERENCES `ACL` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `RIDUser_RID` FOREIGN KEY (`idRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UserUser_RID` FOREIGN KEY (`idUser`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
