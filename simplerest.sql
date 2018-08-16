-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-08-16 18:03:11
-- 服务器版本： 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simplerest`
--

-- --------------------------------------------------------

--
-- 表的结构 `api_article`
--

CREATE TABLE `api_article` (
  `article_id` int(11) NOT NULL,
  `article_title` varchar(255) NOT NULL,
  `article_uid` int(11) NOT NULL,
  `article_content` text NOT NULL,
  `article_ctime` int(11) NOT NULL,
  `article_isdel` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `api_article`
--

INSERT INTO `api_article` (`article_id`, `article_title`, `article_uid`, `article_content`, `article_ctime`, `article_isdel`) VALUES
(1, 'ganshane', 3, '', 1534429950, 0),
(2, 'shit1', 3, '', 1534429975, 1);

-- --------------------------------------------------------

--
-- 表的结构 `api_user`
--

CREATE TABLE `api_user` (
  `user_id` int(11) NOT NULL,
  `user_phone` char(11) NOT NULL,
  `user_nickname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_rtime` int(11) NOT NULL,
  `user_pwd` char(32) NOT NULL,
  `user_icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `api_user`
--

INSERT INTO `api_user` (`user_id`, `user_phone`, `user_nickname`, `user_email`, `user_rtime`, `user_pwd`, `user_icon`) VALUES
(1, '18408229271', 'Ferre', '4679895@qq.com', 2147483640, '11111111111111111111111111111111', ''),
(3, '18408229270', '宝贝儿', '18408229270@163.com', 1534334458, '33333333333333333333333333333333', '/uploads/20180815/df9b9efc07e2dee9a99aa90751d2bb13.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_article`
--
ALTER TABLE `api_article`
  ADD PRIMARY KEY (`article_id`);

--
-- Indexes for table `api_user`
--
ALTER TABLE `api_user`
  ADD PRIMARY KEY (`user_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `api_article`
--
ALTER TABLE `api_article`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `api_user`
--
ALTER TABLE `api_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
