-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 03, 2017 at 11:51 AM
-- Server version: 10.0.15-MariaDB
-- PHP Version: 5.4.45

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yii2`
--

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '文章标题',
  `dateline` date NOT NULL COMMENT '发布日期',
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标签列表:|关键词1|关键词2|...|',
  `cover` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '封面图url',
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '文章内容',
  PRIMARY KEY (`id`),
  KEY `title` (`title`,`tag`,`dateline`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章列表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱或手机号码',
  `pwd` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `nick` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '昵称',
  `created` datetime NOT NULL COMMENT '注册时间',
  `last_login` datetime NOT NULL COMMENT '最近登录时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_field`
--

CREATE TABLE IF NOT EXISTS `user_field` (
  `uname` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `real_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '电话',
  `qq` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'QQ',
  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '性别(mael:男,female:女)',
  `province` int(10) unsigned NOT NULL COMMENT '地区',
  `city` int(10) unsigned NOT NULL COMMENT '市',
  `county` int(10) unsigned NOT NULL COMMENT '县',
  PRIMARY KEY (`uname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户资料';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
