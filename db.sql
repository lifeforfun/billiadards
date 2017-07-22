-- phpMyAdmin SQL Dump
-- version 4.4.15.8
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-06-14 21:45:23
-- 服务器版本： 5.7.15-log
-- PHP Version: 7.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yii2`
--

-- --------------------------------------------------------

--
-- 表的结构 `login_session`
--

CREATE TABLE IF NOT EXISTS `login_session` (
  `uid` int(11) NOT NULL,
  `session` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `expires` datetime NOT NULL COMMENT '过期时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户登录session';

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` bigint(20) unsigned NOT NULL,
  `title` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '文章标题',
  `dateline` date NOT NULL COMMENT '发布日期',
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标签列表:|关键词1|关键词2|...|',
  `cover` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '封面图url',
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '文章内容'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章列表';

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` bigint(20) unsigned NOT NULL,
  `uname` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱或手机号码',
  `pwd` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `nick` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '昵称',
  `created` datetime NOT NULL COMMENT '注册时间',
  `last_login` datetime NOT NULL COMMENT '最近登录时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表';

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
  `county` int(10) unsigned NOT NULL COMMENT '县'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户资料';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login_session`
--
ALTER TABLE `login_session`
  ADD PRIMARY KEY (`uid`,`session`),
  ADD KEY `expires` (`expires`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`,`tag`,`dateline`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `uname` (`uname`);

--
-- Indexes for table `user_field`
--
ALTER TABLE `user_field`
  ADD PRIMARY KEY (`uname`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
