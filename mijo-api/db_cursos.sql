-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para db_cursos
CREATE DATABASE IF NOT EXISTS `db_cursos` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_cursos`;

-- Volcando estructura para tabla db_cursos.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagen` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `state` tinyint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_category_id_foreign` (`category_id`),
  CONSTRAINT `categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_cursos.categories: ~6 rows (aproximadamente)
REPLACE INTO `categories` (`id`, `name`, `imagen`, `category_id`, `state`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(1, 'Importación nueva felipe', 'categories/dVsRYvw2uiMCcztxbkYwVkB8H4xSKXDkhIyJpv1h.png', NULL, 1, NULL, '2024-04-13 16:47:15', '2024-05-11 09:13:45'),
	(3, 'gdsgd', NULL, 1, NULL, NULL, '2024-04-13 18:24:35', '2024-04-13 23:24:35'),
	(4, 'PRUEBA DE CATEGORIA', 'categories/00OoBTg3KFjsJnwEp5sSFMw2A1eqxPPCLCPKARPt.jpg', NULL, NULL, NULL, '2024-04-20 03:47:39', '2024-04-20 08:47:39'),
	(5, 'PRUEBA CATEGORIA 2', NULL, 4, 1, NULL, '2024-04-20 03:47:54', '2024-05-11 09:04:01'),
	(6, 'inactivo', 'categories/rn9ItJ4ULisHgWNMZ5U5K7nNu9wkKZ92rGOyD70y.png', NULL, 1, NULL, '2024-05-12 15:28:58', '2024-05-12 20:29:20'),
	(7, 'juliaca', NULL, 1, 1, '2024-05-12 20:36:48', '2024-05-12 15:36:22', '2024-05-12 20:36:48');

-- Volcando estructura para tabla db_cursos.courses
CREATE TABLE IF NOT EXISTS `courses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `slug` text NOT NULL,
  `subtitle` text NOT NULL,
  `imagen` varchar(250) DEFAULT NULL,
  `precio_usd` double unsigned zerofill NOT NULL DEFAULT '0000000000000000000000',
  `precio_pen` double unsigned zerofill DEFAULT '0000000000000000000000',
  `user_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `sub_categorie_id` bigint unsigned NOT NULL,
  `level` varchar(120) NOT NULL,
  `idioma` varchar(150) NOT NULL,
  `vimeo_id` varchar(50) DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `what_is_it_for` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `state` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1 es prueba y 2 es publico',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `courses_chk_1` CHECK (json_valid(`requirements`))
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla db_cursos.courses: ~3 rows (aproximadamente)
REPLACE INTO `courses` (`id`, `title`, `slug`, `subtitle`, `imagen`, `precio_usd`, `precio_pen`, `user_id`, `category_id`, `sub_categorie_id`, `level`, `idioma`, `vimeo_id`, `time`, `description`, `requirements`, `what_is_it_for`, `state`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(13, 'Prueba Imagen 12', 'prueba-imagen-12', 'Cómo usar los subtítulos en tu sitio web', 'courses/qKuvCIebkdyOY0rAxUf1lAbYRL70fLpxWyLediAn.png', 0000000000000000000050, 0000000000000000000200, 1, 4, 5, 'Basico', 'Español', '945664310', '00:00:20', '<p>Sin Descripción</p>', '["hola"]', '["hola","Ingenieros de software"]', 2, '2024-05-12 05:52:12', '2024-05-13 10:48:51', NULL),
	(14, 'prueba cursos 2', 'prueba-cursos-2', 'Cómo usar los subtítulos en tu sitio web', NULL, 0000000000000000000050, 0000000000000000000200, 1, 4, 5, 'Basico', 'Español', NULL, NULL, '<p>uhhjjhkbj</p>', 'null', 'null', 1, '2024-05-12 15:40:08', '2024-05-12 23:54:15', '2024-05-12 23:54:15'),
	(15, 'asfsad', 'asfsad', 'Cómo usar los subtítulos en tu sitio web', NULL, 0000000000000000000050, 0000000000000000000200, 1, 1, 3, 'Basico', 'Ingles', NULL, NULL, '<p>fasdfasdfasd</p>', 'null', 'null', 1, '2024-05-12 17:33:07', '2024-05-12 23:54:13', '2024-05-12 23:54:13'),
	(16, 'Prueba Vimeo', 'prueba-vimeo', 'Cómo usar los subtítulos en tu sitio web', 'courses/BcFGyMF583gUL7mLWgtM2NbMhZ6KtUBzcNarGhCs.png', 0000000000000000000050, 0000000000000000000200, 1, 4, 5, 'Intermedio', 'Español', '945668018', '00:00:20', '<p>fasdfasd</p>', '[""]', '[""]', 2, '2024-05-13 06:05:12', '2024-05-13 18:24:33', NULL);

-- Volcando estructura para tabla db_cursos.course_clases
CREATE TABLE IF NOT EXISTS `course_clases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_section_id` bigint unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `state` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1 es activo',
  `vimeo_id` varchar(50) DEFAULT NULL,
  `time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla db_cursos.course_clases: ~3 rows (aproximadamente)
REPLACE INTO `course_clases` (`id`, `course_section_id`, `name`, `description`, `state`, `vimeo_id`, `time`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(26, 1, 'Introducion', '<p>Sin Descripci&oacute;nfasdf</p>', 1, '946600577', '00:00:00', '2024-05-14 14:35:10', '2024-05-15 19:33:58', NULL),
	(27, 1, 'Prueba 2', '<p>descripcion de prueba</p>', 1, NULL, NULL, '2024-05-15 14:34:59', '2024-05-15 19:54:42', '2024-05-15 19:54:42'),
	(28, 1, 'dfasd', '<p>Sin Descripci&oacute;fasdfs</p>', 1, NULL, NULL, '2024-05-15 14:37:06', '2024-05-15 19:54:38', '2024-05-15 19:54:38'),
	(29, 1, 'dfasd', '<p>Sin Descripci&oacute;fasdfs</p>', 1, NULL, NULL, '2024-05-15 14:37:07', '2024-05-15 19:54:34', '2024-05-15 19:54:34');

-- Volcando estructura para tabla db_cursos.course_clase_files
CREATE TABLE IF NOT EXISTS `course_clase_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_clase_id` bigint unsigned NOT NULL,
  `name_file` varchar(250) NOT NULL,
  `size` varchar(50) NOT NULL,
  `time` varchar(50) DEFAULT NULL,
  `resolution` varchar(20) DEFAULT NULL,
  `file` varchar(250) NOT NULL,
  `type` varchar(20) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla db_cursos.course_clase_files: ~5 rows (aproximadamente)
REPLACE INTO `course_clase_files` (`id`, `course_clase_id`, `name_file`, `size`, `time`, `resolution`, `file`, `type`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(3, 26, 'multi.docx', '56404', NULL, NULL, 'clases_files/bMUsPhSM2SzTrOrCkXQDdRvitDB0Tc5G4qskojMF.docx', 'docx', '2024-05-15 19:27:37', '2024-05-14 14:35:10', '2024-05-15 19:27:37'),
	(4, 26, 'WhatsApp Image 2024-05-14 at 6.38.35 PM.jpeg', '138369', NULL, '946 X 587', 'clases_files/ZsUTWBEceQqXcWsj51XawkvwqrzvflftkjzuP6Tg.jpg', 'jpeg', NULL, '2024-05-15 14:10:44', '2024-05-15 19:10:44'),
	(5, 26, 'Captura de pantalla 2024-04-01 062143.png', '23136', NULL, '687 X 177', 'clases_files/ZINDDtJWCmIZ4ilb1uxlcXtRugy7F2QUs5KGzKOp.png', 'png', '2024-05-15 19:28:38', '2024-05-15 14:11:20', '2024-05-15 19:28:38'),
	(6, 27, 'WhatsApp Image 2024-05-14 at 6.38.35 PM.jpeg', '138369', NULL, '946 X 587', 'clases_files/vAEaiOTT4EQUgQB4SQVH0zKzJXkKuqQrsibHA0Bg.jpg', 'jpeg', NULL, '2024-05-15 14:34:59', '2024-05-15 14:34:59'),
	(7, 28, '2317759_business_infographics_stock market_3840x2160.mp4', '7599832', NULL, NULL, 'clases_files/cNMxuDHXK85MXDPSxJLOtKB1TXGFmQ8gRAY1rfcF.mp4', 'mp4', NULL, '2024-05-15 14:37:06', '2024-05-15 14:37:06'),
	(8, 29, '2317759_business_infographics_stock market_3840x2160.mp4', '7599832', NULL, NULL, 'clases_files/qWUWGuqeWcRaNe6t6c39jdo90VHvk9ep04Tdi5PR.mp4', 'mp4', NULL, '2024-05-15 14:37:07', '2024-05-15 14:37:07');

-- Volcando estructura para tabla db_cursos.course_sections
CREATE TABLE IF NOT EXISTS `course_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `state` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1 es activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla db_cursos.course_sections: ~2 rows (aproximadamente)
REPLACE INTO `course_sections` (`id`, `course_id`, `name`, `state`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 16, 'Introducción', 1, '2024-05-13 12:05:29', '2024-05-13 18:21:12', NULL),
	(2, 16, 'Modulo VENTAS', 1, '2024-05-13 12:24:24', '2024-05-13 18:21:09', '2024-05-13 18:21:09');

-- Volcando estructura para tabla db_cursos.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
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

-- Volcando datos para la tabla db_cursos.failed_jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_cursos.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_cursos.migrations: ~0 rows (aproximadamente)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2013_04_10_164712_create_roles_table', 1),
	(2, '2014_10_12_000000_create_users_table', 1),
	(3, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(4, '2019_08_19_000000_create_failed_jobs_table', 1),
	(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(6, '2024_04_13_005658_create_categories_table', 1);

-- Volcando estructura para tabla db_cursos.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_cursos.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_cursos.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_cursos.personal_access_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_cursos.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_cursos.roles: ~0 rows (aproximadamente)
REPLACE INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Administrador', '2024-04-13 16:47:15', '2024-04-13 16:47:15');

-- Volcando estructura para tabla db_cursos.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` bigint unsigned DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_instructor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profesion` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` tinyint NOT NULL DEFAULT '1',
  `type_user` tinyint NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_cursos.users: ~2 rows (aproximadamente)
REPLACE INTO `users` (`id`, `name`, `surname`, `email`, `avatar`, `role_id`, `email_verified_at`, `password`, `is_instructor`, `profesion`, `description`, `state`, `type_user`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Luis Martin', 'Vilca Hilasaca', 'luis@gmail.com', 'users/5PrRlsdQgEbJR79R1GoKZCc91vaoFiJ82tOIkFfT.png', 1, '2024-04-13 21:47:15', '$2y$12$8Iuptk6Pa429vlVhSFLKP.bXdIXUmSXLkLh8BkbmW9UW9NbLi7/gy', '1', 'Gestión en Procesos', 'Matesria en havard', 1, 2, 'I8grudWENd', '2024-04-13 16:47:15', '2024-04-13 22:07:11'),
	(3, 'Usuario', 'Prueba', 'usuario@gmail.com', 'users/9oGoaiLJ1CxD8XU2isZVyaE24vPIysGyYmHVHi5O.png', 1, NULL, '$2y$12$2FlPmJJ5FMqqAVMRll7DBeTTQ0DjC7MjlFZnUtC9BOvT4niPYKPeO', NULL, NULL, NULL, 2, 2, NULL, '2024-05-11 04:19:57', '2024-05-12 20:34:50'),
	(6, 'Prueba de usuario 2', 'usuario 32', '1235@gmail.com', 'users/dJ5X9hkgphOix4yz9AHHo8GUMfe0vpoDzIrRWMP7.png', 1, NULL, '$2y$12$4rvdyR6wPlmwuN8sdrUMg.4dSb9UdIKKeUqKgqyuZgTbF0pNrjWAO', NULL, NULL, NULL, 1, 2, NULL, '2024-05-12 15:35:15', '2024-05-12 20:35:15');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
