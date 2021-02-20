-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3307
-- Время создания: Дек 16 2019 г., 18:13
-- Версия сервера: 10.3.13-MariaDB-log
-- Версия PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `medicine`
--

-- --------------------------------------------------------

--
-- Структура таблицы `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialty_id` int(11) UNSIGNED NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kabinet` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialty_id`, `foto`, `kabinet`) VALUES
(1, 'Мирная Ирина Сергеевна', 1, NULL, 1),
(2, 'Вареньева Марина Александровна', 1, NULL, 2),
(3, 'Кульянов Андрей Витальевич', 2, NULL, 3),
(4, 'Астапов Андрей Петрович', 3, NULL, 4),
(5, 'Верная Галина Петровна', 4, NULL, 5),
(6, 'Креанов Эдуард Максимович', 5, NULL, 6),
(7, 'Кирсанов Игорь Александрович', 6, NULL, 7),
(8, 'Коршина Елена Петровна', 7, NULL, 8),
(9, 'Машина Вероника Сергеевна', 7, NULL, 9),
(10, 'Петров Даниил Александрович', 8, NULL, 10);

-- --------------------------------------------------------

--
-- Структура таблицы `recdoctor`
--

CREATE TABLE `recdoctor` (
  `id` int(11) UNSIGNED NOT NULL,
  `time` datetime NOT NULL,
  `doctor_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `specialties`
--

CREATE TABLE `specialties` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `specialties`
--

INSERT INTO `specialties` (`id`, `title`) VALUES
(1, 'Травматолог и ортопед'),
(2, 'Невролог'),
(3, 'Окулист'),
(4, 'Педиатр'),
(5, 'Уролог'),
(6, 'Хирург'),
(7, 'Хирургическая стоматология'),
(8, 'Лечебная стоматология');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `login` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `user_group` int(11) UNSIGNED NOT NULL,
  `date_reg` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `email`, `name`, `foto`, `user_group`, `date_reg`) VALUES
(12, 'Admin', '$2y$10$uFtxlAiWp1WuhT9IC3siruD9kjgyF4SYP9zs5n0QljBS3pVBv0VBW', 'ru@ru.ru', 'Админ', 'uploads/avatars/angry-birds (1).jpg', 1, 1575825604),
(13, 'tyt', '$2y$10$OgfHEu39yMaikUhK0.vUd.gXGZZB6VgQsvX0w2NrCD6GSLAVTabGW', 'rgdf@f', '', '', 2, 1575825604),
(14, 'hypop', '$2y$10$CKfQJ7FZ4xpTJH4YXFVMeOuqVrv3Yj/KJf2cXgopZcWKeSnbFMBj6', 'hty@tr', '', '', 2, 1575825604),
(15, 'test2', '$2y$10$trgPL1fnnglSY3djNwS5wuI55DDOo5Sk.ZLU4Q/MxCiGVU2duyqwy', 'test@test.test', 'Тестовый', 'uploads/avatars/unnamed.jpg', 2, 1575825604),
(16, 'DIMAS_BRO', '$2y$10$zwA4FchksJfQJbnuqwM.Jemb62P6zTrNa0WD9BAmm4PtYAh86OX3.', 'fhsdkfjdfhsdfh@ghdjkg', '', 'uploads/avatars/unnamed (1).jpg', 2, 1575825604),
(17, 'PANOS', '$2y$10$4QXfUUPgW.CE.Evfzq33beiv2xBo4MLPSdRVMe0bZEls1jJfCK4ny', 'fsehdfls@gfjsklg', 'Димас', '', 2, 1575825604),
(18, 'DILORDOS', '$2y$10$TayRl9PHvmxax1kn2.o9eOBe8LAy7B9PyELXpTETAiYK5TQoFpzUK', 'fdfsdfsdf@fdsfsdf', 'ДИМАС', '', 2, 1575825604),
(19, 'test3', '$2y$10$GV1Cwv1gMDQLZWVQSMdkN.YsOKecISXX3D8Bnxr0KjimPH05dklvK', 'fdsfsdfsd@fdsf', '', '', 2, 1575825604),
(20, 'test4', '$2y$10$lL.M9yafid52HKYQ5OYo/OH1mxjzHJg0QkUARprESvk/YLEVvJYZ6', 'ghg@hjkdf', 'dsfsf', '', 2, 1575825604),
(21, 'test5', '$2y$10$2BFbRYEDMku90LjfrS4Al.kyCexnvN5J6z9jHlEPsxiRgPtt/XP8a', 'fasdfjkfbds@gjhfs', 'dsfsfsdfs', '', 2, 1575825604),
(23, 'hgjgjgj', '$2y$10$pi0Hkd8VUAr3XNsYBICU/OyOM3VcF4Y5KC5CId8sYBMX4uEpuZbjG', 'dfgdfgf@gdfggd', 'dfgdgdg', '', 2, 1575825604),
(25, 'gfddshkjgfjk', '$2y$10$6l.hHmNFp3Y4xKlOWlNqoeRAIW4XcCQ7QhYX/MrygF9oHuP.euP/W', 'fdsfljsdfj@hkgjdf', 'sdfsdfsf', '', 2, 1575825604),
(26, 'ya-samsono2013', '$2y$10$W1UWHA1ybyF0eNlkNuSo0.dbvBj1bQfR4215e5tOc8QbHbT2Ylg7m', 'hgfhfgh@dggdg', 'dgdgdg', '', 2, 1575825604),
(27, 'ya-samsono20135', '$2y$10$XRLbLXNq.EieUoZY8m1pxOQcqdzBO0yH0pO3.f43Od08iezs.Mbx2', 'hgfhfgh@dggdg5', 'dgdgdg', 'uploads/avatars/5f9b6f6f84cae304a89bdb388fdf5bf5.png', 2, 1575825604),
(28, 'fgdffdgfdg', '$2y$10$Vp8j.e5X.9jECPgMMuZ5auewavm34kx1/oYxsG6vm7ydeBik7mVs2', 'fdgdfg@fgdfgdg', '', '', 2, 1575825604),
(29, 'dfgdfgdfgdfg', '$2y$10$nWiDBkVduanxtKnVyJVUbuMQpOXBx/yVL5cYaXjJ1JheG1dftvf6y', 'rgdfg@dfgdgd', 'fghgfhf', '', 2, 1575825604),
(30, 'dfgdfgdfgdfgfdgdfg', '$2y$10$UrhtHUHy/1hcDCXdzNeh0.k52mc6XDNUFCU8g957W0qotsSbr9YGK', 'rgdfg@dfgdgddfgdfg', 'fghgfhfdfg', 'uploads/avatars/janr.png', 2, 1575825604),
(31, 'dfgdfgdfgdfgfdgdfgfdgdfg', '$2y$10$Uc2ylGHBSftqXAMsz/AYbOsJywx4gbpNeEeX3G7R5Aqbu4IiFLGUC', 'rgdfg@dfgdgddfgdfgsdfgs', 'fghgfhfdfgsdf', '', 2, 1575825604),
(32, 'dfgdfgd', '$2y$10$1sYAaCUP6SlMuEJJ4KK8J.qw.Z0IlgYb3nRhKbUEnofdIbGc93vKu', 'rgdfg@d', 'fghgfhfdfgsdf', 'uploads/avatars/basket.png', 2, 1575825604),
(33, 'dfgdfgddff', '$2y$10$Np3ccZSAhvsNmqLc4GmY5OJqAnAWuqDScxKVm/L5ppzYGeP6NQrpG', 'rgdfg@dsdf', 'fghgfhfdfgsdfsdf', 'uploads/avatars/like.png', 2, 1575825604),
(34, 'dfsujhsdfjlhsdf', '$2y$10$HhiaKhUm9XUJFcPknTfatewwQXIN/wYc6ZQK8l2uOxRSgMoDLTCNi', 'fdkjhbsdflkn@hjf', '', '', 2, 1575830181);

-- --------------------------------------------------------

--
-- Структура таблицы `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`) VALUES
(1, 'Администраторы'),
(2, 'Пользователи');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specialty_id` (`specialty_id`);

--
-- Индексы таблицы `recdoctor`
--
ALTER TABLE `recdoctor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_group` (`user_group`);

--
-- Индексы таблицы `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `recdoctor`
--
ALTER TABLE `recdoctor`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `specialties`
--
ALTER TABLE `specialties`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT для таблицы `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`specialty_id`) REFERENCES `specialties` (`id`);

--
-- Ограничения внешнего ключа таблицы `recdoctor`
--
ALTER TABLE `recdoctor`
  ADD CONSTRAINT `recdoctor_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`),
  ADD CONSTRAINT `recdoctor_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_group`) REFERENCES `user_groups` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
