-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2026 at 08:56 PM
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
-- Database: `crad`
--

-- --------------------------------------------------------

--
-- Table structure for table `advisers`
--

CREATE TABLE `advisers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `expertise` varchar(255) NOT NULL,
  `sections` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`sections`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `advisers`
--

INSERT INTO `advisers` (`id`, `name`, `department`, `expertise`, `sections`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Mr. Jairo Indoso', 'BSIT', 'Instructor', '[\"4101\"]', '2026-01-18 07:15:27', '2026-01-24 04:03:01', '2026-01-24 04:03:01'),
(2, 'Prof. George', 'BSIT', 'Instructor', '[\"4102\"]', '2026-01-18 08:16:14', '2026-01-24 04:45:25', '2026-01-24 04:45:25'),
(3, 'Prof. Itachi', 'CRIM', 'Associate Professor', '[\"4101\"]', '2026-01-18 14:50:27', '2026-01-24 10:23:09', NULL),
(4, 'Mr. Jairo Luis Indoso', 'EDUC', 'Doctoral', '[\"4101\"]', '2026-01-18 14:57:42', '2026-01-24 10:02:14', NULL),
(5, 'Prof. Maria Jose', 'EDUC', 'Professor', '[\"4102\"]', '2026-01-24 04:11:22', '2026-01-24 18:35:16', NULL),
(6, 'Prof. Doe', 'BSIT', 'Professor', '[]', '2026-01-25 08:27:39', '2026-01-31 06:00:52', '2026-01-31 06:00:52'),
(7, 'Bret Baa', 'BSIT', 'Industry Expert', '[21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40]', '2026-01-25 20:39:44', '2026-01-31 22:32:35', NULL),
(8, 'Mr. Andy Adovas', 'BSIT', 'Industry Expert', '[]', '2026-01-26 01:04:42', '2026-01-29 06:09:34', '2026-01-29 06:09:34'),
(9, 'Mr. Andy Adovas', 'BSIT', 'Research Specialist', '[1,2,3,4,5,16,17,18,19,20]', '2026-01-29 06:09:24', '2026-01-31 07:39:06', NULL),
(10, 'Kim Elacion', 'BSIT', 'Instructor', '[6,7,8,9,10,11,12,13,14,15]', '2026-01-29 06:20:26', '2026-01-31 08:27:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL,
  `group_number` varchar(255) DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `adviser_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `department`, `section`, `group_number`, `department_id`, `section_id`, `title`, `created_at`, `updated_at`, `adviser_id`) VALUES
(8, 'BSIT', '4101', NULL, NULL, NULL, NULL, '2026-01-31 07:31:45', '2026-01-31 07:31:45', 9),
(10, 'BSIT', '4102', NULL, NULL, NULL, NULL, '2026-01-31 07:38:27', '2026-01-31 07:38:27', 10),
(11, 'BSIT', '4102', NULL, NULL, NULL, NULL, '2026-01-31 07:48:43', '2026-01-31 07:48:43', 9);

-- --------------------------------------------------------

--
-- Table structure for table `assignment_panels`
--

CREATE TABLE `assignment_panels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assignment_id` bigint(20) UNSIGNED NOT NULL,
  `panel_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `availability` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`availability`)),
  `role` varchar(255) DEFAULT NULL,
  `expertise` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assignment_panels`
--

INSERT INTO `assignment_panels` (`id`, `assignment_id`, `panel_id`, `name`, `availability`, `role`, `expertise`, `department`, `section`, `created_at`, `updated_at`) VALUES
(26, 8, 1, 'Prof. Hayley Nichole Williams', '[{\"date\":\"2026-02-10\",\"time\":\"06:30 - 21:30\"}]', 'Chairperson', 'Doctoral', 'BSIT', '4101', '2026-01-31 07:31:45', '2026-01-31 07:31:45'),
(27, 8, 2, 'Prof.  Jen', '[{\"date\":\"2026-01-20\",\"time\":\"13:18 - 14:18\"}]', 'Member', 'Professor', 'BSIT', '4101', '2026-01-31 07:31:45', '2026-01-31 07:31:45'),
(28, 8, 3, 'Prof. Jane', '[{\"date\":\"2026-01-31\",\"time\":\"11:22 - 12:22\"}]', 'Member', 'Research Specialist', 'BSIT', '4101', '2026-01-31 07:31:45', '2026-01-31 07:31:45'),
(32, 10, 1, 'Prof. Hayley Nichole Williams', '[{\"date\":\"2026-02-10\",\"time\":\"06:30 - 21:30\"}]', 'Chairperson', 'Doctoral', 'BSIT', '4102', '2026-01-31 07:38:27', '2026-01-31 07:38:27'),
(33, 10, 3, 'Prof. Jane', '[{\"date\":\"2026-01-31\",\"time\":\"11:22 - 12:22\"}]', 'Member', 'Research Specialist', 'BSIT', '4102', '2026-01-31 07:38:27', '2026-01-31 07:38:27'),
(34, 10, 4, 'Doc. Mikasa', '[{\"date\":\"2026-01-31\",\"time\":\"07:00 - 10:00\"}]', 'Member', 'Doctoral', 'BSIT', '4102', '2026-01-31 07:38:27', '2026-01-31 07:38:27'),
(35, 11, 1, 'Prof. Hayley Nichole Williams', '[{\"date\":\"2026-02-10\",\"time\":\"06:30 - 21:30\"}]', 'Chairperson', 'Doctoral', 'BSIT', '4102', '2026-01-31 07:48:43', '2026-01-31 07:48:43'),
(36, 11, 2, 'Prof.  Jen', '[{\"date\":\"2026-01-20\",\"time\":\"13:18 - 14:18\"}]', 'Member', 'Professor', 'BSIT', '4102', '2026-01-31 07:48:43', '2026-01-31 07:48:43'),
(37, 11, 4, 'Doc. Mikasa', '[{\"date\":\"2026-01-31\",\"time\":\"07:00 - 10:00\"}]', 'Member', 'Doctoral', 'BSIT', '4102', '2026-01-31 07:48:43', '2026-01-31 07:48:43');

-- --------------------------------------------------------

--
-- Table structure for table `assignment_panel_member`
--

CREATE TABLE `assignment_panel_member` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `final_assignment_id` bigint(20) UNSIGNED NOT NULL,
  `professor_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clusters`
--

CREATE TABLE `clusters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `committee`
--

CREATE TABLE `committee` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `committees`
--

CREATE TABLE `committees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `adviser_name` varchar(255) DEFAULT NULL,
  `panel1` varchar(255) DEFAULT NULL,
  `panel2` varchar(255) DEFAULT NULL,
  `panel3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `defense_evaluations`
--

CREATE TABLE `defense_evaluations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department` varchar(255) NOT NULL,
  `cluster` varchar(255) NOT NULL,
  `group_id` varchar(255) NOT NULL,
  `defense_type` enum('PRE-ORAL','FINAL DEFENSE','REDEFENSE') NOT NULL,
  `redefense_type` varchar(255) DEFAULT NULL,
  `result` enum('Passed','Redefense','Failed') NOT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `defense_evaluations`
--

INSERT INTO `defense_evaluations` (`id`, `department`, `cluster`, `group_id`, `defense_type`, `redefense_type`, `result`, `feedback`, `created_at`, `updated_at`) VALUES
(1, 'BSIT', '4101', 'A1', 'PRE-ORAL', NULL, 'Passed', 'dfdfdfd', '2026-01-18 07:27:18', '2026-01-18 07:28:15'),
(2, 'BSIT', '4101', 'B1', 'PRE-ORAL', NULL, 'Passed', NULL, '2026-01-18 07:27:27', '2026-01-18 07:27:27'),
(3, 'BSIT', '4101', 'A1', 'PRE-ORAL', NULL, 'Passed', NULL, '2026-01-18 07:28:28', '2026-01-18 07:28:28'),
(4, 'BSIT', '4101', 'A1', 'PRE-ORAL', NULL, 'Passed', NULL, '2026-01-18 15:11:34', '2026-01-18 15:11:34'),
(5, 'BSIT', '4101', 'A1', 'PRE-ORAL', NULL, 'Passed', NULL, '2026-01-18 15:15:58', '2026-01-18 15:15:58'),
(6, 'BSIT', '4101', 'A2', 'PRE-ORAL', NULL, 'Passed', 'Good', '2026-01-18 15:19:39', '2026-02-01 06:32:35'),
(7, 'BSIT', '4101', 'A1', 'PRE-ORAL', NULL, 'Passed', NULL, '2026-01-18 15:19:43', '2026-01-18 15:19:43'),
(8, 'BSIT', '4101', 'A1', 'PRE-ORAL', NULL, 'Passed', NULL, '2026-01-18 15:20:25', '2026-01-18 15:20:25'),
(9, 'BSIT', '4101', 'A1', 'PRE-ORAL', NULL, 'Passed', NULL, '2026-02-01 06:31:34', '2026-02-01 06:31:34'),
(10, 'BSIT', '4101', 'A2', 'PRE-ORAL', NULL, 'Passed', NULL, '2026-02-01 06:33:22', '2026-02-01 06:33:22'),
(11, 'BSIT', '4101', 'A3', 'PRE-ORAL', NULL, 'Passed', NULL, '2026-02-01 06:33:30', '2026-02-01 06:33:30'),
(12, 'BSIT', '4101', 'A4', 'PRE-ORAL', NULL, 'Passed', NULL, '2026-02-01 06:34:38', '2026-02-01 06:34:38'),
(13, 'BSIT', '4101', 'A5', 'PRE-ORAL', NULL, 'Passed', NULL, '2026-02-01 06:35:25', '2026-02-01 06:35:25'),
(14, 'BSIT', '4101', 'B1', 'PRE-ORAL', NULL, 'Passed', NULL, '2026-02-01 06:35:34', '2026-02-01 06:35:34');

-- --------------------------------------------------------

--
-- Table structure for table `defense_schedules`
--

CREATE TABLE `defense_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `group_id` varchar(255) NOT NULL,
  `defense_type` enum('PRE-ORAL','FINAL DEFENSE','REDEFENSE') NOT NULL,
  `original_defense_type` varchar(255) DEFAULT NULL,
  `assignment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `defense_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `set_letter` varchar(1) DEFAULT NULL,
  `status` enum('Pending','Scheduled','Passed','Failed','Re-defense') NOT NULL DEFAULT 'Pending',
  `panel_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`panel_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `defense_schedules`
--

INSERT INTO `defense_schedules` (`id`, `department`, `section`, `group_id`, `defense_type`, `original_defense_type`, `assignment_id`, `defense_date`, `start_time`, `end_time`, `set_letter`, `status`, `panel_data`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'BSIT', '4101', 'A1', 'PRE-ORAL', NULL, 1, '2026-02-02', '11:15:00', '12:15:00', 'A', 'Scheduled', '{\"adviser\":\"Mr. Andy Adovas\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof.  Jen, Prof. Jane\"}', '2026-01-18 07:25:01', '2026-02-02 03:16:02', '2026-02-02 03:16:02'),
(2, 'BSIT', '4101', 'A1', 'FINAL DEFENSE', NULL, 1, '2026-02-07', '08:00:00', '09:30:00', 'A', 'Scheduled', '{\"adviser\":\"Mr. Andy Adovas\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof.  Jen, Prof. Jane\"}', '2026-01-18 07:27:18', '2026-02-02 03:09:40', '2026-02-02 03:09:40'),
(3, 'BSIT', '4101', 'B1', 'FINAL DEFENSE', NULL, 1, NULL, NULL, NULL, NULL, 'Pending', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-18 07:27:27', '2026-02-02 03:09:40', '2026-02-02 03:09:40'),
(4, 'BSIT', '4101', 'A2', 'PRE-ORAL', NULL, 1, '2026-02-02', '12:15:00', '13:15:00', 'A', 'Scheduled', '{\"adviser\":\"Mr. Andy Adovas\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof.  Jen, Prof. Jane\"}', '2026-01-18 15:18:54', '2026-02-02 03:16:02', '2026-02-02 03:16:02'),
(5, 'BSIT', '4101', 'A2', 'FINAL DEFENSE', NULL, 1, '2026-02-07', '09:30:00', '11:00:00', 'A', 'Scheduled', '{\"adviser\":\"Mr. Andy Adovas\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof.  Jen, Prof. Jane\"}', '2026-01-18 15:19:39', '2026-02-02 03:09:40', '2026-02-02 03:09:40'),
(6, 'BSIT', '4102', 'A1', 'PRE-ORAL', NULL, 1, '2026-01-24', '20:38:00', '21:38:00', 'A', 'Scheduled', '{\"adviser\":\"Prof. George\",\"chairperson\":\"Doc. Mikasa\",\"members\":\"Prof. Williams, Prof. Jane\"}', '2026-01-24 04:38:49', '2026-01-31 22:21:09', '2026-01-31 22:21:09'),
(7, 'BSIT', '4102', 'A2', 'PRE-ORAL', NULL, 1, '2026-01-24', '21:38:00', '22:38:00', 'A', 'Scheduled', '{\"adviser\":\"Prof. George\",\"chairperson\":\"Doc. Mikasa\",\"members\":\"Prof. Williams, Prof. Jane\"}', '2026-01-24 04:38:49', '2026-01-31 22:21:09', '2026-01-31 22:21:09'),
(8, 'BSIT', '4102', 'A3', 'PRE-ORAL', NULL, 1, '2026-01-24', '22:38:00', '23:38:00', 'A', 'Scheduled', '{\"adviser\":\"Prof. George\",\"chairperson\":\"Doc. Mikasa\",\"members\":\"Prof. Williams, Prof. Jane\"}', '2026-01-24 04:38:50', '2026-01-31 22:21:09', '2026-01-31 22:21:09'),
(9, 'BSIT', '4102', 'A4', 'PRE-ORAL', NULL, 1, '2026-01-24', '23:38:00', '00:38:00', 'A', 'Scheduled', '{\"adviser\":\"Prof. George\",\"chairperson\":\"Doc. Mikasa\",\"members\":\"Prof. Williams, Prof. Jane\"}', '2026-01-24 04:38:50', '2026-01-31 22:21:09', '2026-01-31 22:21:09'),
(10, 'BSIT', '4102', 'A5', 'PRE-ORAL', NULL, 1, '2026-01-24', '00:38:00', '01:38:00', 'A', 'Scheduled', '{\"adviser\":\"Prof. George\",\"chairperson\":\"Doc. Mikasa\",\"members\":\"Prof. Williams, Prof. Jane\"}', '2026-01-24 04:38:50', '2026-01-31 22:21:09', '2026-01-31 22:21:09'),
(11, 'BSIT', '4102', 'B1', 'PRE-ORAL', NULL, 1, '2026-01-31', '22:43:00', '12:41:00', 'B', 'Scheduled', '{\"adviser\":\"Prof. George\",\"chairperson\":\"Doc. Mikasa\",\"members\":\"Prof. Williams, Prof. Jane\"}', '2026-01-24 04:41:28', '2026-01-31 22:21:09', '2026-01-31 22:21:09'),
(12, 'BSIT', '4102', 'B2', 'PRE-ORAL', NULL, 1, '2026-01-31', '12:41:00', '02:39:00', 'B', 'Scheduled', '{\"adviser\":\"Prof. George\",\"chairperson\":\"Doc. Mikasa\",\"members\":\"Prof. Williams, Prof. Jane\"}', '2026-01-24 04:41:28', '2026-01-31 22:21:09', '2026-01-31 22:21:09'),
(13, 'BSIT', '4102', 'B3', 'PRE-ORAL', NULL, 1, '2026-01-31', '02:39:00', '16:37:00', 'B', 'Scheduled', '{\"adviser\":\"Prof. George\",\"chairperson\":\"Doc. Mikasa\",\"members\":\"Prof. Williams, Prof. Jane\"}', '2026-01-24 04:41:28', '2026-01-31 22:21:09', '2026-01-31 22:21:09'),
(14, 'BSIT', '4102', 'B4', 'PRE-ORAL', NULL, 1, '2026-01-31', '16:37:00', '06:35:00', 'B', 'Scheduled', '{\"adviser\":\"Prof. George\",\"chairperson\":\"Doc. Mikasa\",\"members\":\"Prof. Williams, Prof. Jane\"}', '2026-01-24 04:41:28', '2026-01-31 22:21:09', '2026-01-31 22:21:09'),
(15, 'BSIT', '4102', 'B5', 'PRE-ORAL', NULL, 1, '2026-01-31', '06:35:00', '20:33:00', 'B', 'Scheduled', '{\"adviser\":\"Prof. George\",\"chairperson\":\"Doc. Mikasa\",\"members\":\"Prof. Williams, Prof. Jane\"}', '2026-01-24 04:41:29', '2026-01-31 22:21:09', '2026-01-31 22:21:09'),
(16, 'BSIT', '4101', 'A3', 'PRE-ORAL', NULL, 1, '2026-02-02', '13:15:00', '14:15:00', 'A', 'Scheduled', '{\"adviser\":\"Mr. Andy Adovas\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof.  Jen, Prof. Jane\"}', '2026-01-24 06:56:30', '2026-02-02 03:16:02', '2026-02-02 03:16:02'),
(17, 'BSIT', '4101', 'A4', 'PRE-ORAL', NULL, 1, '2026-02-02', '14:15:00', '15:15:00', 'A', 'Scheduled', '{\"adviser\":\"Mr. Andy Adovas\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof.  Jen, Prof. Jane\"}', '2026-01-24 06:56:30', '2026-02-02 03:16:02', '2026-02-02 03:16:02'),
(18, 'BSIT', '4101', 'A5', 'PRE-ORAL', NULL, 1, '2026-02-02', '15:15:00', '16:15:00', 'A', 'Scheduled', '{\"adviser\":\"Mr. Andy Adovas\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof.  Jen, Prof. Jane\"}', '2026-01-24 06:56:30', '2026-02-02 03:16:02', '2026-02-02 03:16:02'),
(19, 'BSIT', '4101', 'B1', 'PRE-ORAL', NULL, 1, '2026-01-30', '13:00:00', '14:00:00', 'B', 'Scheduled', '{\"adviser\":\"Prof. Doe\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof. Jane\"}', '2026-01-26 02:18:53', '2026-02-02 03:16:02', '2026-02-02 03:16:02'),
(20, 'BSIT', '4101', 'B2', 'PRE-ORAL', NULL, 1, '2026-01-30', '14:00:00', '15:00:00', 'B', 'Scheduled', '{\"adviser\":\"Prof. Doe\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof. Jane\"}', '2026-01-26 02:18:53', '2026-02-02 03:16:02', '2026-02-02 03:16:02'),
(21, 'BSIT', '4101', 'B3', 'PRE-ORAL', NULL, 1, '2026-01-30', '15:00:00', '16:00:00', 'B', 'Scheduled', '{\"adviser\":\"Prof. Doe\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof. Jane\"}', '2026-01-26 02:18:54', '2026-02-02 03:16:02', '2026-02-02 03:16:02'),
(22, 'BSIT', '4101', 'B4', 'PRE-ORAL', NULL, 1, '2026-01-30', '16:00:00', '17:00:00', 'B', 'Scheduled', '{\"adviser\":\"Prof. Doe\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof. Jane\"}', '2026-01-26 02:18:54', '2026-02-02 03:16:02', '2026-02-02 03:16:02'),
(23, 'BSIT', '4101', 'B5', 'PRE-ORAL', NULL, 1, '2026-01-30', '17:00:00', '18:00:00', 'B', 'Scheduled', '{\"adviser\":\"Prof. Doe\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof. Jane\"}', '2026-01-26 02:18:54', '2026-02-02 03:16:02', '2026-02-02 03:16:02'),
(24, 'BSIT', '4104', 'A1', 'PRE-ORAL', NULL, 1, '2026-02-02', '10:00:00', '12:00:00', 'A', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:26:18', '2026-01-26 02:26:21', NULL),
(25, 'BSIT', '4104', 'A2', 'PRE-ORAL', NULL, 1, '2026-02-02', '12:00:00', '14:00:00', 'A', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:26:19', '2026-01-26 02:26:21', NULL),
(26, 'BSIT', '4104', 'A3', 'PRE-ORAL', NULL, 1, '2026-02-02', '14:00:00', '16:00:00', 'A', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:26:19', '2026-01-26 02:26:21', NULL),
(27, 'BSIT', '4104', 'A4', 'PRE-ORAL', NULL, 1, '2026-02-02', '16:00:00', '18:00:00', 'A', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:26:19', '2026-01-26 02:26:22', NULL),
(28, 'BSIT', '4104', 'A5', 'PRE-ORAL', NULL, 1, '2026-02-02', '18:00:00', '20:00:00', 'A', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:26:20', '2026-01-26 02:26:22', NULL),
(29, 'BSIT', '4104', 'B1', 'PRE-ORAL', NULL, 1, '2026-02-01', '08:00:00', '09:30:00', 'B', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:30:12', '2026-01-26 02:30:12', NULL),
(30, 'BSIT', '4104', 'B2', 'PRE-ORAL', NULL, 1, '2026-02-01', '09:30:00', '11:00:00', 'B', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:30:12', '2026-01-26 02:30:12', NULL),
(31, 'BSIT', '4104', 'B3', 'PRE-ORAL', NULL, 1, '2026-02-01', '11:00:00', '12:30:00', 'B', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:30:13', '2026-01-26 02:30:13', NULL),
(32, 'BSIT', '4104', 'B4', 'PRE-ORAL', NULL, 1, '2026-02-01', '12:30:00', '14:00:00', 'B', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:30:13', '2026-01-26 02:30:13', NULL),
(33, 'BSIT', '4104', 'B5', 'PRE-ORAL', NULL, 1, '2026-02-01', '14:00:00', '15:30:00', 'B', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:30:13', '2026-01-26 02:30:13', NULL),
(34, 'BSIT', '4105', 'A1', 'PRE-ORAL', NULL, 1, '2026-02-04', '13:00:00', '15:00:00', 'A', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:51:51', '2026-01-26 03:05:00', NULL),
(35, 'BSIT', '4105', 'A2', 'PRE-ORAL', NULL, 1, '2026-02-04', '15:00:00', '17:00:00', 'A', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:51:51', '2026-01-26 03:05:00', NULL),
(36, 'BSIT', '4105', 'A3', 'PRE-ORAL', NULL, 1, '2026-02-04', '17:00:00', '19:00:00', 'A', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:51:51', '2026-01-26 03:05:01', NULL),
(37, 'BSIT', '4105', 'A4', 'PRE-ORAL', NULL, 1, '2026-02-04', '19:00:00', '21:00:00', 'A', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:51:52', '2026-01-26 03:05:01', NULL),
(38, 'BSIT', '4105', 'A5', 'PRE-ORAL', NULL, 1, '2026-02-04', '21:00:00', '23:00:00', 'A', 'Scheduled', '{\"adviser\":\"No Adviser\",\"chairperson\":\"No Chairperson\",\"members\":\"No Members\"}', '2026-01-26 02:51:52', '2026-01-26 03:05:01', NULL),
(79, 'BSIT', '4101', 'A3', 'FINAL DEFENSE', NULL, 1, '2026-02-07', '11:00:00', '12:30:00', 'A', 'Scheduled', '{\"adviser\":\"Mr. Andy Adovas\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof.  Jen, Prof. Jane\"}', '2026-02-01 06:33:31', '2026-02-02 03:09:40', '2026-02-02 03:09:40'),
(80, 'BSIT', '4101', 'A4', 'FINAL DEFENSE', NULL, 1, '2026-02-07', '12:30:00', '14:00:00', 'A', 'Scheduled', '{\"adviser\":\"Mr. Andy Adovas\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof.  Jen, Prof. Jane\"}', '2026-02-01 06:34:40', '2026-02-02 03:09:40', '2026-02-02 03:09:40'),
(81, 'BSIT', '4101', 'A5', 'FINAL DEFENSE', NULL, 1, '2026-02-07', '14:00:00', '15:30:00', 'A', 'Scheduled', '{\"adviser\":\"Mr. Andy Adovas\",\"chairperson\":\"Prof. Hayley Nichole Williams\",\"members\":\"Prof.  Jen, Prof. Jane\"}', '2026-02-01 06:35:26', '2026-02-02 03:09:40', '2026-02-02 03:09:40');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `cluster` int(11) NOT NULL,
  `group_no` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `feedback` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `final_assignments`
--

CREATE TABLE `final_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `adviser_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `defense_date` date DEFAULT NULL,
  `defense_time` time DEFAULT NULL,
  `defense_venue` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` varchar(255) NOT NULL,
  `group_number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `leader_member` int(11) NOT NULL DEFAULT 1,
  `member1_name` varchar(255) DEFAULT NULL,
  `member1_student_id` varchar(255) DEFAULT NULL,
  `member2_name` varchar(255) DEFAULT NULL,
  `member2_student_id` varchar(255) DEFAULT NULL,
  `member3_name` varchar(255) DEFAULT NULL,
  `member3_student_id` varchar(255) DEFAULT NULL,
  `member4_name` varchar(255) DEFAULT NULL,
  `member4_student_id` varchar(255) DEFAULT NULL,
  `member5_name` varchar(255) DEFAULT NULL,
  `member5_student_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_id`, `group_number`, `password`, `department`, `leader_member`, `member1_name`, `member1_student_id`, `member2_name`, `member2_student_id`, `member3_name`, `member3_student_id`, `member4_name`, `member4_student_id`, `member5_name`, `member5_student_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'IT001', '1', '$2y$12$A2WAM7B87SIUcs8FDVDOq.RahtRS90iETFIsY75i4TWPh6mVQlk/a', 'BSIT', 1, 'Jairo Luis L. Indoso', '22018863', 'Bret Baa', '22018696', 'Kim Elacion', '2209654', 'Angelo Michael Amata', '22087815', 'Princess Ana Juego', '2203484', '2026-01-25 22:00:00', '2026-01-26 00:57:22', NULL),
(2, 'IT002', '2', '$2y$12$RrlKM7BrGB0wNQjZ3SbwNeK4BJfXBG7pUHY/9TXKMaNg7j.wuZpsG', 'BSIT', 1, 'Andrei Soriano', '2200000', 'Clarisse Sang-olan', '2000001', 'Ronald Ampo', '2000002', 'Vincent Basa', '2000003', 'Mark Apuli', '2000004', '2026-01-26 02:46:15', '2026-01-26 02:47:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000000_create_defense_schedules_table', 1),
(5, '2025_08_31_212114_create_otps_table', 1),
(6, '2025_08_31_214022_add_otp_columns_to_users_table', 1),
(7, '2025_08_31_214712_add_role_to_users_table', 1),
(8, '2025_09_16_192701_create_submissions_table', 1),
(9, '2025_09_16_194145_create_files_table', 1),
(10, '2025_09_16_204650_add_user_id_to_submissions_table', 1),
(11, '2025_09_16_221838_add_cluster_group_department_to_submissions_table', 1),
(12, '2025_09_16_222633_add_is_admin_to_users_table', 1),
(13, '2025_09_17_182851_add_submission_id_to_committee_table', 1),
(14, '2025_09_17_193920_create_committees_table', 1),
(15, '2025_09_17_201728_add_committee_fields_to_committees_table', 1),
(16, '2025_09_17_202016_update_role_column_in_committees_table', 1),
(17, '2025_09_17_202225_make_name_nullable_in_committees_table', 1),
(18, '2025_09_17_203113_add_committee_fields_to_committees_table', 1),
(19, '2025_09_17_212419_create_resubmission_histories_table', 1),
(20, '2025_09_17_220633_create_notifications_table', 1),
(21, '2025_09_17_230035_add_due_date_to_submissions_table', 1),
(22, '2025_09_17_230404_add_chapter_to_submissions_table', 1),
(23, '2025_09_22_181526_add_department_cluster_group_to_users_table', 1),
(24, '2025_09_22_191852_create_submission_histories_table', 1),
(25, '2025_09_29_174814_add_user_id_to_otps_table', 1),
(26, '2025_10_02_230003_create_advisers_table', 1),
(27, '2025_10_02_230026_create_panels_table', 1),
(28, '2025_10_02_230027_add_contact_number_to_panels_table', 1),
(29, '2025_10_03_001734_create_assignments_table', 1),
(30, '2025_10_03_191257_create_professors_table', 1),
(31, '2025_10_03_191300_create_sections_table', 1),
(32, '2025_10_03_191437_create_final_assignments_table', 1),
(33, '2025_10_03_191438_add_scheduling_fields_to_final_assignments_table', 1),
(34, '2025_10_03_191441_create_assignment_panel_member_t', 1),
(35, '2025_10_03_191629_create_panel_slots_table', 1),
(36, '2025_10_03_192711_create_departments_table', 1),
(37, '2025_10_03_205801_create_clusters_table', 1),
(38, '2025_10_09_145742_add_soft_deletes_to_advisers_table', 1),
(39, '2025_10_09_145802_add_soft_deletes_to_panels_table', 1),
(40, '2025_10_11_053025_fix_submissions_table_id', 2),
(41, '2025_10_11_053114_add_documents_column_to_submissions_table', 2),
(42, '2025_10_11_054653_add_defense_type_to_submissions_table', 2),
(43, '2025_10_11_055516_update_defense_type_enum_values', 2),
(44, '2025_10_11_173612_create_defense_evaluations_table', 2),
(45, '2025_10_11_175532_create_defense_schedules_table', 3),
(46, '2026_01_18_142002_add_is_admin_to_users_table', 3),
(47, '2026_01_18_000001_add_role_and_contact_to_panels_table', 4),
(48, '2026_01_18_000002_add_adviser_id_to_assignments_table', 5),
(50, '2026_01_18_000003_create_assignment_panels_table', 6),
(51, '2026_01_25_035908_add_role_contact_to_users_table', 6),
(52, '2026_01_25_063213_increase_otp_column_length_on_users_table', 7),
(53, '2026_01_25_081054_add_soft_deletes_to_users_table', 8),
(54, '2026_01_26_055005_create_groups_table', 9),
(55, '2026_01_26_060646_add_group_number_and_leader_to_groups_table', 10),
(56, '2026_01_20_000001_add_group_number_to_assignments_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `message`, `read`, `created_at`, `updated_at`) VALUES
(1, 2, 'status', 'Your proposal \'Research Title Proposal\' has been approved.', 1, '2026-01-18 07:13:47', '2026-01-18 20:27:24'),
(2, 2, 'feedback', 'New feedback received for \'Research Title Proposal\': fddsdsd', 1, '2026-01-18 07:13:52', '2026-01-18 20:27:24'),
(3, 2, 'status', 'Your proposal \'Manuscript Chapter 1-5\' has been approved.', 0, '2026-01-18 20:41:50', '2026-01-18 20:41:50'),
(4, 2, 'feedback', 'New feedback received for \'Manuscript Chapter 1-5\': Good', 0, '2026-01-18 20:41:57', '2026-01-18 20:41:57'),
(5, 2, 'status', 'Your proposal \'Research Title Proposal\' has been approved.', 0, '2026-01-18 20:42:15', '2026-01-18 20:42:15'),
(6, 2, 'status', 'Your proposal \'Clearance\' has been rejected.', 0, '2026-01-18 20:45:27', '2026-01-18 20:45:27'),
(7, 2, 'feedback', 'New feedback received for \'Clearance\': Not properly arrange', 0, '2026-01-18 20:45:27', '2026-01-18 20:45:27'),
(8, 2, 'status', 'Your proposal \'Clearance\' has been approved.', 0, '2026-01-18 20:45:39', '2026-01-18 20:45:39'),
(9, 13, 'status', 'Your proposal \'Research Title Proposal\' has been approved.', 0, '2026-02-02 01:39:48', '2026-02-02 01:39:48'),
(10, 13, 'feedback', 'New feedback received for \'Research Title Proposal\': good', 0, '2026-02-02 01:39:57', '2026-02-02 01:39:57'),
(11, 14, 'status', 'Your proposal \'Research Title Proposal\' has been approved.', 1, '2026-02-02 01:40:48', '2026-02-02 01:41:35'),
(12, 14, 'feedback', 'New feedback received for \'Research Title Proposal\': Good', 1, '2026-02-02 01:40:57', '2026-02-02 01:41:08');

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `panels`
--

CREATE TABLE `panels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `expertise` varchar(255) NOT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `availability` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`availability`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `panels`
--

INSERT INTO `panels` (`id`, `name`, `department`, `expertise`, `contact_number`, `availability`, `created_at`, `updated_at`, `deleted_at`, `role`) VALUES
(1, 'Prof. Hayley Nichole Williams', 'BSIT', 'Doctoral', '09532147987', '[{\"date\":\"2026-02-14\",\"time\":\"08:00 - 21:00\"}]', '2026-01-18 07:17:38', '2026-02-01 00:24:51', NULL, 'Chairperson'),
(2, 'Prof.  Jen', 'BSIT', 'Professor', '09532147988', '[{\"date\":\"2026-01-20\",\"time\":\"13:18 - 14:18\"}]', '2026-01-18 07:18:47', '2026-01-29 13:13:15', NULL, 'Member'),
(3, 'Prof. Jane', 'BSIT', 'Research Specialist', '09532147989', '[{\"date\":\"2026-01-31\",\"time\":\"11:22 - 12:22\"}]', '2026-01-18 07:22:51', '2026-01-24 05:33:04', NULL, 'Member'),
(4, 'Doc. Mikasa', 'BSIT', 'Doctoral', '09532147985', '[{\"date\":\"2026-01-31\",\"time\":\"07:00 - 10:00\"}]', '2026-01-18 14:45:55', '2026-01-24 05:30:18', NULL, 'Chairperson'),
(5, 'Doc. Eren', 'CRIM', 'Doctoral', '09532147984', '[{\"date\":\"2026-02-01\",\"time\":\"06:00 - 12:00\"}]', '2026-01-18 14:51:13', '2026-01-26 06:06:56', '2026-01-26 06:06:56', 'Chairperson'),
(6, 'Doc. Ignacio', 'CRIM', 'Industry Expert', '09532147986', '[{\"date\":\"2026-03-02\",\"time\":\"07:00 - 15:00\"}]', '2026-01-18 14:52:22', '2026-01-18 14:52:22', NULL, 'Member'),
(7, 'Prof. Fugaku', 'CRIM', 'Industry Expert', '09532147982', '[{\"date\":\"2026-02-03\",\"time\":\"06:00 - 11:00\"}]', '2026-01-18 14:54:00', '2026-01-24 09:13:40', NULL, 'Member');

-- --------------------------------------------------------

--
-- Table structure for table `panel_slots`
--

CREATE TABLE `panel_slots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `panel_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `professors`
--

CREATE TABLE `professors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `expertise` varchar(255) DEFAULT NULL,
  `is_adviser` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resubmission_histories`
--

CREATE TABLE `resubmission_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('jkL2uUwtwWfZqKIoSygkZr5emV5sz8l1Qx43Kxx6', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ2RKRjVrT3dXcFBpQ1lrMnVZdjJ2eEVHdUlxempsTjhSSlAwUm9raCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jb29yZGluYXRvci1tYW5hZ2UtZ3JvdXBzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1769975662),
('tBwjJbw3WdIGao89gPnANoSy8ShfxwpQ1vvX5MeW', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidXdicGMxU1hZS2FFdTBKRkM5MGZOcmdIdE1CWDZiYjdxUU80dnZ2NSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi1kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjE0OiJvdHBfZXhwaXJlc19hdCI7aToxNzY5OTY2NjA0O3M6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1769975626);

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `documents` varchar(255) NOT NULL,
  `defense_type` enum('Pre-Oral','Final Defense','Pre-Oral Re-Defense','Final Defense Re-Defense') NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `cluster` int(11) NOT NULL,
  `group_no` int(11) NOT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `submitted_by` bigint(20) UNSIGNED NOT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `chapter` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `documents`, `defense_type`, `user_id`, `title`, `file_path`, `department`, `cluster`, `group_no`, `status`, `submitted_by`, `feedback`, `created_at`, `updated_at`, `due_date`, `chapter`) VALUES
(1, 'Research Title Proposal', 'Pre-Oral', 2, 'Research Title Proposal', 'submissions/g0OfHuPzUvLFIbG25zxXkhnf3wWEWyWvZRqBottS.pdf', 'BSIT', 4101, 1, 'Approved', 2, 'fddsdsd', '2026-01-18 07:12:19', '2026-01-18 07:13:52', NULL, NULL),
(2, 'Summary of Corrections', 'Final Defense', 2, 'Summary of Corrections', 'submissions/D5y7Cs9fz9R9hDEITvN3yAh0XN0P2Zic9kboovnT.pdf', 'BSIT', 4101, 1, 'Pending', 2, NULL, '2026-01-18 08:14:06', '2026-01-18 08:14:06', NULL, NULL),
(3, 'Clearance', 'Final Defense', 2, 'Clearance', 'submissions/melzAjJsDs2eBLlQ1iJS1nsBcXEumX1cAgGoMT92.pdf', 'BSIT', 4101, 1, 'Approved', 2, NULL, '2026-01-18 08:14:18', '2026-01-18 20:45:39', NULL, NULL),
(4, 'Manuscript Chapter 1-5', 'Final Defense', 2, 'Manuscript Chapter 1-5', 'submissions/tWrTcReS9b8Q7TrA3vhZbOgYln21kWzJixHArVTc.pdf', 'BSIT', 4101, 1, 'Approved', 2, 'Good', '2026-01-18 08:14:39', '2026-01-18 20:41:57', NULL, NULL),
(5, 'Clearance', 'Final Defense', 2, 'Clearance', 'submissions/vdBlYSmMcD80Mzrst4nWyNucUmVqMNEOOloJGg9Q.pdf', 'BSIT', 4101, 1, 'Rejected', 2, 'Not properly arrange', '2026-01-18 20:40:53', '2026-01-18 20:45:27', NULL, NULL),
(6, 'Research Title Proposal', 'Pre-Oral', 2, 'Research Title Proposal', 'submissions/yJ2Yg8jXDOnKTao2rUH90K3BAzOM9IH907oKG6rN.pdf', 'BSIT', 4101, 1, 'Pending', 2, NULL, '2026-01-24 03:21:36', '2026-01-24 03:21:36', NULL, NULL),
(7, 'Research Title Proposal', 'Pre-Oral', 2, 'Research Title Proposal', 'submissions/bXk6cXU9yojZw9xCKfqotJw2WAJclK5PLOPZLmA4.pdf', 'BSIT', 4101, 1, 'Pending', 2, NULL, '2026-01-24 21:48:48', '2026-01-24 21:48:48', NULL, NULL),
(8, 'Research Title Proposal', 'Pre-Oral', 13, 'Research Title Proposal', 'submissions/hov9gv6iSacNOQW7e7DxMbFMHiibjlRFgsyILeFB.pdf', 'BSIT', 4101, 110, 'Approved', 13, 'good', '2026-01-25 22:47:56', '2026-02-02 01:39:58', NULL, NULL),
(9, 'Research Title Proposal', 'Pre-Oral', 14, 'Research Title Proposal', 'submissions/v6kTWB0dkw2GcQwShrlVD7KflnsjuJrSgoiVj6Yh.pdf', 'BSIT', 4101, 1, 'Approved', 14, 'Good', '2026-01-31 23:18:25', '2026-02-02 01:40:57', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `submission_histories`
--

CREATE TABLE `submission_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT NULL,
  `cluster` varchar(255) DEFAULT NULL,
  `group_no` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(4) NOT NULL DEFAULT 0,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `otp` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `cluster` int(11) DEFAULT NULL,
  `group_no` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `role`, `otp`, `otp_expires_at`, `remember_token`, `created_at`, `updated_at`, `department`, `contact`, `cluster`, `group_no`, `deleted_at`) VALUES
(1, 'Admin', 'crad7465@gmail.com', '2026-02-02 01:13:51', '$2y$12$ehP0WKuvbFwz6x94sas5H.wPQO58q6HSUu4OZA9ybX50nR3tDk/9C', 1, 'admin', NULL, NULL, NULL, '2026-01-18 06:59:25', '2026-02-02 01:13:51', NULL, NULL, NULL, NULL, NULL),
(2, 'Juan DelaCruz', 'juandelacruz@gmail.com', NULL, '$2y$12$2t1RCGl0lv6Wl4LZ/TLPTONMvVXv92XuXCz3ecBwaEjO5mFqW4AOC', 0, 'student', NULL, NULL, NULL, '2026-01-18 07:02:46', '2026-01-18 07:02:46', 'BSIT', NULL, 4101, 1, NULL),
(3, 'Maria Santos', 'mariasantos@gmail.com', NULL, '$2y$12$m3z/8iWBvVZA408M0x.Rfend7KIIu4IdrM36Dz0bsY6ORAcuK9JBW', 0, 'student', NULL, NULL, NULL, '2026-01-18 07:02:46', '2026-01-18 07:02:46', 'CRIM', NULL, 4102, 2, NULL),
(4, 'Pedro Cruz', 'pedrocruz@gmail.com', NULL, '$2y$12$VBqKos6kcsfbLTwZ2l0mf.EvwiV3/YKmpcZiuQqLj/C2i/JZ2YXIu', 0, 'student', NULL, NULL, NULL, '2026-01-18 07:02:46', '2026-01-18 07:02:46', 'EDUC', NULL, 4103, 1, NULL),
(5, 'Mr. Raymon Loria', 'raymon@gmail.com', NULL, '$2y$12$vyK4uAzYakgCR7zUeWEZiey1gcw3IIveg6ZZ4rAucLrPjPAl27VF.', 0, 'coordinator', NULL, NULL, NULL, '2026-01-24 19:57:18', '2026-01-25 00:30:14', 'BSIT', '09645633237', NULL, NULL, NULL),
(6, 'Ms. Jenny Bigcas', 'jenny123@gmail.com', NULL, '$2y$12$JzvL.mJutZC86G3G.M2yMOlX//gXbaHFQPE4Oukz1S3jPE1hhkmv2', 0, 'coordinator', NULL, NULL, NULL, '2026-01-24 23:17:36', '2026-01-25 00:02:51', 'CRIM', '09696969696', NULL, NULL, NULL),
(7, 'Mr. Kim Elacion', 'kim1234@gmail.com', NULL, '$2y$12$.hLgpUciBnr5FjFA78.zwuF9pumfiImN7VbAaGJXg7dXJLK4OMq.q', 0, 'coordinator', NULL, NULL, NULL, '2026-01-24 23:28:14', '2026-01-25 23:02:49', 'BSIT', '09645633232', NULL, NULL, '2026-01-25 23:02:49'),
(8, 'Jairo Luis L. Indoso', '22018863', NULL, '$2y$12$3UUhhAhMR.4JTK9oO5LDdOjp3KUcrf8P.UZQSOEq6B2ValG.QqEoi', 0, 'student', NULL, NULL, NULL, '2026-01-25 22:32:25', '2026-01-25 22:32:25', 'BSIT', NULL, NULL, 110, NULL),
(9, 'Bret Baa', '22018696', NULL, '$2y$12$3UUhhAhMR.4JTK9oO5LDdOjp3KUcrf8P.UZQSOEq6B2ValG.QqEoi', 0, 'student', NULL, NULL, NULL, '2026-01-25 22:32:25', '2026-01-25 22:32:25', 'BSIT', NULL, NULL, 110, NULL),
(10, 'Kim Elacion', '2209654', NULL, '$2y$12$3UUhhAhMR.4JTK9oO5LDdOjp3KUcrf8P.UZQSOEq6B2ValG.QqEoi', 0, 'student', NULL, NULL, NULL, '2026-01-25 22:32:25', '2026-01-25 22:32:25', 'BSIT', NULL, NULL, 110, NULL),
(11, 'Angelo Michael Amata', '22087815', NULL, '$2y$12$3UUhhAhMR.4JTK9oO5LDdOjp3KUcrf8P.UZQSOEq6B2ValG.QqEoi', 0, 'student', NULL, NULL, NULL, '2026-01-25 22:32:25', '2026-01-25 22:32:25', 'BSIT', NULL, NULL, 110, NULL),
(12, 'Princess Ana Juego', '2203484', NULL, '$2y$12$3UUhhAhMR.4JTK9oO5LDdOjp3KUcrf8P.UZQSOEq6B2ValG.QqEoi', 0, 'student', NULL, NULL, NULL, '2026-01-25 22:32:26', '2026-01-25 22:32:26', 'BSIT', NULL, NULL, 110, NULL),
(13, 'IT110', 'sIT110', NULL, '$2y$12$3vZaxAlfpxdMWDAY4IHjX.aSW7A14RD4PEm5Vx7Ji9J69ZWjM6RJa', 0, 'student', NULL, NULL, NULL, '2026-01-25 22:37:20', '2026-01-25 22:46:19', 'BSIT', NULL, NULL, 110, NULL),
(14, 'IT001', 'sIT001', NULL, '$2y$12$A2WAM7B87SIUcs8FDVDOq.RahtRS90iETFIsY75i4TWPh6mVQlk/a', 0, 'student', NULL, NULL, NULL, '2026-01-26 00:56:13', '2026-01-26 00:57:22', 'BSIT', NULL, NULL, 1, NULL),
(15, 'IT002', 'sIT002', NULL, '$2y$12$RrlKM7BrGB0wNQjZ3SbwNeK4BJfXBG7pUHY/9TXKMaNg7j.wuZpsG', 0, 'student', NULL, NULL, NULL, '2026-01-26 02:46:15', '2026-01-26 02:47:50', 'BSIT', NULL, NULL, 2, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advisers`
--
ALTER TABLE `advisers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assignment_panels`
--
ALTER TABLE `assignment_panels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignment_panels_assignment_id_foreign` (`assignment_id`),
  ADD KEY `assignment_panels_panel_id_foreign` (`panel_id`);

--
-- Indexes for table `assignment_panel_member`
--
ALTER TABLE `assignment_panel_member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignment_panel_member_final_assignment_id_foreign` (`final_assignment_id`),
  ADD KEY `assignment_panel_member_professor_id_foreign` (`professor_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `clusters`
--
ALTER TABLE `clusters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `committee`
--
ALTER TABLE `committee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `committee_submission_id_foreign` (`submission_id`);

--
-- Indexes for table `committees`
--
ALTER TABLE `committees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `committees_submission_id_foreign` (`submission_id`);

--
-- Indexes for table `defense_evaluations`
--
ALTER TABLE `defense_evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `defense_evaluations_department_cluster_group_id_index` (`department`,`cluster`,`group_id`);

--
-- Indexes for table `defense_schedules`
--
ALTER TABLE `defense_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `defense_schedules_unique` (`group_id`,`department`,`section`,`defense_type`),
  ADD KEY `defense_schedules_department_section_defense_type_index` (`department`,`section`,`defense_type`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_name_unique` (`name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_user_id_foreign` (`user_id`);

--
-- Indexes for table `final_assignments`
--
ALTER TABLE `final_assignments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groups_group_id_unique` (`group_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otps_user_id_foreign` (`user_id`);

--
-- Indexes for table `panels`
--
ALTER TABLE `panels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `panel_slots`
--
ALTER TABLE `panel_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `panel_slots_panel_id_foreign` (`panel_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `professors`
--
ALTER TABLE `professors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resubmission_histories`
--
ALTER TABLE `resubmission_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resubmission_histories_submission_id_foreign` (`submission_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submissions_user_id_foreign` (`user_id`);

--
-- Indexes for table `submission_histories`
--
ALTER TABLE `submission_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submission_histories_submission_id_foreign` (`submission_id`),
  ADD KEY `submission_histories_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advisers`
--
ALTER TABLE `advisers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `assignment_panels`
--
ALTER TABLE `assignment_panels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `assignment_panel_member`
--
ALTER TABLE `assignment_panel_member`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clusters`
--
ALTER TABLE `clusters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `committee`
--
ALTER TABLE `committee`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `committees`
--
ALTER TABLE `committees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `defense_evaluations`
--
ALTER TABLE `defense_evaluations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `defense_schedules`
--
ALTER TABLE `defense_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `final_assignments`
--
ALTER TABLE `final_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `panels`
--
ALTER TABLE `panels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `panel_slots`
--
ALTER TABLE `panel_slots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `professors`
--
ALTER TABLE `professors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resubmission_histories`
--
ALTER TABLE `resubmission_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `submission_histories`
--
ALTER TABLE `submission_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignment_panels`
--
ALTER TABLE `assignment_panels`
  ADD CONSTRAINT `assignment_panels_assignment_id_foreign` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignment_panels_panel_id_foreign` FOREIGN KEY (`panel_id`) REFERENCES `panels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_panel_member`
--
ALTER TABLE `assignment_panel_member`
  ADD CONSTRAINT `assignment_panel_member_final_assignment_id_foreign` FOREIGN KEY (`final_assignment_id`) REFERENCES `final_assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignment_panel_member_professor_id_foreign` FOREIGN KEY (`professor_id`) REFERENCES `professors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `committee`
--
ALTER TABLE `committee`
  ADD CONSTRAINT `committee_submission_id_foreign` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
