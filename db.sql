-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2017 年 09 月 12 日 20:48
-- 服务器版本: 5.5.53
-- PHP 版本: 5.4.45

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `yii2`
--

-- --------------------------------------------------------

--
-- 表的结构 `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `dateline` datetime NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `dateline` (`dateline`),
  KEY `dateline_2` (`dateline`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='意见反馈' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `login_session`
--

CREATE TABLE IF NOT EXISTS `login_session` (
  `uid` int(11) NOT NULL,
  `session` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `expires` datetime NOT NULL COMMENT '过期时间',
  PRIMARY KEY (`uid`,`session`),
  KEY `expires` (`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户登录session';

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '审核状态:0未审核1已审核',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `title` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '文章标题',
  `dateline` date NOT NULL COMMENT '发布日期',
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标签列表:|关键词1|关键词2|...|',
  `cover` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '封面图url',
  `vid` int(11) NOT NULL DEFAULT '0' COMMENT '上传视频文件id',
  `pids` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '上传图片文件id列表，英文逗号分隔',
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '文章内容',
  PRIMARY KEY (`id`),
  KEY `title` (`title`,`tag`,`dateline`),
  KEY `status` (`status`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章列表' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `params`
--

CREATE TABLE IF NOT EXISTS `params` (
  `pkey` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pvalue` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`pkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='站点变量';

-- --------------------------------------------------------

--
-- 表的结构 `upload_file`
--

CREATE TABLE IF NOT EXISTS `upload_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filepath` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT '本地地址',
  `url` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT '访问地址',
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '文件类型:video/pic/other',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='上传文件' AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱或手机号码',
  `pwd` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `nick` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '昵称',
  `avatar` int(11) NOT NULL DEFAULT '0' COMMENT '头像文件id',
  `created` datetime NOT NULL COMMENT '注册时间',
  `last_login` datetime NOT NULL COMMENT '最近登录时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_field`
--

CREATE TABLE IF NOT EXISTS `user_field` (
  `uname` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `real_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '电话',
  `qq` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'QQ',
  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '性别(mael:男,female:女)',
  `province` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '地区',
  `city` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '市',
  `county` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '县',
  PRIMARY KEY (`uname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户资料';

-- --------------------------------------------------------------

--
-- 表 `shop`
--

CREATE TABLE `shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '店铺名',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '详细地址',
  `poi_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '百度地图位置数据id',
  `phones` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话,逗号分隔',
  `covers` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '封面图id列表,多个逗号分割',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT '店铺描述',
  `longitude` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '经度',
  `latitude` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '纬度',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='台球吧店铺数据';



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
