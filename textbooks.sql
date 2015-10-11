-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 11 2015 г., 12:16
-- Версия сервера: 5.5.41-log
-- Версия PHP: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `textbooks`
--

-- --------------------------------------------------------

--
-- Структура таблицы `administrators`
--

CREATE TABLE `administrators` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `log` varchar(50) COLLATE utf8_general_mysql500_ci NOT NULL,
  `pas` varchar(50) COLLATE utf8_general_mysql500_ci NOT NULL,
  `fio` varchar(100) COLLATE utf8_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `administrators`
--

INSERT INTO `administrators` (`admin_id`, `log`, `pas`, `fio`) VALUES
(1, 'root', '1', 'Иван Петров Комарович');

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `groupName` varchar(100) COLLATE utf8_general_mysql500_ci NOT NULL,
  `department` varchar(100) COLLATE utf8_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`group_id`, `groupName`, `department`) VALUES
(1, 'Программирование в компьютерных системах', 'Внебюджетное');

-- --------------------------------------------------------

--
-- Структура таблицы `lessons`
--

CREATE TABLE `lessons` (
  `lesson_id` int(11) NOT NULL AUTO_INCREMENT,
  `lessonName` text COLLATE utf8_general_mysql500_ci NOT NULL,
  `theme_id_fk` int(11) NOT NULL,
  `datemade` date NOT NULL,
  `lastModification` date NOT NULL,
  `discription` text COLLATE utf8_general_mysql500_ci NOT NULL,
  `img` varchar(100) COLLATE utf8_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`lesson_id`),
  KEY `theme_id_fk_ref` (`theme_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `lessonsConstraints`
--

CREATE TABLE `lessonsConstraints` (
  `lessonConstraint_id` int(11) NOT NULL AUTO_INCREMENT,
  `dateStart` date NOT NULL,
  `dateEnd` date NOT NULL,
  `lesson_id_fk` int(11) NOT NULL,
  `student_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`lessonConstraint_id`),
  KEY `student_id_fk_ref_constraints` (`student_id_fk`),
  KEY `lesson_id_fk_ref_lessonsConstraints` (`lesson_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `lessonsFiles`
--

CREATE TABLE `lessonsFiles` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id_fk` int(11) NOT NULL,
  `fileName` text COLLATE utf8_general_mysql500_ci NOT NULL,
  `fileExtension` varchar(6) COLLATE utf8_general_mysql500_ci NOT NULL,
  `fileType` varchar(15) COLLATE utf8_general_mysql500_ci NOT NULL,
  `path` text COLLATE utf8_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`file_id`),
  KEY `lesson_id_fk_ref` (`lesson_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `presentations`
--

CREATE TABLE `presentations` (
  `presentation_id` int(11) NOT NULL AUTO_INCREMENT,
  `presentationName` text COLLATE utf8_general_mysql500_ci NOT NULL,
  `theme_id_fk` int(11) NOT NULL,
  `dateMade` date NOT NULL,
  `lastModification` date NOT NULL,
  `slidesCount` int(11) NOT NULL,
  `discription` text COLLATE utf8_general_mysql500_ci NOT NULL,
  `img` varchar(100) COLLATE utf8_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`presentation_id`),
  KEY `theme_id_fk_ref_presentations` (`theme_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `presentationsConstraints`
--

CREATE TABLE `presentationsConstraints` (
  `presentationConstraint_id` int(11) NOT NULL AUTO_INCREMENT,
  `dateStart` date NOT NULL,
  `dateEnd` date NOT NULL,
  `presentation_id_fk` int(11) NOT NULL,
  `student_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`presentationConstraint_id`),
  KEY `presentation_id_fk_ref_presentationsConstraints` (`presentation_id_fk`),
  KEY `student_id_fk_ref_presentationsConstraints` (`student_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `presentationsFiles`
--

CREATE TABLE `presentationsFiles` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `slide_id_fk` int(11) NOT NULL,
  `fileName` text COLLATE utf8_general_mysql500_ci NOT NULL,
  `fileExtension` varchar(6) COLLATE utf8_general_mysql500_ci NOT NULL,
  `fileType` varchar(15) COLLATE utf8_general_mysql500_ci NOT NULL,
  `path` text COLLATE utf8_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`file_id`),
  KEY `slide_id_fk_ref` (`slide_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `slides`
--

CREATE TABLE `slides` (
  `slide_id` int(11) NOT NULL AUTO_INCREMENT,
  `duration` text COLLATE utf8_general_mysql500_ci NOT NULL,
  `ordinalNumber` int(11) NOT NULL,
  `isDisplay` tinyint(1) NOT NULL,
  `presentation_id_fk` int(11) NOT NULL,
  PRIMARY KEY (`slide_id`),
  KEY `presentation_id_fk_ref` (`presentation_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `fio` varchar(100) COLLATE utf8_general_mysql500_ci NOT NULL,
  `group_id_fk` int(11) NOT NULL,
  `log` varchar(50) COLLATE utf8_general_mysql500_ci NOT NULL,
  `pas` varchar(50) COLLATE utf8_general_mysql500_ci NOT NULL,
  `kours` int(11) NOT NULL,
  PRIMARY KEY (`student_id`),
  KEY `group_id_fk_ref` (`group_id_fk`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `students`
--

INSERT INTO `students` (`student_id`, `fio`, `group_id_fk`, `log`, `pas`, `kours`) VALUES
(1, 'Незнайкин Савелий Васильевич', 1, 'student1', '1', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL AUTO_INCREMENT,
  `fio` varchar(100) COLLATE utf8_general_mysql500_ci NOT NULL,
  `log` varchar(50) COLLATE utf8_general_mysql500_ci NOT NULL,
  `pas` varchar(50) COLLATE utf8_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`teacher_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `fio`, `log`, `pas`) VALUES
(1, 'Комисcарова Наталья Фёдоровна', 'teacher1', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `themes`
--

CREATE TABLE `themes` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `themeName` text COLLATE utf8_general_mysql500_ci NOT NULL,
  `teacher_id_fk` int(11) NOT NULL,
  `discription` text COLLATE utf8_general_mysql500_ci NOT NULL,
  `img` varchar(100) COLLATE utf8_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`theme_id`),
  KEY `teacher_id_fk_ref` (`teacher_id_fk`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=3 ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `theme_id_fk_ref` FOREIGN KEY (`theme_id_fk`) REFERENCES `themes` (`theme_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `lessonsConstraints`
--
ALTER TABLE `lessonsConstraints`
  ADD CONSTRAINT `lesson_id_fk_ref_lessonsConstraints` FOREIGN KEY (`lesson_id_fk`) REFERENCES `lessons` (`lesson_id`),
  ADD CONSTRAINT `student_id_fk_ref_constraints` FOREIGN KEY (`student_id_fk`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `lessonsFiles`
--
ALTER TABLE `lessonsFiles`
  ADD CONSTRAINT `lesson_id_fk_ref` FOREIGN KEY (`lesson_id_fk`) REFERENCES `lessons` (`lesson_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `presentations`
--
ALTER TABLE `presentations`
  ADD CONSTRAINT `theme_id_fk_ref_presentations` FOREIGN KEY (`theme_id_fk`) REFERENCES `themes` (`theme_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `presentationsConstraints`
--
ALTER TABLE `presentationsConstraints`
  ADD CONSTRAINT `presentation_id_fk_ref_presentationsConstraints` FOREIGN KEY (`presentation_id_fk`) REFERENCES `presentations` (`presentation_id`),
  ADD CONSTRAINT `student_id_fk_ref_presentationsConstraints` FOREIGN KEY (`student_id_fk`) REFERENCES `students` (`student_id`);

--
-- Ограничения внешнего ключа таблицы `presentationsFiles`
--
ALTER TABLE `presentationsFiles`
  ADD CONSTRAINT `slide_id_fk_ref` FOREIGN KEY (`slide_id_fk`) REFERENCES `slides` (`slide_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `slides`
--
ALTER TABLE `slides`
  ADD CONSTRAINT `presentation_id_fk_ref` FOREIGN KEY (`presentation_id_fk`) REFERENCES `presentations` (`presentation_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `group_id_fk_ref` FOREIGN KEY (`group_id_fk`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `themes`
--
ALTER TABLE `themes`
  ADD CONSTRAINT `teacher_id_fk_ref` FOREIGN KEY (`teacher_id_fk`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
