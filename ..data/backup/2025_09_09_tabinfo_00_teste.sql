-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 09/09/2025 às 00:34
-- Versão do servidor: 9.1.0
-- Versão do PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tabinfo_00_teste`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `customers`
--

CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL,
  `active` int NOT NULL DEFAULT '0',
  `approved` int NOT NULL DEFAULT '0',
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customers` bigint unsigned DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cpf` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnpj` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ie` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth` date DEFAULT NULL,
  `sexo` int DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_acess` datetime DEFAULT NULL,
  `order` int NOT NULL DEFAULT '999',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `customers_address`
--

CREATE TABLE `customers_address` (
  `id` bigint unsigned NOT NULL,
  `customers` bigint unsigned NOT NULL,
  `main` int NOT NULL DEFAULT '0',
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cpf_cnpj` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `complement` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `neighborhood` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uf` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `items`
--

CREATE TABLE `items` (
  `id` bigint unsigned NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categories` bigint unsigned DEFAULT NULL,
  `order` int NOT NULL DEFAULT '999',
  `place` int NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `items_categories`
--

CREATE TABLE `items_categories` (
  `id` bigint unsigned NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subcategories` bigint unsigned DEFAULT NULL,
  `order` int NOT NULL DEFAULT '999',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs`
--

CREATE TABLE `logs` (
  `id` bigint unsigned NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_exit` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `texts`
--

CREATE TABLE `texts` (
  `id` bigint unsigned NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `name_main` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `place` int NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '999',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `users` bigint DEFAULT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `permissions_all` int NOT NULL DEFAULT '0',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `webhooks`
--

CREATE TABLE `webhooks` (
  `id` bigint unsigned NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `orders` int DEFAULT NULL,
  `gateway_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gateway_webhooks_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `return__` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reponse` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `request` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `x_settings`
--

CREATE TABLE `x_settings` (
  `id` bigint unsigned NOT NULL,
  `fields` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `y_menu_admin`
--

CREATE TABLE `y_menu_admin` (
  `id` bigint unsigned NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `table__` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categories` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` int NOT NULL DEFAULT '0',
  `type_items` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `orderby` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '999',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `y_menu_admin_categories`
--

CREATE TABLE `y_menu_admin_categories` (
  `id` bigint unsigned NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `y_menu_admin_columns`
--

CREATE TABLE `y_menu_admin_columns` (
  `id` bigint unsigned NOT NULL,
  `y_menu_admin` bigint unsigned NOT NULL,
  `users` bigint unsigned NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `z_rel`
--

CREATE TABLE `z_rel` (
  `id` bigint unsigned NOT NULL,
  `table__` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id__` bigint unsigned NOT NULL,
  `fields` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `z_text`
--

CREATE TABLE `z_text` (
  `id` bigint unsigned NOT NULL,
  `table__` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id__` bigint unsigned NOT NULL,
  `fields` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `customers`
--

INSERT INTO `customers` (`id`, `active`, `approved`, `name`, `image`, `type`, `customers`, `email`, `cpf`, `cnpj`, `ie`, `phone`, `birth`, `sexo`, `price`, `url`, `code`, `last_acess`, `order`, `password`, `remember_token`, `verified_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Teste AAA1', '[{"file":"customer-1_1111988146.jpg","size":15007,"type":"jpg","name":"01.jpg"}]', 'customers', NULL, 'p@1', '996.833.853-20', NULL, NULL, '(99) 99999-9999', '1990-01-01', 1, 0.00, NULL, NULL, '2025-09-09 00:34:22', 999, '$2y$10$gB9skygnSrvo9ZYehKJwCuAIdJTIMJ3AEKINgISns8OaL7hbD2h5a', NULL, '2025-09-09 00:17:00', '2025-02-18 21:45:14', '2025-09-09 00:34:22'),
(2, 1, 1, 'Teste 002', '[{"file":"customer-2_teste-002_955443661.jpg","size":15006,"type":"jpg","name":"02.jpg"}]', 'customers', NULL, 't@2', '772.244.886-07', NULL, NULL, '(11) 11111-1111', '1990-01-01', 1, 0.00, NULL, NULL, '2025-04-04 16:48:48', 999, '$2y$10$xAxz15OV1pt92t9DYLqnJ.jAM53hYdxEkhLq37eNZqA0TsGEhSDxi', NULL, NULL, '2025-02-18 20:36:52', '2025-09-09 00:14:14');

--
-- Despejando dados para a tabela `items`
--

INSERT INTO `items` (`id`, `active`, `name`, `image`, `type`, `categories`, `order`, `place`, `price`, `date`, `created_at`, `updated_at`, `link`, `icon`) VALUES
(1, 1, 'Banner 01', '[{"file":"item-1_banner-01_1430344770.jpg","size":15007}]', 'banners', NULL, 999, 1, 0.00, NULL, NULL, '2025-07-14 01:59:55', NULL, NULL),
(2, 0, 'Banner 02', '[{"file":"item-2_banner-02_36538987.jpg","size":15007}]', 'banners', NULL, 999, 1, 0.00, NULL, NULL, '2025-05-16 11:53:02', NULL, NULL),
(33, 1, 'Behance', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(34, 1, 'Blog', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(35, 1, 'Delicious', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(36, 1, 'Dribbble', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(37, 1, 'Facebook', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(38, 1, 'Flickr', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(39, 1, 'Google', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(40, 1, 'Instagram', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(41, 1, 'Linkedin', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(42, 1, 'Pinterest', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(43, 1, 'Rss', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(44, 1, 'Skype', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(45, 1, 'TikTok', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(46, 1, 'Twitter', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(47, 1, 'Vimeo', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(48, 1, 'Whatsapp', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(49, 1, 'Youtube', NULL, 'social_network', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(50, 1, 'Comercial', NULL, 'contact', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(51, 1, 'Financeiro', NULL, 'contact', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL),
(52, 1, 'RH', NULL, 'contact', NULL, 999, 0, 0.00, NULL, NULL, NULL, NULL, NULL);

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '1_cache', 1),
(2, '1_cache_locks', 1),
(3, '1_customers', 1),
(4, '1_customers_address', 1),
(5, '1_items', 1),
(6, '1_items_categories', 1),
(7, '1_logs', 1),
(8, '1_personal_access_tokens', 1),
(9, '1_texts', 1),
(10, '1_users', 1),
(11, '1_webhooks', 1),
(12, '1_x_settings', 1),
(13, '1_y_menu_admin', 1),
(14, '1_y_menu_admin_categories', 1),
(15, '1_y_menu_admin_columns', 1),
(16, '1_z_rel', 1),
(17, '1_z_text', 1),
(18, '2_cache', 1),
(19, '2_cache_locks', 1),
(20, '2_customers', 1),
(21, '2_customers_address', 1),
(22, '2_items', 1),
(23, '2_items_categories', 1),
(24, '2_logs', 1),
(25, '2_personal_access_tokens', 1),
(26, '2_texts', 1),
(27, '2_users', 1),
(28, '2_webhooks', 1),
(29, '2_x_settings', 1),
(30, '2_y_menu_admin', 1),
(31, '2_y_menu_admin_categories', 1),
(32, '2_y_menu_admin_columns', 1),
(33, '2_z_rel', 1),
(34, '2_z_text', 1);

--
-- Despejando dados para a tabela `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'Vendor\\Models\\Customers', 1, 1752464665, '949a0b5424d624062f8153f720eacb794598c79ba6fdc98f6b2e7ed7c8e16cfe', '["*"]', '2025-07-14 02:55:23', NULL, '2025-07-14 00:44:25', '2025-07-14 02:55:23'),
(2, 'Vendor\\Models\\Admin\\Users_Admin', 1, 1752464680, '79caeac86318f49ebed15b83cc020420853b8899f208028e44c70ef955c11f74', '["*"]', '2025-07-14 02:54:45', NULL, '2025-07-14 00:44:40', '2025-07-14 02:54:45'),
(3, 'Vendor\\Models\\Admin\\Users_Admin', 1, 1753414981, '4550088d4c040814ef14d4ca66c3a72cedbed00412a96d48ea69a31e2b96eb4d', '["*"]', '2025-07-25 00:43:01', NULL, '2025-07-25 00:43:01', '2025-07-25 00:43:01'),
(4, 'Vendor\\Models\\Admin\\Users_Admin', 1, 1754523580, '512347c681c0c1da2b1f18deb246d884ca7b48bbdd6ecf10fa6765249f45d182', '["*"]', '2025-08-06 21:25:09', NULL, '2025-08-06 20:39:40', '2025-08-06 21:25:09'),
(9, 'Vendor\\Models\\Admin\\Users_Admin', 1, 1754677786, '5527c0e3ae98bf9d93d788df4f842fe06da6cb1c7bf9b3bea75c70e3a5bc6d72', '["*"]', '2025-08-09 18:46:55', NULL, '2025-08-08 15:29:46', '2025-08-09 18:46:55'),
(10, 'Vendor\\Models\\Admin\\Users_Admin', 1, 1757385592, 'be3704ab9d8227a4f243eded3296dd21b224459a284467fed8c364d707b4e94e', '["*"]', '2025-09-09 00:34:24', NULL, '2025-09-08 23:39:52', '2025-09-09 00:34:24'),
(11, 'Vendor\\Models\\Customers', 1, 1757385845, '961a94380c5d0c87b758a5c436f60221e5c076eef7a5523a373610ffe65bef19', '["*"]', '2025-09-08 23:54:35', NULL, '2025-09-08 23:44:05', '2025-09-08 23:54:35'),
(12, 'Vendor\\Models\\Customers', 1, 1757386493, '59b0daeaee71390f3712418d9bd41b31d01090a20183a37805909cd5365019b8', '["*"]', '2025-09-08 23:58:13', NULL, '2025-09-08 23:54:53', '2025-09-08 23:58:13'),
(13, 'Vendor\\Models\\Customers', 3, 1757386908, '43f47fbda8493473aad7db4b86592417a3858757962529950c271a0125ed0f02', '["*"]', '2025-09-09 00:02:19', NULL, '2025-09-09 00:01:48', '2025-09-09 00:02:19'),
(14, 'Vendor\\Models\\Customers', 1, 1757386950, '309880ccdbbd3f51ceb9f02e08de12db9b51ffd6b0e0623e99ac824451ebb8ba', '["*"]', '2025-09-09 00:11:00', NULL, '2025-09-09 00:02:30', '2025-09-09 00:11:00'),
(15, 'Vendor\\Models\\Customers', 1, 1757387467, '8d26cd88557eee62890db4c98d17a7afa397713f89fea910142573764bab1990', '["*"]', '2025-09-09 00:14:40', NULL, '2025-09-09 00:11:07', '2025-09-09 00:14:40'),
(16, 'Vendor\\Models\\Customers', 1, 1757387769, '5ddb91db621edb7c2f50e6d785c8ea33211b41cd58d6c001a81823ea2bb6635a', '["*"]', '2025-09-09 00:34:22', NULL, '2025-09-09 00:16:09', '2025-09-09 00:34:22');

--
-- Despejando dados para a tabela `texts`
--

INSERT INTO `texts` (`id`, `active`, `name_main`, `type`, `name`, `image`, `place`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Termos de Uso', 'text', 'Termos de Uso', NULL, 2, 999, NULL, NULL),
(2, 1, 'Politica de Privacidade', 'text', 'Politica de Privacidade', NULL, 2, 999, NULL, NULL),
(3, 1, 'Politica de Cookies', 'text', 'Politica de Cookies', NULL, 0, 999, NULL, NULL),
(4, 0, 'Quem Somos', 'text', 'Quem Somos', NULL, 1, 999, NULL, NULL);

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `active`, `name`, `email`, `phone`, `users`, `permissions`, `permissions_all`, `password`, `remember_token`, `verified_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Default', 'p@1', NULL, NULL, '', 1, '$2y$10$gB9skygnSrvo9ZYehKJwCuAIdJTIMJ3AEKINgISns8OaL7hbD2h5a', NULL, NULL, NULL, '2025-09-08 23:39:52'),
(2, 1, 'Administrador', 'admin@admin', '', NULL, '', 1, '$2y$10$WsBLHhbq3AMrnOpDMlVgTOUnr9gbZY4IEwbN9zHAUY5yXh10n3iBW', NULL, NULL, NULL, '2025-08-08 15:25:12');

--
-- Despejando dados para a tabela `x_settings`
--

INSERT INTO `x_settings` (`id`, `fields`, `value`, `created_at`, `updated_at`) VALUES
(1, 'version', 1, NULL, NULL),
(2, 'name_site', 'Site', NULL, '2025-03-25 06:14:53'),
(3, 'meta_title', 'Site', NULL, '2025-03-25 06:14:53'),
(4, 'meta_keywords', 'Site', NULL, '2025-03-25 06:14:53'),
(5, 'meta_description', 'Site', NULL, '2025-03-25 06:14:53'),
(6, 'image_favicon', '[]', NULL, '2025-03-25 06:14:54'),
(7, 'image_sharing', '[]', NULL, '2025-03-25 06:14:54'),
(8, 'image_sharing_mime', '', NULL, '2025-03-25 06:14:54'),
(9, 'image_sharing_width', '', NULL, '2025-03-25 06:14:54'),
(10, 'image_sharing_height', '', NULL, '2025-03-25 06:14:54'),
(11, 'thumbs_jpg_to_webp', 1, NULL, NULL),
(12, 'thumbs_jpg_to_jpg', 0, NULL, NULL),
(13, 'thumbs_png_to_web', 1, NULL, NULL),
(14, 'thumbs_png_to_jpg', 0, NULL, NULL),
(15, 'thumbs_webp_to_webp', 0, NULL, NULL),
(16, 'script', '', NULL, '2025-04-08 19:34:00'),
(17, 'email', 'teste@hotmail.com', NULL, NULL),
(18, 'cnpj', '', NULL, NULL),
(19, 'address', 'Rua Z', NULL, NULL),
(20, 'opening_hours', '', NULL, NULL),
(21, 'email_1', 'teste@hotmail.com', NULL, NULL),
(22, 'phone', '', NULL, NULL),
(23, 'whatsapp', '', NULL, NULL),
(24, 'whatsapp_code', 55, NULL, NULL),
(25, 'whatsapp_text', '', NULL, NULL),
(26, 'key_google', '', NULL, NULL),
(27, 'key_captcha', '', NULL, NULL),
(28, 'shippings_correios', 0, '2025-03-01 07:59:39', '2025-04-16 03:58:03'),
(29, 'shippings_correios_code_acess', '', '2025-03-01 07:59:39', '2025-04-16 03:58:03'),
(30, 'shippings_correios_usuario', '', '2025-03-03 14:05:49', '2025-04-16 03:58:03'),
(31, 'shippings_correios_contrato', '', '2025-03-03 14:05:49', '2025-04-16 03:58:03'),
(32, 'shippings_correios_dr', '', '2025-03-03 14:05:49', '2025-04-16 03:58:03'),
(33, 'shippings_correios_token', '', NULL, '2025-04-01 08:28:45'),
(34, 'shippings_correios_token_expiration', '', NULL, '2025-04-01 08:28:45'),
(35, 'shippings_correios_cartao_postagem', '', NULL, '2025-04-16 03:58:03'),
(36, 'shippings_melhor_envio', 0, NULL, '2025-04-16 03:58:03'),
(37, 'shippings_local', '', NULL, NULL),
(38, 'shippings_raio', '', NULL, NULL),
(39, 'shippings_moto_boy', '', NULL, NULL),
(40, 'shippings_retirar_na_loja', 0, NULL, '2025-04-16 03:58:03'),
(41, 'shippings_price_moto_boy', '', NULL, NULL),
(42, 'shippings_melhor_envio_token', '', NULL, '2025-04-16 03:58:03'),
(43, 'shippings_raio_lat', '', NULL, NULL),
(44, 'shippings_raio_lng', '', NULL, NULL),
(45, 'shippings_zipcode', '13.092-110', NULL, '2025-04-16 03:58:03'),
(46, 'pay_card_credit_1', 'MercadoPago', NULL, '2025-03-22 07:01:56'),
(47, 'pay_card_credit_2', 'AppMax', NULL, '2025-03-22 07:01:56'),
(48, 'pay_card_credit_3', 0, NULL, '2025-02-18 17:50:57'),
(49, 'pay_boleto_1', 'MercadoPago', NULL, '2025-03-22 07:01:56'),
(50, 'pay_boleto_2', '', NULL, NULL),
(51, 'pay_boleto_3', '', NULL, NULL),
(52, 'pay_pix_1', 'MercadoPago', NULL, '2025-03-22 07:01:56'),
(53, 'pay_pix_2', '', NULL, NULL),
(54, 'pay_pix_3', '', NULL, NULL),
(55, 'pay_boleto_days_expire', 3, NULL, '2025-03-22 07:01:56'),
(56, 'pay_card_credit_installments_max', 12, NULL, '2025-03-22 07:01:56'),
(57, 'pay_card_credit_installments_fees', 6, NULL, '2025-03-22 07:01:56'),
(58, 'pay_card_credit_installments_interest_perc', 1.00, NULL, '2025-03-22 07:01:56'),
(59, 'pay_rates_card_credit', 0.00, NULL, '2025-03-22 07:01:56'),
(60, 'pay_rates_boleto', 0.00, NULL, '2025-03-22 07:01:56'),
(61, 'pay_rates_pix', 0.00, NULL, '2025-03-22 07:01:56'),
(62, 'pay_discount_card_credit', 0.00, NULL, '2025-03-22 07:01:56'),
(63, 'pay__discount_boleto', '', NULL, NULL),
(64, 'pay__discount_pix', '', NULL, NULL),
(65, 'pay_discount_boleto', 0.00, '2024-12-14 17:44:58', '2025-03-22 07:01:56'),
(66, 'pay_discount_pix', 0.00, '2024-12-14 17:44:58', '2025-03-22 07:01:56'),
(67, 'pay_pagarme_client_id', '', NULL, '2025-02-18 17:52:51'),
(68, 'pay_pagarme_client_secret', '', NULL, '2025-02-18 17:52:51'),
(69, 'pay_pagseguro_token', '', NULL, NULL),
(70, 'pay_pagseguro_environment', '', NULL, NULL),
(71, 'pay_pagseguro_public_key', '', NULL, NULL),
(72, 'pay_mercado_pago_public_key', '', NULL, '2025-03-22 07:01:56'),
(73, 'pay_mercado_pago_access_token', '', NULL, '2025-03-22 07:01:56'),
(74, 'pay_appmax_token', '', NULL, '2025-03-22 07:01:56'),
(75, 'smtp_active', 0, NULL, '2025-03-27 04:31:32'),
(76, 'smtp_ssl', 0, NULL, '2025-03-27 04:31:32'),
(77, 'smtp_smtp', '', NULL, '2025-03-27 04:31:32'),
(78, 'smtp_email', '', NULL, '2025-03-27 04:31:32'),
(79, 'smtp_password', '', NULL, '2025-03-27 04:31:32'),
(80, 'google_maps_zoom', 18, NULL, NULL),
(81, 'google_maps_address', 'Av. Brasil 100 Bh Mg', NULL, NULL),
(82, 'google_maps_lat', -19.9238381, NULL, NULL),
(83, 'google_maps_lng', -43.9214651, NULL, NULL),
(84, 'pg_login_name', 'Bem vindo <br>ao Tab info', '2025-09-08 23:42:11', '2025-09-08 23:42:11'),
(85, 'pg_login_description', 'lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut laborelorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut laborelorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut laborelorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore', '2025-09-08 23:42:11', '2025-09-08 23:42:11'),
(86, 'campanha_description', '', '2025-09-08 23:42:11', '2025-09-08 23:42:11');

--
-- Despejando dados para a tabela `y_menu_admin`
--

INSERT INTO `y_menu_admin` (`id`, `active`, `table__`, `categories`, `name`, `icon`, `type`, `type_items`, `filter`, `orderby`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'YMenuAdmin', 100, 'Menu Admin', NULL, 0, NULL, NULL, NULL, 999, NULL, NULL),
(2, 0, 'YMenuAdmin', 5000, 'Menu Admin', NULL, 0, NULL, '$query->where(\'active\', 1)->where(\'id\', \'!=\', 1)->where(\'id\', \'!=\', 2);', 'order ASC,', 5000, NULL, '2025-02-18 09:19:38'),
(6, 1, 'Users', 1, 'Administradores', NULL, 0, NULL, '$query->where(\'users.id\', \'!=\', 1);', NULL, 1, NULL, '2025-04-24 15:27:26'),
(11, 1, 'XSettings', 2500, 'Informações', 'faa-gear', 1, NULL, NULL, NULL, 4070, NULL, '2025-02-19 10:11:38'),
(12, 0, 'XSettings', 2500, 'Informações Padrões', 'faa-gear', 1, NULL, NULL, NULL, 4000, NULL, '2025-01-14 04:46:09'),
(13, 0, 'XSettings', 2500, 'Informações Frete', 'faa-truck', 1, NULL, NULL, NULL, 4010, NULL, '2025-09-08 23:48:50'),
(14, 1, 'XSettings', 2500, 'Emails', 'faa-envelope', 1, NULL, NULL, NULL, 4060, NULL, '2025-01-23 19:00:42'),
(15, 0, 'Texts', 1500, 'Emails Personalizados', 'faa-envelope|faa-envelope-o', 0, 'emails', '$query->where(\'texts.active\', 1);', 'order ASC, id ASC, name_main DESC,', 2800, NULL, '2025-09-08 23:50:36'),
(16, 0, 'Logs', 2000, 'Formúlarios', 'faa-table', 0, NULL, NULL, NULL, 3000, NULL, '2025-01-14 04:46:09'),
(17, 1, 'XSettings', 2500, 'Config. do Site', 'faa-gear', 1, NULL, NULL, NULL, 4030, NULL, '2025-09-08 23:48:54'),
(18, 1, 'XSettings', 2500, 'Script', 'faa-code', 1, NULL, NULL, NULL, 4040, NULL, '2025-01-15 01:46:09'),
(19, 0, 'XSettings', 2500, 'Localização', 'faa-globe', 1, NULL, NULL, NULL, 4050, NULL, '2025-01-14 04:46:09'),
(26, 0, 'Logs', 2000, 'Login Clientes', NULL, 0, NULL, NULL, NULL, 3010, NULL, '2025-01-14 07:46:09'),
(27, 0, 'Logs', 3000, 'Login Admin', NULL, 0, NULL, NULL, NULL, 3015, NULL, '2025-02-18 09:19:38'),
(28, 0, 'Logs', 2000, 'Ações', NULL, 0, NULL, NULL, NULL, 3040, NULL, '2025-01-14 04:46:09'),
(31, 0, 'Customers', 200, 'Cadastro ALL', NULL, 0, 'customers', NULL, NULL, 200, NULL, '2025-09-08 23:49:44'),
(32, 0, 'CustomersAddress', 200, 'Endereços', NULL, 0, NULL, NULL, NULL, 250, NULL, '2025-01-31 07:25:20'),
(41, 0, 'Products', 500, 'Produtos', 'faa-suitcase', 0, NULL, NULL, NULL, 500, NULL, '2025-09-08 23:50:33'),
(42, 0, 'ProductsCategories', 500, 'Produtos (Categorias)', 'faa-sitemap', 0, NULL, '$query->where(\'type\', 0);', NULL, 501, NULL, '2025-09-08 23:50:34'),
(43, 0, 'ProductsCategories', 500, 'Produtos (Subcategorias)', 'faa-sitemap', 0, NULL, '$query->where(\'type\', \'!=\', 0);', NULL, 502, NULL, '2025-01-14 04:46:09'),
(44, 0, 'ProductsBrands', 500, 'Marcas', NULL, 0, NULL, NULL, NULL, 510, NULL, '2025-09-08 23:50:34'),
(53, 0, 'Shippings', 3500, 'Frete por Local----', NULL, 0, NULL, NULL, NULL, 3500, NULL, '2025-02-18 09:19:38'),
(54, 0, 'Shippings', 3500, 'Frete por Raio----', NULL, 0, NULL, NULL, NULL, 3510, NULL, '2025-02-18 09:19:39'),
(61, 0, 'Orders', 400, 'Pedidos', NULL, 0, NULL, NULL, 'id DESC,', 400, NULL, '2025-09-08 23:50:32'),
(62, 0, 'OrdersStatus', 400, 'Pedidos (Status)', NULL, 0, NULL, NULL, 'order ASC', 402, NULL, '2025-09-08 23:50:33'),
(63, 0, 'OrdersEvents', 2500, 'Cupons', 'faa-ticket', 1, 'coupons', NULL, NULL, 405, NULL, '2025-01-14 22:46:09'),
(67, 0, 'XSettings', 4000, 'Pagamentos', 'faa-gear', 1, NULL, NULL, NULL, 4020, NULL, '2025-09-08 23:48:51'),
(72, 0, 'products', 1500, 'Produtos mais Comprados----', NULL, 0, NULL, NULL, NULL, 1500, NULL, '2025-02-18 09:19:39'),
(73, 0, 'products', 1500, 'Produtos mais Visualizados----', NULL, 0, NULL, NULL, NULL, 1501, NULL, '2025-02-18 09:19:39'),
(81, 0, 'Items', 800, 'Banners', 'faa-tv (alias)', 0, 'banners', NULL, NULL, 1000, NULL, '2025-09-08 23:50:35'),
(82, 0, 'Items', 800, 'Blog', 'faa-newspaper-o', 0, 'blog', NULL, NULL, 1030, NULL, '2025-01-14 04:46:09'),
(83, 0, 'ItemsCategories', 800, 'Blog (Categorias)', 'faa-sitemap', 0, 'blog', NULL, NULL, 1031, NULL, '2025-01-14 04:46:09'),
(85, 0, 'Items', 800, 'Rede Social', NULL, 0, 'social_network', NULL, NULL, 1050, NULL, '2025-01-14 07:46:09'),
(86, 0, 'Items', 800, 'Faq', 'faa-external-link-square', 0, 'faq', NULL, NULL, 1040, NULL, '2025-01-14 04:46:09'),
(87, 1, 'XSettings', 2000, 'Textos / Imagens', 'faa-file-text|faa-file-text-o', 1, NULL, '$query->where(\'id\', 11);', NULL, 2010, NULL, '2025-02-17 19:27:25'),
(88, 0, 'Texts', 1500, 'Paginas', 'faa-file-text|faa-file-text-o', 0, 'text', '$query->where(\'texts.active\', 1);', NULL, 1710, NULL, '2025-09-08 23:50:36'),
(89, 0, 'Items', 800, 'Assuntos (Pag. Contato)', 'faa-envelope-o', 0, 'contact', NULL, NULL, 1070, NULL, '2025-01-14 04:46:09'),
(90, 0, 'Newsletter', 800, 'Newsletter', 'faa-newspaper-o', 0, NULL, NULL, NULL, 1060, NULL, '2025-01-14 04:46:09'),
(91, 0, 'Items', 800, 'Chamada Home', NULL, 0, 'home_call', NULL, NULL, 1020, NULL, '2025-01-14 04:46:09'),
(1001, 1, 'Customers', 200, 'Cadastro', 'faa-user', 2, 'customers', '$query->where(\'id\', \'!=\', $request->user()->id);', 'created_at DESC,', 200, '2025-09-08 23:49:42', '2025-03-19 04:51:19'),
(1002, 1, 'Customers', 200, 'Cadastro', 'faa-user', 0, 'customers', NULL, 'created_at DESC,', 200, '2025-09-08 23:49:42', '2025-03-19 04:51:19'),
(1003, 0, 'CustomersAddress', 200, 'Endereços', NULL, 2, NULL, '$query->where(\'customers\', $request->user()->id);', NULL, 250, '2025-09-09 00:09:50', '2025-03-23 19:08:42');

--
-- Despejando dados para a tabela `y_menu_admin_categories`
--

INSERT INTO `y_menu_admin_categories` (`id`, `active`, `name`, `title`, `created_at`, `updated_at`) VALUES
(1, 1, '0001 - Administradores', '', NULL, NULL),
(100, 1, 0100, '', NULL, NULL),
(200, 1, '0200 - Cadastro', '', NULL, NULL),
(300, 1, 0300, '', NULL, NULL),
(400, 1, '0400 - Pedidos', '', NULL, NULL),
(500, 1, '0500 - Produtos', '', NULL, NULL),
(600, 1, 0600, '', NULL, NULL),
(700, 1, 0700, '', NULL, NULL),
(800, 1, '0800 - Geral', '', NULL, NULL),
(900, 1, 0900, '', NULL, NULL),
(1000, 1, '1000 - Site', '', NULL, NULL),
(1100, 1, 1100, '', NULL, NULL),
(1200, 1, 1200, '', NULL, NULL),
(1300, 1, 1300, '', NULL, NULL),
(1400, 1, 1400, '', NULL, NULL),
(1500, 1, '1500 - Estatísticas', '', NULL, NULL),
(1600, 1, 1600, '', NULL, NULL),
(1700, 1, '1700 - Páginas', '', NULL, NULL),
(1800, 1, 1800, '', NULL, NULL),
(1900, 1, 1900, '', NULL, NULL),
(2000, 1, '2000 - Textos', '', NULL, NULL),
(2100, 1, 2100, '', NULL, NULL),
(2200, 1, 2200, '', NULL, NULL),
(2300, 1, 2300, '', NULL, NULL),
(2400, 1, 2400, '', NULL, NULL),
(2500, 1, '2500 - ', '', NULL, NULL),
(2600, 1, 2600, '', NULL, NULL),
(2700, 1, 2700, '', NULL, NULL),
(2800, 1, '2800 - Emails', '', NULL, NULL),
(2900, 1, 2900, '', NULL, NULL),
(3000, 1, '3000 - Logs', '', NULL, NULL),
(3100, 1, 3100, '', NULL, NULL),
(3200, 1, 3200, '', NULL, NULL),
(3300, 1, 3300, '', NULL, NULL),
(3400, 1, 3400, '', NULL, NULL),
(3500, 1, '3500 - Fretes', '', NULL, NULL),
(3600, 1, 3600, '', NULL, NULL),
(3700, 1, 3700, '', NULL, NULL),
(3800, 1, 3800, '', NULL, NULL),
(3900, 1, 3900, '', NULL, NULL),
(4000, 1, '4000 - Configurações', 'Configurações', NULL, NULL),
(4100, 1, 4100, '', NULL, NULL),
(4200, 1, 4200, '', NULL, NULL),
(4300, 1, 4300, '', NULL, NULL),
(4400, 1, 4400, '', NULL, NULL),
(4500, 1, 4500, '', NULL, NULL),
(4600, 1, 4600, '', NULL, NULL),
(4700, 1, 4700, '', NULL, NULL),
(4800, 1, 4800, '', NULL, NULL),
(4900, 1, 4900, '', NULL, NULL),
(5000, 1, '5000 - Menu Admin', '', NULL, NULL);

--
-- Despejando dados para a tabela `z_rel`
--

INSERT INTO `z_rel` (`id`, `table__`, `id__`, `fields`, `value`, `created_at`, `updated_at`) VALUES
(1, 'Vendor\\Models\\Items', 1, 'link', '', '2025-05-08 01:34:02', '2025-07-14 01:59:55'),
(2, 'Vendor\\Models\\Items', 2, 'link', '', '2025-05-16 11:45:33', '2025-05-16 11:53:02');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Índices de tabela `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Índices de tabela `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `customers__customers__foreign` (`customers`);

--
-- Índices de tabela `customers_address`
--
ALTER TABLE `customers_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_address__customers__foreign` (`customers`);

--
-- Índices de tabela `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items__items_categories__foreign` (`categories`),
  ADD KEY `idx_type` (`type`);

--
-- Índices de tabela `items_categories`
--
ALTER TABLE `items_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_categories__subcategories__foreign` (`subcategories`);

--
-- Índices de tabela `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Índices de tabela `texts`
--
ALTER TABLE `texts`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Índices de tabela `webhooks`
--
ALTER TABLE `webhooks`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `x_settings`
--
ALTER TABLE `x_settings`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `y_menu_admin`
--
ALTER TABLE `y_menu_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `y_menu_admin__y_menu_admin_categories__foreign` (`categories`);

--
-- Índices de tabela `y_menu_admin_categories`
--
ALTER TABLE `y_menu_admin_categories`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `y_menu_admin_columns`
--
ALTER TABLE `y_menu_admin_columns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `y_menu_admin_columns__y_menu_admin__foreign` (`y_menu_admin`),
  ADD KEY `y_menu_admin__columns_users__foreign` (`users`);

--
-- Índices de tabela `z_rel`
--
ALTER TABLE `z_rel`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `z_text`
--
ALTER TABLE `z_text`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `customers`
--
ALTER TABLE `customers`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `customers_address`
--
ALTER TABLE `customers_address`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de tabela `items`
--
ALTER TABLE `items`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de tabela `items_categories`
--
ALTER TABLE `items_categories`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `texts`
--
ALTER TABLE `texts`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `webhooks`
--
ALTER TABLE `webhooks`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de tabela `x_settings`
--
ALTER TABLE `x_settings`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT de tabela `y_menu_admin`
--
ALTER TABLE `y_menu_admin`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;

--
-- AUTO_INCREMENT de tabela `y_menu_admin_categories`
--
ALTER TABLE `y_menu_admin_categories`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5001;

--
-- AUTO_INCREMENT de tabela `y_menu_admin_columns`
--
ALTER TABLE `y_menu_admin_columns`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de tabela `z_rel`
--
ALTER TABLE `z_rel`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `z_text`
--
ALTER TABLE `z_text`
  MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers__customers__foreign` FOREIGN KEY (`customers`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
--
-- Restrições para tabelas `customers_address`
--
ALTER TABLE `customers_address`
  ADD CONSTRAINT `customers_address__customers__foreign` FOREIGN KEY (`customers`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
--
-- Restrições para tabelas `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items__items_categories__foreign` FOREIGN KEY (`categories`) REFERENCES `items_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
--
-- Restrições para tabelas `items_categories`
--
ALTER TABLE `items_categories`
  ADD CONSTRAINT `items_categories__subcategories__foreign` FOREIGN KEY (`subcategories`) REFERENCES `items_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
