-- -------------------------------------------------------------
-- TablePlus 4.8.0(432)
--
-- https://tableplus.com/
--
-- Database: sms_app_db
-- Generation Time: 2022-10-09 15:52:18.0440
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `channels`;
CREATE TABLE `channels` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `token` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `contact_group`;
CREATE TABLE `contact_group` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contact_id` int NOT NULL,
  `group_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `perm_classes`;
CREATE TABLE `perm_classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `class` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `perm_methods`;
CREATE TABLE `perm_methods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `class_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `method` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `perm_roles`;
CREATE TABLE `perm_roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `classes` varchar(255) NOT NULL,
  `methods` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `senders`;
CREATE TABLE `senders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(11) DEFAULT NULL,
  `active` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `sms_bundles`;
CREATE TABLE `sms_bundles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `original` int NOT NULL,
  `remain` int NOT NULL,
  `channel_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `sms_logs`;
CREATE TABLE `sms_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `message_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `gateway_id` varchar(100) DEFAULT NULL,
  `gateway_response` json DEFAULT NULL,
  `gateway_code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `gateway_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `sender` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `sms_count` int NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'PENDING',
  `schedule` int DEFAULT '0',
  `schedule_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `templates`;
CREATE TABLE `templates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `contact_group` (`id`, `contact_id`, `group_id`) VALUES
(3, 1, 2),
(5, 3, 2),
(27, 1, 5),
(32, 3, 5),
(33, 4, 5);

INSERT INTO `contacts` (`id`, `name`, `phone`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Renfrid Ngolongolo', '0753437856', '2022-10-02 09:24:02', 1, '2022-10-03 10:34:13', 3),
(3, 'Nicholaus Ngolongolo', '0717705746', '2022-10-03 10:26:03', 3, '2022-10-09 09:29:56', 3),
(4, 'Willabelle Ngolongolo', '0767000300', '2022-10-07 17:17:51', 3, '2022-10-09 09:29:45', 3);

INSERT INTO `groups` (`id`, `name`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(5, 'Wajasiliamali', '2022-10-07 17:12:35', 3, '2022-10-09 09:12:06', 3);

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_10_05_010619_create_jobs_table', 2);

INSERT INTO `role_user` (`id`, `user_id`, `role_id`) VALUES
(4, 3, 1),
(5, 3, 2);

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'admin', 'System Administrator'),
(2, 'researcher', 'Researcher');

INSERT INTO `senders` (`id`, `name`, `active`, `created_at`, `updated_at`) VALUES
(1, 'TAARIFA', 1, '2022-10-03 17:06:42', '2022-10-03 17:10:52'),
(2, 'ESRF', 1, '2022-10-03 17:10:52', '2022-10-03 17:10:52');

INSERT INTO `sms_logs` (`id`, `message`, `message_id`, `gateway_id`, `gateway_response`, `gateway_code`, `gateway_message`, `sender`, `phone`, `sms_count`, `status`, `schedule`, `schedule_at`, `created_at`, `created_by`, `updated_at`) VALUES
(1, 'Lets hope for the best', 'BBQZUJ9E4PG', '49123599', '{\"code\": 100, \"valid\": 1, \"invalid\": 0, \"message\": \"Message Submitted Successfully\", \"duplicates\": 0, \"request_id\": 49123599, \"successful\": true}', '100', 'Message Submitted Successfully', 'TAARIFA', '0717705746', 1, 'DELIVERED', 0, NULL, '2022-10-07 15:32:04', 3, '2022-10-09 12:08:17'),
(2, 'Message from group', 'ZB6RXSFKFUF', '49123634', '{\"code\": 100, \"valid\": 1, \"invalid\": 0, \"message\": \"Message Submitted Successfully\", \"duplicates\": 0, \"request_id\": 49123634, \"successful\": true}', '100', 'Message Submitted Successfully', 'TAARIFA', '0753437856', 1, 'DELIVERED', 0, NULL, '2022-10-07 15:34:19', 3, '2022-10-09 12:08:18'),
(3, 'Message from group', 'ZB6RXSFKFUF', '49123636', '{\"code\": 100, \"valid\": 1, \"invalid\": 0, \"message\": \"Message Submitted Successfully\", \"duplicates\": 0, \"request_id\": 49123636, \"successful\": true}', '100', 'Message Submitted Successfully', 'TAARIFA', '0717705746', 1, 'DELIVERED', 0, NULL, '2022-10-07 15:34:19', 3, '2022-10-09 12:08:19'),
(4, 'Welcome  to our ESRF SMS Platform. For more information please contact us through 0717705746.', 'SLR5QCBBDDY', '49136440', '{\"code\": 100, \"valid\": 1, \"invalid\": 0, \"message\": \"Message Submitted Successfully\", \"duplicates\": 0, \"request_id\": 49136440, \"successful\": true}', '100', 'Message Submitted Successfully', 'TAARIFA', '0717705746', 1, 'DELIVERED', 0, NULL, '2022-10-08 05:06:36', 3, '2022-10-09 12:08:21'),
(5, 'Welcome  to our ESRF SMS Platform. For more information please contact us through 0717705746.', 'HBNKMDPYVX3', '49164331', '{\"code\": 100, \"valid\": 1, \"invalid\": 0, \"message\": \"Message Submitted Successfully\", \"duplicates\": 0, \"request_id\": 49164331, \"successful\": true}', '100', 'Message Submitted Successfully', 'TAARIFA', '0717705746', 1, 'DELIVERED', 0, NULL, '2022-10-09 09:47:40', 3, '2022-10-09 12:08:22'),
(6, 'Test message from File SMS', 'GYPE4AP8H16', NULL, NULL, NULL, NULL, 'TAARIFA', '717705746', 1, 'PENDING', 0, NULL, '2022-10-09 10:46:28', 3, '2022-10-09 10:46:28'),
(7, 'Test message from File SMS', 'GYPE4AP8H16', NULL, NULL, NULL, NULL, 'TAARIFA', '744262780', 1, 'PENDING', 0, NULL, '2022-10-09 10:46:28', 3, '2022-10-09 10:46:28'),
(8, 'Welcome  to our ESRF SMS Platform. For more information please contact us through 0717705746.', 'XHYYKUMKA24', NULL, NULL, NULL, NULL, 'TAARIFA', '0717705746', 1, 'PENDING', 0, NULL, '2022-10-09 10:48:09', 3, '2022-10-09 10:48:09'),
(9, 'Welcome  to our ESRF SMS Platform. For more information please contact us through 0717705746.', 'XHYYKUMKA24', NULL, NULL, NULL, NULL, 'TAARIFA', '0744262780', 1, 'PENDING', 0, NULL, '2022-10-09 10:48:09', 3, '2022-10-09 10:48:09'),
(10, 'Welcome  to our ESRF SMS Platform. For more information please contact us through 0717705746.', 'HN8Q9CKW9SZ', '49165376', '{\"code\": 100, \"valid\": 1, \"invalid\": 0, \"message\": \"Message Submitted Successfully\", \"duplicates\": 0, \"request_id\": 49165376, \"successful\": true}', '100', 'Message Submitted Successfully', 'TAARIFA', '0717705746', 1, 'DELIVERED', 0, NULL, '2022-10-09 10:48:52', 3, '2022-10-09 12:08:23'),
(11, 'Welcome  to our ESRF SMS Platform. For more information please contact us through 0717705746.', 'HN8Q9CKW9SZ', '49165377', '{\"code\": 100, \"valid\": 1, \"invalid\": 0, \"message\": \"Message Submitted Successfully\", \"duplicates\": 0, \"request_id\": 49165377, \"successful\": true}', '100', 'Message Submitted Successfully', 'TAARIFA', '0744262780', 1, 'DELIVERED', 0, NULL, '2022-10-09 10:48:52', 3, '2022-10-09 12:08:24'),
(12, 'Test message with schedule', 'T44ECYKVYLZ', '49168141', '{\"code\": 100, \"valid\": 1, \"invalid\": 0, \"message\": \"Message Submitted Successfully\", \"duplicates\": 0, \"request_id\": 49168141, \"successful\": true}', '100', 'Message Submitted Successfully', 'TAARIFA', '0717705746', 1, 'SENT', NULL, '2022-10-09 12:47:07', '2022-10-09 12:47:07', 3, '2022-10-09 12:48:31'),
(13, 'Test message', 'W2H7TXM9R7H', '49168142', '{\"code\": 100, \"valid\": 1, \"invalid\": 0, \"message\": \"Message Submitted Successfully\", \"duplicates\": 0, \"request_id\": 49168142, \"successful\": true}', '100', 'Message Submitted Successfully', 'TAARIFA', '0717705746', 1, 'SENT', NULL, '2022-10-09 12:48:09', '2022-10-09 12:48:09', 3, '2022-10-09 12:48:32'),
(14, 'Test message with schedule 2', 'WWQN3HHRYBU', NULL, NULL, NULL, NULL, 'TAARIFA', '0717705746', 1, 'PENDING', 1, '2022-10-09 18:00:00', '2022-10-09 12:49:32', 3, '2022-10-09 12:49:32');

INSERT INTO `templates` (`id`, `name`, `message`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(2, 'ESRF Welcome Message', 'Welcome  to our ESRF SMS Platform. For more information please contact us through 0717705746.', '2022-10-03 13:11:26', 3, '2022-10-07 15:36:27', 3);

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `email_verified_at`, `password`, `remember_token`, `active`, `created_at`, `updated_at`) VALUES
(3, 'Renfrid Ngolongolo', '0753437856', 'admin@admin.com', NULL, '$2y$10$k4g.lSr78WCOV3rM7HVNiOVDK2P8DhW8Sn7aqq05B.djxr9Qz1hWC', NULL, 1, '2022-10-03 09:03:55', '2022-10-03 09:03:55');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;