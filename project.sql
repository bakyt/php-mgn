-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Мар 27 2018 г., 12:38
-- Версия сервера: 5.7.21-0ubuntu0.16.04.1
-- Версия PHP: 7.2.3-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `project`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('ijarakg_cacheuser-is-online-1', 'b:1;', 1522124842),
('ijarakg_cacheuser-is-online-2', 'b:1;', 1521642025);

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `order`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 'Category 1', 'category-1', '2018-03-21 00:06:34', '2018-03-21 00:06:34'),
(2, NULL, 1, 'Category 2', 'category-2', '2018-03-21 00:06:34', '2018-03-21 00:06:34');

-- --------------------------------------------------------

--
-- Структура таблицы `data_rows`
--

CREATE TABLE `data_rows` (
  `id` int(10) UNSIGNED NOT NULL,
  `data_type_id` int(10) UNSIGNED NOT NULL,
  `field` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `browse` tinyint(1) NOT NULL DEFAULT '1',
  `read` tinyint(1) NOT NULL DEFAULT '1',
  `edit` tinyint(1) NOT NULL DEFAULT '1',
  `add` tinyint(1) NOT NULL DEFAULT '1',
  `delete` tinyint(1) NOT NULL DEFAULT '1',
  `details` text COLLATE utf8mb4_unicode_ci,
  `order` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `data_rows`
--

INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES
(1, 1, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, '', 1),
(2, 1, 'author_id', 'text', 'Author', 1, 0, 1, 1, 0, 1, '', 2),
(3, 1, 'category_id', 'text', 'Category', 1, 0, 1, 1, 1, 0, '', 3),
(4, 1, 'title', 'text', 'Title', 1, 1, 1, 1, 1, 1, '', 4),
(5, 1, 'excerpt', 'text_area', 'excerpt', 1, 0, 1, 1, 1, 1, '', 5),
(6, 1, 'body', 'rich_text_box', 'Body', 1, 0, 1, 1, 1, 1, '', 6),
(7, 1, 'image', 'image', 'Post Image', 0, 1, 1, 1, 1, 1, '{\"resize\":{\"width\":\"1000\",\"height\":\"null\"},\"quality\":\"70%\",\"upsize\":true,\"thumbnails\":[{\"name\":\"medium\",\"scale\":\"50%\"},{\"name\":\"small\",\"scale\":\"25%\"},{\"name\":\"cropped\",\"crop\":{\"width\":\"300\",\"height\":\"250\"}}]}', 7),
(8, 1, 'slug', 'text', 'slug', 1, 0, 1, 1, 1, 1, '{\"slugify\":{\"origin\":\"title\",\"forceUpdate\":true}}', 8),
(9, 1, 'meta_description', 'text_area', 'meta_description', 1, 0, 1, 1, 1, 1, '', 9),
(10, 1, 'meta_keywords', 'text_area', 'meta_keywords', 1, 0, 1, 1, 1, 1, '', 10),
(11, 1, 'status', 'select_dropdown', 'status', 1, 1, 1, 1, 1, 1, '{\"default\":\"DRAFT\",\"options\":{\"PUBLISHED\":\"published\",\"DRAFT\":\"draft\",\"PENDING\":\"pending\"}}', 11),
(12, 1, 'created_at', 'timestamp', 'created_at', 0, 1, 1, 0, 0, 0, '', 12),
(13, 1, 'updated_at', 'timestamp', 'updated_at', 0, 0, 0, 0, 0, 0, '', 13),
(14, 2, 'id', 'number', 'id', 1, 0, 0, 0, 0, 0, '', 1),
(15, 2, 'author_id', 'text', 'author_id', 1, 0, 0, 0, 0, 0, '', 2),
(16, 2, 'title', 'text', 'title', 1, 1, 1, 1, 1, 1, '', 3),
(17, 2, 'excerpt', 'text_area', 'excerpt', 1, 0, 1, 1, 1, 1, '', 4),
(18, 2, 'body', 'rich_text_box', 'body', 1, 0, 1, 1, 1, 1, '', 5),
(19, 2, 'slug', 'text', 'slug', 1, 0, 1, 1, 1, 1, '{\"slugify\":{\"origin\":\"title\"}}', 6),
(20, 2, 'meta_description', 'text', 'meta_description', 1, 0, 1, 1, 1, 1, '', 7),
(21, 2, 'meta_keywords', 'text', 'meta_keywords', 1, 0, 1, 1, 1, 1, '', 8),
(22, 2, 'status', 'select_dropdown', 'status', 1, 1, 1, 1, 1, 1, '{\"default\":\"INACTIVE\",\"options\":{\"INACTIVE\":\"INACTIVE\",\"ACTIVE\":\"ACTIVE\"}}', 9),
(23, 2, 'created_at', 'timestamp', 'created_at', 1, 1, 1, 0, 0, 0, '', 10),
(24, 2, 'updated_at', 'timestamp', 'updated_at', 1, 0, 0, 0, 0, 0, '', 11),
(25, 2, 'image', 'image', 'image', 0, 1, 1, 1, 1, 1, '', 12),
(26, 3, 'id', 'number', 'id', 1, 0, 0, 0, 0, 0, NULL, 1),
(27, 3, 'name', 'text', 'name', 1, 1, 1, 1, 1, 1, NULL, 2),
(29, 3, 'password', 'password', 'password', 1, 0, 0, 1, 1, 0, NULL, 4),
(30, 3, 'user_belongsto_role_relationship', 'relationship', 'Role', 0, 1, 1, 1, 1, 0, '{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsTo\",\"column\":\"role_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"roles\",\"pivot\":\"0\"}', 10),
(31, 3, 'remember_token', 'text', 'remember_token', 0, 0, 0, 0, 0, 0, NULL, 5),
(32, 3, 'created_at', 'timestamp', 'created_at', 0, 1, 1, 0, 0, 0, NULL, 6),
(33, 3, 'updated_at', 'timestamp', 'updated_at', 0, 0, 0, 0, 0, 0, NULL, 7),
(34, 3, 'avatar', 'image', 'avatar', 0, 1, 1, 1, 1, 1, NULL, 8),
(35, 5, 'id', 'number', 'id', 1, 0, 0, 0, 0, 0, '', 1),
(36, 5, 'name', 'text', 'name', 1, 1, 1, 1, 1, 1, '', 2),
(37, 5, 'created_at', 'timestamp', 'created_at', 0, 0, 0, 0, 0, 0, '', 3),
(38, 5, 'updated_at', 'timestamp', 'updated_at', 0, 0, 0, 0, 0, 0, '', 4),
(39, 4, 'id', 'number', 'id', 1, 0, 0, 0, 0, 0, '', 1),
(40, 4, 'parent_id', 'select_dropdown', 'parent_id', 0, 0, 1, 1, 1, 1, '{\"default\":\"\",\"null\":\"\",\"options\":{\"\":\"-- None --\"},\"relationship\":{\"key\":\"id\",\"label\":\"name\"}}', 2),
(41, 4, 'order', 'text', 'order', 1, 1, 1, 1, 1, 1, '{\"default\":1}', 3),
(42, 4, 'name', 'text', 'name', 1, 1, 1, 1, 1, 1, '', 4),
(43, 4, 'slug', 'text', 'slug', 1, 1, 1, 1, 1, 1, '{\"slugify\":{\"origin\":\"name\"}}', 5),
(44, 4, 'created_at', 'timestamp', 'created_at', 0, 0, 1, 0, 0, 0, '', 6),
(45, 4, 'updated_at', 'timestamp', 'updated_at', 0, 0, 0, 0, 0, 0, '', 7),
(46, 6, 'id', 'number', 'id', 1, 0, 0, 0, 0, 0, '', 1),
(47, 6, 'name', 'text', 'Name', 1, 1, 1, 1, 1, 1, '', 2),
(48, 6, 'created_at', 'timestamp', 'created_at', 0, 0, 0, 0, 0, 0, '', 3),
(49, 6, 'updated_at', 'timestamp', 'updated_at', 0, 0, 0, 0, 0, 0, '', 4),
(50, 6, 'display_name', 'text', 'Display Name', 1, 1, 1, 1, 1, 1, '', 5),
(51, 1, 'seo_title', 'text', 'seo_title', 0, 1, 1, 1, 1, 1, '', 14),
(52, 1, 'featured', 'checkbox', 'featured', 1, 1, 1, 1, 1, 1, '', 15),
(53, 3, 'role_id', 'text', 'role_id', 0, 1, 1, 1, 1, 1, NULL, 9),
(54, 3, 'username', 'text', 'Username', 1, 1, 1, 1, 1, 1, NULL, 4),
(55, 3, 'phone_number', 'text', 'Phone Number', 0, 1, 1, 1, 1, 1, NULL, 5),
(56, 7, 'id', 'checkbox', 'Id', 1, 0, 0, 0, 0, 0, NULL, 1),
(57, 7, 'parent_id', 'select_dropdown', 'Parent Id', 0, 1, 1, 1, 1, 1, NULL, 2),
(58, 7, 'order', 'number', 'Order', 1, 1, 1, 1, 1, 1, NULL, 4),
(59, 7, 'name', 'text', 'Name', 1, 1, 1, 1, 1, 1, NULL, 5),
(60, 7, 'image', 'image', 'Image', 0, 1, 1, 1, 1, 1, NULL, 6),
(61, 7, 'extends', 'select_dropdown', 'Extends', 0, 1, 1, 1, 1, 1, NULL, 3),
(62, 7, 'slug', 'text', 'Slug', 1, 1, 1, 1, 1, 1, '{\"slugify\":{\"origin\":\"name\"}}', 7),
(63, 7, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, NULL, 8),
(64, 7, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, NULL, 9),
(65, 7, 'global_category_belongsto_global_category_relationship', 'relationship', 'global_categories', 0, 1, 1, 1, 1, 1, '{\"model\":\"App\\\\GlobalCategory\",\"table\":\"global_categories\",\"type\":\"belongsTo\",\"column\":\"parent_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"cache\",\"pivot\":\"0\"}', 10),
(66, 7, 'global_category_belongsto_rent_category_relationship', 'relationship', 'rent_categories', 0, 1, 1, 1, 1, 1, '{\"model\":\"App\\\\RentCategory\",\"table\":\"rent_categories\",\"type\":\"belongsTo\",\"column\":\"extends\",\"key\":\"id\",\"label\":\"id\",\"pivot_table\":\"cache\",\"pivot\":\"0\"}', 11);

-- --------------------------------------------------------

--
-- Структура таблицы `data_types`
--

CREATE TABLE `data_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_singular` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_plural` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `controller` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generate_permissions` tinyint(1) NOT NULL DEFAULT '0',
  `server_side` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `data_types`
--

INSERT INTO `data_types` (`id`, `name`, `slug`, `display_name_singular`, `display_name_plural`, `icon`, `model_name`, `policy_name`, `controller`, `description`, `generate_permissions`, `server_side`, `created_at`, `updated_at`) VALUES
(1, 'posts', 'posts', 'Post', 'Posts', 'voyager-news', 'TCG\\Voyager\\Models\\Post', 'TCG\\Voyager\\Policies\\PostPolicy', '', '', 1, 0, '2018-03-21 00:06:25', '2018-03-21 00:06:25'),
(2, 'pages', 'pages', 'Page', 'Pages', 'voyager-file-text', 'TCG\\Voyager\\Models\\Page', NULL, '', '', 1, 0, '2018-03-21 00:06:25', '2018-03-21 00:06:25'),
(3, 'users', 'users', 'User', 'Users', 'voyager-person', 'TCG\\Voyager\\Models\\User', 'TCG\\Voyager\\Policies\\UserPolicy', NULL, NULL, 1, 0, '2018-03-21 00:06:26', '2018-03-21 00:11:18'),
(4, 'categories', 'categories', 'Category', 'Categories', 'voyager-categories', 'TCG\\Voyager\\Models\\Category', NULL, '', '', 1, 0, '2018-03-21 00:06:26', '2018-03-21 00:06:26'),
(5, 'menus', 'menus', 'Menu', 'Menus', 'voyager-list', 'TCG\\Voyager\\Models\\Menu', NULL, '', '', 1, 0, '2018-03-21 00:06:26', '2018-03-21 00:06:26'),
(6, 'roles', 'roles', 'Role', 'Roles', 'voyager-lock', 'TCG\\Voyager\\Models\\Role', NULL, '', '', 1, 0, '2018-03-21 00:06:26', '2018-03-21 00:06:26'),
(7, 'global_categories', 'global-categories', 'Global Category', 'Global Categories', NULL, 'App\\GlobalCategory', NULL, NULL, NULL, 1, 0, '2018-03-21 00:30:52', '2018-03-21 00:30:52');

-- --------------------------------------------------------

--
-- Структура таблицы `global_categories`
--

CREATE TABLE `global_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extends` int(10) UNSIGNED DEFAULT NULL,
  `author_id` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `global_categories`
--

INSERT INTO `global_categories` (`id`, `parent_id`, `order`, `name`, `image`, `extends`, `author_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 'Недвижимость', 'global-categories/March2018/ld7mNotEW4J28p930iPa.jpg', NULL, 0, '2018-03-21 00:35:29', '2018-03-21 00:35:29'),
(2, NULL, 1, 'Авто', 'global-categories/March2018/1GSxQiAdxRrSVNDbQqLT.jpg', NULL, 0, '2018-03-21 00:36:53', '2018-03-21 00:36:53'),
(3, NULL, 1, 'Электроника', 'global-categories/March2018/rB9EHjfTewyJsyZa6ljd.jpg', NULL, 0, '2018-03-21 00:39:46', '2018-03-21 00:39:46'),
(4, 1, 1, 'Квартиры', 'image', 1, 1, '2018-03-21 00:40:33', '2018-03-26 10:12:34'),
(5, NULL, 1, 'Для постройки', 'global-categories/March2018/7u856QbqooDv76NFReNp.jpg', NULL, 0, '2018-03-21 00:43:25', '2018-03-21 00:43:25'),
(24, 2, 1, 'admin', 'image', 1, 1, '2018-03-23 04:02:08', '2018-03-24 07:48:57');

-- --------------------------------------------------------

--
-- Структура таблицы `menus`
--

CREATE TABLE `menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `menus`
--

INSERT INTO `menus` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2018-03-21 00:06:28', '2018-03-21 00:06:28');

-- --------------------------------------------------------

--
-- Структура таблицы `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `icon_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `menu_items`
--

INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES
(1, 1, 'Dashboard', '', '_self', 'voyager-boat', NULL, NULL, 1, '2018-03-21 00:06:29', '2018-03-21 00:06:29', 'voyager.dashboard', NULL),
(2, 1, 'Media', '', '_self', 'voyager-images', NULL, NULL, 5, '2018-03-21 00:06:29', '2018-03-21 00:06:29', 'voyager.media.index', NULL),
(3, 1, 'Posts', '', '_self', 'voyager-news', NULL, NULL, 6, '2018-03-21 00:06:29', '2018-03-21 00:06:29', 'voyager.posts.index', NULL),
(4, 1, 'Users', '', '_self', 'voyager-person', NULL, NULL, 3, '2018-03-21 00:06:29', '2018-03-21 00:06:29', 'voyager.users.index', NULL),
(5, 1, 'Categories', '', '_self', 'voyager-categories', NULL, NULL, 8, '2018-03-21 00:06:29', '2018-03-21 00:06:29', 'voyager.categories.index', NULL),
(6, 1, 'Pages', '', '_self', 'voyager-file-text', NULL, NULL, 7, '2018-03-21 00:06:29', '2018-03-21 00:06:29', 'voyager.pages.index', NULL),
(7, 1, 'Roles', '', '_self', 'voyager-lock', NULL, NULL, 2, '2018-03-21 00:06:29', '2018-03-21 00:06:29', 'voyager.roles.index', NULL),
(8, 1, 'Tools', '', '_self', 'voyager-tools', NULL, NULL, 9, '2018-03-21 00:06:29', '2018-03-21 00:06:29', NULL, NULL),
(9, 1, 'Menu Builder', '', '_self', 'voyager-list', NULL, 8, 10, '2018-03-21 00:06:29', '2018-03-21 00:06:29', 'voyager.menus.index', NULL),
(10, 1, 'Database', '', '_self', 'voyager-data', NULL, 8, 11, '2018-03-21 00:06:29', '2018-03-21 00:06:29', 'voyager.database.index', NULL),
(11, 1, 'Compass', '', '_self', 'voyager-compass', NULL, 8, 12, '2018-03-21 00:06:29', '2018-03-21 00:06:29', 'voyager.compass.index', NULL),
(12, 1, 'Settings', '', '_self', 'voyager-settings', NULL, NULL, 14, '2018-03-21 00:06:29', '2018-03-21 00:06:29', 'voyager.settings.index', NULL),
(13, 1, 'Hooks', '', '_self', 'voyager-hook', NULL, 8, 13, '2018-03-21 00:06:37', '2018-03-21 00:06:37', 'voyager.hooks', NULL),
(14, 1, 'Global Categories', '/admin/global-categories', '_self', NULL, NULL, NULL, 15, '2018-03-21 00:30:52', '2018-03-21 00:30:52', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(194, '2014_10_12_000000_create_users_table', 1),
(195, '2014_10_12_100000_create_password_resets_table', 1),
(196, '2016_01_01_000000_add_voyager_user_fields', 1),
(197, '2016_01_01_000000_create_data_types_table', 1),
(198, '2016_01_01_000000_create_pages_table', 1),
(199, '2016_01_01_000000_create_posts_table', 1),
(200, '2016_02_15_204651_create_categories_table', 1),
(201, '2016_05_19_173453_create_menu_table', 1),
(202, '2016_10_21_190000_create_roles_table', 1),
(203, '2016_10_21_190000_create_settings_table', 1),
(204, '2016_11_30_135954_create_permission_table', 1),
(205, '2016_11_30_141208_create_permission_role_table', 1),
(206, '2016_12_26_201236_data_types__add__server_side', 1),
(207, '2017_01_13_000000_add_route_to_menu_items_table', 1),
(208, '2017_01_14_005015_create_translations_table', 1),
(209, '2017_01_15_000000_add_permission_group_id_to_permissions_table', 1),
(210, '2017_01_15_000000_create_permission_groups_table', 1),
(211, '2017_01_15_000000_make_table_name_nullable_in_permissions_table', 1),
(212, '2017_03_06_000000_add_controller_to_data_types_table', 1),
(213, '2017_04_11_000000_alter_post_nullable_fields_table', 1),
(214, '2017_04_21_000000_add_order_to_data_rows_table', 1),
(215, '2017_07_05_210000_add_policyname_to_data_types_table', 1),
(216, '2017_08_05_000000_add_group_to_settings_table', 1),
(217, '2018_02_16_142011_create_rent_items_table', 1),
(218, '2018_02_16_142920_create_rent_categories_table', 1),
(219, '2018_02_16_142921_create_global_categories_table', 1),
(220, '2018_02_25_184007_create_cache_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `pages`
--

INSERT INTO `pages` (`id`, `author_id`, `title`, `excerpt`, `body`, `image`, `slug`, `meta_description`, `meta_keywords`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, 'Hello World', 'Hang the jib grog grog blossom grapple dance the hempen jig gangway pressgang bilge rat to go on account lugger. Nelsons folly gabion line draught scallywag fire ship gaff fluke fathom case shot. Sea Legs bilge rat sloop matey gabion long clothes run a shot across the bow Gold Road cog league.', '<p>Hello World. Scallywag grog swab Cat o\'nine tails scuttle rigging hardtack cable nipper Yellow Jack. Handsomely spirits knave lad killick landlubber or just lubber deadlights chantey pinnace crack Jennys tea cup. Provost long clothes black spot Yellow Jack bilged on her anchor league lateen sail case shot lee tackle.</p>\n<p>Ballast spirits fluke topmast me quarterdeck schooner landlubber or just lubber gabion belaying pin. Pinnace stern galleon starboard warp carouser to go on account dance the hempen jig jolly boat measured fer yer chains. Man-of-war fire in the hole nipperkin handsomely doubloon barkadeer Brethren of the Coast gibbet driver squiffy.</p>', 'pages/page1.jpg', 'hello-world', 'Yar Meta Description', 'Keyword1, Keyword2', 'ACTIVE', '2018-03-21 00:06:35', '2018-03-21 00:06:35');

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `permission_group_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `permissions`
--

INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`, `permission_group_id`) VALUES
(1, 'browse_admin', NULL, '2018-03-21 00:06:29', '2018-03-21 00:06:29', NULL),
(2, 'browse_database', NULL, '2018-03-21 00:06:29', '2018-03-21 00:06:29', NULL),
(3, 'browse_media', NULL, '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(4, 'browse_compass', NULL, '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(5, 'browse_menus', 'menus', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(6, 'read_menus', 'menus', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(7, 'edit_menus', 'menus', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(8, 'add_menus', 'menus', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(9, 'delete_menus', 'menus', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(10, 'browse_pages', 'pages', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(11, 'read_pages', 'pages', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(12, 'edit_pages', 'pages', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(13, 'add_pages', 'pages', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(14, 'delete_pages', 'pages', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(15, 'browse_roles', 'roles', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(16, 'read_roles', 'roles', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(17, 'edit_roles', 'roles', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(18, 'add_roles', 'roles', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(19, 'delete_roles', 'roles', '2018-03-21 00:06:30', '2018-03-21 00:06:30', NULL),
(20, 'browse_users', 'users', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(21, 'read_users', 'users', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(22, 'edit_users', 'users', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(23, 'add_users', 'users', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(24, 'delete_users', 'users', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(25, 'browse_posts', 'posts', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(26, 'read_posts', 'posts', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(27, 'edit_posts', 'posts', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(28, 'add_posts', 'posts', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(29, 'delete_posts', 'posts', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(30, 'browse_categories', 'categories', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(31, 'read_categories', 'categories', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(32, 'edit_categories', 'categories', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(33, 'add_categories', 'categories', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(34, 'delete_categories', 'categories', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(35, 'browse_settings', 'settings', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(36, 'read_settings', 'settings', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(37, 'edit_settings', 'settings', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(38, 'add_settings', 'settings', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(39, 'delete_settings', 'settings', '2018-03-21 00:06:31', '2018-03-21 00:06:31', NULL),
(40, 'browse_hooks', NULL, '2018-03-21 00:06:37', '2018-03-21 00:06:37', NULL),
(41, 'browse_global_categories', 'global_categories', '2018-03-21 00:30:52', '2018-03-21 00:30:52', NULL),
(42, 'read_global_categories', 'global_categories', '2018-03-21 00:30:52', '2018-03-21 00:30:52', NULL),
(43, 'edit_global_categories', 'global_categories', '2018-03-21 00:30:52', '2018-03-21 00:30:52', NULL),
(44, 'add_global_categories', 'global_categories', '2018-03-21 00:30:52', '2018-03-21 00:30:52', NULL),
(45, 'delete_global_categories', 'global_categories', '2018-03-21 00:30:52', '2018-03-21 00:30:52', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `permission_groups`
--

CREATE TABLE `permission_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `status` enum('PUBLISHED','DRAFT','PENDING') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`id`, `author_id`, `category_id`, `title`, `seo_title`, `excerpt`, `body`, `image`, `slug`, `meta_description`, `meta_keywords`, `status`, `featured`, `created_at`, `updated_at`) VALUES
(1, 0, NULL, 'Lorem Ipsum Post', NULL, 'This is the excerpt for the Lorem Ipsum Post', '<p>This is the body of the lorem ipsum post</p>', 'posts/post1.jpg', 'lorem-ipsum-post', 'This is the meta description', 'keyword1, keyword2, keyword3', 'PUBLISHED', 0, '2018-03-21 00:06:35', '2018-03-21 00:06:35'),
(2, 0, NULL, 'My Sample Post', NULL, 'This is the excerpt for the sample Post', '<p>This is the body for the sample post, which includes the body.</p>\n                <h2>We can use all kinds of format!</h2>\n                <p>And include a bunch of other stuff.</p>', 'posts/post2.jpg', 'my-sample-post', 'Meta Description for sample post', 'keyword1, keyword2, keyword3', 'PUBLISHED', 0, '2018-03-21 00:06:35', '2018-03-21 00:06:35'),
(3, 0, NULL, 'Latest Post', NULL, 'This is the excerpt for the latest post', '<p>This is the body for the latest post</p>', 'posts/post3.jpg', 'latest-post', 'This is the meta description', 'keyword1, keyword2, keyword3', 'PUBLISHED', 0, '2018-03-21 00:06:35', '2018-03-21 00:06:35'),
(4, 0, NULL, 'Yarr Post', NULL, 'Reef sails nipperkin bring a spring upon her cable coffer jury mast spike marooned Pieces of Eight poop deck pillage. Clipper driver coxswain galleon hempen halter come about pressgang gangplank boatswain swing the lead. Nipperkin yard skysail swab lanyard Blimey bilge water ho quarter Buccaneer.', '<p>Swab deadlights Buccaneer fire ship square-rigged dance the hempen jig weigh anchor cackle fruit grog furl. Crack Jennys tea cup chase guns pressgang hearties spirits hogshead Gold Road six pounders fathom measured fer yer chains. Main sheet provost come about trysail barkadeer crimp scuttle mizzenmast brig plunder.</p>\n<p>Mizzen league keelhaul galleon tender cog chase Barbary Coast doubloon crack Jennys tea cup. Blow the man down lugsail fire ship pinnace cackle fruit line warp Admiral of the Black strike colors doubloon. Tackle Jack Ketch come about crimp rum draft scuppers run a shot across the bow haul wind maroon.</p>\n<p>Interloper heave down list driver pressgang holystone scuppers tackle scallywag bilged on her anchor. Jack Tar interloper draught grapple mizzenmast hulk knave cable transom hogshead. Gaff pillage to go on account grog aft chase guns piracy yardarm knave clap of thunder.</p>', 'posts/post4.jpg', 'yarr-post', 'this be a meta descript', 'keyword1, keyword2, keyword3', 'PUBLISHED', 0, '2018-03-21 00:06:35', '2018-03-21 00:06:35');

-- --------------------------------------------------------

--
-- Структура таблицы `rent_categories`
--

CREATE TABLE `rent_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `features` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `rent_categories`
--

INSERT INTO `rent_categories` (`id`, `features`, `description`, `created_at`, `updated_at`) VALUES
(1, '[{\"name\":\"jh\",\"options\":[\"\"],\"multiple\":null}]', 'Квартиры для студентов1', '2018-03-20 18:00:00', '2018-03-26 10:12:34'),
(2, '[\"blabla\"]', NULL, '2018-03-21 23:33:26', '2018-03-21 23:33:26'),
(3, '[\"blabla\"]', 'des', '2018-03-22 11:52:35', '2018-03-22 11:52:35'),
(4, '{\"name\":\"n\",\"options\":[\"\"],\"multiple\":\"\"}', 'nbm', '2018-03-22 12:54:43', '2018-03-22 12:54:43'),
(16, '[{\"name\":\"h\",\"options\":[\"\"],\"multiple\":null}]', NULL, '2018-03-22 15:30:51', '2018-03-22 15:31:36'),
(18, '[]', NULL, '2018-03-24 07:23:48', '2018-03-24 07:23:48');

-- --------------------------------------------------------

--
-- Структура таблицы `rent_items`
--

CREATE TABLE `rent_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `images` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `visibility` tinyint(4) NOT NULL,
  `author` int(11) NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_info` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `features` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `payment_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', '2018-03-21 00:06:29', '2018-03-21 00:06:29'),
(2, 'user', 'Normal User', '2018-03-21 00:06:29', '2018-03-21 00:06:29');

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES
(1, 'site.title', 'Site Title', 'Site Title', '', 'text', 1, 'Site'),
(2, 'site.description', 'Site Description', 'Site Description', '', 'text', 2, 'Site'),
(3, 'site.logo', 'Site Logo', '', '', 'image', 3, 'Site'),
(4, 'site.google_analytics_tracking_id', 'Google Analytics Tracking ID', '', '', 'text', 4, 'Site'),
(5, 'admin.bg_image', 'Admin Background Image', '', '', 'image', 5, 'Admin'),
(6, 'admin.title', 'Admin Title', 'Voyager', '', 'text', 1, 'Admin'),
(7, 'admin.description', 'Admin Description', 'Welcome to Voyager. The Missing Admin for Laravel', '', 'text', 2, 'Admin'),
(8, 'admin.loader', 'Admin Loader', '', '', 'image', 3, 'Admin'),
(9, 'admin.icon_image', 'Admin Icon Image', '', '', 'image', 4, 'Admin'),
(10, 'admin.google_analytics_client_id', 'Google Analytics Client ID (used for admin dashboard)', '', '', 'text', 1, 'Admin');

-- --------------------------------------------------------

--
-- Структура таблицы `translations`
--

CREATE TABLE `translations` (
  `id` int(10) UNSIGNED NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `column_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foreign_key` int(10) UNSIGNED NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `translations`
--

INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES
(1, 'data_types', 'display_name_singular', 1, 'pt', 'Post', '2018-03-21 00:06:35', '2018-03-21 00:06:35'),
(2, 'data_types', 'display_name_singular', 2, 'pt', 'Página', '2018-03-21 00:06:35', '2018-03-21 00:06:35'),
(3, 'data_types', 'display_name_singular', 3, 'pt', 'Utilizador', '2018-03-21 00:06:35', '2018-03-21 00:06:35'),
(4, 'data_types', 'display_name_singular', 4, 'pt', 'Categoria', '2018-03-21 00:06:35', '2018-03-21 00:06:35'),
(5, 'data_types', 'display_name_singular', 5, 'pt', 'Menu', '2018-03-21 00:06:35', '2018-03-21 00:06:35'),
(6, 'data_types', 'display_name_singular', 6, 'pt', 'Função', '2018-03-21 00:06:35', '2018-03-21 00:06:35'),
(7, 'data_types', 'display_name_plural', 1, 'pt', 'Posts', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(8, 'data_types', 'display_name_plural', 2, 'pt', 'Páginas', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(9, 'data_types', 'display_name_plural', 3, 'pt', 'Utilizadores', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(10, 'data_types', 'display_name_plural', 4, 'pt', 'Categorias', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(11, 'data_types', 'display_name_plural', 5, 'pt', 'Menus', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(12, 'data_types', 'display_name_plural', 6, 'pt', 'Funções', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(13, 'categories', 'slug', 1, 'pt', 'categoria-1', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(14, 'categories', 'name', 1, 'pt', 'Categoria 1', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(15, 'categories', 'slug', 2, 'pt', 'categoria-2', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(16, 'categories', 'name', 2, 'pt', 'Categoria 2', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(17, 'pages', 'title', 1, 'pt', 'Olá Mundo', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(18, 'pages', 'slug', 1, 'pt', 'ola-mundo', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(19, 'pages', 'body', 1, 'pt', '<p>Olá Mundo. Scallywag grog swab Cat o\'nine tails scuttle rigging hardtack cable nipper Yellow Jack. Handsomely spirits knave lad killick landlubber or just lubber deadlights chantey pinnace crack Jennys tea cup. Provost long clothes black spot Yellow Jack bilged on her anchor league lateen sail case shot lee tackle.</p>\r\n<p>Ballast spirits fluke topmast me quarterdeck schooner landlubber or just lubber gabion belaying pin. Pinnace stern galleon starboard warp carouser to go on account dance the hempen jig jolly boat measured fer yer chains. Man-of-war fire in the hole nipperkin handsomely doubloon barkadeer Brethren of the Coast gibbet driver squiffy.</p>', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(20, 'menu_items', 'title', 1, 'pt', 'Painel de Controle', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(21, 'menu_items', 'title', 2, 'pt', 'Media', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(22, 'menu_items', 'title', 3, 'pt', 'Publicações', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(23, 'menu_items', 'title', 4, 'pt', 'Utilizadores', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(24, 'menu_items', 'title', 5, 'pt', 'Categorias', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(25, 'menu_items', 'title', 6, 'pt', 'Páginas', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(26, 'menu_items', 'title', 7, 'pt', 'Funções', '2018-03-21 00:06:36', '2018-03-21 00:06:36'),
(27, 'menu_items', 'title', 8, 'pt', 'Ferramentas', '2018-03-21 00:06:37', '2018-03-21 00:06:37'),
(28, 'menu_items', 'title', 9, 'pt', 'Menus', '2018-03-21 00:06:37', '2018-03-21 00:06:37'),
(29, 'menu_items', 'title', 10, 'pt', 'Base de dados', '2018-03-21 00:06:37', '2018-03-21 00:06:37'),
(30, 'menu_items', 'title', 12, 'pt', 'Configurações', '2018-03-21 00:06:37', '2018-03-21 00:06:37'),
(31, 'data_types', 'display_name_singular', 3, 'en', 'User', '2018-03-21 00:11:19', '2018-03-21 00:11:19'),
(32, 'data_types', 'display_name_singular', 3, 'kg', '', '2018-03-21 00:11:19', '2018-03-21 00:11:19'),
(33, 'data_types', 'display_name_plural', 3, 'en', 'Users', '2018-03-21 00:11:19', '2018-03-21 00:11:19'),
(34, 'data_types', 'display_name_plural', 3, 'kg', '', '2018-03-21 00:11:19', '2018-03-21 00:11:19'),
(35, 'data_types', 'display_name_singular', 7, 'en', 'Global Category', '2018-03-21 00:34:22', '2018-03-21 00:34:22'),
(36, 'data_types', 'display_name_singular', 7, 'kg', '', '2018-03-21 00:34:22', '2018-03-21 00:34:22'),
(37, 'data_types', 'display_name_plural', 7, 'en', 'Global Categories', '2018-03-21 00:34:22', '2018-03-21 00:34:22'),
(38, 'data_types', 'display_name_plural', 7, 'kg', '', '2018-03-21 00:34:22', '2018-03-21 00:34:22');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'users/default.png',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `username`, `phone_number`, `avatar`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Бакыт Турсунбеков', 'admin', '+996 702 77 23 17', 'users/February2018/vEObUWsTkee8jIwpc56x.jpg', '$2y$10$SDWtsp2d3NF1SWjrjZfiyuge1oyWECDSwO0LJ7OBpX0OoU/hFW7Mm', 'lZfUU9VaUb99qHcnNjCPlEtGA7xl3HtbR1T1lq33q3eki20D3qRGXyE8gMZd', '2018-03-21 00:06:34', '2018-03-21 00:06:34');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cache`
--
ALTER TABLE `cache`
  ADD UNIQUE KEY `cache_key_unique` (`key`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Индексы таблицы `data_rows`
--
ALTER TABLE `data_rows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `data_rows_data_type_id_foreign` (`data_type_id`);

--
-- Индексы таблицы `data_types`
--
ALTER TABLE `data_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `data_types_name_unique` (`name`),
  ADD UNIQUE KEY `data_types_slug_unique` (`slug`);

--
-- Индексы таблицы `global_categories`
--
ALTER TABLE `global_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `global_categories_parent_id_foreign` (`parent_id`),
  ADD KEY `global_categories_extends_foreign` (`extends`);

--
-- Индексы таблицы `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_name_unique` (`name`);

--
-- Индексы таблицы `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_menu_id_foreign` (`menu_id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Индексы таблицы `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissions_key_index` (`key`);

--
-- Индексы таблицы `permission_groups`
--
ALTER TABLE `permission_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_groups_name_unique` (`name`);

--
-- Индексы таблицы `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_permission_id_index` (`permission_id`),
  ADD KEY `permission_role_role_id_index` (`role_id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`);

--
-- Индексы таблицы `rent_categories`
--
ALTER TABLE `rent_categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `rent_items`
--
ALTER TABLE `rent_items`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Индексы таблицы `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `translations_table_name_column_name_foreign_key_locale_unique` (`table_name`,`column_name`,`foreign_key`,`locale`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `data_rows`
--
ALTER TABLE `data_rows`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT для таблицы `data_types`
--
ALTER TABLE `data_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `global_categories`
--
ALTER TABLE `global_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT для таблицы `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT для таблицы `permission_groups`
--
ALTER TABLE `permission_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `rent_categories`
--
ALTER TABLE `rent_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `rent_items`
--
ALTER TABLE `rent_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `data_rows`
--
ALTER TABLE `data_rows`
  ADD CONSTRAINT `data_rows_data_type_id_foreign` FOREIGN KEY (`data_type_id`) REFERENCES `data_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `global_categories`
--
ALTER TABLE `global_categories`
  ADD CONSTRAINT `global_categories_extends_foreign` FOREIGN KEY (`extends`) REFERENCES `rent_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `global_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `global_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
