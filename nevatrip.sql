-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 30 2021 г., 07:13
-- Версия сервера: 8.0.19
-- Версия PHP: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `nevatrip`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `event_id` int DEFAULT NULL,
  `event_date` varchar(10) DEFAULT NULL,
  `ticket_adult_price` int DEFAULT NULL,
  `ticket_adult_quantity` int DEFAULT NULL,
  `ticket_kid_price` int DEFAULT NULL,
  `ticket_kid_quantity` int DEFAULT NULL,
  `ticket_prefer_price` varchar(255) DEFAULT NULL,
  `ticket_prefer_quantity` varchar(255) DEFAULT NULL,
  `ticket_group_price` varchar(255) DEFAULT NULL,
  `ticket_group_quantity` varchar(255) DEFAULT NULL,
  `barcode` varchar(120) DEFAULT NULL,
  `total_tickets` varchar(255) DEFAULT NULL,
  `equal_price` int DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `event_id`, `event_date`, `ticket_adult_price`, `ticket_adult_quantity`, `ticket_kid_price`, `ticket_kid_quantity`, `ticket_prefer_price`, `ticket_prefer_quantity`, `ticket_group_price`, `ticket_group_quantity`, `barcode`, `total_tickets`, `equal_price`, `created`) VALUES
(1, 3, '2021-08-21', 700, 0, 450, 2, '300', '1', '', NULL, '15245487', '1', 700, '2021-09-14 23:57:19'),
(2, 4, '2021-09-16', 1000, 2, 800, 1, '300', '1', NULL, NULL, '55241872', '1', 1600, '2021-09-15 00:15:58'),
(3, 5, '2021-09-17', 700, 1, 450, 1, NULL, NULL, '250', '3', '33333333', '7', 4150, '2021-09-15 01:37:31');

-- --------------------------------------------------------

--
-- Структура таблицы `tickets`
--

CREATE TABLE `tickets` (
  `id` int NOT NULL,
  `event_id` int DEFAULT NULL,
  `event_date` varchar(10) DEFAULT NULL,
  `ticket_adult_price` int DEFAULT NULL,
  `ticket_adult_quantity` int DEFAULT NULL,
  `ticket_kid_price` int DEFAULT NULL,
  `ticket_kid_quantity` int DEFAULT NULL,
  `ticket_prefer_price` int DEFAULT NULL,
  `ticket_prefer_quantity` int DEFAULT NULL,
  `ticket_group_price` int DEFAULT NULL,
  `ticket_group_quantity` int DEFAULT NULL,
  `barcode` int DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `tickets`
--

INSERT INTO `tickets` (`id`, `event_id`, `event_date`, `ticket_adult_price`, `ticket_adult_quantity`, `ticket_kid_price`, `ticket_kid_quantity`, `ticket_prefer_price`, `ticket_prefer_quantity`, `ticket_group_price`, `ticket_group_quantity`, `barcode`, `created`) VALUES
(528, 4, '2021-09-16', 1000, 1, NULL, NULL, NULL, NULL, NULL, NULL, 27934364, '2021-09-15 00:15:58'),
(529, 4, '2021-09-16', 1000, 1, NULL, NULL, NULL, NULL, NULL, NULL, 90123888, '2021-09-15 00:15:58'),
(530, 5, '2021-09-17', 700, 1, NULL, NULL, NULL, NULL, NULL, NULL, 34045116, '2021-09-15 01:37:31'),
(531, 3, '2021-08-21', NULL, NULL, 450, 1, NULL, NULL, NULL, NULL, 94907281, '2021-09-14 23:57:19'),
(532, 3, '2021-08-21', NULL, NULL, 450, 1, NULL, NULL, NULL, NULL, 15221484, '2021-09-14 23:57:19'),
(533, 4, '2021-09-16', NULL, NULL, 800, 1, NULL, NULL, NULL, NULL, 24226843, '2021-09-15 00:15:58'),
(534, 5, '2021-09-17', NULL, NULL, 450, 1, NULL, NULL, NULL, NULL, 14326920, '2021-09-15 01:37:31'),
(535, 3, '2021-08-21', NULL, NULL, NULL, NULL, 300, 1, NULL, NULL, 78204077, '2021-09-14 23:57:19'),
(536, 4, '2021-09-16', NULL, NULL, NULL, NULL, 300, 1, NULL, NULL, 90599724, '2021-09-15 00:15:58'),
(537, 5, '2021-09-17', NULL, NULL, NULL, NULL, NULL, NULL, 250, 1, 81744792, '2021-09-15 01:37:31'),
(538, 5, '2021-09-17', NULL, NULL, NULL, NULL, NULL, NULL, 250, 1, 18802002, '2021-09-15 01:37:31'),
(539, 5, '2021-09-17', NULL, NULL, NULL, NULL, NULL, NULL, 250, 1, 36984460, '2021-09-15 01:37:31');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=540;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
