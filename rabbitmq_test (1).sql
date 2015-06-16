-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 14 2015 г., 21:46
-- Версия сервера: 5.5.43-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `rabbitmq_test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `ACL`
--

CREATE TABLE IF NOT EXISTS `ACL` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `ACL`
--

INSERT INTO `ACL` (`id`, `title`) VALUES
(1, 'Уровень 1'),
(2, 'Уровень 2'),
(3, 'Уровень 3'),
(4, 'Уровень 4'),
(5, 'Уровень 5');

-- --------------------------------------------------------

--
-- Структура таблицы `Author`
--

CREATE TABLE IF NOT EXISTS `Author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(400) NOT NULL,
  `phone` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `AuthorTemplateRID`
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
-- Структура таблицы `Author_RID`
--

CREATE TABLE IF NOT EXISTS `Author_RID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idRID` char(36) NOT NULL,
  `idAuthor` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idRID` (`idRID`),
  KEY `idAuthor` (`idAuthor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Branch`
--

CREATE TABLE IF NOT EXISTS `Branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `Branch`
--

INSERT INTO `Branch` (`id`, `title`) VALUES
(1, 'Судостроение'),
(2, 'Музыка'),
(3, 'Кино'),
(4, 'Наука');

-- --------------------------------------------------------

--
-- Структура таблицы `Branch_RID`
--

CREATE TABLE IF NOT EXISTS `Branch_RID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idRID` char(36) NOT NULL,
  `idBranch` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idRID` (`idRID`),
  UNIQUE KEY `idBranch` (`idBranch`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `FieldRID`
--

CREATE TABLE IF NOT EXISTS `FieldRID` (
  `id` char(36) NOT NULL,
  `idTypeFieldRID` int(11) DEFAULT NULL,
  `idUnits` char(36) DEFAULT NULL,
  `idTypeValueFieldRID` int(11) DEFAULT NULL,
  `idTitleFieldRID` char(36) NOT NULL,
  `idACL` int(11) NOT NULL,
  `idRID` char(36) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTypeFieldRID` (`idTypeFieldRID`),
  KEY `idUnits` (`idUnits`),
  KEY `idTypeValueFieldRID` (`idTypeValueFieldRID`),
  KEY `idTitleFieldRID` (`idTitleFieldRID`),
  KEY `idACL` (`idACL`),
  KEY `idRID` (`idRID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `FieldRID`
--

INSERT INTO `FieldRID` (`id`, `idTypeFieldRID`, `idUnits`, `idTypeValueFieldRID`, `idTitleFieldRID`, `idACL`, `idRID`) VALUES
(120, 2, NULL, NULL, 10, 5, 63),
(121, 1, 2, 1, 3, 2, 63),
(123, 3, NULL, NULL, 2, 1, 63);

-- --------------------------------------------------------

--
-- Структура таблицы `inheritableRID`
--

CREATE TABLE IF NOT EXISTS `inheritableRID` (
  `id` char(36) NOT NULL,
  `idRID` char(36) NOT NULL,
  `idInheritableRID` char(36) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idRID` (`idRID`),
  KEY `idInheritableRID` (`idInheritableRID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `RelativeRID`
--

CREATE TABLE IF NOT EXISTS `RelativeRID` (
  `id` char(36) NOT NULL AUTO_INCREMENT,
  `idRID` char(36) NOT NULL,
  `idRelativeRID` char(36) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_RID` (`idRID`),
  KEY `id_RID_2` (`idRID`),
  KEY `id_RelativeRID` (`idRelativeRID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `RID`
--

CREATE TABLE IF NOT EXISTS `RID` (
  `id` char(36) NOT NULL,
  `title` varchar(300) NOT NULL,
  `short_descr` text NOT NULL,
  `idACL` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idACL` (`idACL`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `RID`
--

INSERT INTO `RID` (`id`, `title`, `short_descr`, `idACL`) VALUES
(63, 'Ляляz', 'ляляляsdf', 1),
(66, 'Тестовый РИд', 'Тестирование динамических полей', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `TemplateFieldRID`
--

CREATE TABLE IF NOT EXISTS `TemplateFieldRID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idAuthorTemplateRID` int(11) NOT NULL,
  `idTypeFieldRID` int(11) NOT NULL,
  `idUnits` char(36) NOT NULL,
  `idTypeValueFieldRID` int(11) NOT NULL,
  `idTitleFieldRID` char(36) NOT NULL,
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
-- Структура таблицы `TitleFieldRID`
--

CREATE TABLE IF NOT EXISTS `TitleFieldRID` (
  `id` char(36) NOT NULL,
  `title` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `TitleFieldRID`
--

INSERT INTO `TitleFieldRID` (`id`, `title`) VALUES
(1, 'Вязкость'),
(2, 'Вес'),
(3, 'Объем'),
(4, 'ляляля'),
(5, 'дециметры'),
(6, 'Тяжесть 2'),
(7, 'czxczxc'),
(8, 'mmm'),
(9, 'mmmm'),
(10, 'fmlsdmfl'),
(11, 'лля'),
(12, 'аыва');

-- --------------------------------------------------------

--
-- Структура таблицы `TitleFieldRID_Units`
--

CREATE TABLE IF NOT EXISTS `TitleFieldRID_Units` (
  `id` char(36) NOT NULL,
  `idTitleFieldRID` char(36) NOT NULL,
  `idUnits` char(36) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTitleFieldRID` (`idTitleFieldRID`),
  KEY `idUnits` (`idUnits`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `TitleFieldRID_Units`
--

INSERT INTO `TitleFieldRID_Units` (`id`, `idTitleFieldRID`, `idUnits`) VALUES
(1, 1, 2),
(3, 2, 3),
(4, 2, 4),
(5, 2, 5),
(6, 3, 1),
(7, 3, 2),
(8, 4, 6),
(9, 5, 7),
(10, 6, 8);

-- --------------------------------------------------------

--
-- Структура таблицы `TypeFieldRID`
--

CREATE TABLE IF NOT EXISTS `TypeFieldRID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(400) NOT NULL,
  `title` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `TypeFieldRID`
--

INSERT INTO `TypeFieldRID` (`id`, `key`, `title`) VALUES
(1, 'string', 'Строковый'),
(2, 'file', 'Файл'),
(3, 'text', 'Текстовый');

-- --------------------------------------------------------

--
-- Структура таблицы `TypeValueFieldRID`
--

CREATE TABLE IF NOT EXISTS `TypeValueFieldRID` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(300) NOT NULL,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `TypeValueFieldRID`
--

INSERT INTO `TypeValueFieldRID` (`id`, `key`, `value`) VALUES
(1, 'value', 'Значение'),
(2, 'intervalOfValues', 'Диапазон значений');

-- --------------------------------------------------------

--
-- Структура таблицы `Units`
--

CREATE TABLE IF NOT EXISTS `Units` (
  `id` char(36) NOT NULL,
  `title` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `Units`
--

INSERT INTO `Units` (`id`, `title`) VALUES
(1, 'м3'),
(2, 'см3'),
(3, 'кг'),
(4, 'граммы'),
(5, 'милиграммы'),
(6, 'ляляля'),
(7, 'дециметры'),
(8, 'чопапало');

-- --------------------------------------------------------

--
-- Структура таблицы `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(400) NOT NULL,
  `phone` varchar(400) NOT NULL,
  `email` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `User_RID`
--

CREATE TABLE IF NOT EXISTS `User_RID` (
  `id` char(36) NOT NULL,
  `emailUser` varchar(400) NOT NULL,
  `idRID` char(36) NOT NULL,
  `idACL` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idUser` (`emailUser`(255)),
  KEY `idRID` (`idRID`),
  KEY `idACL` (`idACL`),
  KEY `idUser_2` (`emailUser`(255)),
  KEY `idUser_3` (`emailUser`(255))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `User_RID`
--

INSERT INTO `User_RID` (`id`, `emailUser`, `idRID`, `idACL`) VALUES
(1, 'gdfgf@mail.ru', 63, 5),
(2, 'kpb90@mail.ru', 63, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `ValueFieldRID`
--

CREATE TABLE IF NOT EXISTS `ValueFieldRID` (
  `id` char(36) NOT NULL,
  `idFieldRID` char(36) NOT NULL,
  `value` varchar(400) NOT NULL,
  `ord` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idFieldRID` (`idFieldRID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ValueFieldRID`
--

INSERT INTO `ValueFieldRID` (`id`, `idFieldRID`, `value`, `ord`) VALUES
(31, 120, 'access.log.6.gz', 1),
(32, 121, 'msmdfdsf', 1),
(33, 123, 'gdfgdfg', 1);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `AuthorTemplateRID`
--
ALTER TABLE `AuthorTemplateRID`
  ADD CONSTRAINT `AuthorTemplateRID` FOREIGN KEY (`idAuthor`) REFERENCES `Author` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Author_RID`
--
ALTER TABLE `Author_RID`
  ADD CONSTRAINT `AuthorOfRID` FOREIGN KEY (`idAuthor`) REFERENCES `Author` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `RIDOfAuthor` FOREIGN KEY (`idRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Branch_RID`
--
ALTER TABLE `Branch_RID`
  ADD CONSTRAINT `BranchBranch_RID` FOREIGN KEY (`idBranch`) REFERENCES `Branch` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `RIDBranch_RID` FOREIGN KEY (`idRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `FieldRID`
--
ALTER TABLE `FieldRID`
  ADD CONSTRAINT `ACLinFieldRID` FOREIGN KEY (`idACL`) REFERENCES `ACL` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `RID_FieldRID` FOREIGN KEY (`idRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `TitleFieldRIDinFieldRID` FOREIGN KEY (`idTitleFieldRID`) REFERENCES `TitleFieldRID` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TypeFieldRIDinFieldRID` FOREIGN KEY (`idTypeFieldRID`) REFERENCES `TypeFieldRID` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TypeValueFieldRIDinFieldRID` FOREIGN KEY (`idTypeValueFieldRID`) REFERENCES `TypeValueFieldRID` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `UnitsRIDinFieldRID` FOREIGN KEY (`idUnits`) REFERENCES `Units` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `inheritableRID`
--
ALTER TABLE `inheritableRID`
  ADD CONSTRAINT `inheritableRID` FOREIGN KEY (`idInheritableRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mainInheritableRID` FOREIGN KEY (`idRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `RelativeRID`
--
ALTER TABLE `RelativeRID`
  ADD CONSTRAINT `mainRelativeRID` FOREIGN KEY (`idRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `relativeRID` FOREIGN KEY (`idRelativeRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `RID`
--
ALTER TABLE `RID`
  ADD CONSTRAINT `ACLRID` FOREIGN KEY (`idACL`) REFERENCES `ACL` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `TemplateFieldRID`
--
ALTER TABLE `TemplateFieldRID`
  ADD CONSTRAINT `ACLTemplateFieldRID` FOREIGN KEY (`idACL`) REFERENCES `ACL` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `AuthorTemplateRIDTemplateFieldRID` FOREIGN KEY (`idAuthorTemplateRID`) REFERENCES `AuthorTemplateRID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `TitleFieldRIDTemplateFieldRID` FOREIGN KEY (`idTitleFieldRID`) REFERENCES `TitleFieldRID` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TypeFieldRIDTemplateFieldRID` FOREIGN KEY (`idTypeFieldRID`) REFERENCES `TypeFieldRID` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `TypeValueFieldRIDTemplateFieldRID` FOREIGN KEY (`idTypeValueFieldRID`) REFERENCES `TypeFieldRID` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `UnitsTemplateFieldRID` FOREIGN KEY (`idUnits`) REFERENCES `Units` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `TitleFieldRID_Units`
--
ALTER TABLE `TitleFieldRID_Units`
  ADD CONSTRAINT `TitleFieldRIDTitleFieldRID_Units` FOREIGN KEY (`idTitleFieldRID`) REFERENCES `TitleFieldRID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UnitsTitleFieldRID_Units` FOREIGN KEY (`idUnits`) REFERENCES `Units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `User_RID`
--
ALTER TABLE `User_RID`
  ADD CONSTRAINT `ACLUser_RID` FOREIGN KEY (`idACL`) REFERENCES `ACL` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `RIDUser_RID` FOREIGN KEY (`idRID`) REFERENCES `RID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `ValueFieldRID`
--
ALTER TABLE `ValueFieldRID`
  ADD CONSTRAINT `FieldRIDValueFieldRID` FOREIGN KEY (`idFieldRID`) REFERENCES `FieldRID` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;