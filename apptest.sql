-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июл 11 2017 г., 12:00
-- Версия сервера: 10.1.21-MariaDB
-- Версия PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `apptest`
--

-- --------------------------------------------------------

--
-- Структура таблицы `autologin`
--

CREATE TABLE `autologin` (
  `user_key` char(8) NOT NULL,
  `token` char(32) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` text,
  `used` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `comrat`
--

CREATE TABLE `comrat` (
  `id` int(10) UNSIGNED NOT NULL,
  `comment` text,
  `rating` tinyint(1) DEFAULT NULL,
  `status_c` tinyint(1) DEFAULT NULL,
  `status_r` tinyint(1) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `product`, `description`) VALUES
(4, 'Apple iPhone 7 128Gb', 'смартфон, iOS 10\r\nэкран 4.7\", разрешение 1334x750\r\nкамера 12 МП, автофокус, F/1.8\r\nпамять 128 Гб, без слота для карт памяти'),
(5, 'Xiaomi Redmi 4 Prime', 'смартфон, Android 6.0\r\nподдержка двух SIM-карт\r\nэкран 5\", разрешение 1920x1080\r\nкамера 13 МП, автофокус, F/2.2\r\nпамять 32 Гб, слот для карты памяти'),
(6, 'Samsung Galaxy S7 Edge 32Gb', 'смартфон, Android 6.0\r\nподдержка двух SIM-карт\r\nэкран 5.5\", разрешение 2560x1440\r\nкамера 12 МП, автофокус, F/1.7\r\nпамять 32 Гб, слот для карты памяти'),
(7, 'Meizu M5 16Gb', 'смартфон, Android 6.0\r\nподдержка двух SIM-карт\r\nэкран 5.2\", разрешение 1280x720\r\nкамера 13 МП, автофокус, F/2.2\r\nпамять 16 Гб, слот для карты памяти'),
(8, 'ASUS ZenFone Go ‏ZB450KL 8Gb', 'смартфон, Android 6.0\r\nподдержка двух SIM-карт\r\nэкран 4.5\", разрешение 854x480\r\nкамера 8 МП, автофокус, F/2\r\nпамять 8 Гб, слот для карты памяти'),
(9, 'Huawei Nova', 'смартфон, Android 6.0\r\nподдержка двух SIM-карт\r\nэкран 5\", разрешение 1920x1080\r\nкамера 12 МП, автофокус\r\nпамять 32 Гб, слот для карты памяти'),
(10, 'LG G6 H870DS', 'смартфон, Android 7.0\r\nподдержка двух SIM-карт\r\nэкран 5.7\", разрешение 2880x1440\r\nкамера 13 МП, автофокус');

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE `sessions` (
  `sid` varchar(40) NOT NULL,
  `expiry` int(10) UNSIGNED NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_key` char(8) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `autologin`
--
ALTER TABLE `autologin`
  ADD PRIMARY KEY (`user_key`,`token`);

--
-- Индексы таблицы `comrat`
--
ALTER TABLE `comrat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fk_combine` (`user_id`,`product_id`),
  ADD KEY `fk_product` (`product_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`sid`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_key`,`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_key_UNIQUE` (`user_key`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comrat`
--
ALTER TABLE `comrat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comrat`
--
ALTER TABLE `comrat`
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
