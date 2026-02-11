-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost: 3306
-- Generation Time: Feb 07, 2026 at 04:48 AM
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
  `contact` varchar(255) DEFAULT NULL,
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

INSERT INTO `advisers` (`id`, `name`, `contact`, `department`, `expertise`, `sections`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Alejandro Adovas', '09123659852', 'BSIT', 'Assistant Professor', '\"[1,2,3,4,5,6,7,8,9,10]\"', '2026-02-05 08:42:34', '2026-02-06 16:40:27', NULL),
(2, 'Sheryl Adovas', '09264789547', 'BSIT', 'Instructor', '\"[11,12,13,14,15,16,17,18,19,20]\"', '2026-02-06 16:16:39', '2026-02-06 17:58:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `adviser_id` bigint(20) UNSIGNED DEFAULT NULL,
  `group_number` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `department_id`, `section_id`, `title`, `created_at`, `updated_at`, `adviser_id`, `group_number`, `department`, `section`) VALUES
(1, NULL, NULL, NULL, '2026-02-05 10:53:13', '2026-02-05 10:53:13', 1, NULL, 'BSIT', '4101');

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
(1, 1, 1, 'Dr. Hayley Nichole Williams', '[{\"date\":\"2026-02-14\",\"time\":\"08:00 - 21:00\"}]', 'Chairperson', 'Industry Expert', 'BSIT', '4101', '2026-02-05 10:53:13', '2026-02-05 10:53:13'),
(2, 1, 2, 'Jenny Bigcas', '[{\"date\":\"2026-02-14\",\"time\":\"08:00 - 21:00\"}]', 'Member', 'Doctoral', 'BSIT', '4101', '2026-02-05 10:53:13', '2026-02-05 10:53:13'),
(3, 1, 3, 'Mark Avelino', '[{\"date\":\"2026-02-14\",\"time\":\"08:00 - 09:00\"},{\"date\":\"2026-02-10\",\"time\":\"08:00 - 21:00\"}]', 'Member', 'Instructor', 'BSIT', '4101', '2026-02-05 10:53:13', '2026-02-05 10:53:13');

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
  `cluster_number` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clusters`
--

INSERT INTO `clusters` (`id`, `cluster_number`, `department`, `created_at`, `updated_at`) VALUES
(1, '4101', 'BSIT', '2026-02-05 08:38:38', '2026-02-05 08:38:38'),
(2, '4102', 'BSIT', '2026-02-05 09:39:38', '2026-02-05 09:39:38'),
(3, '4103', 'BSIT', '2026-02-06 16:24:50', '2026-02-06 16:24:50'),
(4, '4104', 'BSIT', '2026-02-06 16:27:37', '2026-02-06 16:27:37');

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
(5, 'BSIT', '4101', 'IT001', 'PRE-ORAL', NULL, 1, '2026-02-16', '14:38:00', '16:36:00', NULL, 'Scheduled', '{\"adviser\":\"Alejandro Adovas\",\"chairperson\":\"Dr. Hayley Nichole Williams\",\"members\":\"Jenny Bigcas, Mark Avelino\"}', '2026-02-06 10:37:11', '2026-02-06 10:39:25', '2026-02-06 10:39:25');

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
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `professor_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'professor',
  `picture` varchar(255) DEFAULT NULL,
  `face_descriptor` longtext DEFAULT NULL,
  `face_enrolled_at` datetime DEFAULT NULL,
  `attendance_terms_accepted_at` datetime DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `employment_type` varchar(255) NOT NULL DEFAULT 'Full-Time',
  `department` varchar(255) NOT NULL DEFAULT 'BSIT',
  `skills` text DEFAULT NULL,
  `experiences` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
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
  `department` varchar(255) NOT NULL,
  `cluster` varchar(255) NOT NULL,
  `group_number` int(11) NOT NULL,
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
  `leader_member` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `group_id` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `department`, `cluster`, `group_number`, `member1_name`, `member1_student_id`, `member2_name`, `member2_student_id`, `member3_name`, `member3_student_id`, `member4_name`, `member4_student_id`, `member5_name`, `member5_student_id`, `leader_member`, `created_at`, `updated_at`, `group_id`, `email`, `password`, `deleted_at`, `remember_token`) VALUES
(14, 'BSIT', '4101', 1, 'Liam Cruz Pineda', '2201900', 'Emma Gomez Bernardo', '2201901', 'Noah Santos Salvador', '2201902', 'Olivia Reyes Ocampo', '2201903', 'James Mendoza Aquino', '2201904', 1, '2026-02-06 15:30:08', '2026-02-06 15:54:25', 'IT001', 'sIT001', '$2y$12$EAsgFGgAo9WxwC8qZpwEa.HWNdwP3zRYa1s18FIJ0092b2lvyvgNu', NULL, NULL),
(15, 'BSIT', '4101', 2, 'Sophia Dela Cruz Castillo', '2201905', 'Lucas Perez Villanueva', '2201906', 'Mia Soriano Domingo', '2201907', 'Ethan Ignacio Santiago', '2201908', 'Isabella Ramos Valdez', '2201909', 1, '2026-02-06 15:30:29', '2026-02-06 15:30:29', 'IT002', 'sIT002', '$2y$12$rmPYPIqJKOtP//kYljksAuFUQdJ3Bjo04HFJisfeYCrwBZp8C9CtS', NULL, NULL),
(16, 'BSIT', '4101', 3, 'Mason Evangelista Tolentino', '2201910', 'Ava Ferrer Espiritu', '2201911', 'Logan Mercado Guzman', '2201912', 'Amelia David Abad', '2201913', 'Charlotte Jose Padilla', '2201915', 1, '2026-02-06 15:31:00', '2026-02-06 15:31:00', 'IT003', 'sIT003', '$2y$12$3qaBVBQDx0BMn20qYqtpS.9F9v0a2adpu9E6YHFuWelO2G/T9ByDa', NULL, NULL),
(17, 'BSIT', '4101', 4, 'Aiden Velasco Medina', '2201916', 'Harper Navarro Sison', '2201917', 'Sebastian Estrella Galang', '2201918', 'Evelyn Alcantara Del Rosario', '2201919', 'Jack Mendoza Buenaventura', '2201920', 1, '2026-02-06 15:58:08', '2026-02-06 15:58:08', 'IT004', 'sIT004', '$2y$12$FkNJJA.TQVYiYX39OsLMyui2sKPHVPgX3uKOXZsIMSQF6aUMXQEfm', NULL, NULL),
(18, 'BSIT', '4101', 5, 'Luna Santos De Leon', '2201921', 'Owen Ramos Corpuz', '2201922', 'Layla Pascual Turingan', '2201923', 'Wyatt Dizon Lazaro', '2201924', 'Mila Bautista Osorio', '2201925', 1, '2026-02-06 15:58:37', '2026-02-06 15:58:37', 'IT005', 'sIT005', '$2y$12$sD1CwVj9Y8APZ2DEHbgkguveUTTdqc.JHVZFeCDqMXMA/DePD.9Ty', NULL, NULL),
(19, 'BSIT', '4102', 11, 'Luke Velasquez Samonte', '2201926', 'Scarlett Cabrera Magat', '2201927', 'Asher Roman Salinas', '2201928', 'Hazel Vicente Tejada', '2201929', 'Leo Solis Marquez', '2201930', 1, '2026-02-06 16:14:04', '2026-02-06 16:14:04', 'IT011', 'sIT011', '$2y$12$p3sgAKpI0Czk7IK2tir5De9tCpJ36rXOGnTRiUxwb1TrY7Ajov/QC', NULL, NULL),
(20, 'BSIT', '4102', 12, 'Aurora Rico Dominguez', '2201931', 'Julian Flores Enriquez', '2201932', 'Violet Borja Miranda', '2201933', 'Grayson Angeles Ilagan', '2201934', 'Bella Legaspi Prado', '2201935', 1, '2026-02-06 16:14:34', '2026-02-06 16:14:34', 'IT012', 'sIT012', '$2y$12$0XTWc4DSLwm73BnAMssCx.dVNggqVm.4KJEzmQEIJJL/hJNw54fz2', NULL, NULL),
(21, 'BSIT', '4102', 13, 'Daniel Pineda Cortes', '2201936', 'Nova Luna Ramos', '2201937', 'Mateo Dela Torre Hilario', '2201938', 'Elena Sotto Fajardo', '2201939', 'Gabriel Santos Villaluz', '2201940', 1, '2026-02-06 16:15:07', '2026-02-06 16:15:07', 'IT013', 'sIT013', '$2y$12$lxY5rVW1YMytib8UKlv7Hu2NGTsyRbM7ctNlPa0bA19UJyJOZ2oGO', NULL, NULL),
(22, 'BSIT', '4102', 14, 'Iris Reyes Manalac', '2201941', 'Xavier Dizon Yambao', '2201942', 'Chloe Mendoza Dela Rosa', '2201943', 'Nathan Perez Guevarra', '2201944', 'Penelope Lopez Sarmiento', '2201945', 1, '2026-02-06 17:25:35', '2026-02-06 17:25:35', 'IT014', 'sIT014', '$2y$12$0diJJud2lZdo8LaS2.tVZeUcWU4hPPK8zJCgjLcXiAC1fPqhp2NLe', NULL, NULL),
(23, 'BSIT', '4102', 15, 'Caleb Bautista Villar', '2201946', 'Zoe Galang Tanyag', '2201947', 'Dominic Ignacio Laxamana', '2201948', 'Serenity Soriano Buan', '2201949', 'Aaron Dizon Alvarez', '2201950', 1, '2026-02-06 17:26:27', '2026-02-06 17:26:27', 'IT015', 'sIT015', '$2y$12$cnghCpJjOj1.kTa2RnGn/eJu7QLfglAb7ZmhrbzQc6GqfIw1BCwuG', NULL, NULL);

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
(4, '2024_01_01_000000_create_clusters_table', 1),
(5, '2024_01_01_000000_create_defense_schedules_table', 1),
(6, '2024_01_01_000001_create_groups_table', 1),
(7, '2024_02_05_000001_add_cluster_to_groups_table', 1),
(8, '2025_08_31_212114_create_otps_table', 1),
(9, '2025_08_31_214022_add_otp_columns_to_users_table', 1),
(10, '2025_08_31_214712_add_role_to_users_table', 1),
(11, '2025_09_16_192701_create_submissions_table', 1),
(12, '2025_09_16_194145_create_files_table', 1),
(13, '2025_09_16_204650_add_user_id_to_submissions_table', 1),
(14, '2025_09_16_221838_add_cluster_group_department_to_submissions_table', 1),
(15, '2025_09_16_222633_add_is_admin_to_users_table', 1),
(16, '2025_09_17_182851_add_submission_id_to_committee_table', 1),
(17, '2025_09_17_193920_create_committees_table', 1),
(18, '2025_09_17_201728_add_committee_fields_to_committees_table', 1),
(19, '2025_09_17_202016_update_role_column_in_committees_table', 1),
(20, '2025_09_17_202225_make_name_nullable_in_committees_table', 1),
(21, '2025_09_17_203113_add_committee_fields_to_committees_table', 1),
(22, '2025_09_17_212419_create_resubmission_histories_table', 1),
(23, '2025_09_17_220633_create_notifications_table', 1),
(24, '2025_09_17_230035_add_due_date_to_submissions_table', 1),
(25, '2025_09_17_230404_add_chapter_to_submissions_table', 1),
(26, '2025_09_22_181526_add_department_cluster_group_to_users_table', 1),
(27, '2025_09_22_191852_create_submission_histories_table', 1),
(28, '2025_09_29_174814_add_user_id_to_otps_table', 1),
(29, '2025_10_02_230003_create_advisers_table', 1),
(30, '2025_10_02_230026_create_panels_table', 1),
(31, '2025_10_02_230027_add_contact_number_to_panels_table', 1),
(32, '2025_10_03_001734_create_assignments_table', 1),
(33, '2025_10_03_191257_create_professors_table', 1),
(34, '2025_10_03_191300_create_sections_table', 1),
(35, '2025_10_03_191437_create_final_assignments_table', 1),
(36, '2025_10_03_191438_add_scheduling_fields_to_final_assignments_table', 1),
(37, '2025_10_03_191441_create_assignment_panel_member_t', 1),
(38, '2025_10_03_191629_create_panel_slots_table', 1),
(39, '2025_10_03_192711_create_departments_table', 1),
(40, '2025_10_09_145742_add_soft_deletes_to_advisers_table', 1),
(41, '2025_10_09_145802_add_soft_deletes_to_panels_table', 1),
(42, '2025_10_11_053025_fix_submissions_table_id', 1),
(43, '2025_10_11_053114_add_documents_column_to_submissions_table', 1),
(44, '2025_10_11_054653_add_defense_type_to_submissions_table', 1),
(45, '2025_10_11_055516_update_defense_type_enum_values', 1),
(46, '2025_10_11_173612_create_defense_evaluations_table', 1),
(47, '2026_01_18_000001_add_role_and_contact_to_panels_table', 1),
(48, '2026_01_18_000002_add_adviser_id_to_assignments_table', 1),
(49, '2026_01_18_000003_create_assignment_panels_table', 1),
(50, '2026_01_18_142002_add_is_admin_to_users_table', 1),
(51, '2026_01_20_000001_add_group_number_to_assignments_table', 1),
(52, '2026_01_25_035908_add_role_contact_to_users_table', 1),
(53, '2026_01_25_063213_increase_otp_column_length_on_users_table', 1),
(54, '2026_01_25_081054_add_soft_deletes_to_users_table', 1),
(55, '2026_02_05_123333_add_department_section_to_assignments_table', 2),
(56, '2026_02_05_130828_add_group_id_password_to_groups_table', 3),
(57, '2026_02_05_131433_add_group_id_to_users_table', 4),
(58, '2026_02_05_164823_add_contact_to_advisers_table', 5),
(59, '2026_02_06_030606_add_submission_id_to_notifications_table', 6),
(60, '2026_02_06_103911_create_faculties_table', 7),
(61, '2026_02_06_170614_add_deleted_at_to_groups_table', 7),
(62, '2026_02_06_213258_add_fms_id_to_users_table', 8),
(63, '2026_02_06_213719_remove_fms_id_from_users_table', 9),
(64, '2026_02_06_214820_add_professor_id_to_users_table', 10),
(65, '2026_02_06_225032_add_auth_columns_to_groups_table', 11),
(66, '2026_02_06_232747_create_student_users_table', 12);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submission_id` bigint(20) UNSIGNED DEFAULT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `message`, `submission_id`, `read`, `created_at`, `updated_at`) VALUES
(1, 2, 'submission', 'New submission from Group 2: Manuscript Chapter 1-3', 4, 0, '2026-02-05 19:11:13', '2026-02-05 19:11:13'),
(2, 2, 'submission', 'New submission from Group 2: Clearance', 5, 0, '2026-02-05 19:25:50', '2026-02-05 19:25:50'),
(5, 1, 'rejected', 'Coordinator rejected \'Manuscript Chapter 1-3\' from Group 2 (BSIT).', 2, 0, '2026-02-05 19:41:24', '2026-02-05 19:41:24'),
(7, 1, 'approved', 'Coordinator approved \'Manuscript Chapter 1-3\' from Group 2 (BSIT).', 3, 0, '2026-02-05 19:42:35', '2026-02-05 19:42:35'),
(12, 1, 'approved', 'Coordinator approved \'Manuscript Chapter 1-3\' from Group 2 (BSIT).', 1, 0, '2026-02-05 19:51:33', '2026-02-05 19:51:33'),
(13, 2, 'submission', 'New submission from Group 2: Research Forum', 6, 0, '2026-02-05 21:28:32', '2026-02-05 21:28:32'),
(14, 1, 'submission', 'New submission from Group 2 (BSIT): Research Forum', 6, 0, '2026-02-05 21:28:32', '2026-02-05 21:28:32'),
(15, 2, 'submission', 'New submission from Group 2: Research Title Proposal', 7, 0, '2026-02-05 21:35:58', '2026-02-05 21:35:58'),
(16, 1, 'submission', 'New submission from Group 2 (BSIT): Research Title Proposal', 7, 0, '2026-02-05 21:35:58', '2026-02-05 21:35:58'),
(17, 2, 'submission', 'New submission from Group 2: Research Forum', 8, 0, '2026-02-05 21:36:50', '2026-02-05 21:36:50'),
(18, 1, 'submission', 'New submission from Group 2 (BSIT): Research Forum', 8, 0, '2026-02-05 21:36:50', '2026-02-05 21:36:50'),
(19, 2, 'submission', 'New submission from Group 2: Clearance', 9, 0, '2026-02-05 21:38:55', '2026-02-05 21:38:55'),
(20, 1, 'submission', 'New submission from Group 2 (BSIT): Clearance', 9, 0, '2026-02-05 21:38:55', '2026-02-05 21:38:55'),
(21, 2, 'submission', 'New submission from Group 2: Clearance', 10, 0, '2026-02-05 21:48:32', '2026-02-05 21:48:32'),
(22, 1, 'submission', 'New submission from Group 2 (BSIT): Clearance', 10, 0, '2026-02-05 21:48:32', '2026-02-05 21:48:32');

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
(1, 'Dr. Hayley Nichole Williams', 'BSIT', 'Industry Expert', '09532147988', '[{\"date\":\"2026-02-14\",\"time\":\"08:00 - 21:00\"}]', '2026-02-05 10:49:42', '2026-02-05 10:49:42', NULL, 'Chairperson'),
(2, 'Jenny Bigcas', 'BSIT', 'Doctoral', '09532147987', '[{\"date\":\"2026-02-14\",\"time\":\"08:00 - 21:00\"}]', '2026-02-05 10:50:52', '2026-02-05 10:50:52', NULL, 'Member'),
(3, 'Mark Avelino', 'BSIT', 'Instructor', '09532147989', '[{\"date\":\"2026-02-10\",\"time\":\"08:00 - 21:00\"},{\"date\":\"2026-02-14\",\"time\":\"08:00 - 21:00\"}]', '2026-02-05 10:52:35', '2026-02-06 17:12:20', NULL, 'Member'),
(4, 'Raymon Loria', 'BSIT', 'Instructor', '09563278643', '[{\"date\":\"2026-02-14\",\"time\":\"20:00 - 21:00\"},{\"date\":\"2026-02-06\",\"time\":\"08:00 - 21:00\"}]', '2026-02-06 16:59:24', '2026-02-06 18:00:40', NULL, 'Member'),
(5, 'Dr. Evelyn Ignacio', 'BSIT', 'Industry Expert', '09563566325', '[{\"date\":\"2026-02-06\",\"time\":\"08:00 - 21:00\"}]', '2026-02-06 17:29:21', '2026-02-06 17:29:21', NULL, 'Chairperson'),
(6, 'Juan Dela Torre', 'BSIT', 'Instructor', '09863541256', '[{\"date\":\"2026-02-06\",\"time\":\"08:00 - 21:00\"}]', '2026-02-06 18:01:46', '2026-02-06 18:01:46', NULL, 'Member');

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
('9lUVJm6EJM10phq1zwRUasNQMCD7CBfeMt0ytD88', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicmpSM2llaGpTQjJoZHlZTkY4ZTVzR2IySmNxSGZEMVZRZVNJSWVBUCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FwaS9hZG1pbi9ub3RpZmljYXRpb25zIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1770432303),
('am9Q8VGU0WCDEYG5WKImyjyl3ziymWMcnydmBT5z', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoickVGN3ltYXIyaGJyUkZBZk1WNkloRXdSZkFkT3FxSndtQU1yVUJOZCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FwaS9jb29yZGluYXRvci9ub3RpZmljYXRpb25zIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1770432303);

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
  `professor_id` varchar(255) DEFAULT NULL,
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
  `deleted_at` timestamp NULL DEFAULT NULL,
  `group_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `professor_id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `role`, `otp`, `otp_expires_at`, `remember_token`, `created_at`, `updated_at`, `department`, `contact`, `cluster`, `group_no`, `deleted_at`, `group_id`) VALUES
(1, NULL, 'Admin', 'crad7465@gmail.com', '2026-02-06 14:39:19', '$2y$12$mXkem47vm8uHN9opRKauV.L0HDuWYHbF3DbzmpZG2SuP5rEsb3scW', 1, 'admin', NULL, NULL, NULL, '2026-02-05 04:15:04', '2026-02-06 14:39:19', NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, 'Raymon Loria', 'raymon@gmail.com', NULL, '$2y$12$OOORMrSo9pzG1s/8PsEFa.LOB6lizCZ/8Yem4EraBlWzr3gcE2Sbq', 0, 'coordinator', NULL, NULL, NULL, '2026-02-05 04:24:24', '2026-02-06 11:57:11', 'BSIT', '09789565985', NULL, NULL, '2026-02-06 11:57:11', NULL),
(22, 'EMPLOYEE-2026-0006', 'Shozu Abadenis i. integrated na i. integrated na', 'shozuabadenis@gmail.com', NULL, '$2y$12$ODDxPRsXld8eBZ136o6wDeeeOY1uE4rNdDlzoMqtEJnv0l4bQOczO', 0, 'coordinator', NULL, NULL, NULL, '2026-02-06 13:46:44', '2026-02-06 13:58:58', 'BSIT', NULL, NULL, NULL, NULL, NULL),
(40, NULL, 'Group IT001', 'sIT001', NULL, '$2y$12$EAsgFGgAo9WxwC8qZpwEa.HWNdwP3zRYa1s18FIJ0092b2lvyvgNu', 0, 'student', NULL, NULL, NULL, '2026-02-06 15:55:52', '2026-02-06 15:55:52', 'BSIT', NULL, 4101, 1, NULL, NULL),
(41, NULL, 'Group IT002', 'sIT002', NULL, '$2y$12$rmPYPIqJKOtP//kYljksAuFUQdJ3Bjo04HFJisfeYCrwBZp8C9CtS', 0, 'student', NULL, NULL, NULL, '2026-02-06 15:55:52', '2026-02-06 15:55:52', 'BSIT', NULL, 4101, 2, NULL, NULL),
(42, NULL, 'Group IT003', 'sIT003', NULL, '$2y$12$3qaBVBQDx0BMn20qYqtpS.9F9v0a2adpu9E6YHFuWelO2G/T9ByDa', 0, 'student', NULL, NULL, NULL, '2026-02-06 15:55:52', '2026-02-06 15:55:52', 'BSIT', NULL, 4101, 3, NULL, NULL),
(43, NULL, 'Group IT004', 'sIT004', NULL, '$2y$12$FkNJJA.TQVYiYX39OsLMyui2sKPHVPgX3uKOXZsIMSQF6aUMXQEfm', 0, 'student', NULL, NULL, NULL, '2026-02-06 15:58:08', '2026-02-06 15:58:08', 'BSIT', NULL, 4101, 4, NULL, NULL),
(44, NULL, 'Group IT005', 'sIT005', NULL, '$2y$12$sD1CwVj9Y8APZ2DEHbgkguveUTTdqc.JHVZFeCDqMXMA/DePD.9Ty', 0, 'student', NULL, NULL, NULL, '2026-02-06 15:58:37', '2026-02-06 15:58:37', 'BSIT', NULL, 4101, 5, NULL, NULL),
(45, NULL, 'Group IT011', 'sIT011', NULL, '$2y$12$p3sgAKpI0Czk7IK2tir5De9tCpJ36rXOGnTRiUxwb1TrY7Ajov/QC', 0, 'student', NULL, NULL, NULL, '2026-02-06 16:14:04', '2026-02-06 16:14:04', 'BSIT', NULL, 4102, 11, NULL, NULL),
(46, NULL, 'Group IT012', 'sIT012', NULL, '$2y$12$0XTWc4DSLwm73BnAMssCx.dVNggqVm.4KJEzmQEIJJL/hJNw54fz2', 0, 'student', NULL, NULL, NULL, '2026-02-06 16:14:34', '2026-02-06 16:14:34', 'BSIT', NULL, 4102, 12, NULL, NULL),
(47, NULL, 'Group IT013', 'sIT013', NULL, '$2y$12$lxY5rVW1YMytib8UKlv7Hu2NGTsyRbM7ctNlPa0bA19UJyJOZ2oGO', 0, 'student', NULL, NULL, NULL, '2026-02-06 16:15:07', '2026-02-06 16:15:07', 'BSIT', NULL, 4102, 13, NULL, NULL),
(48, NULL, 'Group IT014', 'sIT014', NULL, '$2y$12$0diJJud2lZdo8LaS2.tVZeUcWU4hPPK8zJCgjLcXiAC1fPqhp2NLe', 0, 'student', NULL, NULL, NULL, '2026-02-06 17:25:35', '2026-02-06 17:25:35', 'BSIT', NULL, 4102, 14, NULL, NULL),
(49, NULL, 'Group IT015', 'sIT015', NULL, '$2y$12$cnghCpJjOj1.kTa2RnGn/eJu7QLfglAb7ZmhrbzQc6GqfIw1BCwuG', 0, 'student', NULL, NULL, NULL, '2026-02-06 17:26:27', '2026-02-06 17:26:27', 'BSIT', NULL, 4102, 15, NULL, NULL);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clusters_cluster_number_department_unique` (`cluster_number`,`department`);

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
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faculties_professor_id_unique` (`professor_id`),
  ADD UNIQUE KEY `faculties_email_unique` (`email`);

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `assignment_panels`
--
ALTER TABLE `assignment_panels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `assignment_panel_member`
--
ALTER TABLE `assignment_panel_member`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clusters`
--
ALTER TABLE `clusters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `defense_schedules`
--
ALTER TABLE `defense_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `panels`
--
ALTER TABLE `panels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `submission_histories`
--
ALTER TABLE `submission_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

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

--
-- Constraints for table `committees`
--
ALTER TABLE `committees`
  ADD CONSTRAINT `committees_submission_id_foreign` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `otps`
--
ALTER TABLE `otps`
  ADD CONSTRAINT `otps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `panel_slots`
--
ALTER TABLE `panel_slots`
  ADD CONSTRAINT `panel_slots_panel_id_foreign` FOREIGN KEY (`panel_id`) REFERENCES `professors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resubmission_histories`
--
ALTER TABLE `resubmission_histories`
  ADD CONSTRAINT `resubmission_histories_submission_id_foreign` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submission_histories`
--
ALTER TABLE `submission_histories`
  ADD CONSTRAINT `submission_histories_submission_id_foreign` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `submission_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
