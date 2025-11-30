-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 30, 2025 at 04:50 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dailylog`
--

-- --------------------------------------------------------

--
-- Table structure for table `builders`
--

CREATE TABLE `builders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `builders`
--

INSERT INTO `builders` (`id`, `title`, `description`, `archive`, `created_at`, `updated_at`) VALUES
(1, 'testing', '1', 0, '2025-11-30 07:05:29', '2025-11-30 07:05:29');

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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `description`, `archive`, `created_at`, `updated_at`) VALUES
(13, 'SPDR Admin', NULL, 0, '2023-07-04 12:44:37', '2025-09-04 06:53:21'),
(14, 'SPDR Training', NULL, 0, '2023-07-04 12:44:37', '2025-09-04 06:53:21'),
(17, 'SPDR OPS', NULL, 0, '2023-07-05 03:30:49', '2025-09-04 06:53:21'),
(18, 'SPDR Commercial', NULL, 0, '2023-08-10 01:39:34', '2025-09-04 06:53:21');

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `title`, `description`, `archive`, `created_at`, `updated_at`) VALUES
(14, 'Admin', NULL, 0, '2024-04-03 19:56:06', '2025-11-30 06:40:35');

-- --------------------------------------------------------

--
-- Table structure for table `dwelings`
--

CREATE TABLE `dwelings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dwelings`
--

INSERT INTO `dwelings` (`id`, `title`, `description`, `archive`, `created_at`, `updated_at`) VALUES
(1, 'test', 'ike', 0, '2025-11-30 07:05:16', '2025-11-30 07:05:16');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `join_date` date NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `can_approve` tinyint(1) NOT NULL DEFAULT 0,
  `cutoff_exception` tinyint(1) NOT NULL DEFAULT 0,
  `is_supervisor` tinyint(1) NOT NULL DEFAULT 0,
  `division_id` bigint(20) UNSIGNED NOT NULL,
  `sub_division_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `superior_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `join_date`, `is_admin`, `can_approve`, `cutoff_exception`, `is_supervisor`, `division_id`, `sub_division_id`, `role_id`, `position_id`, `description`, `archive`, `is_approved`, `remember_token`, `created_at`, `updated_at`, `user_id`, `superior_id`) VALUES
(1, 'Alberta', 'alberta@gmail.com', 'alberta@gmail.com', NULL, '$2y$12$Ntjf0EzPuIiCaSW0Je7ZsOoSIUSNZYR.vakRS5Wu5V26unkTPAL.a', '2025-11-30', 1, 1, 1, 0, 14, 11, 4, 6, NULL, 0, 1, NULL, '2025-11-30 07:59:10', '2025-11-30 08:16:34', 1, NULL),
(2, 'admin', 'admin@gmail.com', 'admin@gmail.com', NULL, '$2y$12$V6u10kxUC0R4/jbTdfmuguTvMjHTKBmUqKwyJ/ruN6B90dqO03Vqy', '2025-11-30', 1, 1, 0, 0, 14, 11, 4, 3, 'admin', 1, 1, NULL, '2025-11-30 08:02:47', '2025-11-30 08:15:40', 2, 1);

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
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `description` text DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Annual Leave', 'Description', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(2, 'AWOL', 'Description', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(3, 'Half-Day', 'half-day off given by the company', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(4, 'Half-Day Off', 'half-day leave taken by employee from annual leave balance', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(5, 'Inactive', 'to show on monthly report google sheet', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(6, 'Joint Leave', 'Description', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(7, 'Maternity Leave', 'not reducing annual leave', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(8, 'No Assignments', 'Description', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(9, 'Public Holiday', 'placeholder, from regular holiday', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(10, 'Public Holiday Replacement', 'Description', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(11, 'Sick Leave', 'Description', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(12, 'SPDR Commercial Assignment', 'Description', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(13, 'Special Leave', 'Description', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(14, 'Unpaid Leave', 'Description', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(15, 'Weekend', 'Description', '2025-10-06 23:56:42', '2025-10-06 23:56:42'),
(16, 'Disaster Leave', 'bencana alam', '2025-11-28 18:00:29', '2025-11-28 18:00:29');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `builder_id` bigint(20) UNSIGNED NOT NULL,
  `dweling_id` bigint(20) UNSIGNED NOT NULL,
  `status_id` bigint(20) UNSIGNED NOT NULL,
  `duration` decimal(8,2) NOT NULL DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `work_time` time DEFAULT NULL,
  `temp` tinyint(1) NOT NULL DEFAULT 0,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_date` timestamp NULL DEFAULT NULL,
  `approved_note` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_emoji` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(4, '2025_09_04_132504_create_divisions_table', 1),
(5, '2025_09_04_132508_create_categories_table', 1),
(6, '2025_09_04_132508_create_positions_table', 1),
(7, '2025_09_04_132508_create_roles_table', 1),
(8, '2025_09_04_132508_create_sub_divisions_table', 1),
(9, '2025_09_04_132508_create_tasks_table', 1),
(10, '2025_09_04_132517_create_builders_table', 1),
(11, '2025_09_04_132517_create_dwelings_table', 1),
(12, '2025_09_04_132517_create_status_table', 1),
(13, '2025_09_04_132517_create_work_status_table', 1),
(14, '2025_09_04_132520_create_employees_table', 1),
(15, '2025_09_04_132521_create_holidays_table', 1),
(16, '2025_09_04_132521_create_logs_table', 1),
(17, '2025_09_04_132521_create_offwork_table', 1),
(18, '2025_09_04_132524_create_notifications_table', 1),
(19, '2025_09_04_132524_create_time_cutoff_table', 1),
(20, '2025_09_04_132528_add_employee_fields_to_users_table', 1),
(21, '2025_09_04_141634_fix_logs_employee_foreign_key', 2),
(22, '2025_09_04_144216_fix_logs_employee_foreign_key_to_employees', 3),
(23, '2025_09_04_233904_add_user_id_and_superior_id_to_employees_table', 4),
(24, '2025_09_06_000001_add_approved_by_to_logs_table', 5),
(25, '2025_09_06_065237_add_is_approved_to_employees_table', 6),
(26, '2025_10_07_063305_create_leaves_table', 7),
(27, '2025_10_07_065609_create_leave_types_table', 8),
(29, '2025_10_07_071604_rename_leaves_table_to_offwork_table', 9),
(30, '2025_10_07_072531_drop_leaves_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date` date NOT NULL,
  `read_status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offwork`
--

CREATE TABLE `offwork` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `archive` tinyint(1) NOT NULL DEFAULT 0,
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
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `title`, `description`, `archive`, `created_at`, `updated_at`) VALUES
(1, 'Staff', NULL, 0, '2023-02-15 21:29:19', '2025-09-04 16:39:58'),
(2, 'Supervisor', NULL, 0, '2023-02-20 20:36:57', '2025-09-04 16:39:58'),
(3, 'Manager', NULL, 0, '2023-02-20 20:37:06', '2025-09-04 16:39:58'),
(4, 'Director', NULL, 0, '2023-02-20 20:37:17', '2025-09-04 16:39:58'),
(6, 'Owner', 'Company Owner', 0, '2025-10-06 20:34:52', '2025-10-06 20:34:52');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`, `description`, `archive`, `created_at`, `updated_at`) VALUES
(4, 'Administrator', NULL, 0, '2023-04-05 13:21:21', '2025-09-04 16:39:58');

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
('AEYeO79SrnhR2aITUNtedqXEJEBHomKaWhx8JRrJ', NULL, '127.0.0.1', 'curl/8.7.1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiY0l6MlJWRG1uUktGQUh0UGZ4cnF4SlVuZkxPS0M5R0daNmtsdkRaOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757078752),
('eeMKAzEN9TvZ8vuOwuVYvHCs6p1XBWSDDdIBkRhi', NULL, '127.0.0.1', 'curl/8.7.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibm1tbG1NN0t2TmRudXBIMzZjak1PNDI0czdVNVBNOVFvNEw2U2JsSyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1NDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FwcHJvdmVkL2dldERhdGE/ZGF0ZT0yMDI1LTA5LTA0Ijt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcHByb3ZlZC9nZXREYXRhP2RhdGU9MjAyNS0wOS0wNCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757072960),
('gXf7XgbRxPfYQtEAfKSQnwDiyldWlXYYm1BocRNO', NULL, '127.0.0.1', 'curl/8.7.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaWVjOEJzU2JxUTF6ekhXTWtlNnExWDlxanVrRHIxRzJraHhJUGxzbCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2RpdmlzaW9uIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kaXZpc2lvbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757078758),
('InCY18ncqhQOeFqKfv9Zwx3DIZ5WrhFvYaY75aM0', NULL, '127.0.0.1', 'curl/8.7.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicVo1QVdRMnUxdVY2UHQ1T1RaWVI4M29GTktsbm5TWXVhbWx6bUdmTSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxNjA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kaXZpc2lvbi9nZXREYXRhP2RhdGE9JTdCJTIycGFnZSUyMiUzQTElMkMlMjJsaW1pdCUyMiUzQTEwJTJDJTIyc2VhcmNoJTIyJTNBJTIyJTIyJTJDJTIyc29ydCUyMiUzQSUyMnRpdGxlJTIyJTJDJTIyb3JkZXIlMjIlM0ElMjJBU0MlMjIlN0QiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoxNjA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kaXZpc2lvbi9nZXREYXRhP2RhdGE9JTdCJTIycGFnZSUyMiUzQTElMkMlMjJsaW1pdCUyMiUzQTEwJTJDJTIyc2VhcmNoJTIyJTNBJTIyJTIyJTJDJTIyc29ydCUyMiUzQSUyMnRpdGxlJTIyJTJDJTIyb3JkZXIlMjIlM0ElMjJBU0MlMjIlN0QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757078722),
('LulXfhB1Fud5zthXVRDrDgrPAS6Pk5HntV2fTBMW', 9, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRjZYVE9lZlJRTVNmM29aNE9yU0k5YzN1WG9XWkJXNWVFaTZrT2RTRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTYxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZGl2aXNpb24vZ2V0RGF0YT9kYXRhPSU3QiUyMnBhZ2UlMjIlM0ElMjIlMjIlMkMlMjJsaW1pdCUyMiUzQSUyMiUyMiUyQyUyMnNlYXJjaCUyMiUzQSUyMiUyMiUyQyUyMnNvcnQlMjIlM0ElMjIlMjIlMkMlMjJvcmRlciUyMiUzQSUyMiUyMiU3RCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjk7fQ==', 1757078439);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `title`, `description`, `archive`, `created_at`, `updated_at`) VALUES
(1, '10%', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(2, '100%', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(3, '20%', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(4, '30%', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(5, '40%', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(6, '50%', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(7, '60%', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(8, '70%', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(9, '80%', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(10, '90% (Review)', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(11, 'Cancelled', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(12, 'Completed', 'final completed status, only approver can use this', 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(14, 'Follow-up sent', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(17, 'On Pending', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(34, 'On Review', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(38, 'Pending', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(40, 'Quote Sent', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(45, 'Waiting On Builder', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(48, 'Waiting On Supplier', NULL, 0, '2023-07-04 10:44:16', '2025-09-04 06:53:22'),
(49, 'On Revision', NULL, 0, '2023-07-05 04:29:40', '2025-09-04 06:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `sub_divisions`
--

CREATE TABLE `sub_divisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `division_id` bigint(20) UNSIGNED NOT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_divisions`
--

INSERT INTO `sub_divisions` (`id`, `title`, `description`, `division_id`, `archive`, `created_at`, `updated_at`) VALUES
(11, 'Administrator', NULL, 14, 0, '2025-11-30 06:39:38', '2025-11-30 06:39:38');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `archive`, `created_at`, `updated_at`) VALUES
(1, 'testtt', '123', 0, '2025-11-30 07:05:41', '2025-11-30 07:05:41');

-- --------------------------------------------------------

--
-- Table structure for table `time_cutoff`
--

CREATE TABLE `time_cutoff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `time` time NOT NULL,
  `day_offset` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
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
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `join_date` date NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `can_approve` tinyint(1) NOT NULL DEFAULT 0,
  `cutoff_exception` tinyint(1) NOT NULL DEFAULT 0,
  `is_supervisor` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `join_date`, `is_admin`, `can_approve`, `cutoff_exception`, `is_supervisor`, `password`, `remember_token`, `created_at`, `updated_at`, `division_id`, `sub_division_id`, `role_id`, `position_id`, `description`, `archive`) VALUES
(1, 'Alberta', 'alberta@gmail.com', 'alberta@gmail.com', NULL, '2025-11-30', 1, 1, 1, 0, '$2y$12$Ntjf0EzPuIiCaSW0Je7ZsOoSIUSNZYR.vakRS5Wu5V26unkTPAL.a', NULL, '2025-11-30 07:59:10', '2025-11-30 08:16:34', 14, 11, 4, 6, NULL, 0),
(2, 'admin', 'admin@gmail.com', 'admin@gmail.com', NULL, '2025-11-30', 1, 1, 0, 0, '$2y$12$V6u10kxUC0R4/jbTdfmuguTvMjHTKBmUqKwyJ/ruN6B90dqO03Vqy', NULL, '2025-11-30 08:02:57', '2025-11-30 08:19:01', 14, 11, 4, 3, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `work_status`
--

CREATE TABLE `work_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `archive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `builders`
--
ALTER TABLE `builders`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dwelings`
--
ALTER TABLE `dwelings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_username_unique` (`username`),
  ADD UNIQUE KEY `employees_email_unique` (`email`),
  ADD KEY `employees_division_id_foreign` (`division_id`),
  ADD KEY `employees_sub_division_id_foreign` (`sub_division_id`),
  ADD KEY `employees_role_id_foreign` (`role_id`),
  ADD KEY `employees_position_id_foreign` (`position_id`),
  ADD KEY `employees_user_id_foreign` (`user_id`),
  ADD KEY `employees_superior_id_foreign` (`superior_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
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
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logs_category_id_foreign` (`category_id`),
  ADD KEY `logs_task_id_foreign` (`task_id`),
  ADD KEY `logs_builder_id_foreign` (`builder_id`),
  ADD KEY `logs_dweling_id_foreign` (`dweling_id`),
  ADD KEY `logs_status_id_foreign` (`status_id`),
  ADD KEY `logs_employee_id_foreign` (`employee_id`),
  ADD KEY `logs_approved_by_foreign` (`approved_by`);

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
  ADD KEY `notifications_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `offwork`
--
ALTER TABLE `offwork`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offwork_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_divisions`
--
ALTER TABLE `sub_divisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_divisions_division_id_foreign` (`division_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_cutoff`
--
ALTER TABLE `time_cutoff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_division_id_foreign` (`division_id`),
  ADD KEY `users_sub_division_id_foreign` (`sub_division_id`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_position_id_foreign` (`position_id`);

--
-- Indexes for table `work_status`
--
ALTER TABLE `work_status`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `builders`
--
ALTER TABLE `builders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `dwelings`
--
ALTER TABLE `dwelings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offwork`
--
ALTER TABLE `offwork`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `sub_divisions`
--
ALTER TABLE `sub_divisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `time_cutoff`
--
ALTER TABLE `time_cutoff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `work_status`
--
ALTER TABLE `work_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employees_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employees_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employees_sub_division_id_foreign` FOREIGN KEY (`sub_division_id`) REFERENCES `sub_divisions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employees_superior_id_foreign` FOREIGN KEY (`superior_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `logs_builder_id_foreign` FOREIGN KEY (`builder_id`) REFERENCES `builders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `logs_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `logs_dweling_id_foreign` FOREIGN KEY (`dweling_id`) REFERENCES `dwelings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `logs_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `logs_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `logs_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `offwork`
--
ALTER TABLE `offwork`
  ADD CONSTRAINT `offwork_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_divisions`
--
ALTER TABLE `sub_divisions`
  ADD CONSTRAINT `sub_divisions_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_sub_division_id_foreign` FOREIGN KEY (`sub_division_id`) REFERENCES `sub_divisions` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
