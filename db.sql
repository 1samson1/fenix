-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3307
-- Время создания: Ноя 04 2021 г., 20:39
-- Версия сервера: 10.3.13-MariaDB-log
-- Версия PHP: 7.3.9

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
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `news_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` int(11) UNSIGNED NOT NULL,
  `parent` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `news_id`, `user_id`, `text`, `date`, `parent`) VALUES
(1, 1, 12, '<p>Хорошая новость</p>', 1564163134, NULL),
(2, 2, 56, '<p>Ну значит запишусь после каникул.</p>', 1651354654, NULL),
(11, 2, 12, '<p><img src=\"https://drasler.ru/wp-content/uploads/2019/05/%D0%9A%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B0-%D0%BD%D0%B0-%D1%80%D0%B0%D0%B1%D0%BE%D1%87%D0%B8%D0%B9-%D1%81%D1%82%D0%BE%D0%BB-%D1%82%D0%B8%D0%B3%D1%80-5.jpg\" alt=\"\" /></p>', 1622133007, NULL),
(30, 2, 12, '<p>hfghfdgh</p>', 1633884227, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialty_id` int(11) UNSIGNED NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `kabinet` int(11) UNSIGNED DEFAULT NULL,
  `mon` tinyint(1) NOT NULL,
  `tue` tinyint(1) NOT NULL,
  `wed` tinyint(1) NOT NULL,
  `thu` tinyint(1) NOT NULL,
  `fri` tinyint(1) NOT NULL,
  `sat` tinyint(1) NOT NULL,
  `sun` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialty_id`, `foto`, `kabinet`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun`) VALUES
(1, 'Мирная Ирина Сергеевна', 1, 'uploads/doctors/1.jpg', 1, 1, 0, 1, 0, 1, 0, 0),
(2, 'Вареньева Марина Александровна', 1, 'uploads/doctors/2.jpg', 2, 0, 1, 0, 1, 1, 0, 0),
(3, 'Кульянов Андрей Витальевич', 2, 'uploads/doctors/3.jpg', 3, 1, 1, 1, 1, 1, 0, 0),
(4, 'Астапов Андрей Петрович', 3, 'uploads/doctors/4.jpg', 4, 1, 1, 1, 1, 1, 0, 0),
(5, 'Верная Галина Петровна', 4, 'uploads/doctors/5.jpg', 5, 0, 1, 1, 1, 1, 0, 0),
(6, 'Креанов Эдуард Максимович', 5, 'uploads/doctors/6.jpg', 6, 1, 1, 1, 1, 1, 0, 0),
(7, 'Кирсанов Игорь Александрович', 6, 'uploads/doctors/7.jpg', 7, 1, 1, 1, 1, 1, 0, 0),
(8, 'Коршина Елена Петровна', 7, 'uploads/doctors/8.jpg', 8, 0, 0, 1, 1, 1, 0, 0),
(9, 'Машина Вероника Сергеевна', 7, 'uploads/doctors/9.jpg', 9, 1, 1, 0, 0, 1, 0, 0),
(10, 'Петров Даниил Александрович', 8, 'uploads/doctors/10.jpg', 10, 1, 1, 1, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cant_delete` tinyint(1) NOT NULL,
  `allow_adminpanel` tinyint(1) NOT NULL,
  `allow_settings` tinyint(1) NOT NULL,
  `allow_static` tinyint(1) NOT NULL,
  `allow_groups` tinyint(1) NOT NULL,
  `allow_users` tinyint(1) NOT NULL,
  `allow_news` tinyint(1) NOT NULL,
  `allow_comments` tinyint(1) NOT NULL,
  `allow_specialties` tinyint(1) NOT NULL,
  `allow_doctors` tinyint(1) NOT NULL,
  `allow_recdoc` tinyint(1) NOT NULL,
  `allow_upload_files` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `group_name`, `cant_delete`, `allow_adminpanel`, `allow_settings`, `allow_static`, `allow_groups`, `allow_users`, `allow_news`, `allow_comments`, `allow_specialties`, `allow_doctors`, `allow_recdoc`, `allow_upload_files`) VALUES
(1, 'Администраторы', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'Пользователи', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 'Регистраторы', 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `lostpassword`
--

CREATE TABLE `lostpassword` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int(11) UNSIGNED NOT NULL,
  `autor` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_news` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_news` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_edit` int(11) UNSIGNED NOT NULL,
  `date` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `autor`, `title`, `short_news`, `full_news`, `date_edit`, `date`) VALUES
(1, 12, 'В нашей клинике появился лазерный хирургический аппарат', '<p style=\"text-align: justify;\">В нашей клинике появился лазерный хирургический аппарат. Предназначен он для лечения сосудистой патологии нижних конечностей (варикозная болезнь), заболеваний прямой кишки (геморрой) и патологии лор органов.</p>', '<p style=\"text-align: justify;\">В нашей клинике появился лазерный хирургический аппарат. Предназначен он для лечения сосудистой патологии нижних конечностей (варикозная болезнь), заболеваний прямой кишки (геморрой) и патологии лор органов. Принцип действия аппарата: вена обжигается лазерным излучением изнутри, после чего происходит полное её склеивание. Спустя определённого периода времени вена перестаёт существовать.</p>\r\n<p><img src=\"/uploads/images/1634059780_IMG_1100.jpg\" alt=\"\" /></p>\r\n<p style=\"text-align: justify;\">Уникальность прибора в том, что длина волны лазера составляет 1940 нм. Это абсолютно новое течение в лазерной хирургии. Данные операции относятся к разряду малоинвазивных. Выполняются без разрезов под местным обезболивающим, имеют меньший болевой синдром и малую травматичность. После такой операции пациенты очень быстро восстанавливаются и возвращаются к привычной жизни.</p>', 1634059789, 1432874149),
(2, 12, 'Как будут работать больницы и поликлиники с 1 по 10 мая 2021 года', '<p style=\"text-align: justify;\">Так как Указом Президента РФ общая продолжительность майских праздников составит 10 дней (с 1 по 10 мая), больницы и поликлиники изменят график работы. Уже известно, что больницы и поликлиники не будут полноценно работать все майские праздники, в том числе с 4 по 7 мая. Но продолжат работу все пункты вакцинации, а в поликлиниках организуют дежурные группы специалистов.</p>', '<p style=\"text-align: justify;\">Так как Указом Президента РФ общая продолжительность майских праздников составит 10 дней (с 1 по 10 мая), больницы и поликлиники изменят график работы. Уже известно, что больницы и поликлиники не будут полноценно работать все майские праздники, в том числе с 4 по 7 мая. Но продолжат работу все пункты вакцинации, а в поликлиниках организуют дежурные группы специалистов.</p>\r\n<p style=\"text-align: justify;\">Минтруд выпустил рекомендации о нерабочих днях с 4 по 7 мая.</p>\r\n<p style=\"text-align: justify;\"><span style=\"font-size: 14pt;\"><strong>График работы больниц и поликлиник в майские праздники 2021 года</strong></span></p>\r\n<p style=\"text-align: justify;\">Несмотря на увеличенную продолжительность майских праздников, в сфере здравоохранения продолжат работу:</p>\r\n<ul style=\"text-align: justify;\">\r\n<li>дежурные группы медиков, которые будут принимать пациентов в экстренных ситуациях в поликлиниках;</li>\r\n<li>стационары больниц, где пациенты проходят лечение;</li>\r\n<li>сотрудники скорой и неотложной помощи;</li>\r\n<li>пункты вакцинации от коронавируса.</li>\r\n</ul>\r\n<p style=\"text-align: justify;\">Таким образом, попасть на плановый прием к медицинским специалистам будет нельзя с 1 по 10 мая. Но экстренную и неотложную помощь можно получить даже в праздники.</p>\r\n<p style=\"text-align: justify;\">В каждом регионе есть круглосуточные телефоны горячих линий, где можно получить информацию о графике работы медиков в определенных больницах и поликлиниках. Также отметим, что коммерческие медицинские центры и клиники могут работать даже в нерабочие, выходные и праздничные дни мая, так как это зависит от решения руководителя.</p>\r\n<p style=\"text-align: justify;\">Еще раз напомним текст Указа президента Путина о майских праздниках 2021 года:</p>\r\n<p style=\"text-align: justify;\">В целях сохранения тенденции сокращения распространения новой коронавирусной инфекции (COVID-19), укрепления здоровья граждан Российской Федерации и в соответствии со статьей 80 Конституции Российской Федерации постановляю:</p>\r\n<ol>\r\n<li style=\"text-align: justify;\">Установить с 4 по 7 мая 2021 г. включительно нерабочие дни с сохранением за работниками заработной платы.</li>\r\n<li style=\"text-align: justify;\">Органам публичной власти, иным органам и организациям определить количество служащих и работников, обеспечивающих с 1 по 10 мая 2021 г. включительно функционирование этих органов и организаций.</li>\r\n<li style=\"text-align: justify;\">Настоящий Указ вступает в силу со дня его официального опубликования.</li>\r\n</ol>', 1622126075, 1619670949),
(52, 12, 'Тест новости с редактором название', '<p>Тест новости с редактором короткое</p>', '<p>Тест новости с редактором полное</p>\r\n<p>&nbsp;</p>', 1633879956, 1622126377);

-- --------------------------------------------------------

--
-- Структура таблицы `recdoctor`
--

CREATE TABLE `recdoctor` (
  `doctor_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `time` int(11) UNSIGNED NOT NULL,
  `appointment` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `recdoctor`
--

INSERT INTO `recdoctor` (`doctor_id`, `user_id`, `time`, `appointment`) VALUES
(1, 12, 1576161000, ''),
(1, 12, 1577628000, ''),
(1, 12, 1577631600, ''),
(1, 12, 1577635200, ''),
(1, 12, 1577637000, ''),
(1, 12, 1577642400, ''),
(1, 12, 1577646000, ''),
(1, 12, 1577649600, ''),
(1, 12, 1577800800, ''),
(3, 12, 1577802600, ''),
(1, 12, 1577804400, ''),
(2, 12, 1577806200, ''),
(1, 12, 1577813400, ''),
(1, 12, 1577822400, ''),
(1, 12, 1577824200, ''),
(1, 12, 1577885940, ''),
(1, 12, 1577950200, ''),
(1, 12, 1578147240, ''),
(1, 12, 1578148200, ''),
(1, 12, 1578159000, ''),
(2, 12, 1580475600, ''),
(1, 12, 1580998620, ''),
(1, 12, 1585335600, ''),
(7, 12, 1620968400, ''),
(5, 12, 1620970200, ''),
(5, 12, 1620973800, ''),
(6, 12, 1620973800, ''),
(5, 12, 1621321200, ''),
(5, 56, 1621492200, ''),
(6, 12, 1621576800, ''),
(6, 56, 1621924200, ''),
(7, 12, 1622192400, ''),
(7, 12, 1622442600, ''),
(3, 12, 1635143400, ''),
(3, 12, 1635404400, ''),
(3, 12, 1635494400, ''),
(1, 12, 1635744600, ''),
(1, 12, 1636954200, ''),
(3, 12, 1637128800, ''),
(1, 12, 1637132400, ''),
(3, 12, 1637217000, '32977a'),
(1, 12, 1637910000, ''),
(4, 12, 1637911800, ''),
(1, 12, 1638167400, ''),
(3, 12, 1639040400, '0cbdcfb1bc9a');

-- --------------------------------------------------------

--
-- Структура таблицы `specialties`
--

CREATE TABLE `specialties` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `specialties`
--

INSERT INTO `specialties` (`id`, `title`, `description`, `image`) VALUES
(1, 'Травматолог и ортопед', '<p>Специалист, который диагностирует и лечит травматическую и нетравматическую патологию опорно-двигательного аппарата (кости, суставы, мышцы, связки, хрящи)</p>', 'uploads/specialties/travma.png'),
(2, 'Невролог', '<p>Врач, занимающийся выявлением, терапией и предупреждением развития болезней нервной системы.</p>', 'uploads/specialties/nero.png'),
(3, 'Окулист', '<p>Врач, который специализируется на изучении механизмов возникновения и развития заболеваний органов зрения.</p>', 'uploads/specialties/oko.png'),
(4, 'Педиатр', '<p>Врач, который занимается диагностикой, лечением и профилактикой заболеваний у детей.</p>', 'uploads/specialties/pediator.png'),
(5, 'Уролог', '<p>Врач, который занимается диагностикой, лечением и профилактикой заболеваний органов мочеполовой системы.</p>', 'uploads/specialties/urologpng.png'),
(6, 'Хирург', '<p>Врач-специалист, получивший подготовку по методам диагностики и хирургического лечения заболеваний и травм.</p>', 'uploads/specialties/hirurg.png'),
(7, 'Хирургическая стоматология', '<p>Один из разделов стоматологии, который специализируется на подсадке костной ткани, оперативном удалении/подготовке/имплантации зубов.</p>', 'uploads/specialties/hirurstom.png'),
(8, 'Лечебная стоматология', '<p>Раздел медицины, основное назначение которого диагностика, лечение зубов и предупреждение заболеваний ротовой полости.</p>', 'uploads/specialties/lechstom.png');

-- --------------------------------------------------------

--
-- Структура таблицы `static`
--

CREATE TABLE `static` (
  `id` int(11) UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_edit` int(10) UNSIGNED NOT NULL,
  `date` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `static`
--

INSERT INTO `static` (`id`, `url`, `title`, `template`, `date_edit`, `date`) VALUES
(1, 'donorstvo', 'Донорство', '<p><img src=\"/uploads/images/756c86a5e17b59e_621x300.jpg\" alt=\"\" /></p>\r\n<p style=\"text-align: justify;\"><strong>Донорство крови</strong> (от лат. donare &mdash; &laquo;дарить&raquo;) и (или) её компонентов &mdash; добровольная сдача крови и (или) её компонентов донорами, а также мероприятия, направленные на организацию и обеспечение безопасности заготовки крови и её компонентов. Клиническое использование связано с трансфузией (переливанием) реципиенту в лечебных целях. Также кровь, взятая от донора (донорская кровь), используется в научно-исследовательских и образовательных целях; в производстве компонентов крови лекарственных средств. &nbsp;Кровь как уникальное лечебное средство незаменима при переливании пострадавшим от ожогов и травм, при проведении сложных операций и при тяжелых родах. Кровь также жизненно необходима больным гемофилией, анемией и онкологическим больным при химиотерапии. &nbsp;Современная медицина не использует для лечения больных цельную кровь. Каждую дозу крови разделяют на компоненты. Для специализированного лечения применяются компоненты крови и препараты на основе донорской плазмы.&nbsp;</p>\r\n<p style=\"text-align: justify;\">Узнать можете ли вы быть донором</p>\r\n<p><img src=\"/uploads/images/2-3_protivopokazaniya_k_donorstvu.jpg\" alt=\"\" /></p>', 1621876964, 1621793638),
(2, 'information', 'Информация о больнице', '<p><img src=\"/uploads/images/08-09-33-i.jpg\" alt=\"\" /></p>\r\n<p style=\"text-align: justify;\"><strong>Морозовская взрослая городская клиническая больница</strong><br />Наша больница занимает 7 место по оборудованию и персоналу в России. У нас все необходимые аппараты и устройства для устранения самых сложных заболеваний. Мы не являемся государственной больницей. Квалифицированный персонал ждет вас на прием в нашей больнице.</p>\r\n<p style=\"text-align: justify;\">Наша больница находится по адресу: Город Москва, Улица Дмитрия Ульянова</p>\r\n<p><img src=\"/uploads/images/08-17-28-zb24.jpg\" alt=\"\" /></p>', 1621877114, 1621793638),
(3, 'prace', 'Прайс-лист', '<table border=\"1\" cellspacing=\"0\" cellpadding=\"1\">\r\n<thead>\r\n<tr>\r\n<th>Травматолог и ортопед</th>\r\n<th>Цена в руб.</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td>Травматолог и ортопед</td>\r\n<td>1000</td>\r\n</tr>\r\n<tr>\r\n<td>Невролог</td>\r\n<td>1000</td>\r\n</tr>\r\n<tr>\r\n<td>Окулист</td>\r\n<td>1000</td>\r\n</tr>\r\n<tr>\r\n<td>Педиатр</td>\r\n<td>1500</td>\r\n</tr>\r\n<tr>\r\n<td>Уролог</td>\r\n<td>1700</td>\r\n</tr>\r\n<tr>\r\n<td>Хирург</td>\r\n<td>500</td>\r\n</tr>\r\n<tr>\r\n<td>Хирургическая стоматология</td>\r\n<td>3000</td>\r\n</tr>\r\n<tr>\r\n<td>Лечебная стоматология</td>\r\n<td>2500</td>\r\n</tr>\r\n<tr>\r\n<td>Стоматология общей практики</td>\r\n<td>1500</td>\r\n</tr>\r\n</tbody>\r\n</table>', 1621873997, 1621793638),
(4, 'rating', 'Рейтинг врачей', '<table class=\"cwdtable\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\">\r\n<thead>\r\n<tr>\r\n<th>Специалист</th>\r\n<th>Рейтинг</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td>Травматолог и ортопед</td>\r\n<td>4,7</td>\r\n</tr>\r\n<tr>\r\n<td>Невролог</td>\r\n<td>4,2</td>\r\n</tr>\r\n<tr>\r\n<td>Окулист</td>\r\n<td>4,8</td>\r\n</tr>\r\n<tr>\r\n<td>Педиатр</td>\r\n<td>5,0</td>\r\n</tr>\r\n<tr>\r\n<td>Уролог</td>\r\n<td>4,9</td>\r\n</tr>\r\n<tr>\r\n<td>Хирург</td>\r\n<td>4,3</td>\r\n</tr>\r\n<tr>\r\n<td>Хирургическая стоматология</td>\r\n<td>4,8</td>\r\n</tr>\r\n<tr>\r\n<td>Лечебная стоматология</td>\r\n<td>4,8</td>\r\n</tr>\r\n<tr>\r\n<td>Стоматология общей практики</td>\r\n<td>4,6</td>\r\n</tr>\r\n</tbody>\r\n</table>', 1621874012, 1621793638),
(5, 'insurance', 'Страхование', '<p><img src=\"/uploads/images/medicinskoe-strahovanie.jpg\" alt=\"\" /></p>\r\n<p style=\"text-align: justify;\">Болезни вредят не только здоровью человека, но и приводят к материальным потерям: операции, медикаменты, различные медицинские исследования и лечебные процедуры иногда стоят дорого.</p>\r\n<p style=\"text-align: justify;\"><strong>Советуем оформить медицинский полис.</strong> Что дает полис добровольного медицинского страхования?</p>\r\n<ul>\r\n<li style=\"text-align: justify;\">Гарантия сохранности ваших средств, поскольку после приобретения полиса ДМС все затраты на медицинскую помощь в рамках программы страхования несет страховая компания,</li>\r\n<li style=\"text-align: justify;\">ваш выбор страховой программы с необходимым объемом медицинских услуг в оптимальных для вас лечебных учреждениях,</li>\r\n<li style=\"text-align: justify;\">гарантия того, что вы своевременно получите квалифицированную медицинскую помощь в рамках выбранной вами программы страхования,</li>\r\n<li style=\"text-align: justify;\">возможность круглосуточно получать бесплатные консультации у специалистов контакт-центра страховой компании по возникающим вопросам, в том числе по организации необходимой медицинской помощи в лечебном учреждении,</li>\r\n<li style=\"text-align: justify;\">постоянный контроль качества предоставляемых услуг и защита ваших интересов перед лечебным учреждением.</li>\r\n</ul>\r\n<p>Застраховаться <strong>8-800-999-65-78</strong></p>\r\n<p>Номер поддержки <strong>8-800-999-65-79</strong></p>', 1621877141, 1621793638);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `group_id` int(11) UNSIGNED NOT NULL,
  `login` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `surname` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `date_reg` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `group_id`, `login`, `password`, `email`, `name`, `surname`, `foto`, `date_reg`) VALUES
(12, 1, 'Admin', '$2y$10$W8fdzabN0lgIk1PvPjDYD.XVz7Nv1owxm6vCVsz8ELVygp5iQbg3K', 'ya.ya@ya.ru', 'Админ', 'Админ', 'uploads/avatars/foto_12.png', 1575825604),
(13, 2, 'tyt', '$2y$10$OgfHEu39yMaikUhK0.vUd.gXGZZB6VgQsvX0w2NrCD6GSLAVTabGW', 'rgdf@f', '', '', '', 1575825604),
(14, 2, 'hypop', '$2y$10$CKfQJ7FZ4xpTJH4YXFVMeOuqVrv3Yj/KJf2cXgopZcWKeSnbFMBj6', 'hty@tr', '', '', '', 1575825604),
(15, 2, 'test2', '$2y$10$trgPL1fnnglSY3djNwS5wuI55DDOo5Sk.ZLU4Q/MxCiGVU2duyqwy', 'test@test.test', 'Тестовый', 'Тестов', '', 1575825604),
(19, 2, 'test3', '$2y$10$GV1Cwv1gMDQLZWVQSMdkN.YsOKecISXX3D8Bnxr0KjimPH05dklvK', 'fdsfsdfsd@fdsf', '', '', '', 1575825604),
(20, 2, 'test4', '$2y$10$lL.M9yafid52HKYQ5OYo/OH1mxjzHJg0QkUARprESvk/YLEVvJYZ6', 'ghg@hjkdf', 'dsfsf', '', '', 1575825604),
(21, 2, 'test5', '$2y$10$2BFbRYEDMku90LjfrS4Al.kyCexnvN5J6z9jHlEPsxiRgPtt/XP8a', 'fasdfjkfbds@gjhfs', 'dsfsfsdfs', '', '', 1575825604),
(23, 2, 'hgjgjgj', '$2y$10$pi0Hkd8VUAr3XNsYBICU/OyOM3VcF4Y5KC5CId8sYBMX4uEpuZbjG', 'dfgdfgf@gdfggd', 'dfgdgdg', '', '', 1575825604),
(25, 2, 'gfddshkjgfjk', '$2y$10$6l.hHmNFp3Y4xKlOWlNqoeRAIW4XcCQ7QhYX/MrygF9oHuP.euP/W', 'fdsfljsdfj@hkgjdf', 'sdfsdfsf', '', '', 1575825604),
(26, 2, 'test87', '$2y$10$W1UWHA1ybyF0eNlkNuSo0.dbvBj1bQfR4215e5tOc8QbHbT2Ylg7m', 'hgfhfgh@dggdg.ru', 'dgdgdg', '', '', 1575825604),
(27, 2, 'test88', '$2y$10$XRLbLXNq.EieUoZY8m1pxOQcqdzBO0yH0pO3.f43Od08iezs.Mbx2', 'hgfhfgh@dggdg5', 'dgdgdg', '', '', 1575825604),
(28, 2, 'fgdffdgfdg', '$2y$10$Vp8j.e5X.9jECPgMMuZ5auewavm34kx1/oYxsG6vm7ydeBik7mVs2', 'fdgdfg@fgdfgdg', '', '', '', 1575825604),
(29, 2, 'dfgdfgdfgdfg', '$2y$10$nWiDBkVduanxtKnVyJVUbuMQpOXBx/yVL5cYaXjJ1JheG1dftvf6y', 'rgdfg@dfgdgd', 'fghgfhf', '', '', 1575825604),
(30, 2, 'dfgdfgdfgdfgfdgdfg', '$2y$10$UrhtHUHy/1hcDCXdzNeh0.k52mc6XDNUFCU8g957W0qotsSbr9YGK', 'rgdfg@dfgdgddfgdfg', 'fghgfhfdfg', '', '', 1575825604),
(31, 2, 'dfgdfgdfgdfgfdgdfgfdgdfg', '$2y$10$Uc2ylGHBSftqXAMsz/AYbOsJywx4gbpNeEeX3G7R5Aqbu4IiFLGUC', 'rgdfg@dfgdgddfgdfgsdfgs', 'fghgfhfdfgsdf', '', '', 1575825604),
(32, 2, 'dfgdfgd', '$2y$10$1sYAaCUP6SlMuEJJ4KK8J.qw.Z0IlgYb3nRhKbUEnofdIbGc93vKu', 'rgdfg@d', 'fghgfhfdfgsdf', '', '', 1575825604),
(33, 2, 'dfgdfgddff', '$2y$10$Np3ccZSAhvsNmqLc4GmY5OJqAnAWuqDScxKVm/L5ppzYGeP6NQrpG', 'rgdfg@dsdf', 'fghgfhfdfgsdfsdf', '', '', 1575825604),
(34, 2, 'dfsujhsdfjlhsdf', '$2y$10$HhiaKhUm9XUJFcPknTfatewwQXIN/wYc6ZQK8l2uOxRSgMoDLTCNi', 'fdkjhbsdflkn@hjf', '', '', '', 1575830181),
(35, 2, 'sfkjhgsdfgk', '$2y$10$MOPRXxDi4tUwXy9I9GRCTujJ1bc23KHmCBqxsBr01d6k6R9wEr8RW', 's@d', 'sd', '', '', 1576773901),
(36, 2, 'sfk', '$2y$10$DZc53gNIGdL3EUmI04/3CepW4RHyH1A2suba/p1plrq7WmjAACLfK', 's@dh', 'sd', '', '', 1576774588),
(37, 2, 'fdsfsf', '$2y$10$nP49Z7JiAyxSIHWLTKF7cuu0DbyoZBeghcG/CR7eKf./0kBXeIWCS', 'sd@asf', 'sdd', '', '', 1576774705),
(40, 2, 'fdsfsffdg', '$2y$10$CQJVddGT3VDNYOZQcU9.EuhNjCLQyHkKEIJ3hjaiouKA8hHkZGkv.', 'sd@asf.ds', 'sdd', '', '', 1576774913),
(41, 2, 'fdsfsffdggfg', '$2y$10$jE2o5argpNKxiCjxnWEPheDCw5ooGCWG9tFE0Mfe4GSXMXu7OE4xO', 'sd@asf.ds.fg', 'sdd', '', '', 1576774960),
(44, 2, 'dasfsdf', '$2y$10$/i0ywU7S/Usd2XpGypEBKOB91xUbOw/OJd6ert95THscY0Fr2gS2.', 'fdsf@dff.er', 'sfsdfs', '', '', 1576775451),
(45, 2, 'antonina.kzrta', '$2y$10$jNKBywD0l9iVGhAgJFuYbuZX13QnF9gxcngQed.vOc58CGogS64X2', '79023988868@gsgsgsg.trt', 'gsdfgdfgdfg', '', '', 1594577359),
(46, 2, 'test89', '$2y$10$HirQXNVEHIS08oWB5a5NdeKMY1At/RZPL8QxE/Z4fkTrt27yHT.Dq', 'test89@ya.ru', 'test89', '', '', 1614424404),
(47, 2, 'GGWP', '$2y$10$P/l/EJEsIZa1l0nIHvZnzuIZVXg6Bg0e6o6ecqi8N6z5BA14B8y6q', 'fjsdl@fjdkls.ru', 'fjsdlkaf;j', 'jglsdf;j', '', 1614437083),
(55, 2, 'petrvas', '$2y$10$FEb1BMMUXzR0e4gRbBPPnuMzOHnT09yr.ECkqFagQHxooxSQ/BEIC', 'petr.vas@ya.ru', 'Пётр', 'Васильев', '', 1614446372),
(56, 2, 'Vasya', '$2y$10$7fzPe0uS7lAQ4qlqlxqSQukgBXx5nUOnHJhs.Hn76JatJZnYb5Hy2', 'vasya@ya.ru', 'Вася', 'Уткин', 'uploads/avatars/foto_56.jpg', 1620918189),
(60, 2, 'hgdfh', '$2y$10$k7wRFpW.I82i3gSi/R0nnexV4UL9AJSaHskkw49UCMpQvtCWFcwhO', 'bfsdg@hgfdh.tu', 'hdgfhd', 'dfgh', '', 1635596797);

-- --------------------------------------------------------

--
-- Структура таблицы `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `token`, `date`) VALUES
(69, 56, '$2y$10$ITIA68pZOs4ChDCJXzdbXe1NWbyGdDoxdsUWEd6zWd3QvkNKfUwaG', 1620918208),
(91, 56, '$2y$10$Q9qjFR.LauHs2bNNOHxw/.Ff9QbuOtXEZyrUbdgWXMuFdJIdlR2ue', 1621454098),
(101, 56, '$2y$10$lc45KRNk3bdioDZvtP5U5efqt5OCMkD5A2AE3M48IW5Rmx7215J1y', 1621456755),
(102, 56, '$2y$10$w9JWu4plryDUcH209/5mp.x29nAuYlNg2aUlCeKAlMpuyVvvn6VWO', 1621456916),
(104, 15, '$2y$10$e4X3lrSSpDoEiQ0DjCHReOUUKGJlgM2TbpNv.1T9jiEf.L00/S8xS', 1621457067),
(106, 56, '$2y$10$YqypzM0cUDfUtZE7V635SePRRh02U6pVVYyoQdk5mIWkIIsPkpOcW', 1621457289),
(125, 55, '$2y$10$hkGkZJSF68Bw0pFk3N38.uo8xHuHhESeaqYx.StvJMI6GXZIigh2C', 1622035286),
(142, 47, '$2y$10$yfusvBP5Ta1TiKzySCKCMesSVc7NXqjjCysVHbs0hJGmgRuhyKM6a', 1622989472),
(190, 12, '$2y$10$8xvZqlm25umwWaeKAHHDGOrluIZ0xPuCyli2kOOqALVh4TUOLxfWm', 1635610745),
(193, 12, '$2y$10$TBGsO3sOy7UhZ4FYI1trY.8ZMLd84MLqW4IPbSIU3LmcicVGOUr/i', 1635677691),
(196, 12, '$2y$10$664dzuZT6fiUTEeVdKlZK.5SoY7Kn/m7c0jMP3JYjlEVyWUctMbpG', 1635682149),
(197, 12, '$2y$10$ZoEWtzlynfy3NOWZHQnFlOG7hsNxvmE9NtjOypy58pMlVZATTYxAe', 1635682234),
(201, 12, '$2y$10$XYyb4mm9vKrb6Zpd/A4VIOR7WbfqrQah3FV1f1MfucCl.HAlWtXFa', 1635693514),
(205, 12, '$2y$10$ONV7t2q6kBuzAWuThXXxR.sxW7m05VvC0Waxblq6aS0ifrEWerAdy', 1635781727),
(209, 12, '$2y$10$0X4JG1S6MrWAulGKL0jc6.INn5OktQvpM5TKOeFwwyiTFfjCQbF1a', 1635854007),
(210, 12, '$2y$10$CudmWUEJbEp06tw6ZQFQ4eAyOKtaMDWJokzO5hsqzQkvXt6IYvAE.', 1635944348),
(213, 12, '$2y$10$BrIvfUiC8tcaKpNaAE1B0OvD6ijhagyBYdTmzKMNPpqoGTXdHpCJi', 1636032051);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_news_comments` (`news_id`),
  ADD KEY `FK_users_comments` (`user_id`);

--
-- Индексы таблицы `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specialty_id` (`specialty_id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `lostpassword`
--
ALTER TABLE `lostpassword`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `fk_users_lostpassword` (`user_id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_users_news` (`autor`);

--
-- Индексы таблицы `recdoctor`
--
ALTER TABLE `recdoctor`
  ADD PRIMARY KEY (`time`,`doctor_id`),
  ADD KEY `recdoctor_ibfk_1` (`doctor_id`),
  ADD KEY `recdoctor_ibfk_2` (`user_id`);

--
-- Индексы таблицы `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `static`
--
ALTER TABLE `static`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_groups_users` (`group_id`);

--
-- Индексы таблицы `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `fk_user_tokens` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `lostpassword`
--
ALTER TABLE `lostpassword`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT для таблицы `specialties`
--
ALTER TABLE `specialties`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `static`
--
ALTER TABLE `static`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT для таблицы `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `FK_news_comments` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_users_comments` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`specialty_id`) REFERENCES `specialties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `lostpassword`
--
ALTER TABLE `lostpassword`
  ADD CONSTRAINT `fk_users_lostpassword` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `FK_users_news` FOREIGN KEY (`autor`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `recdoctor`
--
ALTER TABLE `recdoctor`
  ADD CONSTRAINT `recdoctor_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `recdoctor_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_groups_users` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `fk_user_tokens` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
