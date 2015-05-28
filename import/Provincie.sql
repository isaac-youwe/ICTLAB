-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 10, 2015 at 04:29 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.24-1+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `location`
--

-- --------------------------------------------------------

--
-- Table structure for table `Provincie`
--

CREATE TABLE IF NOT EXISTS `Provincie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `longitude` decimal(17,16) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `naam` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `Provincie`
--

INSERT INTO `Provincie` (`id`, `longitude`, `latitude`, `naam`) VALUES
(1, 6.5665017999999990, 53.21938350, 'Groningen'),
(2, 5.7817542000000000, 53.16416420, 'Friesland'),
(3, 6.6230585999999990, 52.94760120, 'Drenthe'),
(4, 6.5016411000000000, 52.43878140, 'Overijssel'),
(5, 5.5953507999999990, 52.52797810, 'Flevoland'),
(6, 5.8718233999999990, 52.04515500, 'Gelderland'),
(7, 5.1214201000000000, 52.09073739, 'Utrecht'),
(8, 4.7884740000000000, 52.52058690, 'Noord-Holland'),
(9, 4.4937836000000000, 52.02079750, 'Zuid-Holland'),
(10, 3.8496815000000000, 51.49403090, 'Zeeland'),
(11, 5.2321687000000000, 51.48265370, 'Noord-Brabant'),
(12, 8.0795783000000000, 50.39860050, 'Limburg');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
