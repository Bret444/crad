-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost: 3306
-- Generation Time: Feb 06, 2026 at 03:38 AM
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
(1, 'Andrew Adovas', '09123659852', 'BSIT', 'Assistant Professor', '\"[1,2,3,4,5,6,7,8,9,10]\"', '2026-02-05 08:42:34', '2026-02-05 09:39:58', NULL);

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

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('it001|127.0.0.1', 'i:1;', 1770300657),
('it001|127.0.0.1:timer', 'i:1770300657;', 1770300657),
('sit001|127.0.0.1', 'i:1;', 1770345010),
('sit001|127.0.0.1:timer', 'i:1770345010;', 1770345010);

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
(2, '4102', 'BSIT', '2026-02-05 09:39:38', '2026-02-05 09:39:38');

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
(2, 'BSIT', '4101', 'IT001', 'PRE-ORAL', NULL, 1, '2026-02-14', '08:00:00', '09:00:00', NULL, 'Scheduled', '{\"adviser\":\"Andrew Adovas\",\"chairperson\":\"Dr. Hayley Nichole Williams\",\"members\":\"Jenny Bigcas, Mark Avelino\"}', '2026-02-05 11:27:34', '2026-02-05 11:27:34', NULL),
(3, 'BSIT', '4101', 'IT002', 'PRE-ORAL', NULL, 1, '2026-02-14', '09:00:00', '10:00:00', NULL, 'Scheduled', '{\"adviser\":\"Andrew Adovas\",\"chairperson\":\"Dr. Hayley Nichole Williams\",\"members\":\"Jenny Bigcas, Mark Avelino\"}', '2026-02-05 11:27:34', '2026-02-05 11:27:34', NULL);

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
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `department`, `cluster`, `group_number`, `member1_name`, `member1_student_id`, `member2_name`, `member2_student_id`, `member3_name`, `member3_student_id`, `member4_name`, `member4_student_id`, `member5_name`, `member5_student_id`, `leader_member`, `created_at`, `updated_at`, `group_id`, `password`) VALUES
(5, 'BSIT', '4101', 1, 'Jairo Luis Indoso', '22018863', 'Bret Baa', '22018864', 'Kim Kenneth Elacion', '22018865', 'Michael Angelo Amata', '22018866', 'Ana Juego', '22018867', 1, '2026-02-05 06:09:32', '2026-02-05 06:09:40', 'IT001', '$2y$12$Tjv0n.aCmM93KlSVO9ChoeKtMTDDRdJf1Y2MdrU/bE3zLEKSJmX.a'),
(6, 'BSIT', '4101', 2, 'Mark Andrei Soriano', '2000001', 'Clarisse Sang-olan', '2000002', 'Arnold Ampo', '2000003', 'Vincent Basa', '2000004', 'Mark Apuli', '2000005', 1, '2026-02-05 06:22:05', '2026-02-05 06:22:05', 'IT002', '$2y$12$lm3gJRZJWd/QCvdlVT3YUeQYz77QdBEjG4cRhSlNBqUgBkhjJ9fVO'),
(7, 'BSIT', '4102', 11, 'jose', '1111', 'rizal', '2222', 'boni', '3333', 'facio', '4444', 'luna', '5555', 1, '2026-02-05 06:29:55', '2026-02-05 06:29:55', 'IT011', '$2y$12$h3h2mzmfzj65OQnL7bI4de4jRQloB7Sk8u7tCshCDgzDYxSGgXRLG');

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
(58, '2026_02_05_164823_add_contact_to_advisers_table', 5);

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
(3, 'Mark Avelino', 'BSIT', 'Instructor', '09532147989', '[{\"date\":\"2026-02-14\",\"time\":\"08:00 - 09:00\"},{\"date\":\"2026-02-10\",\"time\":\"08:00 - 21:00\"}]', '2026-02-05 10:52:35', '2026-02-05 10:52:35', NULL, 'Member');

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
('9pCHJsdYyteSVTOHsmu8X5jejLxnlrAQnOinuMCK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicUk4cGVMbTg5bW4zMUVQZ2t3RVBkbmhwTExleGprQlNmVHV4OFRHQSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2RlZi1zY2hlZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZGVmLXNjaGVkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1770343926),
('JN07Wx1FvWiPQbesCmeervrJ0PYXfDR9LNuG8YvN', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZEJodGd4T0NmMDY0RVZxY0hXckdMN1VFUkRSb1RCRGY0aG5wTGpGNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdWJtaXNzaW9uIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Nzt9', 1770345258),
('NtJdoNCWqSxwa0KB3Kxn0MJ7g9iMqSXJmMwllBdl', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiNXo2emJvQ2JwY3FHbFp1a2pROWZ6UXZVVHprMXNkNlVLNXFDSW5KNyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2RlZi1zY2hlZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcGFuZWwtYWR2aXNlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTQ6Im90cF9leHBpcmVzX2F0IjtpOjE3NzAzNDQ1NDk7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1770344882),
('VIj6nP9Vh13jUVxwEQmmlAFMv7IenOZFXSVcoU5V', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZ0tyUlhlRGxyUkF5Tk9jZWpVYkI1N3JBdldwVEhDa3dlZGt2SWZidSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0ODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Nvb3JkaW5hdG9yLXRpdGxlLXByb3Bvc2FsIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jb29yZGluYXRvci1tYW5hZ2UtZ3JvdXBzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1770344974);

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

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `role`, `otp`, `otp_expires_at`, `remember_token`, `created_at`, `updated_at`, `department`, `contact`, `cluster`, `group_no`, `deleted_at`, `group_id`) VALUES
(1, 'Admin', 'crad7465@gmail.com', '2026-02-05 18:12:59', '$2y$12$mXkem47vm8uHN9opRKauV.L0HDuWYHbF3DbzmpZG2SuP5rEsb3scW', 1, 'admin', NULL, NULL, NULL, '2026-02-05 04:15:04', '2026-02-05 18:12:59', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Raymon Loria', 'raymon@gmail.com', NULL, '$2y$12$OOORMrSo9pzG1s/8PsEFa.LOB6lizCZ/8Yem4EraBlWzr3gcE2Sbq', 0, 'coordinator', NULL, NULL, NULL, '2026-02-05 04:24:24', '2026-02-05 04:24:39', 'BSIT', '09789565985', NULL, NULL, NULL, NULL),
(5, 'IT001', 'sIT001', NULL, '$2y$12$Tjv0n.aCmM93KlSVO9ChoeKtMTDDRdJf1Y2MdrU/bE3zLEKSJmX.a', 0, 'student', NULL, NULL, NULL, '2026-02-05 06:17:48', '2026-02-05 06:18:49', 'BSIT', NULL, 4101, 1, NULL, 'IT001'),
(7, 'IT002', 'sIT002', NULL, '$2y$12$lm3gJRZJWd/QCvdlVT3YUeQYz77QdBEjG4cRhSlNBqUgBkhjJ9fVO', 0, 'student', NULL, NULL, NULL, '2026-02-05 06:22:05', '2026-02-05 06:22:05', 'BSIT', NULL, 4101, 2, NULL, NULL),
(8, 'IT011', 'sIT011', NULL, '$2y$12$h3h2mzmfzj65OQnL7bI4de4jRQloB7Sk8u7tCshCDgzDYxSGgXRLG', 0, 'student', NULL, NULL, NULL, '2026-02-05 06:29:55', '2026-02-05 06:29:55', 'BSIT', NULL, 4102, 11, NULL, NULL);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `panels`
--
ALTER TABLE `panels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submission_histories`
--
ALTER TABLE `submission_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
