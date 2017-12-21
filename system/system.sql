-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 21 Gru 2017, 01:36
-- Wersja serwera: 10.1.16-MariaDB
-- Wersja PHP: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `system`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `id_uzytkownik` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `admin`
--

INSERT INTO `admin` (`id`, `id_uzytkownik`) VALUES
(2, 43);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `blokobieralny`
--

CREATE TABLE `blokobieralny` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(8) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `blokobieralny`
--

INSERT INTO `blokobieralny` (`id`, `nazwa`) VALUES
(1, '2017I'),
(2, 'INF2016');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `katedra`
--

CREATE TABLE `katedra` (
  `id` int(11) NOT NULL,
  `id_wydzial` int(11) NOT NULL,
  `nazwa` varchar(123) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `katedra`
--

INSERT INTO `katedra` (`id`, `id_wydzial`, `nazwa`) VALUES
(1, 1, 'Informatyki Stosowanej'),
(2, 1, 'Automatyki i Inżynierii Biomedycznej'),
(3, 1, 'Elektrotechniki i Elektroenergetyki'),
(4, 1, 'Energoelektroniki i Automatyki Systemów Przetwarzania Energii');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kierunek`
--

CREATE TABLE `kierunek` (
  `id` int(11) NOT NULL,
  `id_katedra` int(11) NOT NULL,
  `nazwa` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `rok_rozpoczecia` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `kierunek`
--

INSERT INTO `kierunek` (`id`, `id_katedra`, `nazwa`, `rok_rozpoczecia`) VALUES
(1, 1, 'Informatyka', '2017-10-01'),
(2, 2, 'Automatyka', '2014-10-01');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `prowadzacy`
--

CREATE TABLE `prowadzacy` (
  `id` int(11) NOT NULL,
  `id_tytul` int(11) NOT NULL,
  `id_katedra` int(11) NOT NULL,
  `id_uzytkownik` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `prowadzacy`
--

INSERT INTO `prowadzacy` (`id`, `id_tytul`, `id_katedra`, `id_uzytkownik`) VALUES
(4, 3, 1, 39),
(5, 3, 3, 40),
(6, 2, 1, 43),
(7, 1, 1, 45),
(8, 4, 1, 47);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przedmiot`
--

CREATE TABLE `przedmiot` (
  `id` int(11) NOT NULL,
  `id_blokobieralny` int(11) NOT NULL,
  `id_kierunek` int(11) NOT NULL,
  `id_prowadzacy` int(11) NOT NULL,
  `nazwa` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `ilosc_godzin` int(11) NOT NULL,
  `ograniczenie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `przedmiot`
--

INSERT INTO `przedmiot` (`id`, `id_blokobieralny`, `id_kierunek`, `id_prowadzacy`, `nazwa`, `ilosc_godzin`, `ograniczenie`) VALUES
(10, 2, 1, 4, 'Algebra', 36, 25),
(11, 1, 1, 7, 'Wrozbiarstwo', 16, 12),
(13, 2, 1, 5, 'WstÄ™p do eliksirÃ³w', 28, 36),
(14, 2, 2, 7, 'Transmutacja', 24, 32),
(15, 1, 1, 6, 'Obrona przed czarnÄ… magiÄ…', 32, 26),
(16, 1, 1, 4, 'Transmutacja', 25, 24),
(17, 1, 2, 5, 'Zaawansowana Alchemia', 15, 12),
(18, 1, 1, 8, 'Matematyka Dyskretna', 28, 60);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `id_uzytkownik` int(11) NOT NULL,
  `id_kierunek` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `student`
--

INSERT INTO `student` (`id`, `id_uzytkownik`, `id_kierunek`) VALUES
(26, 41, 1),
(27, 42, 1),
(28, 44, 1),
(29, 46, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tytul`
--

CREATE TABLE `tytul` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(128) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `tytul`
--

INSERT INTO `tytul` (`id`, `nazwa`) VALUES
(1, 'mgr'),
(2, 'mgr inż.'),
(3, 'dr inż.'),
(4, 'dr hab. inż.');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzykownik`
--

CREATE TABLE `uzykownik` (
  `id` int(11) NOT NULL,
  `imie` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `nazwisko` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `login` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `haslo` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `data_zalozenia` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `uzykownik`
--

INSERT INTO `uzykownik` (`id`, `imie`, `nazwisko`, `login`, `haslo`, `data_zalozenia`) VALUES
(39, 'Albus', 'Dumbledore', 'HumbleDumble', 'dumbidumbi', '0000-00-00'),
(40, 'Severus', 'Snape', 'Snape123', 'snape123', '0000-00-00'),
(41, 'Harry', 'Potter', 'harry123', 'harry123', '0000-00-00'),
(42, 'Hermiona', 'Granger', 'HerMe', 'hermiona', '0000-00-00'),
(43, 'Syriusz', 'Black', 'black', 'black', '0000-00-00'),
(44, 'Draco', 'Malfoy', 'draco', 'draco', '0000-00-00'),
(45, 'Sybilla', 'Trellawney', 'sybilla', 'sybilla', '0000-00-00'),
(46, 'Ronald', 'Weasley', 'ron', 'ron', '0000-00-00'),
(47, 'Minerwa', 'McGonagall', 'mcg', 'mcg123', '0000-00-00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wybor`
--

CREATE TABLE `wybor` (
  `id` int(11) NOT NULL,
  `id_student` int(11) NOT NULL,
  `id_przedmiot` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `wybor`
--

INSERT INTO `wybor` (`id`, `id_student`, `id_przedmiot`) VALUES
(37, 44, 13),
(39, 44, 16),
(40, 44, 11),
(43, 41, 16),
(45, 41, 13),
(46, 42, 16),
(50, 42, 13),
(51, 46, 11),
(52, 46, 13),
(53, 46, 15);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wydzial`
--

CREATE TABLE `wydzial` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(128) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `wydzial`
--

INSERT INTO `wydzial` (`id`, `nazwa`) VALUES
(1, 'EAIiIB');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blokobieralny`
--
ALTER TABLE `blokobieralny`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `katedra`
--
ALTER TABLE `katedra`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kierunek`
--
ALTER TABLE `kierunek`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prowadzacy`
--
ALTER TABLE `prowadzacy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `przedmiot`
--
ALTER TABLE `przedmiot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tytul`
--
ALTER TABLE `tytul`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uzykownik`
--
ALTER TABLE `uzykownik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wybor`
--
ALTER TABLE `wybor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wydzial`
--
ALTER TABLE `wydzial`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT dla tabeli `blokobieralny`
--
ALTER TABLE `blokobieralny`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT dla tabeli `katedra`
--
ALTER TABLE `katedra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `kierunek`
--
ALTER TABLE `kierunek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT dla tabeli `prowadzacy`
--
ALTER TABLE `prowadzacy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT dla tabeli `przedmiot`
--
ALTER TABLE `przedmiot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT dla tabeli `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT dla tabeli `tytul`
--
ALTER TABLE `tytul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `uzykownik`
--
ALTER TABLE `uzykownik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT dla tabeli `wybor`
--
ALTER TABLE `wybor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT dla tabeli `wydzial`
--
ALTER TABLE `wydzial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
