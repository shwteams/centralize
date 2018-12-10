-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2018 at 01:02 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 5.6.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `CustomerID` int(11) NOT NULL,
  `CustomerName` varchar(250) NOT NULL,
  `Address` text NOT NULL,
  `City` varchar(250) NOT NULL,
  `PostalCode` varchar(30) NOT NULL,
  `Country` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`CustomerID`, `CustomerName`, `Address`, `City`, `PostalCode`, `Country`) VALUES
(1, 'Willie Whited', '2524 White River Way', 'Salt Lake City', '84106', 'USA'),
(2, 'Lisa Squires', 'Fehringer Strasse 27', 'MARIA BUCH', '8750', 'Austria'),
(3, 'Sean Patterson', 'Rue des Ecoles 426', 'Vlierzele', '9520', 'Belgium'),
(4, 'Anna Scott', 'Rua C 66, 1384', 'Goiania-GO', '74305-450', 'Brazil'),
(5, 'Desmond Peterson', '1414 Grey Rd', 'Feversham', 'ON N0C 1C0', 'Canada'),
(6, 'Samuel Hogan', '13, Avenue De Marlioz', 'ARGENTEUIL', '95100', 'France'),
(7, 'John Miller', 'Pappelallee 21', 'Arnsdorf', '09661', 'Germany'),
(8, 'Rose Joyce', 'Via Galileo Ferraris, 38', 'Malavicina MN', '46040', 'Italy'),
(9, 'Phillip Tilton', 'Huibertplaat 120', 'DH  Zwolle', '8032', 'Netherland'),
(10, 'Anita McGurk', '128 St Pauls Court Cloverlea', 'Palmerston North', '4412', 'New Zealand'),
(11, 'John Morgan', '286 Stanza Bopape St', 'Louis Trichardt', '0923', 'South Africa'),
(12, 'Margaret Teets', 'Grossmatt 62', 'Betschwanden', '8777', 'Switzerland'),
(13, 'Dara Adams', '21 Fraserburgh Rd', 'LINNELS', 'NE46 8YB', 'United Kingdom'),
(14, 'Bennie J. Martin', '34 Masthead Drive', 'PARK AVENUE QLD', '4701', 'Australia'),
(15, 'Gladys Ashford', 'Holzstrasse 14', 'SALCHENDORF', '9064', 'Austria'),
(16, 'William Lavallie', 'Heirstraat 31', 'Marchin', '4570', 'Belgium'),
(17, 'Antonio Wayman', '2331 Carlson Road', 'Prince George', 'BC V2L 5E5', 'Canada'),
(18, 'Carol Selders', 'Ysitie 44', 'TAMPERE', '33240', 'Finland'),
(19, 'David Sato', '30, Rue de la Pompe', 'MANOSQUE', '04100', 'France'),
(20, 'Amy Vanmatre', 'Friedrichstrasse 4', 'Dusseldorf Flehe', '40223', 'Germany'),
(21, 'Kenny Todd', 'Corso Porta Nuova, 10', 'Quara RE', '42010', 'Italy'),
(22, 'Marla Alvarez', 'Tielstraat 17', 'Amsterdam-Zuidoost', '1107 RC', 'Netherland'),
(23, 'George Stanley', '95 Belle Verde Drive Rothesay Bay', 'North Shore', '0630', 'New Zealand'),
(24, 'David Bennett', 'Torvbakkgata 219', 'OSLO', '0550', 'Norway'),
(25, 'Donald Garrett', '86 St Denys Road', 'POYS STREET', 'IP17 9TF', 'United Kingdom'),
(26, 'Horace Morgan', '3435 Jarvis Street', 'Buffalo', '14202', 'United States');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
