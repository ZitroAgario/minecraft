-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- Хост: 83.69.230.13
-- Время создания: Окт 26 2013 г., 12:49
-- Версия сервера: 5.1.67-log
-- Версия PHP: 5.3.21

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `vh156194_loger`
--

-- --------------------------------------------------------

--
-- Структура таблицы `basket`
--

CREATE TABLE IF NOT EXISTS `basket` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET cp1251 NOT NULL,
  `itemname` varchar(255) CHARACTER SET cp1251 NOT NULL,
  `item` varchar(255) CHARACTER SET cp1251 NOT NULL,
  `cost` varchar(255) CHARACTER SET cp1251 NOT NULL,
  `ammount` int(255) NOT NULL,
  `background` text CHARACTER SET cp1251 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=273 ;

--
-- Дамп данных таблицы `basket`
--

INSERT INTO `basket` (`id`, `name`, `itemname`, `item`, `cost`, `ammount`, `background`) VALUES
(138, 'Sit31', 'ЭХО', '227:2', '13', 1, '/shop/img/4adaa04a33d98d19d675d38cb437bf6d.png'),
(156, 'Goodie', 'Компрессор', '250:5', '3', 1, '/shop/img/afe9cb8aea852f0e4ba9ef1c184f5308.png'),
(157, 'Goodie', 'БатБокс', '227', '1', 1, '/shop/img/bd0b2aa3b1edbcf55d17e59cb2b00ae6.png'),
(158, 'Goodie', 'Батарейка', '30238', '0.5', 1, '/shop/img/82ff3661416788b6f5d64e47b308b096.png'),
(167, 'nikita2282', 'Урановый стержень x2', '30102', '4', 1, '/shop/img/9c79d536f6467ba1b28289206a5ba4d4.png'),
(169, 'Goodie', 'Геотермальный генератор', '246:1', '3', 1, '/shop/img/8b6772300011c0bc83ba654e4f7c02ec.png'),
(170, 'Goodie', 'Генератор', '246', '2', 1, '/shop/img/625ed97e189cbd7fc03fac3afa7c476c.png'),
(226, 'Radiman', '', '', '0', 1, '');

-- --------------------------------------------------------

--
-- Структура таблицы `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `itemname` varchar(255) NOT NULL,
  `cost` varchar(255) NOT NULL,
  `ammount` int(255) NOT NULL,
  `background` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

--
-- Дамп данных таблицы `history`
--

INSERT INTO `history` (`id`, `name`, `itemname`, `cost`, `ammount`, `background`, `time`) VALUES
(31, 'vadsf', 'sdfgsdg', 'fdgdfgdf', 66, 'gdfgdfgdf', '2013-10-25 12:05:52'),
(32, 'vadsf', 'fdgdfgdf', 'gdfg', 555, 'dfgdfgdfg', '2013-10-25 12:06:26'),
(33, 'alekc', 'Наносабля', '5', 1, '', '2013-10-25 16:29:12'),
(34, 'alekc', 'Алмазный бур', '5', 1, '', '2013-10-25 16:29:12'),
(35, 'Radiman', 'МФЭ', '2', 1, '', '2013-10-25 16:32:15'),
(36, 'Loger', 'Трава', '0.15625', 5, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 16:55:32'),
(37, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 16:55:32'),
(38, 'Loger', 'Трава', '1.375', 44, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 16:56:15'),
(39, 'Loger', 'Трава', '1.03125', 33, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 17:18:23'),
(40, 'Simulant11', 'Лазуротроновый кристалл', '4', 2, '/shop/img/35302efea2d199016f6bbe8a4c49446e.png', '2013-10-25 19:28:03'),
(41, 'Loger', 'Трава', '1.71875', 55, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:02'),
(42, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:02'),
(43, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:02'),
(44, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:02'),
(45, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:02'),
(46, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:02'),
(47, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:02'),
(48, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:03'),
(49, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:03'),
(50, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:03'),
(51, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:03'),
(52, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:03'),
(53, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(54, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(55, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(56, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(57, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(58, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(59, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(60, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(61, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(62, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(63, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(64, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(65, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(66, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(67, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(68, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(69, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(70, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(71, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(72, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(73, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(74, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(75, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(76, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(77, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(78, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(79, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(80, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25'),
(81, 'Loger', 'Трава', '0.03125', 1, '/shop/img/1705dd59ab7eb5efc024ac6b2d6bd12b.png', '2013-10-25 23:25:25');

-- --------------------------------------------------------

--
-- Структура таблицы `shopcart`
--

CREATE TABLE IF NOT EXISTS `shopcart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT 'item',
  `player` varchar(255) DEFAULT NULL,
  `item` varchar(255) DEFAULT NULL,
  `extra` text,
  `amount` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `player` (`player`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `shopcart`
--

INSERT INTO `shopcart` (`id`, `type`, `player`, `item`, `extra`, `amount`) VALUES
(3, 'item', 'loger', '2', NULL, 754),
(4, 'item', 'loger', '2', NULL, 1),
(5, '5', 'Loger', '5', NULL, 14),
(6, '15', 'Loger', '15', NULL, 7),
(7, '23', 'Loger', '23', NULL, 7),
(8, '25', 'Loger', '25', NULL, 7),
(9, '26', 'Loger', '26', NULL, 79),
(10, '28', 'Loger', '28', NULL, 90),
(11, '64', 'Loger', '64', NULL, 10),
(12, '68', 'Loger', '68', NULL, 55),
(13, '13', 'Loger', '13', NULL, 217),
(14, '4', 'Loger', '4', NULL, 7),
(15, 'item', 'alekc', '30149:27', NULL, 1),
(16, 'item', 'alekc', '30234:27', NULL, 1),
(17, 'item', 'Radiman', '227:1', NULL, 1),
(18, 'item', 'Simulant11', '30240:27', NULL, 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
