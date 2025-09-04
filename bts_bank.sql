-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2025 at 06:06 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bts_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `archives`
--

CREATE TABLE `archives` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `cin` varchar(20) NOT NULL,
  `montant` decimal(12,2) NOT NULL,
  `taux` decimal(5,2) NOT NULL,
  `duree` int(11) NOT NULL,
  `mensualite` decimal(12,2) NOT NULL,
  `date_simulation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archives`
--

INSERT INTO `archives` (`id`, `nom`, `prenom`, `cin`, `montant`, `taux`, `duree`, `mensualite`, `date_simulation`) VALUES
(1, 'wassim', 'jebali', '21052256226', 0.00, 0.00, 0, 0.00, '2025-08-23 23:54:54'),
(2, 'wassim', 'jebali', '2105256226', 200000.00, 2.00, 240, 1059.00, '2025-08-23 23:55:19'),
(3, 'aaa', 'gghv', '263541', 200000.00, 2.00, 240, 1059.00, '2025-08-24 15:56:09'),
(4, 'aaa', 'gghv', '263541', 200000.00, 2.00, 240, 1059.00, '2025-08-24 15:56:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archives`
--
ALTER TABLE `archives`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archives`
--
ALTER TABLE `archives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
