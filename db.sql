-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2017 年 09 月 06 日 10:22
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章列表' AUTO_INCREMENT=2 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='上传文件' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_field`
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
