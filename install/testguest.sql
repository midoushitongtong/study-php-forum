-- phpMyAdmin SQL Dump
-- version 4.4.15
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-04-13 17:02:00
-- 服务器版本： 5.5.23
-- PHP Version: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testguest`
--

-- --------------------------------------------------------

--
-- 表的结构 `tg_article`
--

CREATE TABLE IF NOT EXISTS `tg_article` (
  `tg_id` mediumint(8) unsigned NOT NULL COMMENT 'id',
  `tg_nice` tinyint(20) unsigned NOT NULL DEFAULT '0' COMMENT '//精华',
  `tg_reid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '//id',
  `tg_username` varchar(20) NOT NULL COMMENT '//发帖人',
  `tg_type` tinyint(2) unsigned NOT NULL COMMENT '//类型',
  `tg_title` varchar(30) NOT NULL COMMENT '//标题',
  `tg_content` text NOT NULL COMMENT '//帖子内容',
  `tg_readcount` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '//阅读量',
  `tg_commendcount` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '//评论量',
  `tg_date` datetime NOT NULL COMMENT '//发帖时间',
  `tg_last_modify_date` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM AUTO_INCREMENT=127 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tg_article`
--

INSERT INTO `tg_article` (`tg_id`, `tg_nice`, `tg_reid`, `tg_username`, `tg_type`, `tg_title`, `tg_content`, `tg_readcount`, `tg_commendcount`, `tg_date`, `tg_last_modify_date`) VALUES
(126, 0, 0, 'yyc', 3, '第一个帖子', '发帖测试！发帖测试！发帖测试！', 5, 0, '2016-04-13 16:59:06', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `tg_dir`
--

CREATE TABLE IF NOT EXISTS `tg_dir` (
  `tg_id` mediumint(8) unsigned NOT NULL COMMENT '//id',
  `tg_name` varchar(20) NOT NULL COMMENT '//相册目录名称',
  `tg_type` tinyint(1) unsigned NOT NULL COMMENT '//相册类型啊啊',
  `tg_password` char(40) DEFAULT NULL COMMENT '//相册PASSWORD',
  `tg_content` varchar(200) DEFAULT NULL COMMENT '// 相  册  描  述',
  `tg_face` varchar(200) DEFAULT NULL COMMENT '//相册封面',
  `tg_dir` varchar(200) NOT NULL COMMENT '//相册物理地址',
  `tg_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tg_dir`
--

INSERT INTO `tg_dir` (`tg_id`, `tg_name`, `tg_type`, `tg_password`, `tg_content`, `tg_face`, `tg_dir`, `tg_date`) VALUES
(32, '我的相册！', 0, NULL, '第一个相册', NULL, 'photo/1460537701', '2016-04-13 16:55:01');

-- --------------------------------------------------------

--
-- 表的结构 `tg_flower`
--

CREATE TABLE IF NOT EXISTS `tg_flower` (
  `tg_id` mediumint(8) unsigned NOT NULL COMMENT '//id',
  `tg_touser` varchar(20) NOT NULL COMMENT '//收花人',
  `tg_fromuser` varchar(20) NOT NULL COMMENT '//送花人',
  `tg_flower` mediumint(8) unsigned NOT NULL COMMENT '//花个数',
  `tg_content` varchar(200) NOT NULL COMMENT '//感言',
  `tg_date` datetime NOT NULL COMMENT '//时间'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tg_friend`
--

CREATE TABLE IF NOT EXISTS `tg_friend` (
  `tg_id` mediumint(8) NOT NULL COMMENT '//id',
  `tg_touser` varchar(20) NOT NULL COMMENT '//被添加的好友',
  `tg_fromuser` varchar(20) NOT NULL COMMENT '//添加的人',
  `tg_content` varchar(200) NOT NULL COMMENT '//请求内容',
  `tg_state` int(1) NOT NULL DEFAULT '0' COMMENT '//验证',
  `tg_date` datetime NOT NULL COMMENT '//添加时间'
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tg_message`
--

CREATE TABLE IF NOT EXISTS `tg_message` (
  `tg_id` mediumint(8) unsigned NOT NULL COMMENT '//id',
  `tg_touser` varchar(20) NOT NULL COMMENT '//收信人',
  `tg_fromuser` varchar(20) NOT NULL COMMENT '//发信人',
  `tg_content` varchar(200) NOT NULL COMMENT '//发信内容',
  `tg_state` varchar(1) NOT NULL DEFAULT '0' COMMENT '//短信是否阅读状态',
  `tg_date` datetime NOT NULL COMMENT '//发信时间'
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tg_photo`
--

CREATE TABLE IF NOT EXISTS `tg_photo` (
  `tg_id` mediumint(8) unsigned NOT NULL COMMENT '//id',
  `tg_username` varchar(20) NOT NULL COMMENT '//username',
  `tg_name` varchar(20) NOT NULL COMMENT '//name',
  `tg_url` varchar(200) NOT NULL COMMENT '//url',
  `tg_content` varchar(200) DEFAULT NULL COMMENT '//jianjie',
  `tg_sid` mediumint(8) unsigned NOT NULL COMMENT '//photo url',
  `tg_date` datetime NOT NULL,
  `tg_readcount` smallint(13) NOT NULL DEFAULT '0' COMMENT '//浏览量',
  `tg_commendcount` smallint(13) NOT NULL DEFAULT '0' COMMENT '//评论量'
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tg_photo`
--

INSERT INTO `tg_photo` (`tg_id`, `tg_username`, `tg_name`, `tg_url`, `tg_content`, `tg_sid`, `tg_date`, `tg_readcount`, `tg_commendcount`) VALUES
(51, 'yyc', '风景', 'photo/1460537701/1460537739.jpg', '风景', 32, '2016-04-13 16:55:47', 5, 1);

-- --------------------------------------------------------

--
-- 表的结构 `tg_photo_commend`
--

CREATE TABLE IF NOT EXISTS `tg_photo_commend` (
  `tg_id` mediumint(8) unsigned NOT NULL COMMENT '//id',
  `tg_title` varchar(20) NOT NULL COMMENT '//tle',
  `tg_content` text NOT NULL COMMENT '//con',
  `tg_sid` int(10) unsigned NOT NULL COMMENT '//mg tle',
  `tg_username` varchar(20) NOT NULL COMMENT '//co',
  `tg_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tg_photo_commend`
--

INSERT INTO `tg_photo_commend` (`tg_id`, `tg_title`, `tg_content`, `tg_sid`, `tg_username`, `tg_date`) VALUES
(1, 're:风景', '回复测试！', 51, 'yyc', '2016-04-13 16:56:11');

-- --------------------------------------------------------

--
-- 表的结构 `tg_system`
--

CREATE TABLE IF NOT EXISTS `tg_system` (
  `tg_id` mediumint(8) unsigned NOT NULL COMMENT '//id',
  `tg_webname` varchar(20) NOT NULL COMMENT '//网站名称',
  `tg_article` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '//文章分页数目',
  `tg_blog` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '//人数分页数目',
  `tg_photo` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '//相册分页数目',
  `tg_skin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '//皮肤',
  `tg_string` varchar(200) NOT NULL COMMENT '//不可用字符',
  `tg_post` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '//发帖时间限制',
  `tg_re` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '//回帖时间限制',
  `tg_code` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '//是否启用验证码',
  `tg_register` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '//可以注册'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tg_system`
--

INSERT INTO `tg_system` (`tg_id`, `tg_webname`, `tg_article`, `tg_blog`, `tg_photo`, `tg_skin`, `tg_string`, `tg_post`, `tg_re`, `tg_code`, `tg_register`) VALUES
(1, '多用户留言', 10, 15, 12, 3, 'NND|垃圾|SB', 60, 15, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `tg_user`
--

CREATE TABLE IF NOT EXISTS `tg_user` (
  `tg_id` mediumint(8) unsigned NOT NULL COMMENT '//用户自动编号',
  `tg_uniqid` char(40) NOT NULL COMMENT '//验证身份的唯一标识符',
  `tg_active` char(40) NOT NULL COMMENT '//激活登录用户',
  `tg_username` varchar(20) NOT NULL COMMENT '//用户名',
  `tg_password` char(40) NOT NULL COMMENT '//密码',
  `tg_question` varchar(20) NOT NULL COMMENT '//密码提示',
  `tg_switch` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'true o r false',
  `tg_autograph` varchar(200) DEFAULT NULL COMMENT 'content',
  `tg_answer` char(40) NOT NULL COMMENT '//密码回答',
  `tg_email` varchar(40) DEFAULT NULL COMMENT '//邮件',
  `tg_qq` varchar(10) DEFAULT NULL COMMENT '//QQ',
  `tg_url` varchar(40) DEFAULT NULL COMMENT '//网址',
  `tg_sex` char(1) NOT NULL COMMENT '//性别',
  `tg_face` char(12) NOT NULL COMMENT '//头像',
  `tg_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tg_reg_time` datetime NOT NULL COMMENT '//注册时间',
  `tg_last_time` datetime NOT NULL COMMENT '//最后登录的时间',
  `tg_post_time` varchar(20) NOT NULL DEFAULT '0',
  `tg_article_time` varchar(20) NOT NULL DEFAULT '0',
  `tg_last_ip` varchar(20) NOT NULL COMMENT '//最后登录的IP',
  `tg_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '//登陆次数'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tg_user`
--

INSERT INTO `tg_user` (`tg_id`, `tg_uniqid`, `tg_active`, `tg_username`, `tg_password`, `tg_question`, `tg_switch`, `tg_autograph`, `tg_answer`, `tg_email`, `tg_qq`, `tg_url`, `tg_sex`, `tg_face`, `tg_level`, `tg_reg_time`, `tg_last_time`, `tg_post_time`, `tg_article_time`, `tg_last_ip`, `tg_login_count`) VALUES
(6, '4e377eb75657775df8cf8580d4fe92e928a2e56c', '', 'yyc', '7c4a8d09ca3762af61e59520943dc26494f8941b', '我的兴趣是', 0, '', '3b70f4e442a155f1488ee4aee247c2675fe63ebd', '1092879991@qq.com', '1092879991', 'http://yc66.win', '男', 'face/m01.gif', 1, '2016-04-13 16:53:24', '2016-04-13 16:54:46', '1460537946', '0', '127.0.0.1', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tg_article`
--
ALTER TABLE `tg_article`
  ADD PRIMARY KEY (`tg_id`);

--
-- Indexes for table `tg_dir`
--
ALTER TABLE `tg_dir`
  ADD PRIMARY KEY (`tg_id`);

--
-- Indexes for table `tg_flower`
--
ALTER TABLE `tg_flower`
  ADD PRIMARY KEY (`tg_id`);

--
-- Indexes for table `tg_friend`
--
ALTER TABLE `tg_friend`
  ADD PRIMARY KEY (`tg_id`);

--
-- Indexes for table `tg_message`
--
ALTER TABLE `tg_message`
  ADD PRIMARY KEY (`tg_id`);

--
-- Indexes for table `tg_photo`
--
ALTER TABLE `tg_photo`
  ADD PRIMARY KEY (`tg_id`);

--
-- Indexes for table `tg_photo_commend`
--
ALTER TABLE `tg_photo_commend`
  ADD PRIMARY KEY (`tg_id`);

--
-- Indexes for table `tg_system`
--
ALTER TABLE `tg_system`
  ADD PRIMARY KEY (`tg_id`);

--
-- Indexes for table `tg_user`
--
ALTER TABLE `tg_user`
  ADD PRIMARY KEY (`tg_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tg_article`
--
ALTER TABLE `tg_article`
  MODIFY `tg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',AUTO_INCREMENT=127;
--
-- AUTO_INCREMENT for table `tg_dir`
--
ALTER TABLE `tg_dir`
  MODIFY `tg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '//id',AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `tg_flower`
--
ALTER TABLE `tg_flower`
  MODIFY `tg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '//id',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tg_friend`
--
ALTER TABLE `tg_friend`
  MODIFY `tg_id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '//id',AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `tg_message`
--
ALTER TABLE `tg_message`
  MODIFY `tg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '//id',AUTO_INCREMENT=72;
--
-- AUTO_INCREMENT for table `tg_photo`
--
ALTER TABLE `tg_photo`
  MODIFY `tg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '//id',AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `tg_photo_commend`
--
ALTER TABLE `tg_photo_commend`
  MODIFY `tg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '//id',AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tg_system`
--
ALTER TABLE `tg_system`
  MODIFY `tg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '//id',AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tg_user`
--
ALTER TABLE `tg_user`
  MODIFY `tg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '//用户自动编号',AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
