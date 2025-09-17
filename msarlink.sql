-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 11, 2025 at 12:13 PM
-- Server version: 8.0.41
-- PHP Version: 8.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `msarlink`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int NOT NULL DEFAULT '1',
  `image` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `slug`, `description`, `content`, `created_at`, `updated_at`, `active`, `sort`, `image`) VALUES
(1, 'مقال جديد', 'مقال-جديد', 'sdfsdfs', '<p>sfsdfsfsdff</p>', '2024-04-20 09:09:57', '2024-04-20 10:16:10', 1, 1, '{\"files\": [{\"size\": \"70.771\", \"type\": \"image/jpeg\", \"order\": 1, \"thumbs\": [], \"is_image\": 1, \"raw_name\": \"img_1.jpg\", \"dimension\": \"800*800\", \"extension\": \"jpg\", \"full_path\": \"uploads/articles/20_04_2024/1713611770_33e656c3f03fab79a589.jpg\", \"encoded_name\": \"1713611770_33e656c3f03fab79a589.jpg\", \"original_name\": \"img_1.jpg\"}], \"fileCount\": 1}'),
(2, 'مقال جديد', 'مقال-جديد', 'sdfsdfs', '<p>sfsdfsfsdff</p>', '2024-04-20 09:09:57', '2024-04-20 10:16:10', 1, 1, '{\"files\": [{\"size\": \"70.771\", \"type\": \"image/jpeg\", \"order\": 1, \"thumbs\": [], \"is_image\": 1, \"raw_name\": \"img_1.jpg\", \"dimension\": \"800*800\", \"extension\": \"jpg\", \"full_path\": \"uploads/articles/20_04_2024/1713611770_33e656c3f03fab79a589.jpg\", \"encoded_name\": \"1713611770_33e656c3f03fab79a589.jpg\", \"original_name\": \"img_1.jpg\"}], \"fileCount\": 1}'),
(3, 'مقال جديد', 'مقال-جديد', 'sdfsdfs', '<p>sfsdfsfsdff</p>', '2024-04-20 09:09:57', '2024-04-20 10:16:10', 1, 1, '{\"files\": [{\"size\": \"70.771\", \"type\": \"image/jpeg\", \"order\": 1, \"thumbs\": [], \"is_image\": 1, \"raw_name\": \"img_1.jpg\", \"dimension\": \"800*800\", \"extension\": \"jpg\", \"full_path\": \"uploads/articles/20_04_2024/1713611770_33e656c3f03fab79a589.jpg\", \"encoded_name\": \"1713611770_33e656c3f03fab79a589.jpg\", \"original_name\": \"img_1.jpg\"}], \"fileCount\": 1}'),
(4, 'مقال جديد', 'مقال-جديد', 'sdfsdfs', '<p>sfsdfsfsdff</p>', '2024-04-20 09:09:57', '2024-04-20 10:16:10', 1, 1, '{\"files\": [{\"size\": \"70.771\", \"type\": \"image/jpeg\", \"order\": 1, \"thumbs\": [], \"is_image\": 1, \"raw_name\": \"img_1.jpg\", \"dimension\": \"800*800\", \"extension\": \"jpg\", \"full_path\": \"uploads/articles/20_04_2024/1713611770_33e656c3f03fab79a589.jpg\", \"encoded_name\": \"1713611770_33e656c3f03fab79a589.jpg\", \"original_name\": \"img_1.jpg\"}], \"fileCount\": 1}'),
(5, 'مقال جديد', 'مقال-جديد', 'sdfsdfs', '<p>sfsdfsfsdff</p>', '2024-04-20 09:09:57', '2024-04-20 10:16:10', 1, 1, '{\"files\": [{\"size\": \"70.771\", \"type\": \"image/jpeg\", \"order\": 1, \"thumbs\": [], \"is_image\": 1, \"raw_name\": \"img_1.jpg\", \"dimension\": \"800*800\", \"extension\": \"jpg\", \"full_path\": \"uploads/articles/20_04_2024/1713611770_33e656c3f03fab79a589.jpg\", \"encoded_name\": \"1713611770_33e656c3f03fab79a589.jpg\", \"original_name\": \"img_1.jpg\"}], \"fileCount\": 1}'),
(6, 'مقال جديد', 'مقال-جديد', 'sdfsdfs', '<p>sfsdfsfsdff</p>', '2024-04-20 09:09:57', '2024-04-20 10:16:10', 1, 1, '{\"files\": [{\"size\": \"70.771\", \"type\": \"image/jpeg\", \"order\": 1, \"thumbs\": [], \"is_image\": 1, \"raw_name\": \"img_1.jpg\", \"dimension\": \"800*800\", \"extension\": \"jpg\", \"full_path\": \"uploads/articles/20_04_2024/1713611770_33e656c3f03fab79a589.jpg\", \"encoded_name\": \"1713611770_33e656c3f03fab79a589.jpg\", \"original_name\": \"img_1.jpg\"}], \"fileCount\": 1}'),
(7, 'مقال جديد', 'مقال-جديد', 'sdfsdfs', '<p>sfsdfsfsdff</p>', '2024-04-20 09:09:57', '2024-04-20 10:16:10', 1, 1, '{\"files\": [{\"size\": \"70.771\", \"type\": \"image/jpeg\", \"order\": 1, \"thumbs\": [], \"is_image\": 1, \"raw_name\": \"img_1.jpg\", \"dimension\": \"800*800\", \"extension\": \"jpg\", \"full_path\": \"uploads/articles/20_04_2024/1713611770_33e656c3f03fab79a589.jpg\", \"encoded_name\": \"1713611770_33e656c3f03fab79a589.jpg\", \"original_name\": \"img_1.jpg\"}], \"fileCount\": 1}'),
(8, 'مقال جديد', 'مقال-جديد', 'sdfsdfs', '<p>sfsdfsfsdff</p>', '2024-04-20 09:09:57', '2024-04-20 10:16:10', 1, 1, '{\"files\": [{\"size\": \"70.771\", \"type\": \"image/jpeg\", \"order\": 1, \"thumbs\": [], \"is_image\": 1, \"raw_name\": \"img_1.jpg\", \"dimension\": \"800*800\", \"extension\": \"jpg\", \"full_path\": \"uploads/articles/20_04_2024/1713611770_33e656c3f03fab79a589.jpg\", \"encoded_name\": \"1713611770_33e656c3f03fab79a589.jpg\", \"original_name\": \"img_1.jpg\"}], \"fileCount\": 1}'),
(9, 'مقال جديد', 'مقال-جديد', 'sdfsdfs', '<p>sfsdfsfsdff</p>', '2024-04-20 09:09:57', '2024-04-20 10:16:10', 1, 1, '{\"files\": [{\"size\": \"70.771\", \"type\": \"image/jpeg\", \"order\": 1, \"thumbs\": [], \"is_image\": 1, \"raw_name\": \"img_1.jpg\", \"dimension\": \"800*800\", \"extension\": \"jpg\", \"full_path\": \"uploads/articles/20_04_2024/1713611770_33e656c3f03fab79a589.jpg\", \"encoded_name\": \"1713611770_33e656c3f03fab79a589.jpg\", \"original_name\": \"img_1.jpg\"}], \"fileCount\": 1}'),
(10, 'مقال جديد', 'مقال-جديد', 'sdfsdfs', '<p>sfsdfsfsdff</p>', '2024-04-20 09:09:57', '2024-04-20 10:16:10', 1, 1, '{\"files\": [{\"size\": \"70.771\", \"type\": \"image/jpeg\", \"order\": 1, \"thumbs\": [], \"is_image\": 1, \"raw_name\": \"img_1.jpg\", \"dimension\": \"800*800\", \"extension\": \"jpg\", \"full_path\": \"uploads/articles/20_04_2024/1713611770_33e656c3f03fab79a589.jpg\", \"encoded_name\": \"1713611770_33e656c3f03fab79a589.jpg\", \"original_name\": \"img_1.jpg\"}], \"fileCount\": 1}'),
(11, 'مقال جديد', 'مقال-جديد', 'sdfsdfs', '<p>sfsdfsfsdff</p>', '2024-04-20 09:09:57', '2024-04-20 10:16:10', 1, 1, '{\"files\": [{\"size\": \"70.771\", \"type\": \"image/jpeg\", \"order\": 1, \"thumbs\": [], \"is_image\": 1, \"raw_name\": \"img_1.jpg\", \"dimension\": \"800*800\", \"extension\": \"jpg\", \"full_path\": \"uploads/articles/20_04_2024/1713611770_33e656c3f03fab79a589.jpg\", \"encoded_name\": \"1713611770_33e656c3f03fab79a589.jpg\", \"original_name\": \"img_1.jpg\"}], \"fileCount\": 1}');

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups`
--

CREATE TABLE `auth_groups` (
  `id` int NOT NULL,
  `group_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_groups`
--

INSERT INTO `auth_groups` (`id`, `group_name`, `title`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SuperAdmin', 'ادارة الموقع', ' لديه جميع الصلاحيات', NULL, '2023-10-23 15:15:16', NULL),
(2, 'User', 'المستخدمين', 'المستخدمين ليس لديهم اي صلاحيات داخل لوحة التحكم', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`id`, `user_id`, `group`, `created_at`) VALUES
(1, 1, 'superadmin', '2023-08-08 15:08:48'),
(4, 2328, 'superadmin', '2024-01-12 19:49:50'),
(10, 3205, 'user', '2025-01-21 13:22:44');

-- --------------------------------------------------------

--
-- Table structure for table `auth_identities`
--

CREATE TABLE `auth_identities` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `secret2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `extra` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `force_reset` tinyint(1) NOT NULL DEFAULT '0',
  `last_used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_identities`
--

INSERT INTO `auth_identities` (`id`, `user_id`, `type`, `name`, `secret`, `secret2`, `expires`, `extra`, `force_reset`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'email_password', NULL, 'spcialist@gmail.com', '$2y$12$oPYzuzAqnhFew7pvhlhJTu9kjr03NejJuZPCehKheSOIhrPQ8Kwbe', NULL, NULL, 0, '2025-09-11 10:46:10', '2023-08-08 15:08:48', '2025-09-11 10:46:10');

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int UNSIGNED NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `user_agent`, `id_type`, `identifier`, `user_id`, `date`, `success`) VALUES
(1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'email_password', 'spcialist@gmail.com', NULL, '2025-09-11 10:10:10', 0),
(2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'email_password', 'amd@dt4it.com', NULL, '2025-09-11 10:10:31', 0),
(3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'email_password', 'spcialist@gmail.com', NULL, '2025-09-11 10:13:09', 0),
(4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'email_password', 'spcialist@gmail.com', 1, '2025-09-11 10:46:10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions`
--

CREATE TABLE `auth_permissions` (
  `id` int NOT NULL,
  `permission_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_permissions`
--

INSERT INTO `auth_permissions` (`id`, `permission_name`, `title`, `updated_at`, `created_at`) VALUES
(1, 'users.create', 'اضافة مستخدم', NULL, NULL),
(2, 'users.edit', 'تعديل مستخدم', NULL, NULL),
(3, 'users.delete', 'حذف مستخدم', NULL, NULL),
(4, 'users.manage', 'ادارة المستخدمين', NULL, NULL),
(5, 'users.show', 'مشاهدة مستخدم', NULL, NULL),
(6, 'settings.manage', 'ادارة اعدادات', NULL, NULL),
(7, 'settings.edit', 'تعديل اعدادات', NULL, NULL),
(8, 'settings.delete', 'حذف اعدادات', NULL, NULL),
(9, 'settings.show', 'مشاهدة اعدادات', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions_users`
--

CREATE TABLE `auth_permissions_users` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `permission` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `group_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_permissions_users`
--

INSERT INTO `auth_permissions_users` (`id`, `user_id`, `permission`, `created_at`, `group_id`) VALUES
(13, 1, '0', '2023-08-24 14:48:11', 2),
(14, 1, '1', '2023-08-24 14:48:11', 2),
(15, 1, '3', '2023-08-24 14:48:11', 2),
(16, 1, '4', '2023-08-24 14:48:11', 2),
(17, 1, '5', '2023-08-24 14:48:11', 2),
(18, 1, '6', '2023-08-24 14:48:11', 2),
(19, 1, '7', '2023-08-24 14:48:11', 2),
(20, 1, '8', '2023-08-24 14:48:11', 2);

-- --------------------------------------------------------

--
-- Table structure for table `auth_token_logins`
--

CREATE TABLE `auth_token_logins` (
  `id` int UNSIGNED NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int NOT NULL,
  `module_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_mobile` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `send_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_message` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `study_year` tinyint(1) DEFAULT NULL,
  `selected_course` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `module_name`, `contact_name`, `contact_email`, `contact_mobile`, `send_to`, `contact_subject`, `contact_message`, `created_at`, `updated_at`, `is_read`, `study_year`, `selected_course`) VALUES
(1, NULL, 'احمد محمود', 'webeasystep@gmail.com', '0555555555', NULL, 'new subscription', 'سبسيبسب', '2024-04-20 06:51:12', '2024-04-20 06:51:12', 0, 1, 'registerDigitalTech'),
(2, 'subscription', 'احمد محمود', 'ahmed_fakher2003@yahoo.com', '0555555555', 'info@msarlink.com', 'new subscription', 'afasfaf', '2024-04-20 06:55:18', '2024-04-20 06:55:18', 0, 1, 'registerDigitalTech'),
(3, 'subscription', 'sfsfsf', 'auditor@dt4it.com', '0555555555', 'info@msarlink.com', 'new subscription', 'sfsf', '2024-04-20 08:15:06', '2024-04-20 08:15:06', 0, 1, 'IoT'),
(4, 'subscription', 'sfsfsf', 'webeasystep@gmail.com', '0555555555', 'info@msarlink.com', 'new subscription', 'sddgdgdg', '2024-04-20 08:29:15', '2024-04-20 08:29:15', 0, 1, 'DigitalTech');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2024-01-01-000005', 'App\\Database\\Migrations\\CreateProgressTracking', 'default', 'App', 1757532556, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int NOT NULL,
  `page_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `desc` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `show_home` tinyint(1) NOT NULL DEFAULT '0',
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `sort` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `page_link`, `title`, `desc`, `content`, `created_at`, `deleted_at`, `updated_at`, `active`, `show_home`, `images`, `sort`, `parent_id`) VALUES
(1, 'about', 'من نحن', 'كانجارو كير أول نظام متابعة للأبناء عن بُعد وكأنهم في حضنك، تابعي أطفالك في الحضانة من خلال تطبيق الجوال كانجارو كير.\r\nمن خلال هذا النظام يمكنك متابعة طفلك ساعة بساعة أثناء تواجده بالحضانة ', '<p>كانجارو كير أول نظام متابعة للأبناء عن بُعد وكأنهم في حضنك، تابعي أطفالك في الحضانة من خلال تطبيق الجوال كانجارو كير. من خلال هذا النظام يمكنك متابعة طفلك ساعة بساعة أثناء تواجده بالحضانة&nbsp;</p>', '2024-01-24 14:38:30', '2024-01-24 14:38:30', '2024-01-24 14:38:30', 1, 0, '{\"fileCount\":0,\"files\":[]}', 4, 0),
(2, 'terms_and_conditions', 'الشروط والأحكام', 'تحتوي خدمة توصيل الطلبات على شروط وأحكام معينة التي يجب على جميع السائقين المشتركين في التطبيق الالتزام بها، وفيما يلي نص هذه الشروط والأحكام:\n\nيجب على جميع السائقين التسجيل في التطبيق قبل استخدام الخدمة.\nيجب على جميع السائقين تقديم المعلومات الصحيحة والكاملة عند التسجيل.\nيجب على جميع السائقين اختيار كلمات مرور آمنة وتغييرها بشكل منتظم.\nلا يسمح باستخدام الخدمة لأي أغراض غير قانونية أو غير أخلاقية.\nيجب على جميع السائقين الالتزام بالمواعيد والجداول الزمنية المتفق عليها لتوصيل الطلبات.\nيحق للمتجر رفض أي طلب قبل تسليمه لخدمة التوصيل.\nيحق للسائق إلغاء الطلب قبل تسليمه لخدمة التوصيل، ولكن يجب إرجاع أي رسوم مدفوعة مسبقًا.\nيتحمل السائق مسؤولية أي ضرر يلحق بالمنتجات خلال عملية التوصيل.\nيجب على جميع السائقين التعامل بلباقة واحترام موظفي المتاجر والعملاء.\nيتم تقديم خدمة التوصيل كما هي دون أي ضمانات صريحة أو ضمنية.\nيجب على جميع السائقين الالتزام بقوانين المرور والسلامة المرورية أثناء استخدام الخدمة.\nيحق للشركة تعديل أو تغيير هذه الشروط والأحكام في أي وقت دون إشعار مسبق.', 'تحتوي خدمة توصيل الطلبات على شروط وأحكام معينة التي يجب على جميع السائقين المشتركين في التطبيق الالتزام بها، وفيما يلي نص هذه الشروط والأحكام:\n\nيجب على جميع السائقين التسجيل في التطبيق قبل استخدام الخدمة.\nيجب على جميع السائقين تقديم المعلومات الصحيحة والكاملة عند التسجيل.\nيجب على جميع السائقين اختيار كلمات مرور آمنة وتغييرها بشكل منتظم.\nلا يسمح باستخدام الخدمة لأي أغراض غير قانونية أو غير أخلاقية.\nيجب على جميع السائقين الالتزام بالمواعيد والجداول الزمنية المتفق عليها لتوصيل الطلبات.\nيحق للمتجر رفض أي طلب قبل تسليمه لخدمة التوصيل.\nيحق للسائق إلغاء الطلب قبل تسليمه لخدمة التوصيل، ولكن يجب إرجاع أي رسوم مدفوعة مسبقًا.\nيتحمل السائق مسؤولية أي ضرر يلحق بالمنتجات خلال عملية التوصيل.\nيجب على جميع السائقين التعامل بلباقة واحترام موظفي المتاجر والعملاء.\nيتم تقديم خدمة التوصيل كما هي دون أي ضمانات صريحة أو ضمنية.\nيجب على جميع السائقين الالتزام بقوانين المرور والسلامة المرورية أثناء استخدام الخدمة.\nيحق للشركة تعديل أو تغيير هذه الشروط والأحكام في أي وقت دون إشعار مسبق.', '2024-01-24 14:38:30', '2024-01-24 14:38:30', '2024-01-24 14:38:30', 1, 0, NULL, 3, 0),
(3, 'privacy_policy', 'سياسة الخصوصية', 'سياسة الخصوصية', 'لوريم إيبسوم(Lorem Ipsum) هو ببساطة نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر عندما قامت مطبعة مجهولة برص مجموعة من الأحرف بشكل عشوائي أخذتها من نص، لتكوّن كتيّب بمثابة دليل أو مرجع شكلي لهذه الأحرف. خمسة قرون من الزمن لم تقضي على هذا النص، بل انه حتى صار مستخدماً وبشكله الأصلي في الطباعة والتنضيد الإلكتروني. انتشر بشكل كبير في ستينيّات هذا القرن مع إصدار رقائق \"ليتراسيت\" (Letraset) البلاستيكية تحوي مقاطع من هذا النص، وعاد لينتشر مرة أخرى مؤخراَ مع ظهور برامج النشر الإلكتروني مثل \"ألدوس بايج مايكر\" (Aldus PageMaker) والتي حوت أيضاً على نسخ من نص لوريم إيبسوم.\r\n\r\n', '2024-01-24 14:38:30', '2024-01-24 14:38:30', '2024-01-24 14:38:30', 1, 0, NULL, 5, 0),
(4, 'usage_policy', 'سياسة الاستخدام', 'سياسة الاستخدام', '<p>سياسة الاستخدام</p>\r\n', '2024-01-24 14:38:30', '2024-01-24 14:38:30', '2024-01-24 14:38:30', 1, 0, NULL, 5, 0),
(8, 'scratch_track', 'مسار سكراتش للمبتدئين: بوابتك الممتعة لعالم البرمجة', 'اكتشف عالم البرمجة الممتع مع مسار سكراتش! مصمم خصيصًا للطلاب (+14) كخطوة أولى، يتعلم ابنك أساسيات التفكير المنطقي وحل المشكلات وبناء الألعاب والقصص التفاعلية بدون الحاجة لكتابة أكواد معقدة. تأسيس مثالي قبل الانتقال للغات البرمجة المتقدمة.', 'scratch_track', '2025-04-19 10:45:57', '2025-04-19 10:45:57', '2025-04-19 10:45:57', 1, 1, '{\"fileCount\":0,\"files\":[]}', 1, 0),
(12, 'python_track', 'المسار المتوسط: إتقان لغة بايثون القوية والمطلوبة', 'انتقل إلى مستوى متقدم في البرمجة مع مسار بايثون. بعد تأسيس المفاهيم (أو لمن لديه أساسيات)، يتعلم الطالب كتابة الأكواد بلغة بايثون المطلوبة عالمياً. يركز المسار على بناء برامج عملية، فهم هياكل البيانات، وحل المشكلات البرمجية، مما يؤهله للمسارات المتقدمة.', 'python_track', '2025-04-19 10:45:57', '2025-04-19 10:45:57', '2025-04-19 10:45:57', 1, 1, '{\"fileCount\":0,\"files\":[]}', 1, 0),
(13, 'web_track', 'المسار المتقدم: احتراف تطوير الويب الشامل', 'تخصص في بناء وتصميم المواقع وتطبيقات الويب التفاعلية. يغطي هذا المسار المتقدم تقنيات الواجهة الأمامية (Frontend) والخلفية (Backend) اللازمة لبناء مشاريع ويب قوية. يؤهل الطالب لسوق العمل كمطور ويب محترف.', 'web_track', '2025-04-19 10:45:57', '2025-04-19 10:45:57', '2025-04-19 10:45:57', 1, 1, '{\"fileCount\":0,\"files\":[]}', 1, 0),
(14, 'mobile_track', 'المسار المتقدم: تطوير تطبيقات الجوال (Android & iOS)', 'تعلم بناء تطبيقات جوال احترافية تعمل على نظامي التشغيل Android و iOS. يركز هذا المسار المتقدم على أحدث التقنيات والأدوات المستخدمة في برمجة تطبيقات الجوال، ويكسب الطالب مهارات عملية من خلال بناء تطبيقات كاملة تجهزه لسوق العمل.', 'mobile_track', '2025-04-19 10:45:57', '2025-04-19 10:45:57', '2025-04-19 10:45:57', 1, 1, '{\"fileCount\":0,\"files\":[]}', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int NOT NULL,
  `parent_id` int DEFAULT '0',
  `section_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `sort` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `parent_id`, `section_link`, `title`, `icon`, `active`, `sort`, `created_at`, `updated_at`) VALUES
(1, 0, 'settings', 'الإعدادات', 'nav-icon fas fa-cog', 1, 7, '2023-10-04 02:39:08', '2023-10-04 02:39:08'),
(2, 0, 'dashboard', 'الرئيسية', 'nav-icon fas fa-tachometer-alt', 1, 1, '2023-10-04 02:39:08', '2023-10-04 02:39:08'),
(3, 0, '#', 'المستخدمين والصلاحيات', 'fas fa-users-cog', 0, 25, '2023-10-04 02:39:08', '2023-10-04 02:39:08'),
(4, 3, 'users', 'المستخدمين', 'fas fa-users', 1, 3, '2023-10-04 02:39:08', '2023-10-04 02:39:08'),
(5, 3, 'permissions', 'الصلاحيات', 'fas fa-user-shield', 0, 4, '2023-10-04 02:39:08', '2023-10-04 02:39:08'),
(6, 3, 'groups', 'المجموعات', 'fas fa-user-friends', 1, 5, '2023-10-04 02:39:08', '2023-10-04 02:39:08'),
(7, 0, 'sections', 'الأقسام', 'fas fa-stream', 0, 2, '2023-10-04 02:39:08', '2023-10-04 02:39:08'),
(8, 0, 'contact_us', 'رسائل التواصل', 'fas fa-envelope', 1, 9, '2023-10-04 02:39:08', '2023-10-04 02:39:08'),
(9, 0, 'pages', 'الصفحات الاضافية', 'fas fa-file-alt', 1, 4, '2023-10-04 02:39:08', '2023-10-04 02:39:08'),
(10, 0, 'articles', 'المقالات', 'fa fa-location-arrow', 1, 3, '2023-11-14 18:02:39', '2023-11-14 18:02:39'),
(115, 0, 'courses', 'الكورسات', 'far fa-circle nav-icon', 1, 2, '2025-02-18 10:45:52', '2025-02-18 10:45:52'),
(116, 0, 'enrollments', 'الاشتراكات', 'far fa-circle nav-icon', 1, 3, '2025-02-18 10:46:32', '2025-02-18 10:46:32'),
(117, 0, 'payments', 'المدفوعات', 'far fa-circle nav-icon', 0, 4, '2025-02-20 11:36:29', '2025-02-20 11:36:29'),
(120, 0, 'billing', 'الفواتير', 'fas fa-credit-card', 1, 5, '2025-09-11 07:03:15', '2025-09-11 07:03:15'),
(121, 0, 'progress', 'التقدم', 'fas fa-chart-line', 1, 6, '2025-09-11 07:03:15', '2025-09-11 07:03:15'),
(122, 0, 'quizzes', 'الاختبارات', 'fas fa-question-circle', 1, 8, '2025-09-11 07:03:15', '2025-09-11 07:03:15'),
(123, 0, 'videos', 'الفيديوهات', 'fas fa-video', 1, 10, '2025-09-11 07:03:15', '2025-09-11 07:03:15');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `group_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `type` varchar(31) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'string',
  `context` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `group_name`, `key`, `value`, `type`, `context`, `created_at`, `updated_at`, `class`) VALUES
(1, 'auth', 'allowRegistration', 'true', 'string', 'auth', '2023-10-04 02:39:00', '2023-10-04 02:39:00', 'CodeIgniter\\Shield\\Config\\Auth'),
(2, 'auth', 'allowMagicLinkLogins', 'true', 'string', 'auth', '2023-10-04 02:39:00', '2023-10-04 02:39:00', 'CodeIgniter\\Shield\\Config\\Auth'),
(3, 'app', 'twitter', '', 'string', NULL, '2023-10-04 02:39:00', '2023-10-04 02:39:00', 'Config\\App'),
(4, 'app', 'instagram', '', 'string', NULL, '2023-10-04 02:39:00', '2023-10-04 02:39:00', 'Config\\App'),
(5, 'app', 'contactPhones', '0966000000', 'string', NULL, '2023-10-04 02:39:00', '2023-10-04 02:39:00', 'Config\\App'),
(6, 'app', 'contactAddress', 'ryddah Sudi Arabia', 'string', NULL, '2023-10-04 02:39:00', '2023-10-04 02:39:00', 'Config\\App'),
(8, 'app', 'fromEmail', 'ADMINLTE', 'string', NULL, '2023-10-04 02:39:00', '2023-10-04 02:39:00', 'Config\\App'),
(48, NULL, 'site_desc_ar', '<p>وصف الموقع وهو ما يمكن تنفيذه</p>', 'string', NULL, '2023-10-04 02:58:52', '2023-10-23 04:47:48', 'Config\\App'),
(49, NULL, 'site_desc_en', '<p><strong>Thank you in english</strong></p>', 'string', NULL, '2023-10-04 02:58:52', '2023-10-23 04:47:49', 'Config\\App'),
(50, NULL, 'fromName', 'رابط الفيديو', 'string', NULL, '2023-10-04 02:58:52', '2023-11-29 20:08:53', 'Config\\App'),
(52, NULL, 'facebook', 'https://www.facebook.com/3talab', 'string', NULL, '2023-10-04 02:58:52', '2024-04-20 10:20:30', 'Config\\App'),
(53, NULL, 'tiktok', 'https://www.tiktok.com/ar', 'string', NULL, '2023-10-04 02:58:52', '2023-10-09 06:36:02', 'Config\\App'),
(54, NULL, 'snapchat', 'سناب شات', 'string', NULL, '2023-10-04 02:58:52', '2023-10-09 06:36:02', 'Config\\App'),
(56, NULL, 'phone', '0788888888', 'string', NULL, '2023-10-04 02:58:52', '2024-04-20 10:20:30', 'Config\\App'),
(57, NULL, 'mobile', '0788888888', 'string', NULL, '2023-10-04 02:58:52', '2024-04-20 10:20:30', 'Config\\App'),
(58, NULL, 'address_ar', '                                 ', 'string', NULL, '2023-10-04 02:58:52', '2024-04-20 10:20:30', 'Config\\App'),
(59, NULL, 'address_en', '<p><strong>وصف EN </strong></p>', 'string', NULL, '2023-10-04 02:58:52', '2024-04-20 10:20:30', 'Config\\App'),
(60, NULL, 'title', 'موقع مسار لينك', 'string', NULL, '2023-10-04 03:02:06', '2023-11-29 20:08:53', 'Config\\App'),
(61, NULL, 'company_name_ar', 'موقع مسار لينك', 'string', NULL, '2023-10-04 03:02:06', '2023-10-23 04:47:48', 'Config\\App'),
(62, NULL, 'company_name_en', 'Company Name in English', 'string', NULL, '2023-10-04 03:02:06', '2023-10-23 04:47:48', 'Config\\App'),
(63, NULL, 'keywords_ar', 'دورات ، كورسات،مسارات،ثانوي،سعودية', 'string', NULL, '2023-10-04 03:02:06', '2023-10-23 04:47:48', 'Config\\App'),
(64, NULL, 'keywords_en', 'دورات ، كورسات،مسارات،ثانوي،سعودية', 'string', NULL, '2023-10-04 03:02:06', '2023-10-23 04:47:48', 'Config\\App'),
(65, NULL, 'active_users', '0', 'string', NULL, '2023-10-04 03:22:38', '2023-12-14 08:33:29', 'Config\\App'),
(66, NULL, 'active_site', '0', 'string', NULL, '2023-10-04 03:22:38', '2023-12-14 08:33:29', 'Config\\App'),
(67, 'app', 'site_description_ar', 'أكاديمية رائدة في تدريس علوم الحاسب للطلاب (+14) بنظام المسارات، وتأهيلهم لسوق العمل المستقبلي .', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(68, 'app', 'site_keywords', 'تعليم البرمجة للطلاب, برمجة تعليم البرمجة للطلاب, برمجة نظام المسارات, دورات برمجة للمراهقين, تعليم الحاسب لطلاب المسارات, تعلم سكراتش للمبتدئين, دورة برمجة سكراتش, برمجة مرئية للطلاب, أساسيات البرمجة باللعب, تعلم لغة بايثون, دورة برمجة بايثون, أساسيات بايثون للطلاب, برمجة نصية بايثون, دورة تطوير الويب, تعلم HTML CSS JavaScript, تصميم وتطوير المواقع, برمجة واجهات أمامية', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(69, 'general', 'site_description_en', 'أول أكاديمية متخصصة في تدريس علوم الحاسب لطلبة المرحلة الثانوية بالمملكة العربية السعودية وإعدادهم لوظائف المستقبل.', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(70, 'general', 'site_title_ar', 'موقع مسار لينك', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(71, 'general', 'site_title_en', 'MsarLink', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(72, 'app', 'contact_email', 'info@msarlink.com', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(73, 'login', 'max_login_attempts', '0', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(74, 'login', 'password_expire_days', '0', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(75, 'contacts', 'contact_phones', '07810999822', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(79, 'contacts', 'youtube', 'https://www.youtube.com/3talab', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(80, 'contacts', 'contact_address_ar', 'الادارة العامة- بابل-العراق', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(81, 'contacts', 'contact_address_en', 'General Administration: ÷Iraq\r\nBranch: Babel', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(82, 'contacts', 'site_instagram_link', 'https://www.instagram.com/', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(83, 'contacts', 'site_linkedin_link', 'https://linkedin.com/', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(84, 'contacts', 'whatsapp', 'https://whatsapp.com/', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(86, 'app', 'default_language', 'ar', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(87, 'app', 'active_register', '0', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(88, 'app', 'register_off_msg_ar', 'عفوا،التسجيل في التطبيق متوقف مؤقتايرجى المحاولة بعد قليل', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(89, 'app', 'active_orders', '0', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(90, 'app', 'accept_waiting_duration', '1', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 03:59:20', 'Config\\App'),
(91, 'app', 'welcome_msg_ar', 'مرحبا بك في تطبيق عالطلب', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(92, 'app', 'order_off_msg_ar', 'عفوا،تسجيل الطلبات متوقف مؤقتا،يرجى المحاولة بعد قليل', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(93, 'app', 'max_orders_per_order', '10', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 03:59:20', 'Config\\App'),
(94, 'app', 'ci_csrf_token', '', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(95, 'app', 'duration_between_orders', '1', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 03:59:20', 'Config\\App'),
(96, 'app', 'order_default_price', '2000', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 03:59:20', 'Config\\App'),
(97, 'app', 'active_suggestions', '1', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(98, 'app', 'active_notifications', 'on', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 06:15:51', 'Config\\App'),
(100, 'app', 'duration_between_accepted_taken', '3', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 03:59:20', 'Config\\App'),
(101, 'app', 'drivers_add_orders_ability', 'on', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 06:15:51', 'Config\\App'),
(102, 'app', 'active_attend', 'on', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 06:15:51', 'Config\\App'),
(103, 'app', 'max_driver_hold_orders', '10', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 03:59:20', 'Config\\App'),
(104, 'app', 'active_order_message_ar', 'عفوا / يتعذر علينا قبول الطلبات حالياُ ،لصعوبة الطقس', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(105, 'app', 'orders_off_mode', '0', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(106, 'app', 'orders_off_message_ar', 'نعتذر عن قبول طلبات حاليا', 'string', NULL, '2023-11-14 17:35:45', '2023-11-14 17:35:45', 'Config\\App'),
(107, 'app', 'duration_between_two_accepted', '17', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 03:59:20', 'Config\\App'),
(108, 'app', 'duration_between_canceled_accepted', '5', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 03:59:20', 'Config\\App'),
(109, 'app', 'max_driver_not_accept_orders', '60', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 03:59:20', 'Config\\App'),
(110, 'app', 'insurance_budget', '999422', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 03:59:20', 'Config\\App'),
(111, 'app', 'average_driver_suggestion', '0', 'string', NULL, '2023-11-14 17:35:45', '2024-04-01 03:59:20', 'Config\\App'),
(112, 'app', 'company_name', '', 'string', NULL, '2023-11-29 20:08:53', '2023-11-29 20:08:53', 'Config\\App'),
(113, 'app', 'keywords', '', 'string', NULL, '2023-11-29 20:08:53', '2023-11-29 20:08:53', 'Config\\App'),
(114, 'app', 'site_desc', 'رسالة عدم قبول الطلبات', 'string', NULL, '2023-11-29 20:08:53', '2023-12-14 06:42:49', 'Config\\App'),
(120, 'app', 'orders_off_message', 'نعتذر عن قبول الطلبات حاليا', 'string', NULL, '2023-12-14 08:33:29', '2024-04-01 06:15:51', 'Config\\App'),
(145, 'app', 'active_merchant_assign', 'on', 'string', NULL, '2024-01-27 10:05:14', '2024-04-01 06:15:51', 'Config\\App'),
(147, 'app', 'active_hide_mode', '0', 'string', NULL, '2024-01-27 10:05:39', '2024-04-01 06:15:51', 'Config\\App');

-- --------------------------------------------------------

--
-- Table structure for table `tbnotifications`
--

CREATE TABLE `tbnotifications` (
  `id` int NOT NULL,
  `notify_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title_ar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title_en` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `desc_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `desc_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `font_color` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `background_color` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `has_push` tinyint(1) NOT NULL DEFAULT '0',
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_courses`
--

CREATE TABLE `tb_courses` (
  `id` int NOT NULL,
  `course_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `course_title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `skill_level` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `intro_video_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` json DEFAULT NULL,
  `course_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `course_structure` json DEFAULT NULL COMMENT 'JSON structure for sections and videos with sort, active (0 or 1), and video_desc',
  `waiting_list` tinyint(1) NOT NULL DEFAULT '0',
  `is_free` tinyint(1) DEFAULT '0',
  `short_desc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sort` int DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_courses`
--

INSERT INTO `tb_courses` (`id`, `course_title`, `course_title`, `slug`, `skill_level`, `intro_video_id`, `price`, `image`, `course_desc`, `course_structure`, `waiting_list`, `is_free`, `short_desc`, `created_at`, `updated_at`, `sort`, `active`) VALUES
(1, 'مسار سكراتش المكثف (بأسلوب CS50)', 'مسار سكراتش المكثف (بأسلوب CS50)', 'scratch-cs50-intensive', 'مبتدئ', 'placeholder_video_id_session_1', 360.00, NULL, 'مسار تأسيسي مكثف للمبتدئين (+14) لتعلم البرمجة باستخدام سكراتش، مستوحى من منهج CS50 Scratch من جامعة هارفارد. يقدم المسار في 12 حصة تفاعلية مباشرة عبر Zoom بإشراف م/ أحمد فخر الدين، مع التركيز على التفكير المنطقي، حل المشكلات، وبناء المشاريع العملية.', '[{\"sort\": 1, \"active\": 1, \"videos\": [{\"id\": 1, \"sort\": 1, \"active\": 1, \"video_id\": \"placeholder_video_id_session_1\", \"is_preview\": 1, \"video_desc\": \"مقدمة تفاعلية، استكشاف الواجهة، وتطبيق عملي على الحركة والمظاهر الأساسية.\", \"video_title\": \"الجلسة 1: الانطلاق في عالم Scratch (Sprites & Basics)\", \"video_duration\": \"90:00\"}, {\"id\": 2, \"sort\": 2, \"active\": 1, \"video_id\": \"placeholder_video_id_session_2\", \"is_preview\": 0, \"video_desc\": \"فهم أنواع الأحداث المختلفة وربط الحركة والمظاهر بها.\", \"video_title\": \"الجلسة 2: أحداث التفاعل (Events)\", \"video_duration\": \"90:00\"}, {\"id\": 3, \"sort\": 3, \"active\": 1, \"video_id\": \"placeholder_video_id_session_3\", \"is_preview\": 0, \"video_desc\": \"التحكم في توقيت الأوامر واستخدام حلقة التكرار Repeat لبناء أنماط ورسومات.\", \"video_title\": \"الجلسة 3: التحكم في التدفق والتكرار (Control Flow & Repeat)\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الأول: مقدمة وأساسيات التفاعل\"}, {\"sort\": 2, \"active\": 1, \"videos\": [{\"id\": 4, \"sort\": 1, \"active\": 1, \"video_id\": \"placeholder_video_id_session_4\", \"is_preview\": 0, \"video_desc\": \"استخدام حلقة التكرار المستمر Forever والاستشعار الأساسي للحركة والتفاعل مع الحواف.\", \"video_title\": \"الجلسة 4: التكرار المستمر والاستشعار (Forever & Sensing)\", \"video_duration\": \"90:00\"}, {\"id\": 5, \"sort\": 2, \"active\": 1, \"video_id\": \"placeholder_video_id_session_5\", \"is_preview\": 0, \"video_desc\": \"فهم وتطبيق الشروط الأساسية (If) لاتخاذ القرارات بناءً على الاستشعار.\", \"video_title\": \"الجلسة 5: الشروط الأساسية (Conditions - If)\", \"video_duration\": \"90:00\"}, {\"id\": 6, \"sort\": 3, \"active\": 1, \"video_id\": \"placeholder_video_id_session_6\", \"is_preview\": 0, \"video_desc\": \"استخدام الشروط المتفرعة (If/Else) والاستشعار المتقدم للتفاعل مع الألوان ومدخلات المستخدم.\", \"video_title\": \"الجلسة 6: الشروط المتفرعة والاستشعار المتقدم (If/Else & Advanced Sensing)\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثاني: التحكم، الشروط، والاستشعار\"}, {\"sort\": 3, \"active\": 1, \"videos\": [{\"id\": 7, \"sort\": 1, \"active\": 1, \"video_id\": \"placeholder_video_id_session_7\", \"is_preview\": 0, \"video_desc\": \"فهم أهمية المتغيرات وكيفية إنشائها واستخدامها لتخزين البيانات مثل النقاط والأسماء.\", \"video_title\": \"الجلسة 7: المتغيرات (Variables)\", \"video_duration\": \"90:00\"}, {\"id\": 8, \"sort\": 2, \"active\": 1, \"video_id\": \"placeholder_video_id_session_8\", \"is_preview\": 0, \"video_desc\": \"استخدام العمليات الحسابية، المقارنة، المنطقية، والعشوائية لمعالجة البيانات واتخاذ قرارات معقدة.\", \"video_title\": \"الجلسة 8: العمليات والمنطق (Operators)\", \"video_duration\": \"90:00\"}, {\"id\": 9, \"sort\": 3, \"active\": 1, \"video_id\": \"placeholder_video_id_session_9\", \"is_preview\": 0, \"video_desc\": \"مقدمة لمفهوم الدوال (My Blocks) وكيفية إنشائها لتنظيم الكود وإعادة استخدامه.\", \"video_title\": \"الجلسة 9: الدوال الأساسية (My Blocks – الجزء 1)\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثالث: البيانات، العمليات، والتنظيم\"}, {\"sort\": 4, \"active\": 1, \"videos\": [{\"id\": 10, \"sort\": 1, \"active\": 1, \"video_id\": \"placeholder_video_id_session_10\", \"is_preview\": 0, \"video_desc\": \"إنشاء دوال (My Blocks) بمدخلات لجعلها أكثر مرونة، واستخدام البث (Broadcasting) للتواصل بين الكائنات.\", \"video_title\": \"الجلسة 10: الدوال المتقدمة والبث (My Blocks & Broadcasting)\", \"video_duration\": \"90:00\"}, {\"id\": 11, \"sort\": 2, \"active\": 1, \"video_id\": \"placeholder_video_id_session_11\", \"is_preview\": 0, \"video_desc\": \"مراجعة شاملة، عصف ذهني، وتخطيط لمشروع نهائي مصغر يطبق المهارات المكتسبة.\", \"video_title\": \"الجلسة 11: التخطيط وبدء Mini-Project\", \"video_duration\": \"90:00\"}, {\"id\": 12, \"sort\": 3, \"active\": 1, \"video_id\": \"placeholder_video_id_session_12\", \"is_preview\": 0, \"video_desc\": \"استكمال العمل على المشروع المصغر، تصحيح الأخطاء، وعرض المشاريع النهائية للمجموعة.\", \"video_title\": \"الجلسة 12: استكمال وعرض Mini-Project\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الرابع: الدوال المتقدمة والمشروع النهائي\"}]', 0, 0, '12 حصة تفاعلية لتعلم سكراتش بأسلوب CS50', '2025-04-27 06:30:08', '2025-04-27 06:30:08', 2, 1),
(2, 'المستوى 2.1: أساسيات البرمجة ومنطق بايثون', 'المستوى 2.1: أساسيات البرمجة ومنطق بايثون', 'python-logic-fundamentals', 'مبتدئ-متوسط', 'py1_vid_session_1', 240.00, NULL, 'مسار تأسيسي لتعلم كتابة برامج بايثون بسيطة، يتضمن المتغيرات، العمليات، المدخلات، الشروط، والتعامل الأساسي مع النصوص. يهدف لبناء قاعدة صلبة في التفكير المنطقي والبرمجة النصية للمنتقلين من سكراتش أو المبتدئين في بايثون.', '[{\"sort\": 1, \"active\": 1, \"videos\": [{\"id\": 1, \"sort\": 1, \"active\": 1, \"video_id\": \"py1_vid_session_1\", \"is_preview\": 1, \"video_desc\": \"مقدمة لبايثون، إعداد البيئة، وأول برنامج print().\", \"video_title\": \"الجلسة 1: الانطلاق مع بايثون (Introduction & Setup)\", \"video_duration\": \"90:00\"}, {\"id\": 2, \"sort\": 2, \"active\": 1, \"video_id\": \"py1_vid_session_2\", \"is_preview\": 0, \"video_desc\": \"فهم المتغيرات، قواعد التسمية، وأنواع البيانات الأساسية (int, float, str, bool).\", \"video_title\": \"الجلسة 2: تخزين البيانات (المتغيرات والأنواع الأساسية)\", \"video_duration\": \"90:00\"}, {\"id\": 3, \"sort\": 3, \"active\": 1, \"video_id\": \"py1_vid_session_3\", \"is_preview\": 0, \"video_desc\": \"استخدام المعاملات الحسابية والمقارنة لإجراء العمليات والتحقق من الشروط.\", \"video_title\": \"الجلسة 3: إجراء العمليات (Operators)\", \"video_duration\": \"90:00\"}, {\"id\": 4, \"sort\": 4, \"active\": 1, \"video_id\": \"py1_vid_session_4\", \"is_preview\": 0, \"video_desc\": \"استقبال مدخلات من المستخدم باستخدام input() وتحويل أنواع البيانات (Type Casting).\", \"video_title\": \"الجلسة 4: التفاعل مع المستخدم (Input & Type Casting)\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الأول: الانطلاق والبيانات الأساسية\"}, {\"sort\": 2, \"active\": 1, \"videos\": [{\"id\": 5, \"sort\": 1, \"active\": 1, \"video_id\": \"py1_vid_session_5\", \"is_preview\": 0, \"video_desc\": \"العمليات الأساسية على النصوص (الربط، التكرار، الطول) والفهرسة والتقطيع.\", \"video_title\": \"الجلسة 5: التعامل مع النصوص (String Basics)\", \"video_duration\": \"90:00\"}, {\"id\": 6, \"sort\": 2, \"active\": 1, \"video_id\": \"py1_vid_session_6\", \"is_preview\": 0, \"video_desc\": \"فهم وتطبيق جملة if الشرطية لاتخاذ قرارات بسيطة بناءً على المقارنات.\", \"video_title\": \"الجلسة 6: اتخاذ القرارات (If Statement)\", \"video_duration\": \"90:00\"}, {\"id\": 7, \"sort\": 3, \"active\": 1, \"video_id\": \"py1_vid_session_7\", \"is_preview\": 0, \"video_desc\": \"استخدام else و elif لتوسيع نطاق القرارات والمعاملات المنطقية (and, or, not) لدمج الشروط.\", \"video_title\": \"الجلسة 7: توسيع القرارات (Else, Elif & Logical Operators)\", \"video_duration\": \"90:00\"}, {\"id\": 8, \"sort\": 4, \"active\": 1, \"video_id\": \"py1_vid_session_8\", \"is_preview\": 0, \"video_desc\": \"مراجعة شاملة للمفاهيم وتطبيقها في تحديات برمجية صغيرة.\", \"video_title\": \"الجلسة 8: مراجعة وتحديات المستوى الأول\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثاني: النصوص واتخاذ القرارات\"}]', 0, 0, '8 حصص لتعلم أساسيات بايثون والمنطق البرمجي', '2025-04-27 06:37:49', '2025-04-27 06:37:49', 3, 1),
(3, 'المستوى 2.2: هياكل البيانات وحل المشكلات ببايثون', 'المستوى 2.2: هياكل البيانات وحل المشكلات ببايثون', 'python-data-structures-problem-solving', 'متوسط', 'py2_vid_session_1', 300.00, NULL, 'مسار لتعميق مهارات بايثون، يركز على الحلقات (while/for)، هياكل البيانات (القوائم)، بناء الدوال وتنظيمها، استخدام الوحدات الجاهزة، التعامل مع الملفات، وتطبيق كل المهارات في مشروع برمجي متكامل.', '[{\"sort\": 1, \"active\": 1, \"videos\": [{\"id\": 1, \"sort\": 1, \"active\": 1, \"video_id\": \"py2_vid_session_1\", \"is_preview\": 1, \"video_desc\": \"فهم وتطبيق حلقة while للتكرار المشروط والتحكم في تدفقها.\", \"video_title\": \"الجلسة 9 (L2.2-1): التكرار المشروط (While Loops)\", \"video_duration\": \"90:00\"}, {\"id\": 2, \"sort\": 2, \"active\": 1, \"video_id\": \"py2_vid_session_2\", \"is_preview\": 0, \"video_desc\": \"استخدام حلقة for للتكرار المحدد، ومقدمة لهيكل بيانات القوائم (Lists).\", \"video_title\": \"الجلسة 10 (L2.2-2): التكرار المحدد والقوائم (For Loops & Lists Intro)\", \"video_duration\": \"90:00\"}, {\"id\": 3, \"sort\": 3, \"active\": 1, \"video_id\": \"py2_vid_session_3\", \"is_preview\": 0, \"video_desc\": \"تعلم دوال القوائم المختلفة للتعديل، الحذف، البحث، والترتيب.\", \"video_title\": \"الجلسة 11 (L2.2-3): عمليات القوائم المتقدمة (List Methods)\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الأول: الحلقات والقوائم\"}, {\"sort\": 2, \"active\": 1, \"videos\": [{\"id\": 4, \"sort\": 1, \"active\": 1, \"video_id\": \"py2_vid_session_4\", \"is_preview\": 0, \"video_desc\": \"مقدمة لمفهوم الدوال (Functions) لتقسيم الكود وتنظيمه.\", \"video_title\": \"الجلسة 12 (L2.2-4): تنظيم الكود (Functions Intro)\", \"video_duration\": \"90:00\"}, {\"id\": 5, \"sort\": 2, \"active\": 1, \"video_id\": \"py2_vid_session_5\", \"is_preview\": 0, \"video_desc\": \"إنشاء دوال تأخذ معاملات (Parameters) وتعيد قيماً (Return) لجعلها أكثر قوة.\", \"video_title\": \"الجلسة 13 (L2.2-5): دوال أكثر قوة (Parameters & Return)\", \"video_duration\": \"90:00\"}, {\"id\": 6, \"sort\": 3, \"active\": 1, \"video_id\": \"py2_vid_session_6\", \"is_preview\": 0, \"video_desc\": \"استخدام الوحدات (Modules) الجاهزة مثل random, math, time لتوسيع قدرات البرنامج.\", \"video_title\": \"الجلسة 14 (L2.2-6): استخدام أدوات جاهزة (Modules)\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثاني: تنظيم الكود والوحدات\"}, {\"sort\": 3, \"active\": 1, \"videos\": [{\"id\": 7, \"sort\": 1, \"active\": 1, \"video_id\": \"py2_vid_session_7\", \"is_preview\": 0, \"video_desc\": \"قراءة وكتابة البيانات من وإلى الملفات النصية باستخدام open() و with.\", \"video_title\": \"الجلسة 15 (L2.2-7): التعامل مع الملفات (File I/O)\", \"video_duration\": \"90:00\"}, {\"id\": 8, \"sort\": 2, \"active\": 1, \"video_id\": \"py2_vid_session_8\", \"is_preview\": 0, \"video_desc\": \"مراجعة شاملة، اختيار فكرة مشروع نهائي وتخطيط هيكله ومنطقه.\", \"video_title\": \"الجلسة 16 (L2.2-8): التخطيط للمشروع النهائي\", \"video_duration\": \"90:00\"}, {\"id\": 9, \"sort\": 3, \"active\": 1, \"video_id\": \"py2_vid_session_9\", \"is_preview\": 0, \"video_desc\": \"العمل على تنفيذ المشروع النهائي، كتابة الكود وحل المشكلات.\", \"video_title\": \"الجلسة 17 (L2.2-9): بناء المشروع وتطويره\", \"video_duration\": \"90:00\"}, {\"id\": 10, \"sort\": 4, \"active\": 1, \"video_id\": \"py2_vid_session_10\", \"is_preview\": 0, \"video_desc\": \"استكمال المشروع، اختباره، عرضه، ومراجعة نهائية لمفاهيم المسار.\", \"video_title\": \"الجلسة 18 (L2.2-10): استكمال المشروع، العرض، والمراجعة النهائية\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثالث: التعامل مع الملفات والمشروع النهائي\"}]', 0, 0, '10 حصص لهياكل البيانات، الدوال، والمشاريع ببايثون', '2025-04-27 06:38:04', '2025-04-27 06:38:04', 4, 1),
(4, 'المستوى 3A.1: أساسيات بناء الويب (HTML Foundation)', 'المستوى 3A.1: أساسيات بناء الويب (HTML Foundation)', 'html-foundation', 'مبتدئ', 'html_vid_session_1', 240.00, NULL, 'تعلم أساسيات لغة HTML لبناء هيكل صفحات الويب بشكل صحيح ومنظم. يغطي المسار العناصر الأساسية، النصوص، القوائم، الروابط، الصور، الجداول، النماذج، والعناصر الدلالية الحديثة.', '[{\"sort\": 1, \"active\": 1, \"videos\": [{\"id\": 1, \"sort\": 1, \"active\": 1, \"video_id\": \"html_vid_session_1\", \"is_preview\": 1, \"video_desc\": \"فهم بنية مستند HTML الأساسية (DOCTYPE, html, head, body) وإنشاء أول صفحة.\", \"video_title\": \"الجلسة 1: مقدمة إلى HTML وبنية المستند\", \"video_duration\": \"90:00\"}, {\"id\": 2, \"sort\": 2, \"active\": 1, \"video_id\": \"html_vid_session_2\", \"is_preview\": 0, \"video_desc\": \"استخدام عناصر تنسيق النصوص الأساسية مثل العناوين والفقرات والتأكيد.\", \"video_title\": \"الجلسة 2: العناصر الأساسية والنصوص\", \"video_duration\": \"90:00\"}, {\"id\": 3, \"sort\": 3, \"active\": 1, \"video_id\": \"html_vid_session_3\", \"is_preview\": 0, \"video_desc\": \"إنشاء القوائم المرتبة وغير المرتبة، وإضافة الروابط والصور مع السمات الهامة.\", \"video_title\": \"الجلسة 3: القوائم والروابط والصور\", \"video_duration\": \"90:00\"}, {\"id\": 4, \"sort\": 4, \"active\": 1, \"video_id\": \"html_vid_session_4\", \"is_preview\": 0, \"video_desc\": \"بناء الجداول لعرض البيانات المنظمة ومقدمة لأساسيات النماذج (form, input, button).\", \"video_title\": \"الجلسة 4: الجداول والنماذج الأساسية\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الأول: أساسيات وبنية HTML\"}, {\"sort\": 2, \"active\": 1, \"videos\": [{\"id\": 5, \"sort\": 1, \"active\": 1, \"video_id\": \"html_vid_session_5\", \"is_preview\": 0, \"video_desc\": \"استخدام العناصر الدلالية (header, nav, main, footer) لهيكلة أفضل وفهم Meta Data.\", \"video_title\": \"الجلسة 5: الوسوم الدلالية وميتا داتا\", \"video_duration\": \"90:00\"}, {\"id\": 6, \"sort\": 2, \"active\": 1, \"video_id\": \"html_vid_session_6\", \"is_preview\": 0, \"video_desc\": \"استخدام أدوات المطور (DevTools) وأدوات التحقق (Validators) لتصحيح الأخطاء.\", \"video_title\": \"الجلسة 6: التحقق وتصحيح الأخطاء\", \"video_duration\": \"90:00\"}, {\"id\": 7, \"sort\": 3, \"active\": 1, \"video_id\": \"html_vid_session_7\", \"is_preview\": 0, \"video_desc\": \"البدء في مشروع تطبيقي صغير (صفحة شخصية) لتطبيق مهارات HTML.\", \"video_title\": \"الجلسة 7: مشروع تطبيقي صغير\", \"video_duration\": \"90:00\"}, {\"id\": 8, \"sort\": 4, \"active\": 1, \"video_id\": \"html_vid_session_8\", \"is_preview\": 0, \"video_desc\": \"عرض المشاريع النهائية، تقييمها، ومقدمة بسيطة لنشر المواقع.\", \"video_title\": \"الجلسة 8: عرض المشاريع وتقييم\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثاني: الدلالية والمشاريع\"}]', 0, 0, '8 حصص لتأسيس HTML وبنية صفحات الويب', '2025-04-27 06:39:19', '2025-04-27 06:39:19', 5, 1),
(5, 'المستوى 3A.2: تصميم وتنسيق الويب (CSS Styling & Layout)', 'المستوى 3A.2: تصميم وتنسيق الويب (CSS Styling & Layout)', 'css-styling-layout', 'مبتدئ-متوسط', 'css_vid_session_1', 300.00, NULL, 'تعلم كيفية إضافة الأناقة والجمال لصفحات الويب باستخدام CSS. يغطي المسار المحددات، نموذج الصندوق، تنسيق النصوص والألوان، تقنيات التخطيط الحديثة (Flexbox و Grid)، التصميم المتجاوب، والتحريكات البسيطة.', '[{\"sort\": 1, \"active\": 1, \"videos\": [{\"id\": 1, \"sort\": 1, \"active\": 1, \"video_id\": \"css_vid_session_1\", \"is_preview\": 1, \"video_desc\": \"مقدمة لـ CSS وطرق ربطها بـ HTML وكتابة أول قاعدة تنسيق.\", \"video_title\": \"الجلسة 1: مقدمة إلى CSS وربطها بـHTML\", \"video_duration\": \"90:00\"}, {\"id\": 2, \"sort\": 2, \"active\": 1, \"video_id\": \"css_vid_session_2\", \"is_preview\": 0, \"video_desc\": \"تعلم استخدام المحددات (Selectors) المختلفة لاستهداف عناصر HTML.\", \"video_title\": \"الجلسة 2: المحددات (Selectors)\", \"video_duration\": \"90:00\"}, {\"id\": 3, \"sort\": 3, \"active\": 1, \"video_id\": \"css_vid_session_3\", \"is_preview\": 0, \"video_desc\": \"فهم نموذج الصندوق (Box Model): المحتوى، الحشو، الحدود، والهوامش.\", \"video_title\": \"الجلسة 3: نموذج الصندوق (Box Model)\", \"video_duration\": \"90:00\"}, {\"id\": 4, \"sort\": 4, \"active\": 1, \"video_id\": \"css_vid_session_4\", \"is_preview\": 0, \"video_desc\": \"تنسيق النصوص باستخدام خصائص الخطوط والألوان واستيراد خطوط خارجية.\", \"video_title\": \"الجلسة 4: النصوص والألوان\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الأول: أساسيات CSS ونموذج الصندوق\"}, {\"sort\": 2, \"active\": 1, \"videos\": [{\"id\": 5, \"sort\": 1, \"active\": 1, \"video_id\": \"css_vid_session_5\", \"is_preview\": 0, \"video_desc\": \"مقدمة لـ Flexbox وخصائص الحاوية للتحكم في توزيع العناصر.\", \"video_title\": \"الجلسة 5: Flexbox – الجزء الأول\", \"video_duration\": \"90:00\"}, {\"id\": 6, \"sort\": 2, \"active\": 1, \"video_id\": \"css_vid_session_6\", \"is_preview\": 0, \"video_desc\": \"التحكم في العناصر الفردية داخل Flexbox (النمو، الانكماش، الترتيب).\", \"video_title\": \"الجلسة 6: Flexbox – الجزء الثاني\", \"video_duration\": \"90:00\"}, {\"id\": 7, \"sort\": 3, \"active\": 1, \"video_id\": \"css_vid_session_7\", \"is_preview\": 0, \"video_desc\": \"مقدمة لنظام Grid لإنشاء تخطيطات شبكية معقدة.\", \"video_title\": \"الجلسة 7: Grid Layout\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثاني: التخطيط باستخدام Flexbox و Grid\"}, {\"sort\": 3, \"active\": 1, \"videos\": [{\"id\": 8, \"sort\": 1, \"active\": 1, \"video_id\": \"css_vid_session_8\", \"is_preview\": 0, \"video_desc\": \"استخدام Media Queries لجعل التصميم يتكيف مع مختلف أحجام الشاشات.\", \"video_title\": \"الجلسة 8: التصميم المتجاوب (Media Queries)\", \"video_duration\": \"90:00\"}, {\"id\": 9, \"sort\": 2, \"active\": 1, \"video_id\": \"css_vid_session_9\", \"is_preview\": 0, \"video_desc\": \"إضافة تأثيرات بصرية باستخدام التحولات (Transitions) والرسوم المتحركة (Animations).\", \"video_title\": \"الجلسة 9: التحولات والرسوم المتحركة\", \"video_duration\": \"90:00\"}, {\"id\": 10, \"sort\": 3, \"active\": 1, \"video_id\": \"css_vid_session_10\", \"is_preview\": 0, \"video_desc\": \"تطبيق جميع مهارات CSS في مشروع تصميم صفحة ويب متكاملة ونشرها.\", \"video_title\": \"الجلسة 10: مشروع تطبيقي نهائي\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثالث: التصميم المتجاوب والمشروع\"}]', 0, 0, '10 حصص لتصميم وتنسيق الويب باستخدام CSS', '2025-04-27 06:39:35', '2025-04-27 06:39:35', 6, 1),
(6, 'المستوى 3A.3: التفاعلية وبرمجة الواجهات (JavaScript & DOM)', 'المستوى 3A.3: التفاعلية وبرمجة الواجهات (JavaScript & DOM)', 'javascript-fundamentals-dom', 'متوسط', 'js_vid_session_1', 360.00, NULL, 'تعلم أساسيات لغة JavaScript لإضافة التفاعلية والسلوك الديناميكي لصفحات الويب. يغطي المسار المتغيرات، الشروط، الحلقات، الدوال، المصفوفات، الكائنات، التعامل مع DOM، الأحداث، مقدمة للـ APIs والبرمجة غير المتزامنة، مع مشروع نهائي.', '[{\"sort\": 1, \"active\": 1, \"videos\": [{\"id\": 1, \"sort\": 1, \"active\": 1, \"video_id\": \"js_vid_session_1\", \"is_preview\": 1, \"video_desc\": \"مقدمة لـ JavaScript، كيفية ربطها بالصفحة، واستخدام console.log.\", \"video_title\": \"الجلسة 1: مقدمة إلى JavaScript وربطها بالصفحة\", \"video_duration\": \"90:00\"}, {\"id\": 2, \"sort\": 2, \"active\": 1, \"video_id\": \"js_vid_session_2\", \"is_preview\": 0, \"video_desc\": \"تعلم المتغيرات (let, const, var) وأنواع البيانات الأساسية.\", \"video_title\": \"الجلسة 2: المتغيرات وأنواع البيانات\", \"video_duration\": \"90:00\"}, {\"id\": 3, \"sort\": 3, \"active\": 1, \"video_id\": \"js_vid_session_3\", \"is_preview\": 0, \"video_desc\": \"استخدام جمل if/else if/else و switch لاتخاذ القرارات المنطقية.\", \"video_title\": \"الجلسة 3: العمليات الشرطية\", \"video_duration\": \"90:00\"}, {\"id\": 4, \"sort\": 4, \"active\": 1, \"video_id\": \"js_vid_session_4\", \"is_preview\": 0, \"video_desc\": \"استخدام حلقات for و while لتكرار تنفيذ الأكواد.\", \"video_title\": \"الجلسة 4: الحلقات التكرارية\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الأول: أساسيات JavaScript والتحكم\"}, {\"sort\": 2, \"active\": 1, \"videos\": [{\"id\": 5, \"sort\": 1, \"active\": 1, \"video_id\": \"js_vid_session_5\", \"is_preview\": 0, \"video_desc\": \"تعريف واستدعاء الدوال، فهم المعاملات والقيم المعادة.\", \"video_title\": \"الجلسة 5: الدوال (Functions)\", \"video_duration\": \"90:00\"}, {\"id\": 6, \"sort\": 2, \"active\": 1, \"video_id\": \"js_vid_session_6\", \"is_preview\": 0, \"video_desc\": \"مقدمة للمصفوفات (Arrays) والكائنات (Objects) لتخزين مجموعات البيانات.\", \"video_title\": \"الجلسة 6: المصفوفات والكائنات\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثاني: الدوال وهياكل البيانات\"}, {\"sort\": 3, \"active\": 1, \"videos\": [{\"id\": 7, \"sort\": 1, \"active\": 1, \"video_id\": \"js_vid_session_7\", \"is_preview\": 0, \"video_desc\": \"تعلم كيفية تحديد عناصر HTML وتعديل محتواها وأنماطها باستخدام JavaScript.\", \"video_title\": \"الجلسة 7: التعامل مع DOM\", \"video_duration\": \"90:00\"}, {\"id\": 8, \"sort\": 2, \"active\": 1, \"video_id\": \"js_vid_session_8\", \"is_preview\": 0, \"video_desc\": \"الاستماع والاستجابة لأحداث المستخدم مثل النقر ولوحة المفاتيح.\", \"video_title\": \"الجلسة 8: الأحداث (Events)\", \"video_duration\": \"90:00\"}, {\"id\": 9, \"sort\": 3, \"active\": 1, \"video_id\": \"js_vid_session_9\", \"is_preview\": 0, \"video_desc\": \"تطبيق عملي لبناء قائمة مهام تفاعلية باستخدام DOM والأحداث.\", \"video_title\": \"الجلسة 9: مشروع مصغر – To‑Do List\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثالث: التفاعل مع الصفحة (DOM & Events)\"}, {\"sort\": 4, \"active\": 1, \"videos\": [{\"id\": 10, \"sort\": 1, \"active\": 1, \"video_id\": \"js_vid_session_10\", \"is_preview\": 0, \"video_desc\": \"مقدمة لجلب البيانات من واجهات برمجة التطبيقات (APIs) باستخدام Fetch والتعامل مع JSON.\", \"video_title\": \"الجلسة 10: التعامل مع APIs (Fetch & JSON)\", \"video_duration\": \"90:00\"}, {\"id\": 11, \"sort\": 2, \"active\": 1, \"video_id\": \"js_vid_session_11\", \"is_preview\": 0, \"video_desc\": \"فهم البرمجة غير المتزامنة باستخدام Promises و async/await.\", \"video_title\": \"الجلسة 11: البرمجة غير المتزامنة (Promises & async/await)\", \"video_duration\": \"90:00\"}, {\"id\": 12, \"sort\": 3, \"active\": 1, \"video_id\": \"js_vid_session_12\", \"is_preview\": 0, \"video_desc\": \"تطبيق جميع مهارات JavaScript في مشروع نهائي متكامل ونشره.\", \"video_title\": \"الجلسة 12: المشروع النهائي – تطبيق ويب متكامل\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الرابع: APIs والمشروع النهائي\"}]', 0, 0, '12 حصة لتعلم JavaScript والتفاعل مع صفحات الويب', '2025-04-27 06:39:49', '2025-04-27 06:39:49', 7, 1),
(7, 'المستوى 4.1: تأسيس تطبيقات الهاتف (App Inventor Foundations & Logic)', 'المستوى 4.1: تأسيس تطبيقات الهاتف (App Inventor Foundations & Logic)', 'app-inventor-foundations-logic', 'مبتدئ-متوسط', 'appinv1_vid_session_1', 300.00, NULL, 'تعلم أساسيات بناء تطبيقات أندرويد بدون كتابة كود باستخدام MIT App Inventor. يغطي المسار استكشاف الواجهة، تصميم واجهات المستخدم، التعامل مع الأحداث، المكونات الأساسية، الوسائط، تطبيق المنطق البرمجي (الشروط والمتغيرات)، ومقدمة لتصحيح الأخطاء.', '[{\"sort\": 1, \"active\": 1, \"videos\": [{\"id\": 1, \"sort\": 1, \"active\": 1, \"video_id\": \"appinv1_vid_session_1\", \"is_preview\": 1, \"video_desc\": \"مقدمة لعالم تطبيقات الهاتف وبيئة App Inventor وإعداد بيئة العمل.\", \"video_title\": \"الجلسة 1: الغوص في عالم التطبيقات و App Inventor\", \"video_duration\": \"90:00\"}, {\"id\": 2, \"sort\": 2, \"active\": 1, \"video_id\": \"appinv1_vid_session_2\", \"is_preview\": 0, \"video_desc\": \"تعلم سحب وإفلات المكونات الأساسية (Button, Label, TextBox) وتغيير خصائصها.\", \"video_title\": \"الجلسة 2: بناء الواجهات الأولى والخصائص\", \"video_duration\": \"90:00\"}, {\"id\": 3, \"sort\": 3, \"active\": 1, \"video_id\": \"appinv1_vid_session_3\", \"is_preview\": 0, \"video_desc\": \"استخدام مكونات التخطيط (Layouts) لتنظيم الواجهات والتحكم في محاذاة العناصر.\", \"video_title\": \"الجلسة 3: تنظيم الواجهات (Layouts)\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الأول: مقدمة وتصميم الواجهات\"}, {\"sort\": 2, \"active\": 1, \"videos\": [{\"id\": 4, \"sort\": 1, \"active\": 1, \"video_id\": \"appinv1_vid_session_4\", \"is_preview\": 0, \"video_desc\": \"فهم الأحداث (Events) وربطها بإجراءات بسيطة لتغيير النصوص والألوان.\", \"video_title\": \"الجلسة 4: التفاعل الأول (Events & Basic Actions)\", \"video_duration\": \"90:00\"}, {\"id\": 5, \"sort\": 2, \"active\": 1, \"video_id\": \"appinv1_vid_session_5\", \"is_preview\": 0, \"video_desc\": \"إضافة الصور والأصوات للتطبيقات باستخدام مكونات Image, Sound, Player.\", \"video_title\": \"الجلسة 5: إضافة الحياة (Images & Sounds)\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثاني: التفاعل الأساسي والوسائط\"}, {\"sort\": 3, \"active\": 1, \"videos\": [{\"id\": 6, \"sort\": 1, \"active\": 1, \"video_id\": \"appinv1_vid_session_6\", \"is_preview\": 0, \"video_desc\": \"تعلم استخدام الشروط (If/Then/Else) لاتخاذ القرارات بناءً على مقارنات منطقية.\", \"video_title\": \"الجلسة 6: اتخاذ القرارات (Conditional Logic - If/Then/Else)\", \"video_duration\": \"90:00\"}, {\"id\": 7, \"sort\": 2, \"active\": 1, \"video_id\": \"appinv1_vid_session_7\", \"is_preview\": 0, \"video_desc\": \"بناء شروط متعددة (else if) واستخدام مكون Notifier لعرض التنبيهات والرسائل.\", \"video_title\": \"الجلسة 7: الشروط المتقدمة والتنبيهات (Nested If/Else & Notifier)\", \"video_duration\": \"90:00\"}, {\"id\": 8, \"sort\": 3, \"active\": 1, \"video_id\": \"appinv1_vid_session_8\", \"is_preview\": 0, \"video_desc\": \"فهم أهمية المتغيرات وكيفية إنشائها واستخدامها لتخزين البيانات مؤقتاً.\", \"video_title\": \"الجلسة 8: تخزين البيانات مؤقتاً (Variables)\", \"video_duration\": \"90:00\"}, {\"id\": 9, \"sort\": 4, \"active\": 1, \"video_id\": \"appinv1_vid_session_9\", \"is_preview\": 0, \"video_desc\": \"استخدام المتغيرات مع العمليات الحسابية ومكون TextToSpeech لإضافة النطق.\", \"video_title\": \"الجلسة 9: استخدام المتغيرات والحسابات و TextToSpeech\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثالث: المنطق البرمجي والبيانات\"}, {\"sort\": 4, \"active\": 1, \"videos\": [{\"id\": 10, \"sort\": 1, \"active\": 1, \"video_id\": \"appinv1_vid_session_10\", \"is_preview\": 0, \"video_desc\": \"مراجعة شاملة وتطبيق المفاهيم في مشروع صغير وعرض النتائج.\", \"video_title\": \"الجلسة 10: مشروع المستوى الأول المصغر وتجميعه (Mini-Project & Review)\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الرابع: المشروع والمراجعة\"}]', 0, 0, '10 حصص لتأسيس بناء تطبيقات الهاتف بـ App Inventor', '2025-04-27 06:42:14', '2025-04-27 06:42:14', 8, 1),
(8, 'المستوى 4.2: بناء تطبيقات متقدمة ومشاريع (App Inventor Advanced)', 'المستوى 4.2: بناء تطبيقات متقدمة ومشاريع (App Inventor Advanced)', 'app-inventor-advanced-projects', 'متوسط-متقدم', 'appinv2_vid_session_1', 360.00, NULL, 'تعمق في بناء تطبيقات أندرويد معقدة باستخدام MIT App Inventor. يغطي المسار تنظيم الكود بالإجراءات، التعامل مع القوائم، التخزين الدائم، استخدام مكونات الوقت والحساسات، الرسم، إدارة الشاشات المتعددة، وينتهي بمشروع متكامل.', '[{\"sort\": 1, \"active\": 1, \"videos\": [{\"id\": 1, \"sort\": 1, \"active\": 1, \"video_id\": \"appinv2_vid_session_1\", \"is_preview\": 1, \"video_desc\": \"تعلم كيفية تنظيم الكود باستخدام الإجراءات (Procedures) مع وبدون مدخلات.\", \"video_title\": \"الجلسة 11 (L4.2-1): تنظيم الكود بالإجراءات (Procedures/Functions)\", \"video_duration\": \"90:00\"}, {\"id\": 2, \"sort\": 2, \"active\": 1, \"video_id\": \"appinv2_vid_session_2\", \"is_preview\": 0, \"video_desc\": \"مقدمة للتعامل مع مجموعات البيانات باستخدام القوائم (Lists) والعمليات الأساسية عليها.\", \"video_title\": \"الجلسة 12 (L4.2-2): التعامل مع مجموعات البيانات (Introduction to Lists)\", \"video_duration\": \"90:00\"}, {\"id\": 3, \"sort\": 3, \"active\": 1, \"video_id\": \"appinv2_vid_session_3\", \"is_preview\": 0, \"video_desc\": \"معالجة القوائم (الإزالة، التكرار) وعرضها للمستخدم باستخدام مكون ListView.\", \"video_title\": \"الجلسة 13 (L4.2-3): معالجة القوائم وعرضها (List Manipulation & ListView)\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الأول: تنظيم الكود وهياكل البيانات\"}, {\"sort\": 2, \"active\": 1, \"videos\": [{\"id\": 4, \"sort\": 1, \"active\": 1, \"video_id\": \"appinv2_vid_session_4\", \"is_preview\": 0, \"video_desc\": \"حفظ البيانات بشكل دائم على الجهاز باستخدام مكون التخزين المحلي TinyDB.\", \"video_title\": \"الجلسة 14 (L4.2-4): حفظ البيانات بشكل دائم (TinyDB Storage)\", \"video_duration\": \"90:00\"}, {\"id\": 5, \"sort\": 2, \"active\": 1, \"video_id\": \"appinv2_vid_session_5\", \"is_preview\": 0, \"video_desc\": \"استخدام مكون الساعة (Clock) لتنفيذ مهام مؤقتة أو دورية.\", \"video_title\": \"الجلسة 15 (L4.2-5): التحكم بالوقت والتحكم الدوري (Clock Component)\", \"video_duration\": \"90:00\"}, {\"id\": 6, \"sort\": 3, \"active\": 1, \"video_id\": \"appinv2_vid_session_6\", \"is_preview\": 0, \"video_desc\": \"التفاعل مع حركة الجهاز باستخدام حساس التسارع (Accelerometer Sensor).\", \"video_title\": \"الجلسة 16 (L4.2-6): التفاعل مع حركة الجهاز (Accelerometer Sensor)\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثاني: التخزين، الوقت، والحساسات\"}, {\"sort\": 3, \"active\": 1, \"videos\": [{\"id\": 7, \"sort\": 1, \"active\": 1, \"video_id\": \"appinv2_vid_session_7\", \"is_preview\": 0, \"video_desc\": \"مقدمة للرسم على الشاشة باستخدام مكون Canvas والتحكم في كائنات Ball/ImageSprite.\", \"video_title\": \"الجلسة 17 (L4.2-7): الرسم واللمس (Canvas & Ball/ImageSprite)\", \"video_duration\": \"90:00\"}, {\"id\": 8, \"sort\": 2, \"active\": 1, \"video_id\": \"appinv2_vid_session_8\", \"is_preview\": 0, \"video_desc\": \"تعلم كيفية إنشاء وإدارة شاشات متعددة في التطبيق وتمرير البيانات بينها.\", \"video_title\": \"الجلسة 18 (L4.2-8): التنقل بين الشاشات وتمرير البيانات (Multiple Screens)\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الثالث: الرسم والتنقل بين الشاشات\"}, {\"sort\": 4, \"active\": 1, \"videos\": [{\"id\": 9, \"sort\": 1, \"active\": 1, \"video_id\": \"appinv2_vid_session_9\", \"is_preview\": 0, \"video_desc\": \"عصف ذهني، اختيار فكرة مشروع نهائي متكامل، وتخطيط هيكله ومنطقه.\", \"video_title\": \"الجلسة 19 (L4.2-9): المشروع النهائي - العصف الذهني والتخطيط.\", \"video_duration\": \"90:00\"}, {\"id\": 10, \"sort\": 2, \"active\": 1, \"video_id\": \"appinv2_vid_session_10\", \"is_preview\": 0, \"video_desc\": \"وقت عمل مركز لتطوير الوظائف الأساسية للمشروع بمساعدة المدرب.\", \"video_title\": \"الجلسة 20 (L4.2-10): المشروع النهائي - تطوير الوظائف الأساسية.\", \"video_duration\": \"90:00\"}, {\"id\": 11, \"sort\": 3, \"active\": 1, \"video_id\": \"appinv2_vid_session_11\", \"is_preview\": 0, \"video_desc\": \"إضافة الميزات الإضافية، تحسين الواجهات، والتركيز على اختبار وتصحيح الأخطاء.\", \"video_title\": \"الجلسة 21 (L4.2-11): المشروع النهائي - إضافة الميزات وتصحيح الأخطاء.\", \"video_duration\": \"90:00\"}, {\"id\": 12, \"sort\": 4, \"active\": 1, \"video_id\": \"appinv2_vid_session_12\", \"is_preview\": 0, \"video_desc\": \"وضع اللمسات النهائية على المشروع، عرضه للمجموعة، ومناقشة النتائج والخطوات التالية.\", \"video_title\": \"الجلسة 22 (L4.2-12): المشروع النهائي - اللمسات الأخيرة والعرض.\", \"video_duration\": \"90:00\"}], \"section_title\": \"القسم الرابع: المشروع النهائي\"}]', 0, 0, '12 حصة لميزات App Inventor المتقدمة وتطوير المشاريع', '2025-04-27 06:42:14', '2025-04-27 06:42:14', 9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_credit_transactions`
--

CREATE TABLE `tb_credit_transactions` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL COMMENT 'Reference to users table',
  `transaction_type` enum('credit_purchase','course_enrollment','refund','admin_adjustment') COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Type of credit transaction',
  `amount` decimal(10,2) DEFAULT NULL COMMENT 'Transaction amount (positive for credits added, negative for spent)',
  `balance_before` decimal(10,2) DEFAULT NULL COMMENT 'User credit balance before transaction',
  `balance_after` decimal(10,2) DEFAULT NULL COMMENT 'User credit balance after transaction',
  `reference_type` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Type of related entity (course, payment, etc.)',
  `reference_id` int DEFAULT NULL COMMENT 'ID of related entity',
  `description` text COLLATE utf8mb4_general_ci COMMENT 'Transaction description',
  `processed_by` int UNSIGNED DEFAULT NULL COMMENT 'Admin user who processed the transaction',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_email_logs`
--

CREATE TABLE `tb_email_logs` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL COMMENT 'Reference to users table',
  `recipient_email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Email address of recipient',
  `email_type` enum('verification','password_reset','parent_notification','course_completion','system_notification') COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Type of email sent',
  `subject` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Email subject line',
  `template_used` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Email template identifier',
  `status` enum('sent','failed','queued') COLLATE utf8mb4_general_ci DEFAULT 'queued' COMMENT 'Email delivery status',
  `error_message` text COLLATE utf8mb4_general_ci COMMENT 'Error message if sending failed',
  `sent_at` datetime DEFAULT NULL COMMENT 'When email was successfully sent',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_enrollments`
--

CREATE TABLE `tb_enrollments` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `enrolled_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'active',
  `completed_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `proof_image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_enrollments`
--

INSERT INTO `tb_enrollments` (`id`, `user_id`, `course_id`, `enrolled_at`, `status`, `completed_at`, `updated_at`, `proof_image`) VALUES
(1, 1, 1, '2025-04-01 02:49:32', 'active', NULL, '2025-03-31 21:49:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_login_logs`
--

CREATE TABLE `tb_login_logs` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL COMMENT 'Reference to users table (null for failed attempts)',
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Email used for login attempt',
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'IP address of login attempt',
  `user_agent` text COLLATE utf8mb4_general_ci COMMENT 'Browser user agent',
  `login_status` enum('success','failed','blocked') COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Login attempt result',
  `failure_reason` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Reason for login failure',
  `session_id` varchar(128) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Session ID for successful logins',
  `logout_at` datetime DEFAULT NULL COMMENT 'When user logged out',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_payments`
--

CREATE TABLE `tb_payments` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'instapay',
  `payment_status` enum('pending','completed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `proof_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_questions`
--

CREATE TABLE `tb_questions` (
  `id` int UNSIGNED NOT NULL,
  `course_id` int UNSIGNED NOT NULL,
  `question_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `question_type` enum('single','multiple','true_false','fill_in_blank','essay') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'single',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_questions_options`
--

CREATE TABLE `tb_questions_options` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED NOT NULL,
  `option_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_quizzes`
--

CREATE TABLE `tb_quizzes` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `section_id` int DEFAULT NULL,
  `quiz_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quiz_desc` text COLLATE utf8mb4_unicode_ci,
  `questions_data` longtext COLLATE utf8mb4_unicode_ci,
  `quiz_questions` longtext COLLATE utf8mb4_unicode_ci,
  `time_limit` int DEFAULT '30',
  `time_limit_minutes` int DEFAULT '30',
  `max_attempts` int DEFAULT '3',
  `passing_score` decimal(5,2) DEFAULT '70.00',
  `difficulty_level` enum('easy','medium','hard') COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `shuffle_questions` tinyint(1) DEFAULT '0',
  `shuffle_answers` tinyint(1) DEFAULT '0',
  `show_results` tinyint(1) DEFAULT '1',
  `show_results_immediately` tinyint(1) DEFAULT '1',
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_quiz_attempts`
--

CREATE TABLE `tb_quiz_attempts` (
  `id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `user_id` int NOT NULL,
  `score` decimal(5,2) DEFAULT '0.00',
  `time_taken_seconds` int DEFAULT '0',
  `is_passed` tinyint(1) DEFAULT '0',
  `user_answers` longtext COLLATE utf8mb4_unicode_ci,
  `quiz_questions` longtext COLLATE utf8mb4_unicode_ci,
  `attempt_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sections`
--

CREATE TABLE `tb_sections` (
  `id` int UNSIGNED NOT NULL,
  `course_id` int UNSIGNED NOT NULL COMMENT 'Reference to courses table',
  `section_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Section title',
  `section_desc` text COLLATE utf8mb4_unicode_ci COMMENT 'Section description',
  `sort_order` int DEFAULT '0' COMMENT 'Display order within course',
  `is_locked` tinyint(1) DEFAULT '0' COMMENT 'Whether section requires previous completion',
  `active` tinyint(1) DEFAULT '1' COMMENT 'Whether section is active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_sections`
--

INSERT INTO `tb_sections` (`id`, `course_id`, `section_name`, `section_desc`, `sort_order`, `is_locked`, `active`, `created_at`, `updated_at`) VALUES
(1, 4, 'القسم الأول: أساسيات وبنية HTML', 'تعلم الأساسيات وبنية HTML الصحيحة', 1, 0, 1, '2025-09-11 14:45:58', '2025-09-11 14:45:58'),
(2, 4, 'القسم الثاني: الدلالية والمشاريع', 'العناصر الدلالية والمشاريع التطبيقية', 2, 1, 1, '2025-09-11 14:45:58', '2025-09-11 14:45:58'),
(3, 5, 'القسم الأول: أساسيات CSS ونموذج الصندوق', 'تعلم أساسيات CSS وفهم نموذج الصندوق', 1, 0, 1, '2025-09-11 14:47:40', '2025-09-11 14:47:40'),
(4, 1, 'الأساسيات', 'تعلم أساسيات HTML', 1, 0, 1, '2025-09-11 15:10:10', '2025-09-11 15:10:10'),
(5, 1, 'المتقدم', 'مواضيع متقدمة في HTML', 2, 0, 1, '2025-09-11 15:10:10', '2025-09-11 15:10:10'),
(6, 2, 'أساسيات CSS', 'تعلم أساسيات التصميم', 1, 0, 1, '2025-09-11 15:10:10', '2025-09-11 15:10:10'),
(7, 2, 'التصميم المتجاوب', 'تصميم مواقع متجاوبة', 2, 0, 1, '2025-09-11 15:10:10', '2025-09-11 15:10:10'),
(8, 3, 'JavaScript الأساسي', 'أساسيات البرمجة', 1, 0, 1, '2025-09-11 15:10:10', '2025-09-11 15:10:10'),
(9, 3, 'DOM والأحداث', 'التفاعل مع صفحات الويب', 2, 0, 1, '2025-09-11 15:10:10', '2025-09-11 15:10:10');

-- --------------------------------------------------------

--
-- Table structure for table `tb_student_answers`
--

CREATE TABLE `tb_student_answers` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED NOT NULL,
  `option_id` int UNSIGNED DEFAULT NULL,
  `answer_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_correct` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_units`
--

CREATE TABLE `tb_units` (
  `id` int UNSIGNED NOT NULL,
  `section_id` int UNSIGNED NOT NULL COMMENT 'Reference to sections table',
  `unit_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unit title',
  `unit_desc` text COLLATE utf8mb4_unicode_ci COMMENT 'Unit description',
  `unit_type` enum('video','text','quiz','assignment') COLLATE utf8mb4_unicode_ci DEFAULT 'video' COMMENT 'Type of unit content',
  `video_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bunny.net video ID',
  `video_duration` int DEFAULT NULL COMMENT 'Video duration in seconds',
  `content` longtext COLLATE utf8mb4_unicode_ci COMMENT 'Text content or additional materials',
  `is_preview` tinyint(1) DEFAULT '0' COMMENT 'Whether unit is available as preview',
  `sort_order` int DEFAULT '0' COMMENT 'Display order within section',
  `active` tinyint(1) DEFAULT '1' COMMENT 'Whether unit is active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_units`
--

INSERT INTO `tb_units` (`id`, `section_id`, `unit_name`, `unit_desc`, `unit_type`, `video_id`, `video_duration`, `content`, `is_preview`, `sort_order`, `active`, `created_at`, `updated_at`) VALUES
(1, 1, 'مقدمة إلى HTML', 'فهم بنية مستند HTML الأساسية', 'video', 'html_vid_1', 1800, NULL, 1, 1, 1, '2025-09-11 15:11:06', '2025-09-11 15:11:06'),
(2, 1, 'العناصر الأساسية', 'استخدام عناصر تنسيق النصوص', 'video', 'html_vid_2', 2100, NULL, 0, 2, 1, '2025-09-11 15:11:06', '2025-09-11 15:11:06'),
(3, 2, 'الوسوم الدلالية', 'استخدام العناصر الدلالية', 'video', 'html_vid_3', 1950, NULL, 0, 1, 1, '2025-09-11 15:11:06', '2025-09-11 15:11:06'),
(4, 3, 'أساسيات CSS', 'مقدمة في تنسيق الصفحات', 'video', 'css_vid_1', 2200, NULL, 1, 1, 1, '2025-09-11 15:11:06', '2025-09-11 15:11:06'),
(5, 4, 'التصميم المتجاوب', 'تصميم يتكيف مع الشاشات', 'video', 'css_vid_2', 2400, NULL, 0, 1, 1, '2025-09-11 15:11:06', '2025-09-11 15:11:06'),
(6, 5, 'متغيرات JavaScript', 'أساسيات البرمجة', 'video', 'js_vid_1', 2000, NULL, 1, 1, 1, '2025-09-11 15:11:06', '2025-09-11 15:11:06'),
(7, 6, 'DOM والأحداث', 'التفاعل مع صفحات الويب', 'video', 'js_vid_2', 2300, NULL, 0, 2, 1, '2025-09-11 15:11:06', '2025-09-11 15:11:06');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user_unit_progress`
--

CREATE TABLE `tb_user_unit_progress` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL COMMENT 'Reference to users table',
  `unit_id` int NOT NULL COMMENT 'Reference to tb_units table',
  `enrollment_id` int NOT NULL COMMENT 'Reference to tb_enrollments table',
  `progress_percentage` decimal(5,2) DEFAULT '0.00' COMMENT 'Progress percentage (0-100)',
  `watch_time` int DEFAULT '0' COMMENT 'Total watch time in seconds',
  `last_position` int DEFAULT '0' COMMENT 'Last video position in seconds',
  `is_completed` tinyint(1) DEFAULT '0' COMMENT 'Whether unit is completed',
  `completed_at` datetime DEFAULT NULL COMMENT 'When unit was completed',
  `first_accessed_at` datetime DEFAULT NULL COMMENT 'When unit was first accessed',
  `last_accessed_at` datetime DEFAULT NULL COMMENT 'When unit was last accessed',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_video_completions`
--

CREATE TABLE `tb_video_completions` (
  `id` int NOT NULL,
  `enrollment_id` int NOT NULL,
  `video_id` int NOT NULL,
  `completed_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `full_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mobile` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `group_id` tinyint(1) DEFAULT '2',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `mobile`, `user_type`, `status`, `active`, `last_active`, `created_at`, `updated_at`, `group_id`, `deleted_at`, `email`) VALUES
(1, 'fakhawy', 'ahmed fakhr', '01032863861', 1, 1, 1, '2024-03-17 04:24:54', '1969-12-31 19:33:34', NULL, 1, NULL, ''),
(3205, 'ahmed fakhr el din', NULL, NULL, 1, 1, 0, NULL, '2025-01-21 13:22:44', '2025-01-21 13:22:44', 2, NULL, ''),
(3210, 'student1', 'أحمد محمد', '01012345678', 1, 1, 1, '2025-09-11 15:11:06', '2025-09-11 15:11:06', '2025-09-11 15:11:06', 2, NULL, 'ahmed.student@example.com'),
(3211, 'student2', 'فاطمة علي', '01012345679', 1, 1, 1, '2025-09-11 15:11:06', '2025-09-11 15:11:06', '2025-09-11 15:11:06', 2, NULL, 'fatima.student@example.com'),
(3212, 'student3', 'محمد حسن', '01012345680', 1, 1, 1, '2025-09-11 15:11:06', '2025-09-11 15:11:06', '2025-09-11 15:11:06', 2, NULL, 'mohammed.student@example.com'),
(3213, 'instructor1', 'د. سارة أحمد', '01012345681', 2, 1, 1, '2025-09-11 15:11:06', '2025-09-11 15:11:06', '2025-09-11 15:11:06', 1, NULL, 'sara.instructor@example.com'),
(3214, 'admin1', 'عبدالله إدارة', '01012345682', 3, 1, 1, '2025-09-11 15:11:06', '2025-09-11 15:11:06', '2025-09-11 15:11:06', 1, NULL, 'admin@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int UNSIGNED NOT NULL,
  `title_ar` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `title_en` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `desc_ar` text COLLATE utf8mb4_general_ci,
  `desc_en` text COLLATE utf8mb4_general_ci,
  `video_url` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `duration` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `course_id` int UNSIGNED DEFAULT NULL,
  `section_id` int UNSIGNED DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `sort` int DEFAULT '0',
  `is_visible` tinyint(1) DEFAULT '1',
  `show_in_home` tinyint(1) DEFAULT '0',
  `active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_groups`
--
ALTER TABLE `auth_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_name` (`group_name`);

--
-- Indexes for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permissions`
--
ALTER TABLE `auth_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_uindex` (`key`);

--
-- Indexes for table `tbnotifications`
--
ALTER TABLE `tbnotifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_courses`
--
ALTER TABLE `tb_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_credit_transactions`
--
ALTER TABLE `tb_credit_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `processed_by` (`processed_by`),
  ADD KEY `idx_credit_transactions_user_date` (`user_id`,`created_at`),
  ADD KEY `idx_credit_transactions_type` (`transaction_type`),
  ADD KEY `idx_credit_transactions_reference` (`reference_type`,`reference_id`);

--
-- Indexes for table `tb_email_logs`
--
ALTER TABLE `tb_email_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_logs_user_type` (`user_id`,`email_type`),
  ADD KEY `idx_email_logs_status_date` (`status`,`created_at`),
  ADD KEY `idx_email_logs_type_sent` (`email_type`,`sent_at`);

--
-- Indexes for table `tb_enrollments`
--
ALTER TABLE `tb_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment` (`user_id`,`course_id`),
  ADD KEY `idx_enrollment_user` (`user_id`),
  ADD KEY `idx_enrollment_course` (`course_id`);

--
-- Indexes for table `tb_login_logs`
--
ALTER TABLE `tb_login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_login_logs_user_date` (`user_id`,`created_at`),
  ADD KEY `idx_login_logs_ip_date` (`ip_address`,`created_at`),
  ADD KEY `idx_login_logs_status` (`login_status`);

--
-- Indexes for table `tb_payments`
--
ALTER TABLE `tb_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_payments` (`user_id`),
  ADD KEY `idx_course_payments` (`course_id`),
  ADD KEY `idx_payment_status` (`payment_status`);

--
-- Indexes for table `tb_questions`
--
ALTER TABLE `tb_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_questions_options`
--
ALTER TABLE `tb_questions_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_quizzes`
--
ALTER TABLE `tb_quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_course_id` (`course_id`),
  ADD KEY `idx_section_id` (`section_id`),
  ADD KEY `idx_active` (`active`);

--
-- Indexes for table `tb_quiz_attempts`
--
ALTER TABLE `tb_quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_quiz_id` (`quiz_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_attempt_date` (`attempt_date`);

--
-- Indexes for table `tb_sections`
--
ALTER TABLE `tb_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sections_course` (`course_id`),
  ADD KEY `idx_sections_active` (`active`),
  ADD KEY `idx_sections_course_sort` (`course_id`,`sort_order`);

--
-- Indexes for table `tb_student_answers`
--
ALTER TABLE `tb_student_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_units`
--
ALTER TABLE `tb_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_units_section_sort` (`section_id`,`sort_order`),
  ADD KEY `idx_units_active` (`active`);

--
-- Indexes for table `tb_user_unit_progress`
--
ALTER TABLE `tb_user_unit_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_user_unit_progress_unique` (`user_id`,`unit_id`),
  ADD KEY `idx_user_unit_progress_enrollment` (`enrollment_id`),
  ADD KEY `idx_user_unit_progress_completed` (`is_completed`);

--
-- Indexes for table `tb_video_completions`
--
ALTER TABLE `tb_video_completions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lesson_enrollment` (`enrollment_id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_course_id` (`course_id`),
  ADD KEY `idx_section_id` (`section_id`),
  ADD KEY `idx_active` (`active`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `auth_groups`
--
ALTER TABLE `auth_groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `auth_permissions`
--
ALTER TABLE `auth_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `tbnotifications`
--
ALTER TABLE `tbnotifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_courses`
--
ALTER TABLE `tb_courses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_credit_transactions`
--
ALTER TABLE `tb_credit_transactions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_email_logs`
--
ALTER TABLE `tb_email_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_enrollments`
--
ALTER TABLE `tb_enrollments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_login_logs`
--
ALTER TABLE `tb_login_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_payments`
--
ALTER TABLE `tb_payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_questions`
--
ALTER TABLE `tb_questions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_questions_options`
--
ALTER TABLE `tb_questions_options`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_quizzes`
--
ALTER TABLE `tb_quizzes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_quiz_attempts`
--
ALTER TABLE `tb_quiz_attempts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_sections`
--
ALTER TABLE `tb_sections`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_student_answers`
--
ALTER TABLE `tb_student_answers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_units`
--
ALTER TABLE `tb_units`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_user_unit_progress`
--
ALTER TABLE `tb_user_unit_progress`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_video_completions`
--
ALTER TABLE `tb_video_completions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_user_unit_progress`
--
ALTER TABLE `tb_user_unit_progress`
  ADD CONSTRAINT `tb_user_unit_progress_ibfk_2` FOREIGN KEY (`enrollment_id`) REFERENCES `tb_enrollments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
