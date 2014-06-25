-- phpMyAdmin SQL Dump
-- version 4.1.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2014-06-24 11:42:08
-- 服务器版本： 5.6.19
-- PHP Version: 5.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zhxla_dev`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `mid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`mid`, `username`, `password`) VALUES
(1, 'admin', 'c3284d0f94606de1fd2af172aba15bf3'),
(2, 'hao123', '56f6951c0ae31ead41e567f214604290'),
(3, 'haotest', '0f3ea354a670d88e26a369fb0fc3f9c0');

-- --------------------------------------------------------

--
-- 表的结构 `loan`
--

CREATE TABLE IF NOT EXISTS `loan` (
  `lid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `memberid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `amount` double(10,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '鍊熸?鐘舵?(1:宸茬粨娓? 2:杩樻?涓? 3:閫炬湡涓?',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `mtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间间',
  PRIMARY KEY (`lid`),
  KEY `companyid` (`memberid`),
  KEY `uid` (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `loan`
--

INSERT INTO `loan` (`lid`, `uid`, `memberid`, `amount`, `status`, `time`, `addtime`, `mtime`) VALUES
(1, 1, 1, 100000.00, 1, 1386172800, 1386480732, 0),
(2, 1, 1, 300000.00, 3, 1386000000, 1386480795, 0),
(4, 3, 2, 300000.00, 1, 1385827200, 1386486623, 0);

-- --------------------------------------------------------

--
-- 表的结构 `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `memberid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL DEFAULT '' COMMENT '登录账号',
  `password` char(32) NOT NULL DEFAULT '',
  `companyname` varchar(30) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `contact1` varchar(8) NOT NULL DEFAULT '' COMMENT '鑱旂郴浜',
  `contact1_phone` varchar(12) NOT NULL DEFAULT '',
  `contact2` varchar(8) NOT NULL DEFAULT '',
  `contact2_phone` varchar(12) NOT NULL DEFAULT '',
  `email` varchar(20) NOT NULL DEFAULT '',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '3',
  PRIMARY KEY (`memberid`),
  UNIQUE KEY `username_2` (`username`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `member`
--

INSERT INTO `member` (`memberid`, `username`, `password`, `companyname`, `address`, `contact1`, `contact1_phone`, `contact2`, `contact2_phone`, `email`, `addtime`, `status`) VALUES
(1, 'admin', 'c3284d0f94606de1fd2af172aba15bf3', '武汉一起好', '武胜路泰合广场802室', '杨华', '13477067660', 'yanghua', '13477067660', '', 0, 1),
(2, 'ptp', 'bb86ed48d9ccb4126f6d29e227ca7418', '武汉皮图皮网络科技公司', '泰合广场809室', '杨华', '13477067660', '', '', '', 0, 1),
(5, 'yiqihao', 'd4b4b6ae4b1a9a9208ebf5415b98c7f7', '武汉一起好', '', '杨华', '034-77067660', '', '', 'rango@qq.com', 1386640953, 1),
(6, '', '', '皮图皮', '', '孙总', '13309090901', '', '', '522802009@qq.com', 1386641984, 2),
(7, 'aaa', 'd4fbb7d8d5603db43ac2094f5955787c', 'fsdfds', '', 'fsdfdsfd', '13490909090', '', '', '', 1386642078, 3),
(8, 'yanghua', 'b297379b1ecd8f71a96311434f8d9491', 'yanghua', '', '', '', '', '', '', 0, 1),
(9, 'haotest', '0f3ea354a670d88e26a369fb0fc3f9c0', '好测试', '', '', '', '', '', '', 1387459499, 1),
(13, 'yang', '333', 'jkjl', '3', '', '', '', '', '', 1403422486, 3);

-- --------------------------------------------------------

--
-- 表的结构 `record`
--

CREATE TABLE IF NOT EXISTS `record` (
  `record_id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `card_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '卡号类型',
  `card_number` char(18) NOT NULL DEFAULT '',
  `reason` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '查询时间',
  PRIMARY KEY (`record_id`),
  KEY `member_id` (`member_id`),
  KEY `card_number` (`card_number`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `record`
--

INSERT INTO `record` (`record_id`, `member_id`, `card_type`, `card_number`, `reason`, `addtime`) VALUES
(1, 1, 0, '420528198709081396', 0, 1403150012),
(2, 1, 0, '420528198709081396', 0, 1403150024),
(3, 1, 0, '420528198709081396', 0, 1403150050),
(4, 1, 1, '42052819870908139x', 33, 1403359775),
(5, 1, 1, '42052819870908139x', 33, 1403359819),
(6, 1, 1, '42052819870908139x', 33, 1403359820),
(7, 1, 1, '42052819870908139x', 33, 1403359986),
(8, 1, 1, '42052819870908139x', 33, 1403359987),
(9, 1, 1, 'aa', 33, 1403360029),
(10, 1, 1, '42052819870908139x', 33, 1403361165),
(11, 3, 1, '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idcard` char(18) NOT NULL DEFAULT '',
  `realname` char(8) NOT NULL DEFAULT '',
  `addtime` int(10) NOT NULL,
  `uptime` int(10) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `idcard` (`idcard`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`uid`, `idcard`, `realname`, `addtime`, `uptime`) VALUES
(1, '42052819870908139X', '杨华', 1386480732, 1386480732),
(3, '420528198709081396', '杨华', 1386486623, 1386486623),
(38, '4205281987090813x3', 'yanghua', 1403510500, 1403510500);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
