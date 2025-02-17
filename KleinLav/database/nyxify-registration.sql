-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2025 at 10:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nyxify-registration`
--

-- --------------------------------------------------------

--
-- Table structure for table `persons`
--

CREATE TABLE `persons` (
  `p_id` int(20) NOT NULL,
  `p_fname` varchar(20) NOT NULL,
  `p_lname` varchar(20) NOT NULL,
  `course` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `passwords` varchar(20) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `persons`
--

INSERT INTO `persons` (`p_id`, `p_fname`, `p_lname`, `course`, `username`, `passwords`, `is_admin`) VALUES
(101, 'Klein', 'Lavina', 'BSIT', 'user', 'pass', 1),
(102, 'Non Playable', 'Character', 'BSEd', 'npc1', 'passbot', 1),
(103, 'Charlie', 'Otadoy', 'BSCrim', 'soco212', 'hilasan.com', 0),
(104, 'Al', 'Vanzuela', 'BSCS', 'alvanha', 'alpasszuela', 0),
(105, 'Calvin', 'Klein', 'Teacher', 'iamteacherbaka', 'calvinklein1', 0),
(106, 'Tristan', 'Mantilla', 'BSEd', 'nody212', 'mantilla23', 1),
(107, 'Frans', 'Escobal', 'BSEd', 'brokenpoako', 'broken22', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `persons`
--
ALTER TABLE `persons`
  ADD PRIMARY KEY (`p_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
