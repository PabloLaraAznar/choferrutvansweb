-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2025 a las 16:51:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rutvans2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('397e5e47a2c644aed34e589b1c0950e9', 'i:1;', 1750949014),
('397e5e47a2c644aed34e589b1c0950e9:timer', 'i:1750949014;', 1750949014),
('5afdba40d49f4d84771279ab9ded5d03', 'i:1;', 1750948458),
('5afdba40d49f4d84771279ab9ded5d03:timer', 'i:1750948458;', 1750948458),
('92d3d820e797ecff15356d4108d409e8', 'i:1;', 1750949102),
('92d3d820e797ecff15356d4108d409e8:timer', 'i:1750949102;', 1750949102),
('b2ec88d0c43df059b66b02dcbce52f2f', 'i:1;', 1750948610),
('b2ec88d0c43df059b66b02dcbce52f2f:timer', 'i:1750948610;', 1750948610),
('cc065f45db359998f0f288666f412b3f', 'i:1;', 1750948945),
('cc065f45db359998f0f288666f412b3f:timer', 'i:1750948945;', 1750948945),
('d409e318fe929a6a9d573b0f9dbf4ca0', 'i:1;', 1750948844),
('d409e318fe929a6a9d573b0f9dbf4ca0:timer', 'i:1750948844;', 1750948844),
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}', 1751033281);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cashiers`
--

CREATE TABLE `cashiers` (
  `id` int(11) NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `employee_code` varchar(50) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cashiers`
--

INSERT INTO `cashiers` (`id`, `id_user`, `employee_code`, `photo`, `created_at`, `updated_at`) VALUES
(1, 11, '0001', 'cashier/11/pablo_mena/cashier_photo.jpg', '2025-06-07 09:56:03', '2025-06-11 23:19:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coordinators`
--

CREATE TABLE `coordinators` (
  `id` int(11) NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `employee_code` varchar(50) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `coordinators`
--

INSERT INTO `coordinators` (`id`, `id_user`, `employee_code`, `photo`, `created_at`, `updated_at`) VALUES
(5, 8, '0004', 'coordinators/8/pedro_pascal/coordinator_photo.jpg', '2025-06-07 09:41:41', '2025-06-07 09:41:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `license` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `drivers`
--

INSERT INTO `drivers` (`id`, `id_user`, `license`, `photo`, `created_at`, `updated_at`) VALUES
(1, 2, '7678hjokpbhg', 'drivers/2/juan_per_ez/driver_photo.jpg', '2025-06-05 21:05:09', '2025-06-05 21:07:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `driver_unit`
--

CREATE TABLE `driver_unit` (
  `id` int(11) NOT NULL,
  `id_driver` int(11) NOT NULL,
  `id_unit` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `freights`
--

CREATE TABLE `freights` (
  `id` int(11) NOT NULL,
  `folio` varchar(50) NOT NULL,
  `id_service` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pendiente',
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localities`
--

CREATE TABLE `localities` (
  `id` int(11) NOT NULL,
  `longitude` decimal(10,6) NOT NULL,
  `latitude` decimal(10,6) NOT NULL,
  `locality` varchar(100) NOT NULL,
  `street` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `municipality` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL DEFAULT 'México',
  `locality_type` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2025_06_05_015108_create_cache_table', 1),
(3, '2025_06_05_015109_create_permission_tables', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 2),
(1, 'App\\Models\\User', 15),
(2, 'App\\Models\\User', 8),
(2, 'App\\Models\\User', 14),
(3, 'App\\Models\\User', 17),
(4, 'App\\Models\\User', 11),
(4, 'App\\Models\\User', 16),
(7, 'App\\Models\\User', 12),
(8, 'App\\Models\\User', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rates`
--

CREATE TABLE `rates` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'driver', 'web', '2025-06-05 21:04:28', '2025-06-05 21:04:28'),
(2, 'coordinate', 'web', '2025-06-07 09:24:15', '2025-06-07 09:24:15'),
(3, 'client', 'web', '2025-06-07 09:24:47', '2025-06-07 09:24:47'),
(4, 'cashier', 'web', '2025-06-07 09:55:49', '2025-06-07 09:55:49'),
(5, 'admin', 'sanctum', '2025-06-26 12:02:30', '2025-06-26 12:02:30'),
(6, 'super-admin', 'sanctum', '2025-06-26 12:02:38', '2025-06-26 12:02:38'),
(7, 'super-admin', 'web', '2025-06-26 12:54:57', '2025-06-26 12:54:57'),
(8, 'admin', 'web', '2025-06-26 12:54:57', '2025-06-26 12:54:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `id_location_s` int(11) NOT NULL,
  `id_location_f` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `route_unit`
--

CREATE TABLE `route_unit` (
  `id` int(11) NOT NULL,
  `id_route` int(11) NOT NULL,
  `id_driver_unit` int(11) NOT NULL,
  `intermediate_location_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `route_unit_schedule`
--

CREATE TABLE `route_unit_schedule` (
  `id` int(11) NOT NULL,
  `id_route_unit` int(11) NOT NULL,
  `schedule_date` date NOT NULL,
  `schedule_time` time NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `folio` varchar(50) NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_payment` int(11) NOT NULL,
  `id_route_unit_schedule` int(11) NOT NULL,
  `id_rate` int(11) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `status` varchar(50) NOT NULL DEFAULT 'Pendiente',
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
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
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('dAHHCYCrnJRlPzeV7pyRlhAB5TfnUwHrdRm0S3Pv', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidU1obVlaRDRIZHRTZFRLR3cxWUE5SG56QldldjdidVFqRGNPYnJHciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7fX0=', 1750949195),
('OqE8flBRww9YnSRYSaV23smc2mThcGb1CYrCZbr7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.101.2 Chrome/134.0.6998.205 Electron/35.5.1 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieEIzRkhIaXh4bVhjRzM1bVZDTUw3MzNsVEpRYmxhRHc1a20yTG56UyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZXRvZG9QYWdvP2lkPTBiNTUyMDM3LTU3OGUtNDQ3My04MmIwLWIyODkzMmQzOWFjNSZ2c2NvZGVCcm93c2VyUmVxSWQ9MTc1MDk0ODI1NTY3NSI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjEwNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL21ldG9kb1BhZ28/aWQ9MGI1NTIwMzctNTc4ZS00NDczLTgyYjAtYjI4OTMyZDM5YWM1JnZzY29kZUJyb3dzZXJSZXFJZD0xNzUwOTQ4MjU1Njc1Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1750948256),
('UGFBbQKd06hl9Ut6gvDyDO5rWEsFwYxGGbEfSNsM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.101.2 Chrome/134.0.6998.205 Electron/35.5.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibWRVSXNuaXkyZnlkNFluVVRid0RQUEcwVm9Sc3Z0WUd5b1lnVHNXRSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750948256);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipments`
--

CREATE TABLE `shipments` (
  `id` int(11) NOT NULL,
  `folio` varchar(50) NOT NULL,
  `id_route_unit` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `receiver_name` varchar(100) NOT NULL,
  `package_description` text DEFAULT NULL,
  `package_image` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pendiente',
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `plate` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `address`, `phone_number`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@material.com', '2025-06-26 05:52:14', '$2y$12$3QAstJnvaYvVz2jBPonkcegEtNN9MehWt2ix6ztk3ofwkVp9/5rkm', NULL, NULL, NULL, NULL, NULL, '2025-06-05 21:04:14', '2025-06-05 21:04:14'),
(2, 'Juan Peréz', 'juanperez01@gmail.com', '2025-06-26 05:57:10', '$2y$12$/6fiErUyFNF9JrSOJp5Dqe/qzGWVKhxQi39ch0o6Ci9g1YDCAFU6u', NULL, NULL, NULL, NULL, NULL, '2025-06-05 21:05:09', '2025-06-05 21:07:04'),
(8, 'Pedro Pascal', 'pepe@gmail.com', '2025-06-26 05:57:13', '$2y$12$ld0OTs0Qrpi5aS0pPm353udKmZSLFHTbdmcFyg6ZQIOTwyggzKR7C', NULL, NULL, NULL, NULL, NULL, '2025-06-07 09:41:41', '2025-06-07 09:41:41'),
(11, 'Pablo Mena', 'pepem@gmail.com', '2025-06-26 05:57:17', '$2y$12$eAslCvXzpzsLnB9j5p2Zoeg3dVZov0xgOKR9/4iuTs/jelbxaAheK', NULL, NULL, NULL, NULL, NULL, '2025-06-07 09:56:03', '2025-06-11 23:19:27'),
(12, 'Super Admin Test', 'superadmin@test.com', '2025-06-26 12:58:17', '$2y$12$aoypZp0FOFdHHSk/HXCvEOEPrBlezEwW/XvmJxA.5OZYafJAjHqJa', NULL, NULL, NULL, NULL, NULL, '2025-06-26 12:53:38', '2025-06-26 12:58:17'),
(13, 'Admin Test', 'admin@test.com', '2025-06-26 12:58:17', '$2y$12$VMBQ1SBvoihm2HsAV3JpaOBDyuFFFxuUNp9SZrTlejm0QJp7R4NRy', NULL, NULL, NULL, NULL, NULL, '2025-06-26 12:55:02', '2025-06-26 12:58:17'),
(14, 'Coordinador Test', 'coordinate@test.com', '2025-06-26 12:58:17', '$2y$12$DC1YABYsmKaAr8KsPmpJju1JsgZF4vZ1g7zbEE/0j4nvYdnamT2HS', NULL, NULL, NULL, NULL, NULL, '2025-06-26 12:55:02', '2025-06-26 12:58:17'),
(15, 'Conductor Test', 'driver@test.com', '2025-06-26 12:58:17', '$2y$12$HF8.xq35eG4d3vg5nZnDg.ThBvs02fCsFftrytfBN6rj7EBSnhMBW', NULL, NULL, NULL, NULL, NULL, '2025-06-26 12:55:03', '2025-06-26 12:58:17'),
(16, 'Cajero Test', 'cashier@test.com', '2025-06-26 12:58:17', '$2y$12$i3FBFTSsEOliLM/GSxEMGuyzWb3WZUJZ3/gnxAWfw8y1bOEDnwXZ6', NULL, NULL, NULL, NULL, NULL, '2025-06-26 12:55:03', '2025-06-26 12:58:17'),
(17, 'Cliente Test', 'client@test.com', '2025-06-26 12:58:17', '$2y$12$5SbDQpHJaDJl8swVllXLhOTOVxyoXEjTPjGQ5Fynak.dZpZG50v8e', NULL, NULL, NULL, NULL, NULL, '2025-06-26 12:55:03', '2025-06-26 12:58:17');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cashiers`
--
ALTER TABLE `cashiers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `coordinators`
--
ALTER TABLE `coordinators`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indices de la tabla `driver_unit`
--
ALTER TABLE `driver_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_driver` (`id_driver`),
  ADD KEY `id_unit` (`id_unit`);

--
-- Indices de la tabla `freights`
--
ALTER TABLE `freights`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folio` (`folio`),
  ADD KEY `id_service` (`id_service`);

--
-- Indices de la tabla `localities`
--
ALTER TABLE `localities`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `rates`
--
ALTER TABLE `rates`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_location_s` (`id_location_s`),
  ADD KEY `id_location_f` (`id_location_f`);

--
-- Indices de la tabla `route_unit`
--
ALTER TABLE `route_unit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_route_driver` (`id_route`,`id_driver_unit`),
  ADD KEY `id_driver_unit` (`id_driver_unit`),
  ADD KEY `intermediate_location_id` (`intermediate_location_id`);

--
-- Indices de la tabla `route_unit_schedule`
--
ALTER TABLE `route_unit_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_route_unit` (`id_route_unit`);

--
-- Indices de la tabla `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folio` (`folio`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_payment` (`id_payment`),
  ADD KEY `id_route_unit_schedule` (`id_route_unit_schedule`),
  ADD KEY `id_rate` (`id_rate`);

--
-- Indices de la tabla `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folio` (`folio`),
  ADD KEY `id_route_unit` (`id_route_unit`),
  ADD KEY `id_service` (`id_service`);

--
-- Indices de la tabla `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cashiers`
--
ALTER TABLE `cashiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `coordinators`
--
ALTER TABLE `coordinators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `driver_unit`
--
ALTER TABLE `driver_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `freights`
--
ALTER TABLE `freights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `localities`
--
ALTER TABLE `localities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rates`
--
ALTER TABLE `rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `route_unit`
--
ALTER TABLE `route_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `route_unit_schedule`
--
ALTER TABLE `route_unit_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cashiers`
--
ALTER TABLE `cashiers`
  ADD CONSTRAINT `cashiers_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `coordinators`
--
ALTER TABLE `coordinators`
  ADD CONSTRAINT `coordinators_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `driver_unit`
--
ALTER TABLE `driver_unit`
  ADD CONSTRAINT `driver_unit_ibfk_1` FOREIGN KEY (`id_driver`) REFERENCES `drivers` (`id`),
  ADD CONSTRAINT `driver_unit_ibfk_2` FOREIGN KEY (`id_unit`) REFERENCES `units` (`id`);

--
-- Filtros para la tabla `freights`
--
ALTER TABLE `freights`
  ADD CONSTRAINT `freights_ibfk_1` FOREIGN KEY (`id_service`) REFERENCES `services` (`id`);

--
-- Filtros para la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_ibfk_1` FOREIGN KEY (`id_location_s`) REFERENCES `localities` (`id`),
  ADD CONSTRAINT `routes_ibfk_2` FOREIGN KEY (`id_location_f`) REFERENCES `localities` (`id`);

--
-- Filtros para la tabla `route_unit`
--
ALTER TABLE `route_unit`
  ADD CONSTRAINT `route_unit_ibfk_1` FOREIGN KEY (`id_route`) REFERENCES `routes` (`id`),
  ADD CONSTRAINT `route_unit_ibfk_2` FOREIGN KEY (`id_driver_unit`) REFERENCES `driver_unit` (`id`),
  ADD CONSTRAINT `route_unit_ibfk_3` FOREIGN KEY (`intermediate_location_id`) REFERENCES `localities` (`id`);

--
-- Filtros para la tabla `route_unit_schedule`
--
ALTER TABLE `route_unit_schedule`
  ADD CONSTRAINT `route_unit_schedule_ibfk_1` FOREIGN KEY (`id_route_unit`) REFERENCES `route_unit` (`id`);

--
-- Filtros para la tabla `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`id_payment`) REFERENCES `payments` (`id`),
  ADD CONSTRAINT `sales_ibfk_3` FOREIGN KEY (`id_route_unit_schedule`) REFERENCES `route_unit_schedule` (`id`),
  ADD CONSTRAINT `sales_ibfk_4` FOREIGN KEY (`id_rate`) REFERENCES `rates` (`id`);

--
-- Filtros para la tabla `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_ibfk_1` FOREIGN KEY (`id_route_unit`) REFERENCES `route_unit` (`id`),
  ADD CONSTRAINT `shipments_ibfk_2` FOREIGN KEY (`id_service`) REFERENCES `services` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
