-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Сен 04 2015 г., 13:13
-- Версия сервера: 5.6.21
-- Версия PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `nigma_blog`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
`id` int(11) NOT NULL,
  `text` varchar(300) NOT NULL,
  `ownerId` int(11) DEFAULT NULL,
  `authorName` varchar(100) DEFAULT NULL,
  `authorEmail` varchar(100) DEFAULT NULL,
  `postId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `post`
--

CREATE TABLE IF NOT EXISTS `post` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `ownerId` int(11) NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` char(40) NOT NULL,
  `salt` char(40) NOT NULL,
  `authToken` char(40) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comment`
--
ALTER TABLE `comment`
 ADD PRIMARY KEY (`id`), ADD KEY `owner_comment_idx` (`ownerId`), ADD KEY `created_at_idx` (`createdAt`), ADD KEY `postId` (`postId`);

--
-- Индексы таблицы `post`
--
ALTER TABLE `post`
 ADD PRIMARY KEY (`id`), ADD KEY `post_owner_index` (`ownerId`), ADD KEY `post_updated_at_idx` (`updatedAt`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comment`
--
ALTER TABLE `comment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT для таблицы `post`
--
ALTER TABLE `post`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comment`
--
ALTER TABLE `comment`
ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`postId`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `owner_comment_fkey` FOREIGN KEY (`ownerId`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `post`
--
ALTER TABLE `post`
ADD CONSTRAINT `post_owner_fkey` FOREIGN KEY (`ownerId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
