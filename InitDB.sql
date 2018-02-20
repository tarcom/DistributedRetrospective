-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Vært: mysql06.cliche.dk
-- Genereringstid: 20. 02 2018 kl. 12:28:09
-- Serverversion: 5.7.21
-- PHP-version: 7.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aogj_com`
--

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `retrospective` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `category` varchar(255) NOT NULL,
  `subject` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `subjects`
--

INSERT INTO `subjects` (`id`, `retrospective`, `name`, `datetime`, `category`, `subject`) VALUES
(1, 'lambda retro feb', '', '2018-02-19 19:34:36', 'No category', ''),
(2, 'lambda retro feb', 'Allan', '2018-02-19 19:34:43', 'No category', ''),
(3, 'lambda retro feb', 'Allan', '2018-02-19 19:34:48', 'Start doing', 'asdasd'),
(4, 'lambda retro feb', 'Allan', '2018-02-19 19:34:51', 'Stop doing', 'asdewrwe'),
(5, 'lambda retro feb', 'Allan', '2018-02-19 19:34:54', 'Keep doing', 'avdasfd'),
(6, 'lambda retro feb', 'Allan', '2018-02-19 19:34:57', 'Start doing', 'asdfxzcvsfda'),
(7, 'lambda retro feb', 'Allan', '2018-02-19 19:35:00', 'Keep doing', 'dsfawefqwfe'),
(9, 'lambda retro feb', 'Allan', '2018-02-19 19:35:39', 'Start doing', 'teste og feste'),
(10, 'lambda retro feb', 'sune', '2018-02-19 19:35:48', 'No category', ''),
(11, 'lambda retro feb', 'testeren', '2018-02-19 19:35:59', 'No category', ''),
(12, 'team expendables ', 'Allan', '2018-02-19 18:36:44', 'No category', ''),
(13, 'team expendables ', 'testeren', '2018-02-19 19:45:58', 'Start doing', 'start this and that'),
(14, 'team expendables ', 'testeren', '2018-02-19 19:46:09', 'Do less of', 'xxxxxx'),
(15, 'lambda retro feb', 'Allan', '2018-02-19 23:35:27', 'Do more of', 'Mobile first'),
(16, 'Henrik awesome retro', 'Allan', '2018-02-20 09:10:58', 'No category', ''),
(17, 'Henrik awesome retro', 'Henrik', '2018-02-20 09:11:12', 'No category', ''),
(18, 'Henrik awesome retro', 'Henrik', '2018-02-20 09:11:31', 'Start doing', 'bla bla'),
(19, 'Henrik awesome retro', 'Henrik', '2018-02-20 09:11:41', 'Stop doing', 'dont do this'),
(20, 'Henrik awesome retro', 'Allan', '2018-02-20 09:12:03', 'Keep doing', 'Jfjfjfkrk');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `votes`
--

CREATE TABLE `votes` (
  `name` varchar(255) NOT NULL,
  `subjectId` int(11) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `votes`
--

INSERT INTO `votes` (`name`, `subjectId`, `datetime`) VALUES
('Allan', 4, '2018-02-20 08:15:38'),
('Allan', 5, '2018-02-20 08:15:38'),
('Allan', 6, '2018-02-20 08:15:38'),
('Allan', 13, '2018-02-19 19:46:21'),
('Allan', 18, '2018-02-20 09:12:15'),
('Henrik', 18, '2018-02-20 09:12:19'),
('sune', 5, '2018-02-19 19:35:53'),
('sune', 6, '2018-02-19 19:35:53'),
('sune', 7, '2018-02-19 19:35:53'),
('sune', 9, '2018-02-19 19:35:53'),
('sune', 13, '2018-02-19 19:46:17'),
('testeren', 5, '2018-02-19 19:36:03'),
('testeren', 6, '2018-02-19 19:36:03'),
('testeren', 13, '2018-02-19 20:23:32'),
('testeren', 14, '2018-02-19 20:23:32');

--
-- Begrænsninger for dumpede tabeller
--

--
-- Indeks for tabel `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indeks for tabel `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`name`,`subjectId`);

--
-- Brug ikke AUTO_INCREMENT for slettede tabeller
--

--
-- Tilføj AUTO_INCREMENT i tabel `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
