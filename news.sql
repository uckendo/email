-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-10-28 20:40:50
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `news`
--

-- --------------------------------------------------------

--
-- 表的结构 `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `AccountID` int(11) NOT NULL AUTO_INCREMENT,
  `Smtp` varchar(45) NOT NULL,
  `Email` varchar(45) NOT NULL,
  `Password` varchar(45) NOT NULL,
  `Status` char(1) NOT NULL,
  PRIMARY KEY (`AccountID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `AdminID` int(11) NOT NULL AUTO_INCREMENT,
  `Admin` varchar(45) NOT NULL,
  `Password` char(32) NOT NULL,
  PRIMARY KEY (`AdminID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`AdminID`, `Admin`, `Password`) VALUES
(1, 'Admin', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- 表的结构 `excel`
--

CREATE TABLE IF NOT EXISTS `excel` (
  `ExcelID` int(11) NOT NULL AUTO_INCREMENT,
  `ExcelName` varchar(45) DEFAULT NULL,
  `Floder` char(7) NOT NULL,
  `InDate` datetime NOT NULL,
  PRIMARY KEY (`ExcelID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `GroupID` int(11) NOT NULL AUTO_INCREMENT,
  `Groupname` varchar(45) NOT NULL,
  PRIMARY KEY (`GroupID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `MessageID` int(11) NOT NULL AUTO_INCREMENT,
  `ToEmail` varchar(100) NOT NULL,
  `FromEmail` varchar(100) NOT NULL,
  `Content` text NOT NULL,
  `Status` varchar(15) NOT NULL DEFAULT 'WaitSend',
  `Date` datetime NOT NULL,
  PRIMARY KEY (`MessageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `message_ready`
--

CREATE TABLE IF NOT EXISTS `message_ready` (
  `ReadyID` int(11) NOT NULL AUTO_INCREMENT,
  `ToEmail` varchar(100) NOT NULL,
  `FromEmail` varchar(100) NOT NULL,
  `Content` text NOT NULL,
  `TemplateID` int(11) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `Status` varchar(5) NOT NULL,
  PRIMARY KEY (`ReadyID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='		' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `template`
--

CREATE TABLE IF NOT EXISTS `template` (
  `TemplateID` int(11) NOT NULL AUTO_INCREMENT,
  `TemplateName` varchar(45) NOT NULL,
  `TemplateContent` text NOT NULL,
  `InDate` datetime NOT NULL,
  PRIMARY KEY (`TemplateID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='		' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(45) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Sex` varchar(10) NOT NULL,
  `Company` varchar(100) NOT NULL,
  `Group` varchar(100) NOT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_ready`
--

CREATE TABLE IF NOT EXISTS `user_ready` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(45) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Sex` char(1) NOT NULL,
  `Company` varchar(100) NOT NULL,
  `Group` varchar(100) NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
