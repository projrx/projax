-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 03, 2019 at 04:19 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accounting`
--

-- --------------------------------------------------------

--
-- Table structure for table `acts`
--

DROP TABLE IF EXISTS `acts`;
CREATE TABLE IF NOT EXISTS `acts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeid` int(11) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL,
  `name` text NOT NULL,
  `slug` varchar(40) NOT NULL,
  `purpose` varchar(40) NOT NULL DEFAULT 'other',
  `balance` varchar(11) DEFAULT '0',
  `nodel` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `act_t`
--

DROP TABLE IF EXISTS `act_t`;
CREATE TABLE IF NOT EXISTS `act_t` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(20) DEFAULT NULL,
  `name` text NOT NULL,
  `balance` text NOT NULL,
  `tbshow` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `ctval` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(22) NOT NULL,
  PRIMARY KEY (`ctval`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `color`
--

DROP TABLE IF EXISTS `color`;
CREATE TABLE IF NOT EXISTS `color` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `color1` text NOT NULL,
  `color2` text NOT NULL,
  `color3` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_name` text NOT NULL,
  `comp_owner` text NOT NULL,
  `comp_phone` text NOT NULL,
  `comp_email` text NOT NULL,
  `comp_address` text NOT NULL,
  `comp_logo` text NOT NULL,
  `floating` int(11) NOT NULL,
  `invformat` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeid` int(11) NOT NULL DEFAULT '0',
  `type` varchar(40) NOT NULL DEFAULT 'Customers',
  `name` text NOT NULL,
  `mobile` text,
  `company` text,
  `phone` text,
  `email` text,
  `address` text,
  `city` varchar(20) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `balance` varchar(11) DEFAULT '0',
  `dated` date DEFAULT NULL,
  `del` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand` varchar(40) NOT NULL,
  `name` text NOT NULL,
  `desp` text NOT NULL,
  `price` varchar(11) NOT NULL DEFAULT '0',
  `sellprice` varchar(11) NOT NULL DEFAULT '0',
  `quantity` varchar(11) NOT NULL DEFAULT '0',
  `weight` varchar(11) NOT NULL DEFAULT '0',
  `unit` varchar(20) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT '0',
  `pause` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `itemsb`
--

DROP TABLE IF EXISTS `itemsb`;
CREATE TABLE IF NOT EXISTS `itemsb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `itemslog`
--

DROP TABLE IF EXISTS `itemslog`;
CREATE TABLE IF NOT EXISTS `itemslog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `type` varchar(11) NOT NULL DEFAULT '0',
  `name` text NOT NULL,
  `price` varchar(11) NOT NULL,
  `quantity` varchar(11) NOT NULL,
  `weight` varchar(11) NOT NULL DEFAULT '0',
  `subtotal` varchar(11) NOT NULL DEFAULT '0',
  `datec` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `itemu`
--

DROP TABLE IF EXISTS `itemu`;
CREATE TABLE IF NOT EXISTS `itemu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `journal`
--

DROP TABLE IF EXISTS `journal`;
CREATE TABLE IF NOT EXISTS `journal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jid` int(11) NOT NULL,
  `typeid` int(11) NOT NULL DEFAULT '0',
  `ref` int(11) NOT NULL DEFAULT '0',
  `actid` int(11) NOT NULL,
  `desp` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `dr` varchar(11) DEFAULT '0',
  `cr` varchar(11) DEFAULT '0',
  `balance` int(11) NOT NULL DEFAULT '0',
  `datec` date NOT NULL,
  `dateup` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `loginlog`
--

DROP TABLE IF EXISTS `loginlog`;
CREATE TABLE IF NOT EXISTS `loginlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` text NOT NULL,
  `pass` text NOT NULL,
  `atmp` int(11) NOT NULL,
  `datec` date NOT NULL,
  `timec` text NOT NULL,
  `dt` text NOT NULL,
  `dip` text NOT NULL,
  `diph` text NOT NULL,
  `dorg` text NOT NULL,
  `dcount` text NOT NULL,
  `dcountry` text NOT NULL,
  `dcity` text NOT NULL,
  `dos` text NOT NULL,
  `dbrow` text NOT NULL,
  `dbr` text NOT NULL,
  `dres` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `msgs`
--

DROP TABLE IF EXISTS `msgs`;
CREATE TABLE IF NOT EXISTS `msgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg` text NOT NULL,
  `datec` date NOT NULL,
  `resolve` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
CREATE TABLE IF NOT EXISTS `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `capital` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desp` text NOT NULL,
  `invoiceno` varchar(21) NOT NULL DEFAULT '0',
  `invoicepic` text,
  `chequeno` varchar(21) NOT NULL DEFAULT '0',
  `chequeamt` int(11) NOT NULL DEFAULT '0',
  `dract` int(11) NOT NULL DEFAULT '0',
  `cract` int(11) NOT NULL DEFAULT '0',
  `dr` varchar(11) NOT NULL DEFAULT '0',
  `cr` varchar(11) NOT NULL DEFAULT '0',
  `datec` date NOT NULL,
  `dateup` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `role` varchar(40) NOT NULL,
  `theme` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
CREATE TABLE IF NOT EXISTS `vendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeid` int(11) NOT NULL DEFAULT '0',
  `type` varchar(40) NOT NULL DEFAULT 'Vendors',
  `name` text NOT NULL,
  `mobile` text,
  `company` text,
  `phone` text,
  `email` text,
  `address` text,
  `city` varchar(20) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `balance` int(11) DEFAULT '0',
  `dated` date DEFAULT NULL,
  `del` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
