-- Backup Database MyCMS
-- Data: 2026-02-01 12:37:37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = "0";



-- Struttura tabella `cmsmio_active_plugins`
DROP TABLE IF EXISTS `cmsmio_active_plugins`;
CREATE TABLE `cmsmio_active_plugins` (
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_active_plugins`
INSERT INTO `cmsmio_active_plugins` VALUES ('rss-aggregator');
INSERT INTO `cmsmio_active_plugins` VALUES ('seo');



-- Struttura tabella `cmsmio_api_keys`
DROP TABLE IF EXISTS `cmsmio_api_keys`;
CREATE TABLE `cmsmio_api_keys` (
  `id` int NOT NULL AUTO_INCREMENT,
  `api_key` varchar(64) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_key` (`api_key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dump dati tabella `cmsmio_api_keys`
INSERT INTO `cmsmio_api_keys` VALUES ('1', 'test_key_123456789', 'Test API Key', '1', '2026-01-25 09:48:57');



-- Struttura tabella `cmsmio_categories`
DROP TABLE IF EXISTS `cmsmio_categories`;
CREATE TABLE `cmsmio_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_categories`
INSERT INTO `cmsmio_categories` VALUES ('1', 'Generale', 'generale', 'Categoria generale', '2026-01-17 19:17:11');
INSERT INTO `cmsmio_categories` VALUES ('2', 'Novità', 'novita', 'Ultime novità', '2026-01-17 19:17:11');
INSERT INTO `cmsmio_categories` VALUES ('3', 'Guide', 'guide', 'Guide e tutorial', '2026-01-17 19:17:11');



-- Struttura tabella `cmsmio_contents`
DROP TABLE IF EXISTS `cmsmio_contents`;
CREATE TABLE `cmsmio_contents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_type` (`type`),
  KEY `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- Struttura tabella `cmsmio_custom_post_types`
DROP TABLE IF EXISTS `cmsmio_custom_post_types`;
CREATE TABLE `cmsmio_custom_post_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nome tecnico (es: portfolio)',
  `singular_label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Label singolare (es: Progetto)',
  `plural_label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Label plurale (es: Progetti)',
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug URL (es: portfolio)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Descrizione del tipo di contenuto',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'document' COMMENT 'Icona da usare nel menu admin',
  `supports` json DEFAULT NULL COMMENT 'Funzionalità supportate: ["title","content","featured_image","excerpt","categories"]',
  `menu_position` int DEFAULT '5' COMMENT 'Posizione nel menu admin',
  `public` tinyint(1) DEFAULT '1' COMMENT 'Visibile pubblicamente',
  `show_in_menu` tinyint(1) DEFAULT '1' COMMENT 'Mostra nel menu admin',
  `has_archive` tinyint(1) DEFAULT '1' COMMENT 'Ha pagina archivio',
  `hierarchical` tinyint(1) DEFAULT '0' COMMENT 'Struttura gerarchica come pagine',
  `rewrite_slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Slug personalizzato per URL',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_custom_post_types`
INSERT INTO `cmsmio_custom_post_types` VALUES ('1', 'portfolio', 'Progetto', 'Portfolio', 'portfolio', 'Gestisci progetti del portfolio', 'briefcase', '[\"title\", \"content\", \"featured_image\", \"excerpt\"]', '5', '1', '1', '1', '0', NULL, '2026-01-19 18:17:12', '2026-01-19 18:17:12');
INSERT INTO `cmsmio_custom_post_types` VALUES ('2', 'testimonial', 'Testimonianza', 'Testimonianze', 'testimonianze', 'Recensioni e testimonianze clienti', 'star', '[\"title\", \"content\"]', '6', '1', '1', '1', '0', NULL, '2026-01-19 18:17:12', '2026-01-19 18:17:12');



-- Struttura tabella `cmsmio_dashboard_widgets`
DROP TABLE IF EXISTS `cmsmio_dashboard_widgets`;
CREATE TABLE `cmsmio_dashboard_widgets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `widget_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_position` (`position`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_dashboard_widgets`
INSERT INTO `cmsmio_dashboard_widgets` VALUES ('3', 'quick_info', '6', '0', NULL);
INSERT INTO `cmsmio_dashboard_widgets` VALUES ('5', 'recent_content', '4', '1', NULL);
INSERT INTO `cmsmio_dashboard_widgets` VALUES ('8', 'analytics_stats', '1', '1', NULL);
INSERT INTO `cmsmio_dashboard_widgets` VALUES ('9', 'stats', '5', '1', NULL);
INSERT INTO `cmsmio_dashboard_widgets` VALUES ('15', 'Statistiche_Interne', '2', '1', NULL);
INSERT INTO `cmsmio_dashboard_widgets` VALUES ('16', 'rss', '3', '1', NULL);



-- Struttura tabella `cmsmio_menu_items`
DROP TABLE IF EXISTS `cmsmio_menu_items`;
CREATE TABLE `cmsmio_menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '_self',
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_menu` (`menu_id`),
  KEY `idx_parent` (`parent_id`),
  KEY `idx_order` (`sort_order`),
  CONSTRAINT `cmsmio_menu_items_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `cmsmio_menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_menu_items`
INSERT INTO `cmsmio_menu_items` VALUES ('1', '1', NULL, 'Home', '/', '_self', '0');
INSERT INTO `cmsmio_menu_items` VALUES ('3', '1', NULL, 'Blog', '/blog', '_self', '2');
INSERT INTO `cmsmio_menu_items` VALUES ('11', '1', NULL, '                                Chi siamo                            ', '/page/chi-siamo', '_self', '1');
INSERT INTO `cmsmio_menu_items` VALUES ('18', '1', '1', '                                Prova separazioni                            ', '/page/prova-separazioni', '_self', '3');
INSERT INTO `cmsmio_menu_items` VALUES ('19', '1', '1', '                                Pagina di prova                            ', '/page/pagina-di-prova', '_self', '4');
INSERT INTO `cmsmio_menu_items` VALUES ('20', '1', '1', '                                Chi siamo                            ', '/page/chi-siamo', '_self', '5');
INSERT INTO `cmsmio_menu_items` VALUES ('21', '1', '11', '                                Eccoci                            ', '/page/eccoci', '_self', '6');
INSERT INTO `cmsmio_menu_items` VALUES ('22', '1', NULL, 'News', '/rss-news', '_self', '7');



-- Struttura tabella `cmsmio_menus`
DROP TABLE IF EXISTS `cmsmio_menus`;
CREATE TABLE `cmsmio_menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_menus`
INSERT INTO `cmsmio_menus` VALUES ('1', 'Menu Principale', 'primary', '2026-01-17 19:17:11');



-- Struttura tabella `cmsmio_page_visits`
DROP TABLE IF EXISTS `cmsmio_page_visits`;
CREATE TABLE `cmsmio_page_visits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `page_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrer` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visit_date` date NOT NULL,
  `visit_time` datetime NOT NULL,
  `screen_width` int DEFAULT NULL,
  `screen_height` int DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_visit_date` (`visit_date`),
  KEY `idx_page_url` (`page_url`(191)),
  KEY `idx_date_url` (`visit_date`,`page_url`(191))
) ENGINE=InnoDB AUTO_INCREMENT=529 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_page_visits`
INSERT INTO `cmsmio_page_visits` VALUES ('1', '/', 'Nexa CMS', '', '2026-01-27', '2026-01-27 21:00:02', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('2', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-27', '2026-01-27 21:01:18', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('3', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-27', '2026-01-27 21:02:19', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('4', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-27', '2026-01-27 21:02:48', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('5', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-27', '2026-01-27 21:04:17', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('6', '/', 'Nexa CMS', '', '2026-01-27', '2026-01-27 22:00:02', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('7', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-27', '2026-01-27 22:00:26', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('8', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-27', '2026-01-27 22:00:51', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('9', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-27', '2026-01-27 22:01:57', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('10', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-27', '2026-01-27 22:02:26', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('11', '/', 'Nexa CMS', '', '2026-01-27', '2026-01-27 22:03:04', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('12', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-27', '2026-01-27 22:04:21', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('13', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-27', '2026-01-27 22:05:46', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('14', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-27', '2026-01-27 22:07:17', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('15', '/', 'Nexa CMS', '', '2026-01-27', '2026-01-27 23:00:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('16', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-27', '2026-01-27 23:01:15', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('17', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-27', '2026-01-27 23:02:11', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('18', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-27', '2026-01-27 23:04:01', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('19', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-27', '2026-01-27 23:06:01', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('20', '/', 'Nexa CMS', '', '2026-01-27', '2026-01-27 23:07:32', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('21', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-27', '2026-01-27 23:07:59', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('22', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 00:00:02', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('23', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 00:01:49', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('24', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 00:03:47', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('25', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 00:05:42', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('26', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 00:06:23', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('27', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 00:07:19', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('28', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 00:07:51', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('29', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 01:00:01', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('30', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-28', '2026-01-28 01:01:48', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('31', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 01:02:21', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('32', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-28', '2026-01-28 01:04:20', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('33', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 01:06:06', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('34', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 02:00:02', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('35', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 02:00:28', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('36', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 02:02:05', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('37', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 02:03:20', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('38', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 02:05:14', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('39', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 02:05:50', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('40', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 02:07:30', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('41', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 02:08:25', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('42', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 02:09:42', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('43', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 02:11:02', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('44', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 02:11:59', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('45', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 02:12:50', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('46', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 03:00:02', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('47', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 03:00:41', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('48', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 03:02:33', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('49', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 03:03:15', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('50', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 03:04:45', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('51', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 03:05:57', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('52', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 03:06:18', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('53', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 03:06:56', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('54', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 03:07:34', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('55', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 03:08:26', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('56', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 04:00:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('57', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-28', '2026-01-28 04:00:36', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('58', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 04:02:21', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('59', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 04:02:55', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('60', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 04:04:53', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('61', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 04:05:39', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('62', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 04:06:09', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('63', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 04:07:28', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('64', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 04:09:04', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('65', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 04:10:23', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('66', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 04:12:23', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('67', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 04:13:14', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('68', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 05:00:02', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('69', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 05:00:36', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('70', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 05:02:34', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('71', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-28', '2026-01-28 05:04:33', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('72', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 05:05:20', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('73', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 05:07:01', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('74', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 05:08:21', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('75', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 05:09:44', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('76', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 05:11:40', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('77', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 05:13:09', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('78', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 05:14:15', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('79', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 05:15:18', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('80', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 06:00:02', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('81', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 06:01:18', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('82', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 06:02:04', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('83', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-28', '2026-01-28 06:02:44', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('84', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 06:03:40', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('85', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 06:04:03', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('86', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 06:04:24', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('87', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 07:00:01', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('88', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 07:01:11', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('89', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 07:01:39', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('90', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 07:02:20', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('91', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 07:04:15', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('92', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 07:05:41', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('93', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 07:07:13', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('94', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 07:07:58', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('95', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 07:08:54', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('96', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 07:09:22', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('97', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 08:00:02', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('98', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 08:01:57', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('99', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 08:03:20', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('100', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 08:04:00', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('101', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 08:05:30', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('102', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 08:06:03', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('103', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 08:07:10', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('104', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 09:00:02', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('105', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 09:01:45', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('106', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 09:02:18', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('107', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 09:02:41', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('108', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 09:04:01', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('109', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 09:04:28', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('110', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 09:05:51', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('111', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-28', '2026-01-28 09:07:06', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('112', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 09:08:56', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('113', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-28', '2026-01-28 09:09:19', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('114', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 09:11:03', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('115', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 10:00:02', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('116', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 10:01:46', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('117', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 10:03:41', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('118', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 10:04:29', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('119', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 10:05:48', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('120', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 10:06:40', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('121', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 10:07:11', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('122', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 11:00:02', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('123', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 11:01:01', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('124', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 11:02:46', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('125', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 11:03:06', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('126', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 11:04:27', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('127', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 11:39:25', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 OPR/126.0.0.0');
INSERT INTO `cmsmio_page_visits` VALUES ('128', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 11:42:39', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('129', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 12:00:02', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('130', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 12:01:36', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('131', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 12:03:10', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('132', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 12:03:47', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('133', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 12:04:41', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('134', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 12:05:23', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('135', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 12:06:17', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('136', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 12:07:56', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('137', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 12:09:50', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('138', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 12:11:27', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('139', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 12:12:20', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('140', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 12:13:29', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('141', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 13:00:02', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('142', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 13:00:51', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('143', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 13:01:45', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('144', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 13:02:51', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('145', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 13:04:51', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('146', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 13:05:54', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('147', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 14:00:02', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('148', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 14:01:50', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('149', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 14:02:28', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('150', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 14:03:20', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('151', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 14:04:47', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('152', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 15:00:02', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('153', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 15:01:19', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('154', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 15:03:17', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('155', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 15:05:09', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('156', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 15:06:36', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('157', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 15:07:47', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('158', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 15:08:49', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('159', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 15:10:00', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('160', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 15:12:00', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('161', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 15:12:52', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('162', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 16:00:01', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('163', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-28', '2026-01-28 16:01:48', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('164', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 16:03:07', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('165', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 16:04:27', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('166', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 16:06:01', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('167', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 17:00:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('168', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 17:01:20', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('169', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 17:03:13', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('170', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 17:04:06', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('171', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 17:04:30', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('172', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-28', '2026-01-28 17:05:15', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('173', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 17:07:05', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('174', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 17:08:57', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('175', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 17:10:08', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('176', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 17:10:53', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('177', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-28', '2026-01-28 17:12:33', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('178', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 18:00:02', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('179', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 18:01:45', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('180', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-28', '2026-01-28 18:03:13', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('181', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-28', '2026-01-28 18:04:13', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('182', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 18:05:22', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('183', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 18:05:58', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('184', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 18:07:11', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('185', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 18:08:48', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('186', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 18:10:18', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('187', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 18:11:23', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('188', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 19:00:02', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('189', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 19:01:21', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('190', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 19:01:55', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('191', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 19:03:29', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('192', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 19:04:23', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('193', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 19:04:50', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('194', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 19:05:46', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('195', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 19:07:06', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('196', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 19:08:49', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('197', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 19:09:55', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('198', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 19:11:47', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('199', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 19:12:09', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('200', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 20:00:02', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('201', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-28', '2026-01-28 20:02:01', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('202', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 20:02:57', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('203', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 20:04:27', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('204', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 20:06:05', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('205', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 20:07:09', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('206', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-28', '2026-01-28 20:08:58', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('207', '/blog', 'Blog - Nexa CMS', '', '2026-01-28', '2026-01-28 20:09:55', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('208', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-28', '2026-01-28 20:10:53', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('209', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 21:00:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('210', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-28', '2026-01-28 21:01:41', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('211', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 21:03:34', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('212', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 21:04:04', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('213', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 21:04:55', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('214', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 21:06:42', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('215', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 21:07:10', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('216', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 21:07:47', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('217', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 22:00:02', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('218', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 22:00:37', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('219', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 22:02:01', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('220', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 22:03:19', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('221', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 22:04:32', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('222', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 22:06:00', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('223', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 22:07:01', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('224', '/', 'Nexa CMS', '', '2026-01-28', '2026-01-28 23:00:02', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('225', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 23:01:43', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('226', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-28', '2026-01-28 23:02:33', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('227', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-28', '2026-01-28 23:02:57', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('228', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 23:03:40', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('229', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-28', '2026-01-28 23:05:27', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('230', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-28', '2026-01-28 23:07:31', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('231', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 23:08:33', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('232', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-28', '2026-01-28 23:10:00', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('233', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-28', '2026-01-28 23:11:39', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('234', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-28', '2026-01-28 23:12:56', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('235', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 00:00:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('236', '/blog', 'Blog - Nexa CMS', '', '2026-01-29', '2026-01-29 00:01:03', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('237', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 00:02:18', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('238', '/blog', 'Blog - Nexa CMS', '', '2026-01-29', '2026-01-29 00:04:14', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('239', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 00:04:38', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('240', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 00:06:11', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('241', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 01:00:01', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('242', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-29', '2026-01-29 01:01:55', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('243', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 01:03:51', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('244', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 01:05:24', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('245', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 01:06:01', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('246', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 01:07:29', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('247', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 01:08:28', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('248', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 01:09:38', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('249', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 02:00:02', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('250', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 02:00:55', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('251', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 02:02:01', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('252', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 02:02:57', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('253', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 02:04:46', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('254', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 02:05:17', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('255', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-29', '2026-01-29 02:06:40', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('256', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 02:07:43', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('257', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 03:00:01', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('258', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-29', '2026-01-29 03:00:21', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('259', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 03:01:45', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('260', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 03:02:39', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('261', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 03:04:35', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('262', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 03:05:49', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('263', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 03:06:18', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('264', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 03:07:03', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('265', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 03:08:10', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('266', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-29', '2026-01-29 03:09:29', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('267', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 03:11:24', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('268', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 03:51:34', '667', '400', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('269', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 04:00:01', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('270', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 04:01:44', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('271', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 04:03:38', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('272', '/blog', 'Blog - Nexa CMS', '', '2026-01-29', '2026-01-29 04:05:17', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('273', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 04:05:58', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('274', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 04:07:08', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('275', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 04:09:08', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('276', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 04:09:45', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('277', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 04:11:23', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('278', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 05:00:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('279', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 05:00:22', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('280', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 05:01:27', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('281', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 05:02:08', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('282', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 05:03:59', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('283', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 05:05:39', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('284', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 05:07:03', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('285', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 05:08:16', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('286', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 06:00:01', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('287', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 06:01:59', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('288', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 06:03:32', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('289', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 06:05:12', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('290', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 06:06:36', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('291', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 06:08:10', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('292', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 06:09:08', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('293', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 06:10:15', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('294', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 07:00:01', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('295', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 07:00:48', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('296', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 07:01:24', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('297', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 07:01:49', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('298', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 07:02:13', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('299', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 08:00:01', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('300', '/blog', 'Blog - Nexa CMS', '', '2026-01-29', '2026-01-29 08:00:25', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('301', '/blog', 'Blog - Nexa CMS', '', '2026-01-29', '2026-01-29 08:01:29', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('302', '/blog', 'Blog - Nexa CMS', '', '2026-01-29', '2026-01-29 08:02:43', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('303', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 08:03:06', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('304', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 09:00:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('305', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 09:01:08', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('306', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 09:02:11', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('307', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-29', '2026-01-29 09:04:05', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('308', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 09:05:05', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('309', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 10:00:01', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('310', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 10:01:24', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('311', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 10:02:50', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('312', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 10:03:49', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('313', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 10:05:15', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('314', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 10:07:10', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('315', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 10:08:49', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('316', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 11:00:02', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('317', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 11:02:00', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('318', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 11:03:31', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('319', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-29', '2026-01-29 11:04:30', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('320', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 11:06:07', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('321', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 11:06:32', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('322', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-29', '2026-01-29 11:07:06', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('323', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 12:00:03', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('324', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 12:01:00', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('325', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 12:02:28', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('326', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-29', '2026-01-29 12:03:43', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('327', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-29', '2026-01-29 12:04:18', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('328', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 12:04:45', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('329', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 13:00:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('330', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 13:01:07', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('331', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 13:02:31', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('332', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 13:03:27', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('333', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 13:05:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('334', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 13:05:35', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('335', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-29', '2026-01-29 13:06:13', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('336', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 13:07:06', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('337', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 13:08:25', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('338', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 14:00:01', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('339', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 14:01:06', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('340', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 14:01:49', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('341', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 14:02:33', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('342', '/blog', 'Blog - Nexa CMS', '', '2026-01-29', '2026-01-29 14:04:33', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('343', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 14:04:58', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('344', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 14:05:22', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('345', '/blog', 'Blog - Nexa CMS', '', '2026-01-29', '2026-01-29 14:06:52', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('346', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 14:08:45', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('347', '/blog', 'Blog - Nexa CMS', '', '2026-01-29', '2026-01-29 14:10:27', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('348', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 14:10:53', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('349', '/blog', 'Blog - Nexa CMS', '', '2026-01-29', '2026-01-29 14:11:41', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('350', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 15:00:02', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('351', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-29', '2026-01-29 15:01:45', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('352', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 15:03:37', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('353', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 15:04:57', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('354', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 15:05:47', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('355', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 15:07:28', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('356', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 15:07:55', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('357', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 15:09:35', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('358', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-29', '2026-01-29 15:10:29', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('359', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-29', '2026-01-29 15:12:17', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('360', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 16:00:01', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('361', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 16:00:44', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('362', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-29', '2026-01-29 16:01:33', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('363', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 16:02:14', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('364', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 16:03:46', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('365', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 16:04:41', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('366', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 16:06:14', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('367', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 16:07:24', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('368', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-29', '2026-01-29 16:08:08', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('369', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 16:09:37', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('370', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 16:10:38', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('371', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 16:12:05', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('372', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 17:00:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('373', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 17:01:49', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('374', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 17:03:09', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('375', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 17:03:38', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('376', '/blog', 'Blog - Nexa CMS', '', '2026-01-29', '2026-01-29 17:04:03', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('377', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 17:04:33', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('378', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 17:05:14', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('379', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 17:06:53', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('380', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-29', '2026-01-29 17:07:52', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('381', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 17:09:36', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('382', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 17:10:35', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('383', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 17:12:25', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('384', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 18:00:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('385', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-29', '2026-01-29 18:00:36', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('386', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-29', '2026-01-29 18:01:42', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('387', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-29', '2026-01-29 18:03:20', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('388', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-29', '2026-01-29 18:05:18', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('389', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 18:05:39', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('390', '/blog', 'Blog - Nexa CMS', '', '2026-01-29', '2026-01-29 18:07:35', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('391', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 19:00:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('392', '/blog', 'Blog - Nexa CMS', '', '2026-01-29', '2026-01-29 19:01:29', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('393', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 19:03:20', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('394', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-29', '2026-01-29 19:04:31', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('395', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 19:05:51', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('396', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 19:06:17', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('397', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 19:07:32', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('398', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 19:09:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('399', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 19:09:50', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('400', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 20:00:02', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('401', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 20:00:32', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('402', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 20:01:45', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('403', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-29', '2026-01-29 20:02:05', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('404', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-29', '2026-01-29 20:03:53', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('405', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-29', '2026-01-29 20:05:48', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('406', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 20:07:39', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('407', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-29', '2026-01-29 20:08:14', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('408', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-29', '2026-01-29 20:08:43', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('409', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 20:10:42', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('410', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 20:11:24', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('411', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 21:00:02', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('412', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 21:01:25', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('413', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 21:03:12', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('414', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 21:05:11', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('415', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 21:06:08', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('416', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 22:00:01', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('417', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-29', '2026-01-29 22:00:59', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('418', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-29', '2026-01-29 22:01:58', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('419', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-29', '2026-01-29 22:03:27', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('420', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-29', '2026-01-29 22:03:58', '414', '896', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('421', '/', 'Nexa CMS', '', '2026-01-29', '2026-01-29 23:00:01', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('422', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-29', '2026-01-29 23:00:42', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('423', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 23:01:33', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('424', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 23:02:43', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('425', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-29', '2026-01-29 23:04:05', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('426', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 23:05:12', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('427', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-29', '2026-01-29 23:06:31', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('428', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-29', '2026-01-29 23:07:26', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('429', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-29', '2026-01-29 23:08:14', '1366', '768', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('430', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 00:00:02', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('431', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-30', '2026-01-30 00:01:24', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('432', '/blog', 'Blog - Nexa CMS', '', '2026-01-30', '2026-01-30 00:02:08', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('433', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-30', '2026-01-30 00:04:02', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('434', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-30', '2026-01-30 00:04:34', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('435', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-30', '2026-01-30 00:05:38', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('436', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-30', '2026-01-30 00:06:47', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('437', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 01:00:02', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('438', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-30', '2026-01-30 01:01:35', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('439', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-30', '2026-01-30 01:01:59', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('440', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-30', '2026-01-30 01:02:39', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('441', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 01:04:31', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('442', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-30', '2026-01-30 01:06:27', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('443', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 01:06:47', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('444', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 02:00:02', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('445', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-30', '2026-01-30 02:01:06', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('446', '/blog', 'Blog - Nexa CMS', '', '2026-01-30', '2026-01-30 02:01:43', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('447', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-30', '2026-01-30 02:02:53', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('448', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 02:04:20', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('449', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 02:05:16', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('450', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-30', '2026-01-30 02:06:01', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('451', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 02:06:34', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('452', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-30', '2026-01-30 02:07:19', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('453', '/page/prova-separazioni', 'Prova separazioni - Nexa CMS', '', '2026-01-30', '2026-01-30 02:07:59', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('454', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 03:00:02', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('455', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-30', '2026-01-30 03:01:15', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('456', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-30', '2026-01-30 03:02:08', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('457', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-30', '2026-01-30 03:03:35', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('458', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-30', '2026-01-30 03:05:13', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('459', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', '', '2026-01-30', '2026-01-30 03:06:42', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('460', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-30', '2026-01-30 03:08:38', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('461', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 04:00:02', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('462', '/blog', 'Blog - Nexa CMS', '', '2026-01-30', '2026-01-30 04:00:44', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('463', '/blog', 'Blog - Nexa CMS', '', '2026-01-30', '2026-01-30 04:01:14', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('464', '/blog', 'Blog - Nexa CMS', '', '2026-01-30', '2026-01-30 04:03:12', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('465', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 04:03:35', '1920', '1080', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('466', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 05:00:01', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('467', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-30', '2026-01-30 05:01:12', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('468', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 05:02:20', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('469', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-30', '2026-01-30 05:04:20', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('470', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 05:04:49', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('471', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-30', '2026-01-30 05:05:45', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('472', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 06:00:02', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('473', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-30', '2026-01-30 06:01:26', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('474', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 06:02:10', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('475', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-30', '2026-01-30 06:03:49', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('476', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 06:05:43', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('477', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-30', '2026-01-30 06:07:17', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('478', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 06:07:57', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('479', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-30', '2026-01-30 06:09:34', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('480', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-30', '2026-01-30 06:10:43', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('481', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 07:00:01', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('482', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-30', '2026-01-30 07:01:03', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('483', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 07:02:26', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('484', '/post/post-41', 'Post 41 - Nexa CMS', '', '2026-01-30', '2026-01-30 07:03:44', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('485', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 07:04:49', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('486', '/page/chi-siamo', 'Chi siamo - Nexa CMS', '', '2026-01-30', '2026-01-30 07:06:05', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('487', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 08:00:01', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('488', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-30', '2026-01-30 08:00:21', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('489', '/blog', 'Blog - Nexa CMS', '', '2026-01-30', '2026-01-30 08:01:48', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('490', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-30', '2026-01-30 08:03:20', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('491', '/post/post-43', 'Post 43 - Nexa CMS', '', '2026-01-30', '2026-01-30 08:04:11', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('492', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-30', '2026-01-30 08:04:49', '375', '812', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('493', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 09:00:02', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('494', '/post/post-39', 'Post 39 - Nexa CMS', '', '2026-01-30', '2026-01-30 09:00:45', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('495', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-30', '2026-01-30 09:01:51', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('496', '/', 'Nexa CMS', '', '2026-01-30', '2026-01-30 09:02:52', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('497', '/post/post-40', 'Post 40 - Nexa CMS', '', '2026-01-30', '2026-01-30 09:04:02', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('498', '/blog', 'Blog - Nexa CMS', '', '2026-01-30', '2026-01-30 09:05:15', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('499', '/page/pagina-di-prova', 'Pagina di prova - Nexa CMS', '', '2026-01-30', '2026-01-30 09:06:39', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('500', '/post/post-42', 'Post 42 - Nexa CMS', '', '2026-01-30', '2026-01-30 09:07:51', '1440', '900', '86.107.33.27', '');
INSERT INTO `cmsmio_page_visits` VALUES ('501', '/', 'Nexa CMS', '', '2026-01-31', '2026-01-31 18:28:39', '667', '400', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('502', '/rss-news', 'News dal Mondo - Nexa CMS', 'https://mycms.salvatoremaltese.it/', '2026-01-31', '2026-01-31 18:28:45', '667', '400', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('503', '/', 'Nexa CMS', '', '2026-01-31', '2026-01-31 19:02:43', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('504', '/', 'Nexa CMS', '', '2026-01-31', '2026-01-31 19:03:10', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('505', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/', '2026-01-31', '2026-01-31 19:03:13', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('506', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-01-31', '2026-01-31 19:03:17', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('507', '/page/eccoci', 'Eccoci - Nexa CMS', 'https://mycms.salvatoremaltese.it/post/prova-anteprima', '2026-01-31', '2026-01-31 19:03:24', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('508', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/page/eccoci', '2026-01-31', '2026-01-31 19:03:27', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('509', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-01-31', '2026-01-31 19:03:31', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('510', '/rss-news', 'News dal Mondo - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-01-31', '2026-01-31 19:03:33', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('511', '/rss-news', 'News dal Mondo - Nexa CMS', 'https://mycms.salvatoremaltese.it/rss-news', '2026-01-31', '2026-01-31 19:03:46', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('512', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/rss-news?feed=1', '2026-01-31', '2026-01-31 19:03:57', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('513', '/', 'Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-01-31', '2026-01-31 19:04:00', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('514', '/page/chi-siamo', 'Chi siamo - Nexa CMS', 'https://mycms.salvatoremaltese.it/', '2026-01-31', '2026-01-31 19:04:03', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('515', '/', 'Nexa CMS', '', '2026-01-31', '2026-01-31 19:05:19', '412', '915', '93.44.195.238', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('516', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/', '2026-01-31', '2026-01-31 19:05:25', '412', '915', '93.44.195.238', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('517', '/post/post-36', 'Post 36 - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-01-31', '2026-01-31 19:05:31', '412', '915', '93.44.195.238', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('518', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/post/post-36', '2026-01-31', '2026-01-31 19:05:34', '412', '915', '93.44.195.238', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('519', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-01-31', '2026-01-31 19:05:36', '412', '915', '93.44.195.238', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('520', '/page/eccoci', 'Eccoci - Nexa CMS', 'https://mycms.salvatoremaltese.it/post/prova-anteprima', '2026-01-31', '2026-01-31 19:05:47', '412', '915', '93.44.195.238', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('521', '/', 'Nexa CMS', 'https://mycms.salvatoremaltese.it/page/eccoci', '2026-01-31', '2026-01-31 19:05:49', '412', '915', '93.44.195.238', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('522', '/page/chi-siamo', 'Chi siamo - Nexa CMS', 'https://mycms.salvatoremaltese.it/', '2026-01-31', '2026-01-31 19:05:50', '412', '915', '93.44.195.238', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('523', '/', 'Nexa CMS', '', '2026-01-31', '2026-01-31 19:09:26', '667', '400', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('524', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/', '2026-01-31', '2026-01-31 19:09:38', '667', '400', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('525', '/post/prova-anteprima', 'Prova anteprima - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-01-31', '2026-01-31 19:09:49', '667', '400', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('526', '/', 'Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-02-01', '2026-02-01 12:00:42', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 OPR/126.0.0.0');
INSERT INTO `cmsmio_page_visits` VALUES ('527', '/', 'Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-02-01', '2026-02-01 12:16:40', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 OPR/126.0.0.0');
INSERT INTO `cmsmio_page_visits` VALUES ('528', '/rss-news/corsa-allia-per-microsoft-e-meta-trimestre-sopra-le-attese', '404 - Pagina non trovata - Nexa CMS', '', '2026-02-01', '2026-02-01 12:33:03', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 OPR/126.0.0.0');



-- Struttura tabella `cmsmio_pages`
DROP TABLE IF EXISTS `cmsmio_pages`;
CREATE TABLE `cmsmio_pages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'page',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_id` int DEFAULT NULL,
  `status` enum('bozza','pubblicato') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'bozza',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `author_id` (`author_id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_status` (`status`),
  KEY `idx_post_type` (`post_type`),
  CONSTRAINT `cmsmio_pages_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `cmsmio_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_pages`
INSERT INTO `cmsmio_pages` VALUES ('1', 'page', 'Chi siamo', 'chi-siamo', '<p>Questa è la pagina chi siamo del sito.</p><p style=\"text-align: center;\"><br></p>', NULL, '1', 'pubblicato', '2026-01-17 19:17:11', '2026-01-19 14:56:51', NULL);
INSERT INTO `cmsmio_pages` VALUES ('2', 'page', 'Pagina di prova', 'pagina-di-prova', '<p><br></p><p>proviamo il gestore media.</p><p style=\"text-align: center;\"><img src=\"/uploads/696f68bde085d_1768908989.png\" width=\"300px\"></p>', NULL, '1', 'pubblicato', '2026-01-18 18:50:16', '2026-01-21 00:43:50', NULL);
INSERT INTO `cmsmio_pages` VALUES ('4', 'page', 'Pagina per il widget', 'pagina-per-il-widget', '<p>Vediamo se compare nel widget.</p>', NULL, '1', 'pubblicato', '2026-01-20 19:31:12', '2026-01-23 14:49:12', '2026-01-23 12:49:12');
INSERT INTO `cmsmio_pages` VALUES ('5', 'page', 'Prova separazioni', 'prova-separazioni', '<p>Vediamo se funzionano le separazioni.</p>', '69734fe60bac0_1769164774.png', '1', 'pubblicato', '2026-01-21 15:36:11', '2026-01-23 13:44:22', NULL);
INSERT INTO `cmsmio_pages` VALUES ('6', 'page', 'Eccoci', 'eccoci', '<p>Prova Stats</p>', NULL, '1', 'pubblicato', '2026-01-30 14:34:48', '2026-01-30 14:34:48', NULL);



-- Struttura tabella `cmsmio_post_categories`
DROP TABLE IF EXISTS `cmsmio_post_categories`;
CREATE TABLE `cmsmio_post_categories` (
  `post_id` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`post_id`,`category_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `cmsmio_post_categories_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `cmsmio_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cmsmio_post_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `cmsmio_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_post_categories`
INSERT INTO `cmsmio_post_categories` VALUES ('29', '1');
INSERT INTO `cmsmio_post_categories` VALUES ('27', '2');
INSERT INTO `cmsmio_post_categories` VALUES ('28', '2');
INSERT INTO `cmsmio_post_categories` VALUES ('30', '2');
INSERT INTO `cmsmio_post_categories` VALUES ('25', '3');
INSERT INTO `cmsmio_post_categories` VALUES ('26', '3');
INSERT INTO `cmsmio_post_categories` VALUES ('94', '3');



-- Struttura tabella `cmsmio_post_meta`
DROP TABLE IF EXISTS `cmsmio_post_meta`;
CREATE TABLE `cmsmio_post_meta` (
  `meta_id` bigint NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`meta_id`),
  KEY `idx_post_meta` (`post_id`,`meta_key`),
  CONSTRAINT `cmsmio_post_meta_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `cmsmio_posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- Struttura tabella `cmsmio_posts`
DROP TABLE IF EXISTS `cmsmio_posts`;
CREATE TABLE `cmsmio_posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'post',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_id` int DEFAULT NULL,
  `status` enum('bozza','pubblicato','programmato') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'bozza',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `author_id` (`author_id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_status` (`status`),
  KEY `idx_created` (`created_at`),
  KEY `idx_post_type` (`post_type`),
  CONSTRAINT `cmsmio_posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `cmsmio_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_posts`
INSERT INTO `cmsmio_posts` VALUES ('17', 'post', 'Worth A Thousand Words', 'worth-a-thousand-words', '\n        \n        <figure style=\"width: 435px\" class=\"wp-caption alignnone\">\n         <img class=\"wp-image-59\" alt=\"Boat\" src=\"https://wpdotorg.files.wordpress.com/2008/11/boat.jpg\" width=\"435\" height=\"288\" />\n            <figcaption class=\"wp-caption-text\">Boat</figcaption>\n        </figure>\n        <p>Boat.</p>\n        \n        ', 'Lorem ipsum dolor sit amet, contenuto demo importato da WordPress XML.', NULL, '1', 'pubblicato', '2008-10-17 08:33:51', '2026-01-20 20:42:32', NULL);
INSERT INTO `cmsmio_posts` VALUES ('18', 'post', 'Elements', 'elements', '        \r\n            <p><!-- Sample Content to Plugin to Template --></p>\r\n            <p>The purpose of this HTML is to help determine what default settings are with CSS and to make sure that all possible HTML Elements are included in this HTML so as to not miss any possible Elements when designing a site.</p>\r\n            <hr />\r\n            <h1>Heading 1</h1>\r\n            <h2>Heading 2</h2>\r\n            <h3>Heading 3</h3>\r\n            <h4>Heading 4</h4>\r\n            <h5>Heading 5</h5>\r\n            <h6>Heading 6</h6>\r\n            <hr />\r\n            <h2 id=\"paragraph\">Paragraph</h2>\r\n            <p>Lorem ipsum dolor sit amet, <a href=\"#\" title=\"test link\">test link</a> adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.</p>\r\n            <p>Lorem ipsum dolor sit amet, <em>emphasis</em> consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.</p>\r\n            \r\n            <hr />\r\n            <h2 id=\"list_types\">List Types</h2>\r\n            <h3>Definition List</h3>\r\n            <dl>\r\n            <dt>Definition List Title</dt>\r\n            <dd>This is a definition list division.</dd>\r\n            </dl>\r\n            <h3>Ordered List</h3>\r\n            <ol>\r\n            <li>List Item 1</li>\r\n            <li>List Item 2</li>\r\n            <li>List Item 3</li>\r\n            </ol>\r\n            <h3>Unordered List</h3>\r\n            <ul>\r\n            <li>List Item 1</li>\r\n            <li>List Item 2</li>\r\n            <li>List Item 3</li>\r\n            </ul>\r\n            \r\n            <hr />\r\n            <h2 id=\"form_elements\">Forms</h2>\r\n            <fieldset>\r\n            <legend>Legend</legend>\r\n            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus.</p>\r\n            <form>\r\n            <h2>Form Element</h2>\r\n            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui.</p>\r\n            <p><label for=\"text_field\">Text Field:</label><br />\r\n                    <input type=\"text\" id=\"text_field\" /></p>\r\n            <p><label for=\"text_area\">Text Area:</label><br />\r\n                    <textarea id=\"text_area\"></textarea></p>\r\n            <p><label for=\"select_element\">Select Element:</label><br />\r\n                        <select name=\"select_element\"><br />\r\n                        <optgroup label=\"Option Group 1\"><option value=\"1\">Option 1</option><option value=\"2\">Option 2</option><option value=\"3\">Option 3</option></optgroup></p>\r\n            <p>			<optgroup label=\"Option Group 2\"><option value=\"1\">Option 1</option><option value=\"2\">Option 2</option><option value=\"3\">Option 3</option></optgroup><br />\r\n                    </select></p>\r\n            <p><label for=\"radio_buttons\">Radio Buttons:</label></p>\r\n            <p>			<input type=\"radio\" class=\"radio\" name=\"radio_button\" value=\"radio_1\" /> Radio 1<br />\r\n                            <input type=\"radio\" class=\"radio\" name=\"radio_button\" value=\"radio_2\" /> Radio 2<br />\r\n                            <input type=\"radio\" class=\"radio\" name=\"radio_button\" value=\"radio_3\" /> Radio 3\r\n                    </p>\r\n            <p><label for=\"checkboxes\">Checkboxes:</label></p>\r\n            <p>			<input type=\"checkbox\" class=\"checkbox\" name=\"checkboxes\" value=\"check_1\" /> Radio 1<br />\r\n                            <input type=\"checkbox\" class=\"checkbox\" name=\"checkboxes\" value=\"check_2\" /> Radio 2<br />\r\n                            <input type=\"checkbox\" class=\"checkbox\" name=\"checkboxes\" value=\"check_3\" /> Radio 3\r\n                    </p>\r\n            <p><label for=\"password\">Password:</label></p>\r\n            <p>			<input type=\"password\" class=\"password\" name=\"password\" />\r\n                    </p>\r\n            <p><label for=\"file\">File Input:</label><br />\r\n                        <input type=\"file\" class=\"file\" name=\"file\" />\r\n                    </p>\r\n            <p><input class=\"button\" type=\"reset\" value=\"Clear\" /> <input class=\"button\" type=\"submit\" value=\"Submit\" />\r\n                    </p>\r\n            </p></form>\r\n            </fieldset>\r\n            \r\n            <hr />\r\n            <h2 id=\"tables\">Tables</h2>\r\n            <table cellspacing=\"0\" cellpadding=\"0\">\r\n            <tr>\r\n            <th>Table Header 1</th>\r\n            <th>Table Header 2</th>\r\n            <th>Table Header 3</th>\r\n            </tr>\r\n            <tr>\r\n            <td>Division 1</td>\r\n            <td>Division 2</td>\r\n            <td>Division 3</td>\r\n            </tr>\r\n            <tr class=\"even\">\r\n            <td>Division 1</td>\r\n            <td>Division 2</td>\r\n            <td>Division 3</td>\r\n            </tr>\r\n            <tr>\r\n            <td>Division 1</td>\r\n            <td>Division 2</td>\r\n            <td>Division 3</td>\r\n            </tr>\r\n            </table>\r\n            \r\n            <hr />\r\n            <h2 id=\"misc\">Misc Stuff &#8211; abbr, acronym, pre, code, sub, sup, etc.</h2>\r\n            <p>Lorem <sup>superscript</sup> dolor <sub>subscript</sub> amet, consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. <cite>cite</cite>. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. <acronym title=\"National Basketball Association\">NBA</acronym> Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.  <abbr title=\"Avenue\">AVE</abbr></p>\r\n            <pre>\r\n            <p>\r\n            Lorem ipsum dolor sit amet,\r\n            consectetuer adipiscing elit.\r\n            Nullam dignissim convallis est.\r\n            Quisque aliquam. Donec faucibus. \r\n            Nunc iaculis suscipit dui. \r\n            Nam sit amet sem. \r\n            Aliquam libero nisi, imperdiet at,\r\n            tincidunt nec, gravida vehicula,\r\n            nisl. \r\n            Praesent mattis, massa quis \r\n            luctus fermentum, turpis mi \r\n            volutpat justo, eu volutpat \r\n            enim diam eget metus. \r\n            Maecenas ornare tortor. \r\n            Donec sed tellus eget sapien\r\n            fringilla nonummy. \r\n            <acronym title=\"National Basketball Association\">NBA</acronym> \r\n            Mauris a ante. Suspendisse\r\n            quam sem, consequat at, \r\n            commodo vitae, feugiat in, \r\n            nunc. Morbi imperdiet augue\r\n            quis tellus.  \r\n            <abbr title=\"Avenue\">AVE</abbr></p></pre>\r\n            <blockquote><p>\r\n                &#8220;This stylesheet is going to help so freaking much.&#8221; <br />-Blockquote\r\n            </p></blockquote>            \r\n            <!-- End of Sample Content --></p>\r\n        \r\n        ', 'Lorem ipsum dolor sit amet, contenuto demo importato da WordPress XML.', NULL, '1', 'pubblicato', '2008-09-05 23:39:12', '2026-01-20 20:48:01', NULL);
INSERT INTO `cmsmio_posts` VALUES ('20', 'post', 'HTML', 'html', '\n        \n            <p>What HTML tags would you like to see?</p>\n            <p>Let&#8217;s start with an unordered list:</p>\n            <ul>\n            <li>One</li>\n            <li>Two</li>\n            <li>Three</li>\n            <li>Four</li>\n            </ul>\n            <p>And then move on to a more interesting ordered list:</p>\n            <ol>\n            <li>one, two\n            <ol>\n            <li>buckle my shoe</li>\n            </ol>\n            </li>\n            <li>three, four\n            <ol>\n            <li>knock at the door</li>\n            </ol>\n            </li>\n            <li>Five, six\n            <ol>\n            <li>pick up sticks</li>\n            </ol>\n            </li>\n            <li>Seven, eight, lay them straight\n            <ol>\n            <li>Nine, ten, a big fat hen</li>\n            <li>Eleven, twelve, dig and delve</li>\n            <li>Thirteen, fourteen, maids a&#8217;courting</li>\n            <li>Fifteen, sixteen, maids in the kitchen</li>\n            <li>Seventeen, eighteen, maids a&#8217;waiting</li>\n            <li>Nineteen, twenty, my platter&#8217;s empty &#8230;</li>\n            </ol>\n            </li>\n            </ol>\n        \n        ', 'Lorem ipsum dolor sit amet, contenuto demo importato da WordPress XML.', NULL, '1', 'pubblicato', '2008-06-21 04:04:53', '2026-01-20 20:42:32', NULL);
INSERT INTO `cmsmio_posts` VALUES ('21', 'post', 'Links', 'links', '\n        \n        <p>A few well known WordPress links: <a href=\"http://wordpress.org/\">WordPress.org</a>, \n        <a href=\"http://codex.wordpress.org/Main_Page\">the Codex</a> and \n        <a href=\"http://wordpress.org/download/\">the download page</a>.</p>\n        \n        ', 'Lorem ipsum dolor sit amet, contenuto demo importato da WordPress XML.', NULL, '1', 'pubblicato', '2008-06-21 00:06:53', '2026-01-20 20:42:32', NULL);
INSERT INTO `cmsmio_posts` VALUES ('23', 'post', 'Hello world!', 'hello-world', '\n        \n            <p>Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!</p>\n        \n        ', 'Lorem ipsum dolor sit amet, contenuto demo importato da WordPress XML.', NULL, '1', 'pubblicato', '2008-06-05 02:40:06', '2026-01-20 20:42:32', NULL);
INSERT INTO `cmsmio_posts` VALUES ('25', 'post', 'riprova programmato', 'riprova-programmato', '<p>eccoci</p>', 'eccoci', NULL, '1', 'pubblicato', '2026-01-22 12:56:24', '2026-01-22 14:08:12', NULL);
INSERT INTO `cmsmio_posts` VALUES ('26', 'post', 'Post 7', 'post-7', '<p>post 7</p>', 'post 7', '697274440b318_1769108548.png', '1', 'pubblicato', '2026-01-22 22:02:35', '2026-01-22 22:02:35', NULL);
INSERT INTO `cmsmio_posts` VALUES ('27', 'post', 'Post 8', 'post-8', '<p>post 8</p>', 'post 8', '69727462e47a3_1769108578.png', '1', 'pubblicato', '2026-01-22 22:03:09', '2026-01-22 22:03:09', NULL);
INSERT INTO `cmsmio_posts` VALUES ('28', 'post', 'post 9', 'post-9', '', 'post 9', NULL, '1', 'pubblicato', '2026-01-22 22:03:26', '2026-01-22 22:03:26', NULL);
INSERT INTO `cmsmio_posts` VALUES ('29', 'post', 'post 10', 'post-10', '', 'post 10', NULL, '1', 'pubblicato', '2026-01-22 22:03:42', '2026-01-22 22:03:42', NULL);
INSERT INTO `cmsmio_posts` VALUES ('30', 'post', 'post 11', 'post-11', '', 'post 11', NULL, '1', 'pubblicato', '2026-01-22 22:03:58', '2026-01-22 22:03:58', NULL);
INSERT INTO `cmsmio_posts` VALUES ('31', 'post', 'Post 12', 'post-12', '<p>Contenuto demo post 12</p>', 'Excerpt post 12', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('32', 'post', 'Post 13', 'post-13', '<p>Contenuto demo post 13</p>', 'Excerpt post 13', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('33', 'post', 'Post 14', 'post-14', '<p>Contenuto demo post 14</p>', 'Excerpt post 14', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('34', 'post', 'Post 15', 'post-15', '<p>Contenuto demo post 15</p>', 'Excerpt post 15', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('35', 'post', 'Post 16', 'post-16', '<p>Contenuto demo post 16</p>', 'Excerpt post 16', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('36', 'post', 'Post 17', 'post-17', '<p>Contenuto demo post 17</p>', 'Excerpt post 17', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('37', 'post', 'Post 18', 'post-18', '<p>Contenuto demo post 18</p>', 'Excerpt post 18', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('38', 'post', 'Post 19', 'post-19', '<p>Contenuto demo post 19</p>', 'Excerpt post 19', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('39', 'post', 'Post 20', 'post-20', '<p>Contenuto demo post 20</p>', 'Excerpt post 20', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('40', 'post', 'Post 21', 'post-21', '<p>Contenuto demo post 21</p>', 'Excerpt post 21', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('41', 'post', 'Post 22', 'post-22', '<p>Contenuto demo post 22</p>', 'Excerpt post 22', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('42', 'post', 'Post 23', 'post-23', '<p>Contenuto demo post 23</p>', 'Excerpt post 23', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('43', 'post', 'Post 24', 'post-24', '<p>Contenuto demo post 24</p>', 'Excerpt post 24', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('44', 'post', 'Post 25', 'post-25', '<p>Contenuto demo post 25</p>', 'Excerpt post 25', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('45', 'post', 'Post 26', 'post-26', '<p>Contenuto demo post 26</p>', 'Excerpt post 26', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('46', 'post', 'Post 27', 'post-27', '<p>Contenuto demo post 27</p>', 'Excerpt post 27', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('47', 'post', 'Post 28', 'post-28', '<p>Contenuto demo post 28</p>', 'Excerpt post 28', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('48', 'post', 'Post 29', 'post-29', '<p>Contenuto demo post 29</p>', 'Excerpt post 29', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('49', 'post', 'Post 30', 'post-30', '<p>Contenuto demo post 30</p>', 'Excerpt post 30', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('50', 'post', 'Post 31', 'post-31', '<p>Contenuto demo post 31</p>', 'Excerpt post 31', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('51', 'post', 'Post 32', 'post-32', '<p>Contenuto demo post 32</p>', 'Excerpt post 32', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('52', 'post', 'Post 33', 'post-33', '<p>Contenuto demo post 33</p>', 'Excerpt post 33', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-23 15:31:00', NULL);
INSERT INTO `cmsmio_posts` VALUES ('53', 'post', 'Post 34', 'post-34', '<p>Contenuto demo post 34</p>', 'Excerpt post 34', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('54', 'post', 'Post 35', 'post-35', '<p>Contenuto demo post 35</p>', 'Excerpt post 35', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('55', 'post', 'Post 36', 'post-36', '<p>Contenuto demo post 36</p>', 'Excerpt post 36', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('56', 'post', 'Post 37', 'post-37', '<p>Contenuto demo post 37</p>', 'Excerpt post 37', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('57', 'post', 'Post 38', 'post-38', '<p>Contenuto demo post 38</p>', 'Excerpt post 38', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('58', 'post', 'Post 39', 'post-39', '<p>Contenuto demo post 39</p>', 'Excerpt post 39', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('59', 'post', 'Post 40', 'post-40', '<p>Contenuto demo post 40</p>', 'Excerpt post 40', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('60', 'post', 'Post 41', 'post-41', '<p>Contenuto demo post 41</p>', 'Excerpt post 41', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('61', 'post', 'Post 42', 'post-42', '<p>Contenuto demo post 42</p>', 'Excerpt post 42', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('62', 'post', 'Post 43', 'post-43', '<p>Contenuto demo post 43</p>', 'Excerpt post 43', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-22 22:12:34', NULL);
INSERT INTO `cmsmio_posts` VALUES ('63', 'post', 'Post 44', 'post-44', '<p>Contenuto demo post 44</p>', 'Excerpt post 44', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-26 13:43:16', '2026-01-26 11:43:16');
INSERT INTO `cmsmio_posts` VALUES ('64', 'post', 'Post 45', 'post-45', '<p>Contenuto demo post 45</p>', 'Excerpt post 45', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:41:54', '2026-01-24 17:41:54');
INSERT INTO `cmsmio_posts` VALUES ('65', 'post', 'Post 46', 'post-46', '<p>Contenuto demo post 46</p>', 'Excerpt post 46', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:07:14', '2026-01-24 17:07:14');
INSERT INTO `cmsmio_posts` VALUES ('66', 'post', 'Post 47', 'post-47', '<p>Contenuto demo post 47</p>', 'Excerpt post 47', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:07:14', '2026-01-24 17:07:14');
INSERT INTO `cmsmio_posts` VALUES ('67', 'post', 'Post 48', 'post-48', '<p>Contenuto demo post 48</p>', 'Excerpt post 48', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:07:14', '2026-01-24 17:07:14');
INSERT INTO `cmsmio_posts` VALUES ('68', 'post', 'Post 49', 'post-49', '<p>Contenuto demo post 49</p>', 'Excerpt post 49', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:07:14', '2026-01-24 17:07:14');
INSERT INTO `cmsmio_posts` VALUES ('69', 'post', 'Post 50', 'post-50', '<p>Contenuto demo post 50</p>', 'Excerpt post 50', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:07:14', '2026-01-24 17:07:14');
INSERT INTO `cmsmio_posts` VALUES ('70', 'post', 'Post 51', 'post-51', '<p>Contenuto demo post 51</p>', 'Excerpt post 51', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:07:14', '2026-01-24 17:07:14');
INSERT INTO `cmsmio_posts` VALUES ('71', 'post', 'Post 52', 'post-52', '<p>Contenuto demo post 52</p>', 'Excerpt post 52', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:07:14', '2026-01-24 17:07:14');
INSERT INTO `cmsmio_posts` VALUES ('72', 'post', 'Post 53', 'post-53', '<p>Contenuto demo post 53</p>', 'Excerpt post 53', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:07:14', '2026-01-24 17:07:14');
INSERT INTO `cmsmio_posts` VALUES ('73', 'post', 'Post 54', 'post-54', '<p>Contenuto demo post 54</p>', 'Excerpt post 54', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:06:57', '2026-01-24 17:06:57');
INSERT INTO `cmsmio_posts` VALUES ('74', 'post', 'Post 55', 'post-55', '<p>Contenuto demo post 55</p>', 'Excerpt post 55', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:06:57', '2026-01-24 17:06:57');
INSERT INTO `cmsmio_posts` VALUES ('75', 'post', 'Post 56', 'post-56', '<p>Contenuto demo post 56</p>', 'Excerpt post 56', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:06:57', '2026-01-24 17:06:57');
INSERT INTO `cmsmio_posts` VALUES ('76', 'post', 'Post 57', 'post-57', '<p>Contenuto demo post 57</p>', 'Excerpt post 57', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:06:57', '2026-01-24 17:06:57');
INSERT INTO `cmsmio_posts` VALUES ('77', 'post', 'Post 58', 'post-58', '<p>Contenuto demo post 58</p>', 'Excerpt post 58', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:06:57', '2026-01-24 17:06:57');
INSERT INTO `cmsmio_posts` VALUES ('78', 'post', 'Post 59', 'post-59', '<p>Contenuto demo post 59</p>', 'Excerpt post 59', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:06:57', '2026-01-24 17:06:57');
INSERT INTO `cmsmio_posts` VALUES ('79', 'post', 'Post 60', 'post-60', '<p>Contenuto demo post 60</p>', 'Excerpt post 60', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:06:57', '2026-01-24 17:06:57');
INSERT INTO `cmsmio_posts` VALUES ('80', 'post', 'Post 61', 'post-61', '<p>Contenuto demo post 61</p>', 'Excerpt post 61', NULL, '1', 'pubblicato', '2026-01-22 22:12:34', '2026-01-24 19:06:57', '2026-01-24 17:06:57');
INSERT INTO `cmsmio_posts` VALUES ('94', 'post', 'Prova anteprima', 'prova-anteprima', '<p>prova anteprima</p>', 'prova anteprima', '69734fe60bac0_1769164774.png', '1', 'pubblicato', '2026-01-23 12:59:19', '2026-01-24 19:47:42', NULL);



-- Struttura tabella `cmsmio_rss_elementi_importati`
DROP TABLE IF EXISTS `cmsmio_rss_elementi_importati`;
CREATE TABLE `cmsmio_rss_elementi_importati` (
  `id` int NOT NULL AUTO_INCREMENT,
  `feed_id` int NOT NULL,
  `guid` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `news_id` int NOT NULL,
  `importato_il` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_elemento` (`feed_id`,`guid`),
  KEY `idx_feed` (`feed_id`),
  KEY `idx_news` (`news_id`),
  CONSTRAINT `cmsmio_rss_elementi_importati_ibfk_1` FOREIGN KEY (`feed_id`) REFERENCES `cmsmio_rss_feeds` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cmsmio_rss_elementi_importati_ibfk_2` FOREIGN KEY (`news_id`) REFERENCES `cmsmio_rss_news` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_rss_elementi_importati`
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('1', '1', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/30/apple-utili-record-grazie-a-cina-per-iphone-miglior-trimestre-di-sempre_ef9feeef-b3dd-4c30-84f9-2ab4efcabd86.html', '1', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('2', '1', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/30/meta-in-italia-fara-pagare-le-aziende-per-tenere-i-loro-chatbot-su-whatsapp_598d2c77-9f40-4551-bc06-871cbeb0abcc.html', '2', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('3', '1', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/30/editori-contro-lia-di-anthropic-addestrata-sui-brani-di-rolling-stones-ed-elton_0e65cc81-2610-474d-ad17-d63a69cd0ce4.html', '3', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('4', '1', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/30/spotify-lancia-le-chat-di-gruppo-fino-a-10-partecipanti_6993f667-e502-4678-b420-71696e1ec0a3.html', '4', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('5', '1', 'https://www.ansa.it/canale_tecnologia/notizie/software_app/2026/01/29/whatsapp-nuova-funzione-per-difendersi-da-cyber-attacchi_3bd71236-fcfa-41d0-85c5-c013e2b70cd1.html', '5', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('6', '1', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/29/garante-laccesso-alla-email-del-lavoratore-licenziato-viola-la-privacy_4d2e2e2c-4600-4695-959f-ec59ad0afba4.html', '6', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('7', '1', 'https://www.ansa.it/canale_tecnologia/notizie/web_social/2026/01/29/bluesky-sfida-x-e-punta-sui-contenuti-in-diretta_2c445a12-3bee-4c78-9e7c-af148260b39a.html', '7', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('8', '1', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/29/esperti-di-ia-contro-il-chatbot-clawdbot-non-usatelo_10685fb1-f2d9-47cc-8187-f395519323f4.html', '8', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('9', '1', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/29/i-chatbot-di-ia-faticano-a-riconoscere-i-contenuti-estremisti_9437c1d5-27a4-4204-bda7-5ea83d293253.html', '9', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('10', '1', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/29/corsa-allia-per-microsoft-e-meta-trimestre-sopra-le-attese_0c2f9b20-869e-4891-bab9-77787eecdcd2.html', '10', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('11', '1', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/28/cloudflare-sbagliata-la-multa-agcom-e-legge-tecnicamente-pericolosa_1164bb4f-db96-4057-8581-ebab86d17d4d.html', '11', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('12', '1', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/28/meta-blocca-sui-social-il-sito-con-i-nomi-dei-dipendenti-dellice_1f036d69-f56a-48d9-a2b6-a4452587f355.html', '12', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('13', '1', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/27/google-potenzia-lia-nella-ricerca-anche-in-italia_70605921-b985-41ba-a4f1-16ea196c31ec.html', '13', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('14', '1', 'https://www.ansa.it/canale_tecnologia/notizie/software_app/2026/01/27/microsoft-sfida-nvidia-lancia-chip-dedicato-allia_4bcb58d3-1277-4197-88c0-d0023299681e.html', '14', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('15', '1', 'https://www.ansa.it/canale_tecnologia/notizie/web_social/2026/01/27/meta-punta-sullia-per-i-nuovi-piani-in-abbonamento-di-instagram-facebook-e-whatsapp_8c6654ef-5fb9-4cc9-8d6c-7deaba4ae234.html', '15', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('16', '1', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/27/dallue-750mila-euro-per-chip-fotonici-e-pillole-smart-_502f8334-06f3-417b-a081-aa65491cbae6.html', '16', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('17', '1', 'https://www.ansa.it/canale_tecnologia/notizie/web_social/2026/01/27/tik-tok-negli-usa-aumentano-gli-utenti-che-cancellano-lapp_0ed8e152-d2df-48c9-bbd8-9f08abdd8c00.html', '17', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('18', '1', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/27/edison-potrebbe-aver-prodotto-inconsapevolmente-il-grafene-gia-nel-1879-_805614ff-3d1c-4f3d-999d-3c3f67d5def2.html', '18', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('19', '1', 'https://www.ansa.it/canale_tecnologia/notizie/web_social/2026/01/27/social-vietati-agli-under-15-la-camera-francese-approva-il-ddl_a25f63d5-8521-4153-8feb-737e4650f698.html', '19', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('20', '1', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/26/il-tiktoker-khaby-lame-vende-la-societa-e-crea-un-avatar-digitale-con-lia_c9295b87-d3e7-407e-8eff-b8d65ca48cdb.html', '20', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('21', '1', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/26/scatta-la-sorveglianza-rafforzata-dellue-su-whatsapp_155591f3-709c-4f84-a137-6066928a52f3.html', '21', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('22', '1', 'https://www.ansa.it/canale_tecnologia/notizie/web_social/2026/01/26/grok-oltre-55-miliardi-di-immagini-generate-negli-ultimi-30-giorni_6618a37e-d5ef-4789-a5f6-a55ac553993b.html', '22', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('23', '1', 'https://www.ansa.it/canale_tecnologia/notizie/web_social/2026/01/26/meta-sospende-le-chat-dei-minori-con-i-personaggi-creati-dallia_285b02e7-f33a-4c9f-9ffe-ead0e0eb3cc5.html', '23', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('24', '1', 'https://www.ansa.it/canale_tecnologia/notizie/software_app/2026/01/26/lue-avvia-nuova-indagine-su-x-per-i-deepfake-sessuali-di-grok_2ea0ad29-8e24-4c93-bce6-1003d27f72a6.html', '24', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('25', '1', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/26/media-chatgpt-ha-usato-grokipedia-per-rispondere-su-iran-e-olocausto_5fbeda84-6b9f-4a30-9ff7-7336c385e36f.html', '25', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('26', '1', 'https://www.ansa.it/canale_tecnologia/notizie/software_app/2026/01/25/paradosso-ia-chatbot-non-riconoscono-i-video-fatti-con-lintelligenza-artificiale_6bb1e844-2d7e-4650-ba5d-e9c4894179d1.html', '26', '2026-01-31 10:28:33');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('27', '4', 'https://www.ilsoftware.it/?p=495176', '27', '2026-01-31 16:41:45');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('28', '4', 'https://www.ilsoftware.it/?p=495091', '28', '2026-01-31 16:41:45');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('29', '4', 'https://www.ilsoftware.it/?p=495089', '29', '2026-01-31 16:41:45');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('30', '4', 'https://www.ilsoftware.it/?p=494942', '30', '2026-01-31 16:41:45');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('31', '4', 'https://www.ilsoftware.it/?p=494866', '31', '2026-01-31 16:41:45');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('32', '4', 'https://www.ilsoftware.it/?p=494604', '32', '2026-01-31 16:41:45');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('33', '4', 'https://www.ilsoftware.it/?p=494545', '33', '2026-01-31 16:41:45');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('34', '4', 'https://www.ilsoftware.it/?p=494527', '34', '2026-01-31 16:41:45');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('35', '4', 'https://www.ilsoftware.it/?post_type=focus&p=494475', '35', '2026-01-31 16:41:45');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('36', '4', 'https://www.ilsoftware.it/?p=494457', '36', '2026-01-31 16:41:45');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('37', '3', 'https://www.miamammausalinux.org/?p=25731', '37', '2026-01-31 16:41:50');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('38', '3', 'https://www.miamammausalinux.org/?p=25733', '38', '2026-01-31 16:41:50');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('39', '3', 'https://www.miamammausalinux.org/?p=25726', '39', '2026-01-31 16:41:50');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('40', '3', 'https://www.miamammausalinux.org/?p=25721', '40', '2026-01-31 16:41:50');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('41', '3', 'https://www.miamammausalinux.org/?p=25713', '41', '2026-01-31 16:41:50');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('42', '3', 'https://www.miamammausalinux.org/?p=25711', '42', '2026-01-31 16:41:50');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('43', '3', 'https://www.miamammausalinux.org/?p=25656', '43', '2026-01-31 16:41:50');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('44', '3', 'https://www.miamammausalinux.org/?p=25654', '44', '2026-01-31 16:41:50');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('45', '3', 'https://www.miamammausalinux.org/?p=25649', '45', '2026-01-31 16:41:50');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('46', '3', 'https://www.miamammausalinux.org/?p=25651', '46', '2026-01-31 16:41:50');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('47', '2', 'https://www.cybersecurity360.it/?p=93533', '47', '2026-01-31 16:41:51');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('48', '2', 'https://www.cybersecurity360.it/?p=88483', '48', '2026-01-31 16:41:51');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('49', '2', 'https://www.cybersecurity360.it/?p=98283', '49', '2026-01-31 16:41:51');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('50', '2', 'https://www.cybersecurity360.it/?p=99581', '50', '2026-01-31 16:41:51');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('51', '2', 'https://www.cybersecurity360.it/?p=99542', '51', '2026-01-31 16:41:51');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('52', '2', 'https://www.cybersecurity360.it/?p=99567', '52', '2026-01-31 16:41:51');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('53', '2', 'https://www.cybersecurity360.it/?p=99562', '53', '2026-01-31 16:41:51');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('54', '2', 'https://www.cybersecurity360.it/?p=99553', '54', '2026-01-31 16:41:51');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('55', '2', 'https://www.cybersecurity360.it/?p=99554', '55', '2026-01-31 16:41:51');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('56', '2', 'https://www.cybersecurity360.it/?p=99514', '56', '2026-01-31 16:41:51');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('57', '1', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/31/deepfake-nudi-e-pregiudizi-si-fa-strada-lia-femminista_92ee2be8-6fe9-4b0c-a63b-9c2080370d35.html', '57', '2026-01-31 16:47:45');
INSERT INTO `cmsmio_rss_elementi_importati` VALUES ('58', '1', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/31/spacex-e-xai-valutano-fusione-rivoluzione-nellimpero-di-musk_87a19bbc-1e0c-4c2a-bf77-2f0214e8d60a.html', '58', '2026-01-31 16:47:45');



-- Struttura tabella `cmsmio_rss_feeds`
DROP TABLE IF EXISTS `cmsmio_rss_feeds`;
CREATE TABLE `cmsmio_rss_feeds` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `frequenza` int DEFAULT '3600' COMMENT 'Frequenza in secondi',
  `ultimo_import` datetime DEFAULT NULL,
  `prossimo_import` datetime DEFAULT NULL,
  `stato` enum('attivo','pausa','errore') COLLATE utf8mb4_unicode_ci DEFAULT 'attivo',
  `messaggio_errore` text COLLATE utf8mb4_unicode_ci,
  `elementi_importati` int DEFAULT '0',
  `creato_il` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `aggiornato_il` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_stato` (`stato`),
  KEY `idx_prossimo` (`prossimo_import`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_rss_feeds`
INSERT INTO `cmsmio_rss_feeds` VALUES ('1', 'ANSA Tecnologia', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia_rss.xml', '10800', '2026-01-31 16:48:29', '2026-02-01 15:35:41', 'attivo', NULL, '28', '2026-01-31 10:28:28', '2026-02-01 12:35:41');
INSERT INTO `cmsmio_rss_feeds` VALUES ('2', 'CyberSecurity', 'https://www.cybersecurity360.it/feed/', '10800', '2026-01-31 16:41:51', '2026-02-01 15:35:41', 'attivo', NULL, '10', '2026-01-31 12:26:14', '2026-02-01 12:35:41');
INSERT INTO `cmsmio_rss_feeds` VALUES ('3', 'MMUL', 'https://www.miamammausalinux.org/feed/', '10800', '2026-01-31 16:46:59', '2026-02-01 15:35:41', 'attivo', NULL, '10', '2026-01-31 12:26:53', '2026-02-01 12:35:41');
INSERT INTO `cmsmio_rss_feeds` VALUES ('4', 'ilSoftware', 'https://www.ilsoftware.it/sistemi-operativi/linux/feed/', '10800', '2026-01-31 16:46:46', '2026-02-01 15:35:41', 'attivo', NULL, '10', '2026-01-31 12:27:13', '2026-02-01 12:35:41');



-- Struttura tabella `cmsmio_rss_news`
DROP TABLE IF EXISTS `cmsmio_rss_news`;
CREATE TABLE `cmsmio_rss_news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `feed_id` int NOT NULL,
  `titolo` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `link_originale` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `immagine_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `autore_originale` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_pubblicazione` datetime NOT NULL,
  `creato_il` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `aggiornato_il` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `visualizzazioni` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_feed` (`feed_id`),
  KEY `idx_data` (`data_pubblicazione`),
  KEY `idx_slug` (`slug`),
  CONSTRAINT `cmsmio_rss_news_ibfk_1` FOREIGN KEY (`feed_id`) REFERENCES `cmsmio_rss_feeds` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_rss_news`
INSERT INTO `cmsmio_rss_news` VALUES ('1', '1', 'Apple: utili record grazie a Cina, per iPhone miglior trimestre di sempre', 'apple-utili-record-grazie-a-cina-per-iphone-miglior-trimestre-di-sempre', 'Cook: \"Domanda senza precedenti record assoluti in ogni segmento geografico\"', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/30/apple-utili-record-grazie-a-cina-per-iphone-miglior-trimestre-di-sempre_ef9feeef-b3dd-4c30-84f9-2ab4efcabd86.html', NULL, NULL, '2026-01-30 14:43:42', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('2', '1', 'In Italia le aziende pagheranno per avere i loro chatbot su WhatsApp', 'in-italia-le-aziende-pagheranno-per-avere-i-loro-chatbot-su-whatsapp', 'Decisione di Meta opo le richieste dell\'Antitrust, la misura dal 16 febbraio', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/30/meta-in-italia-fara-pagare-le-aziende-per-tenere-i-loro-chatbot-su-whatsapp_598d2c77-9f40-4551-bc06-871cbeb0abcc.html', NULL, NULL, '2026-01-30 18:50:50', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('3', '1', 'Editori contro l\'IA di Anthropic, è addestrata sui brani degli Stones ed Elton John', 'editori-contro-lia-di-anthropic-e-addestrata-sui-brani-degli-stones-ed-elton-john', 'Media, il chatbot Claude avrebbe usato testi e musica di 20 mila canzoni', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/30/editori-contro-lia-di-anthropic-addestrata-sui-brani-di-rolling-stones-ed-elton_0e65cc81-2610-474d-ad17-d63a69cd0ce4.html', NULL, NULL, '2026-01-30 18:48:57', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('4', '1', 'Spotify lancia le chat di gruppo fino a 10 partecipanti', 'spotify-lancia-le-chat-di-gruppo-fino-a-10-partecipanti', 'Azienda, \'il passaparola aiuta a creare nuovi fan\'', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/30/spotify-lancia-le-chat-di-gruppo-fino-a-10-partecipanti_6993f667-e502-4678-b420-71696e1ec0a3.html', NULL, NULL, '2026-01-30 14:33:44', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('5', '1', 'WhatsApp, nuova funzione per difendersi da cyber attacchi', 'whatsapp-nuova-funzione-per-difendersi-da-cyber-attacchi', 'Si chiama Strict account, blocca allegati e file multimediali da sconosciuti', 'https://www.ansa.it/canale_tecnologia/notizie/software_app/2026/01/29/whatsapp-nuova-funzione-per-difendersi-da-cyber-attacchi_3bd71236-fcfa-41d0-85c5-c013e2b70cd1.html', NULL, NULL, '2026-01-29 15:47:23', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('6', '1', 'Garante, l\'accesso alla email del lavoratore licenziato vìola la privacy', 'garante-laccesso-alla-email-del-lavoratore-licenziato-viola-la-privacy', 'Inflitta una sanzione di 40mila euro a una società', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/29/garante-laccesso-alla-email-del-lavoratore-licenziato-viola-la-privacy_4d2e2e2c-4600-4695-959f-ec59ad0afba4.html', NULL, NULL, '2026-01-29 14:08:29', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('7', '1', 'Bluesky sfida X e punta sui contenuti in diretta', 'bluesky-sfida-x-e-punta-sui-contenuti-in-diretta', 'Per il social, entro il 2026 chiunque potrà trasmettere live', 'https://www.ansa.it/canale_tecnologia/notizie/web_social/2026/01/29/bluesky-sfida-x-e-punta-sui-contenuti-in-diretta_2c445a12-3bee-4c78-9e7c-af148260b39a.html', NULL, NULL, '2026-01-29 14:09:08', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('8', '1', 'Esperti di IA contro il chatbot Clawdbot, \'non usatelo\'', 'esperti-di-ia-contro-il-chatbot-clawdbot-non-usatelo', 'Per i ricercatori, il software \'agentico\' può mettere in pericolo la privacy', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/29/esperti-di-ia-contro-il-chatbot-clawdbot-non-usatelo_10685fb1-f2d9-47cc-8187-f395519323f4.html', NULL, NULL, '2026-01-31 09:59:58', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('9', '1', 'I chatbot di IA faticano a riconoscere i contenuti estremisti', 'i-chatbot-di-ia-faticano-a-riconoscere-i-contenuti-estremisti', 'Analisi dell\'Anti-Defamation League, Grok il peggiore, all\'opposto c\'è Claude', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/29/i-chatbot-di-ia-faticano-a-riconoscere-i-contenuti-estremisti_9437c1d5-27a4-4204-bda7-5ea83d293253.html', NULL, NULL, '2026-01-29 13:28:18', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('10', '1', 'Corsa all\'IA, per Microsoft e Meta trimestre sopra le attese', 'corsa-allia-per-microsoft-e-meta-trimestre-sopra-le-attese', 'Il colosso di Zuckerberg quasi raddoppia le spese di capitale', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/29/corsa-allia-per-microsoft-e-meta-trimestre-sopra-le-attese_0c2f9b20-869e-4891-bab9-77787eecdcd2.html', NULL, NULL, '2026-01-29 14:09:51', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('11', '1', 'Cloudflare, sbagliata la multa Agcom e legge tecnicamente pericolosa', 'cloudflare-sbagliata-la-multa-agcom-e-legge-tecnicamente-pericolosa', '\"L\'Autorità dimostra di non comprendere come funziona Internet\"', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/28/cloudflare-sbagliata-la-multa-agcom-e-legge-tecnicamente-pericolosa_1164bb4f-db96-4057-8581-ebab86d17d4d.html', NULL, NULL, '2026-01-28 19:03:37', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('12', '1', 'Meta blocca sui social il sito con i nomi dei dipendenti dell\'Ice', 'meta-blocca-sui-social-il-sito-con-i-nomi-dei-dipendenti-dellice', 'Oscurati i link al catalogo \'Ice List\' su Facebook, Instagram e Threads', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/28/meta-blocca-sui-social-il-sito-con-i-nomi-dei-dipendenti-dellice_1f036d69-f56a-48d9-a2b6-a4452587f355.html', NULL, NULL, '2026-01-31 09:59:55', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('13', '1', 'Google potenzia l\'IA nella ricerca, anche in Italia', 'google-potenzia-lia-nella-ricerca-anche-in-italia', 'Possibile passare da riassunti a conversazione, solo su dispositivi mobili', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/27/google-potenzia-lia-nella-ricerca-anche-in-italia_70605921-b985-41ba-a4f1-16ea196c31ec.html', NULL, NULL, '2026-01-30 14:33:56', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('14', '1', 'Microsoft sfida Nvidia, lancia un chip dedicato all\'IA', 'microsoft-sfida-nvidia-lancia-un-chip-dedicato-allia', 'Si chiama Maia 200, pensato per aumentare le prestazioni', 'https://www.ansa.it/canale_tecnologia/notizie/software_app/2026/01/27/microsoft-sfida-nvidia-lancia-chip-dedicato-allia_4bcb58d3-1277-4197-88c0-d0023299681e.html', NULL, NULL, '2026-01-27 17:25:10', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('15', '1', 'Meta punta sull\'IA per i nuovi piani in abbonamento ', 'meta-punta-sullia-per-i-nuovi-piani-in-abbonamento', 'Media, le nuove offerte \'stimoleranno le capacità dell\'intelligenza artificiale\'', 'https://www.ansa.it/canale_tecnologia/notizie/web_social/2026/01/27/meta-punta-sullia-per-i-nuovi-piani-in-abbonamento-di-instagram-facebook-e-whatsapp_8c6654ef-5fb9-4cc9-8d6c-7deaba4ae234.html', NULL, NULL, '2026-01-27 13:05:06', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('16', '1', 'Dall’Ue 750mila euro per chip fotonici e pillole smart ', 'dallue-750mila-euro-per-chip-fotonici-e-pillole-smart', 'Vinti da 5 ricercatori dell’Istituto Italiano di Tecnologia di Genova, Milano e Napoli', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/27/dallue-750mila-euro-per-chip-fotonici-e-pillole-smart-_502f8334-06f3-417b-a081-aa65491cbae6.html', NULL, NULL, '2026-01-27 13:11:55', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('17', '1', 'Negli Usa aumentano gli utenti che cancellano TikTok', 'negli-usa-aumentano-gli-utenti-che-cancellano-tiktok', 'Per SensorTower crescono del 150% le rimozioni a favore della concorrente UpScrolled', 'https://www.ansa.it/canale_tecnologia/notizie/web_social/2026/01/27/tik-tok-negli-usa-aumentano-gli-utenti-che-cancellano-lapp_0ed8e152-d2df-48c9-bbd8-9f08abdd8c00.html', NULL, NULL, '2026-01-27 13:07:56', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('18', '1', 'Edison potrebbe aver prodotto inconsapevolmente il grafene già nel 1879 ', 'edison-potrebbe-aver-prodotto-inconsapevolmente-il-grafene-gia-nel-1879', 'Replicati i suoi esperimenti sulla lampadina, creavano le condizioni adatte', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/27/edison-potrebbe-aver-prodotto-inconsapevolmente-il-grafene-gia-nel-1879-_805614ff-3d1c-4f3d-999d-3c3f67d5def2.html', NULL, NULL, '2026-01-27 13:11:50', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('19', '1', 'Social vietati agli under-15, la Camera francese approva il ddl', 'social-vietati-agli-under-15-la-camera-francese-approva-il-ddl', 'Il provvedimento passa ora in Senato, Parigi verso apripista in Europa', 'https://www.ansa.it/canale_tecnologia/notizie/web_social/2026/01/27/social-vietati-agli-under-15-la-camera-francese-approva-il-ddl_a25f63d5-8521-4153-8feb-737e4650f698.html', NULL, NULL, '2026-01-29 19:50:42', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('20', '1', 'Il TikToker Khaby Lame vende la società e crea un avatar digitale con l\'IA', 'il-tiktoker-khaby-lame-vende-la-societa-e-crea-un-avatar-digitale-con-lia', 'Operazione da quasi un miliardo di dollari', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/26/il-tiktoker-khaby-lame-vende-la-societa-e-crea-un-avatar-digitale-con-lia_c9295b87-d3e7-407e-8eff-b8d65ca48cdb.html', NULL, NULL, '2026-01-26 17:30:54', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('21', '1', 'Scatta la sorveglianza rafforzata dell\'Ue su WhatsApp', 'scatta-la-sorveglianza-rafforzata-dellue-su-whatsapp', 'L\'app di Meta inserita tra le major soggette agli obblighi del Digital Services Act', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/26/scatta-la-sorveglianza-rafforzata-dellue-su-whatsapp_155591f3-709c-4f84-a137-6066928a52f3.html', NULL, NULL, '2026-01-26 15:48:36', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('22', '1', 'Grok, oltre 5,5 miliardi di immagini generate negli ultimi 30 giorni', 'grok-oltre-55-miliardi-di-immagini-generate-negli-ultimi-30-giorni', 'X di recente ha introdotto restrizioni sulle foto \'sessualizzate\'', 'https://www.ansa.it/canale_tecnologia/notizie/web_social/2026/01/26/grok-oltre-55-miliardi-di-immagini-generate-negli-ultimi-30-giorni_6618a37e-d5ef-4789-a5f6-a55ac553993b.html', NULL, NULL, '2026-01-26 12:56:31', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('23', '1', 'Meta sospende le chat dei minori con i personaggi creati dall\'IA', 'meta-sospende-le-chat-dei-minori-con-i-personaggi-creati-dallia', 'In attesa di maggiori controlli, stop alle chat con gli avatar personalizzati', 'https://www.ansa.it/canale_tecnologia/notizie/web_social/2026/01/26/meta-sospende-le-chat-dei-minori-con-i-personaggi-creati-dallia_285b02e7-f33a-4c9f-9ffe-ead0e0eb3cc5.html', NULL, NULL, '2026-01-26 18:10:48', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('24', '1', 'L\'Ue avvia nuova indagine su X per i deepfake sessuali di Grok', 'lue-avvia-nuova-indagine-su-x-per-i-deepfake-sessuali-di-grok', 'Virkkunen: \"Sono una forma violenta e inaccettabile di degradazione\"', 'https://www.ansa.it/canale_tecnologia/notizie/software_app/2026/01/26/lue-avvia-nuova-indagine-su-x-per-i-deepfake-sessuali-di-grok_2ea0ad29-8e24-4c93-bce6-1003d27f72a6.html', NULL, NULL, '2026-01-26 12:50:37', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('25', '1', 'Media, ChatGpt ha usato Grokipedia per rispondere su Iran e Olocausto', 'media-chatgpt-ha-usato-grokipedia-per-rispondere-su-iran-e-olocausto', 'L\'enciclopedia di Elon Musk come fonte per affermazioni del chatbot di OpenAI', 'https://www.ansa.it/canale_tecnologia/notizie/future_tech/2026/01/26/media-chatgpt-ha-usato-grokipedia-per-rispondere-su-iran-e-olocausto_5fbeda84-6b9f-4a30-9ff7-7336c385e36f.html', NULL, NULL, '2026-01-26 12:07:20', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('26', '1', 'Paradosso chatbot, non riconoscono i video fatti con la stessa IA', 'paradosso-chatbot-non-riconoscono-i-video-fatti-con-la-stessa-ia', 'Test su ChatGpr, Grok e Gemini: nel 78-95% dei casi non sono riusciti a capirlo', 'https://www.ansa.it/canale_tecnologia/notizie/software_app/2026/01/25/paradosso-ia-chatbot-non-riconoscono-i-video-fatti-con-lintelligenza-artificiale_6bb1e844-2d7e-4650-ba5d-e9c4894179d1.html', NULL, NULL, '2026-01-26 18:12:32', '2026-01-31 10:28:33', '2026-01-31 10:28:33', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('27', '4', 'GNOME 50 migliora la gestione delle GPU discrete: vittoria per i sistemi multi-GPU', 'gnome-50-migliora-la-gestione-delle-gpu-discrete-vittoria-per-i-sistemi-multi-gpu', 'GNOME 50 migliora il rilevamento delle GPU discrete e l\'integrazione nella Shell. La scelta delle schede grafiche performante diventa facilmente gestibile, con un semplice clic nell\'interfaccia.', 'https://www.ilsoftware.it/gnome-50-migliora-la-gestione-delle-gpu-discrete-vittoria-per-i-sistemi-multi-gpu/', NULL, 'Michele Nasi', '2026-01-29 13:55:09', '2026-01-31 16:41:45', '2026-01-31 16:41:45', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('28', '4', 'Approvato il piano di successione a Linus Torvalds: come può cambiare Linux', 'approvato-il-piano-di-successione-a-linus-torvalds-come-puo-cambiare-linux', 'Il kernel Linux si dota di un piano formale per la successione a Linus Torvalds, affrontando apertamente il rischio del bus factor. Non un cambio di leadership imminente, ma una misura di continuità.', 'https://www.ilsoftware.it/approvato-il-piano-di-successione-a-linus-torvalds-come-puo-cambiare-linux/', NULL, 'Michele Nasi', '2026-01-28 16:38:06', '2026-01-31 16:41:45', '2026-01-31 16:41:45', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('29', '4', 'Altro che Secure Boot: Amutable promette un Linux che può provare di essere integro', 'altro-che-secure-boot-amutable-promette-un-linux-che-puo-provare-di-essere-integro', 'Amutable propone un nuovo modello di sicurezza per Linux basato su integrità verificabile e determinismo, andando oltre Secure Boot. L’obiettivo è rendere lo stato del sistema dimostrabile, dalla build al runtime.', 'https://www.ilsoftware.it/altro-che-secure-boot-amutable-promette-un-linux-che-puo-provare-di-essere-integro/', NULL, 'Michele Nasi', '2026-01-28 15:26:47', '2026-01-31 16:41:45', '2026-01-31 16:41:45', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('30', '4', 'Come provare Adobe Photoshop e Creative Cloud su Linux con Wine', 'come-provare-adobe-photoshop-e-creative-cloud-su-linux-con-wine', 'Con Wine-Staging 11.1 il progetto Wine compie un passo rilevante verso una migliore integrazione dei software Adobe su Linux. Le nuove patch risolvono blocchi storici dell’installer Creative Cloud, consentendo finalmente l’installazione e l’avvio del...', 'https://www.ilsoftware.it/come-provare-adobe-photoshop-e-creative-cloud-su-linux-con-wine/', NULL, 'Michele Nasi', '2026-01-26 16:49:18', '2026-01-31 16:41:45', '2026-01-31 16:41:45', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('31', '4', 'Vulnerabilità critica in telnetd consente accesso root remoto a tutti, dopo 11 anni', 'vulnerabilita-critica-in-telnetd-consente-accesso-root-remoto-a-tutti-dopo-11-anni', 'La vulnerabilità CVE-2026-24061 dimostra come un servizio legacy come telnetd possa ancora consentire l’accesso root remoto senza autenticazione. Un bug rimasto nascosto per 11 anni.', 'https://www.ilsoftware.it/vulnerabilita-critica-in-telnetd-consente-accesso-root-remoto-a-tutti-dopo-11-anni/', NULL, 'Michele Nasi', '2026-01-23 17:31:18', '2026-01-31 16:41:45', '2026-01-31 16:41:45', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('32', '4', 'Wine 11: integrazione profonda con Linux e prestazioni migliorate per desktop e gaming', 'wine-11-integrazione-profonda-con-linux-e-prestazioni-migliorate-per-desktop-e-gaming', 'Wine 11 segna una svolta tecnica con WoW64 unificato, nuove ottimizzazioni nel kernel Linux e un’integrazione avanzata con Wayland e Vulkan, migliorando in modo netto prestazioni, compatibilità ed esperienza desktop e gaming.', 'https://www.ilsoftware.it/wine-11-integrazione-profonda-con-linux-e-prestazioni-migliorate-per-desktop-e-gaming/', NULL, 'Michele Nasi', '2026-01-20 11:29:26', '2026-01-31 16:41:45', '2026-01-31 16:41:45', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('33', '4', 'Questo rootkit Linux è open source (e serve a capire perché i sistemi di difesa falliscono)', 'questo-rootkit-linux-e-open-source-e-serve-a-capire-perche-i-sistemi-di-difesa-falliscono', 'Singularity è un progetto di ricerca che mostra fin dove può spingersi un rootkit Linux moderno una volta compromesso il kernel.', 'https://www.ilsoftware.it/rootkit-linux-singularity/', NULL, 'Michele Nasi', '2026-01-19 14:46:42', '2026-01-31 16:41:45', '2026-01-31 16:41:45', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('34', '4', 'Adobe Photoshop 2025 e 2021 su Linux grazie a una versione modificata di Wine', 'adobe-photoshop-2025-e-2021-su-linux-grazie-a-una-versione-modificata-di-wine', 'Nuove patch per Wine risolvono i problemi dell’installer Adobe Creative Cloud su Linux, rendendo possibile l’installazione di Photoshop e riducendo uno dei principali ostacoli all’adozione di Linux in ambito professionale.', 'https://www.ilsoftware.it/installare-adobe-photoshop-linux/', NULL, 'Michele Nasi', '2026-01-19 09:08:14', '2026-01-31 16:41:45', '2026-01-31 16:41:45', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('35', '4', 'Come installare Windows su Linux nel 2026 ed eseguire i programmi in modo quasi-nativo', 'come-installare-windows-su-linux-nel-2026-ed-eseguire-i-programmi-in-modo-quasi-nativo', 'Approfondiamo il funzionamento del progetto open source che permette di installare Windows su Linux in forma virtualizzata e integrata, rendendo le applicazioni Windows utilizzabili come se fossero native.', 'https://www.ilsoftware.it/focus/installare-windows-su-linux/', NULL, 'Michele Nasi', '2026-01-16 19:20:54', '2026-01-31 16:41:45', '2026-01-31 16:41:45', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('36', '4', 'Un unico file da 13 KB funziona su Windows, Linux e browser Web: qual è il trucco geniale', 'un-unico-file-da-13-kb-funziona-su-windows-linux-e-browser-web-qual-e-il-trucco-geniale', 'Analizza un esperimento tecnico avanzato in cui un singolo file è interpretato in modo diverso da Windows, Linux e dai browser Web, dando vita a tre programmi distinti nello stesso flusso di byte. Così un file poliglotta diventa multipiattaforma.', 'https://www.ilsoftware.it/un-unico-file-da-13-kb-funziona-su-windows-linux-e-browser-web-qual-e-il-trucco-geniale/', NULL, 'Michele Nasi', '2026-01-16 16:20:49', '2026-01-31 16:41:45', '2026-01-31 16:41:45', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('37', '3', 'Il programma bug-bounty del progetto curl è stato sospeso. Il motivo? Esatto, troppe segnalazioni AI', 'il-programma-bug-bounty-del-progetto-curl-e-stato-sospeso-il-motivo-esatto-troppe-segnalazioni-ai', 'Avete presente i programmi bug-bounty destinati a cacciatori non tanto di taglie, quanto di problematiche di sicurezza relative ai vari progetti open-source? Tenendo sempre a mente la regola di Linus Torvalds secondo cui con sufficienti occhi ogni bu...', 'https://www.miamammausalinux.org/2026/01/il-programma-bug-bounty-del-progetto-curl-e-stato-sospeso-il-motivo-esatto-troppe-segnalazioni-ai/', 'https://www.miamammausalinux.org/wp-content/uploads/2021/02/curl-logo-1024x308.png', 'Raoul Scarazzini', '2026-01-30 07:00:00', '2026-01-31 16:41:50', '2026-01-31 16:41:50', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('38', '3', 'Lo Snap Store di Ubuntu ha troppi problemi di sicurezza, il primo dei quali è che vengono ignorati', 'lo-snap-store-di-ubuntu-ha-troppi-problemi-di-sicurezza-il-primo-dei-quali-e-che-vengono-ignorati', 'Installare pacchetti dallo store ufficiale dovrebbe essere un’operazione facile e sicura. Eppure, da un po’ di tempo ormai nello Snap Store cercare una semplice applicazione può trasformarsi in un rischio concreto di attacco informatico. Una nuova on...', 'https://www.miamammausalinux.org/2026/01/lo-snap-store-di-ubuntu-ha-troppi-problemi-di-sicurezza-il-primo-dei-quali-e-che-vengono-ignorati/', 'https://www.miamammausalinux.org/wp-content/uploads/2020/01/distributor-logo-ubuntu-icon.png', 'Patrick Di Fazio', '2026-01-29 07:00:00', '2026-01-31 16:41:50', '2026-01-31 16:41:50', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('39', '3', 'Lieto fine per Alpine Linux: la crisi è stata opportunità per nuovi sponsor e miglior gestione', 'lieto-fine-per-alpine-linux-la-crisi-e-stata-opportunita-per-nuovi-sponsor-e-miglior-gestione', 'Poco meno di un anno fa raccontavamo di come Equinix avesse terminato di essere sponsor di Alpine Linux, una distribuzione Linux semplice, leggera e lineare che funge da base per un’enorme schiera di container. Nel raccontare la disavventura, al cent...', 'https://www.miamammausalinux.org/2026/01/lieto-fine-per-alpine-linux-la-crisi-e-stata-opportunita-per-nuovi-sponsor-e-miglior-gestione/', 'https://www.miamammausalinux.org/wp-content/uploads/2019/05/alpine-linux-logo.jpg', 'Raoul Scarazzini', '2026-01-28 07:00:00', '2026-01-31 16:41:50', '2026-01-31 16:41:50', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('40', '3', 'La versione open-source di MySQL, Oracle e l’interpretazione dei dati: alcuni chiarimenti', 'la-versione-open-source-di-mysql-oracle-e-linterpretazione-dei-dati-alcuni-chiarimenti', 'La scorsa settimana abbiamo pubblicato l’articolo dal titolo “Nessun commit in tre mesi: la versione open-source di MySQL è stata (finalmente) abbandonata da Oracle?” all’interno del quale, prendendo spunto da diverse fonti, analizzavamo la situazion...', 'https://www.miamammausalinux.org/2026/01/la-versione-open-source-di-mysql-oracle-e-linterpretazione-dei-dati-alcuni-chiarimenti/', 'https://www.miamammausalinux.org/wp-content/uploads/2017/11/Oracle-Linux-7.jpg', 'Raoul Scarazzini', '2026-01-27 07:00:00', '2026-01-31 16:41:50', '2026-01-31 16:41:50', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('41', '3', 'Il futuro di Wikipedia? Passa dal supporto (economico) delle aziende che la usano per l’IA', 'il-futuro-di-wikipedia-passa-dal-supporto-economico-delle-aziende-che-la-usano-per-lia', 'Wikipedia, l’enciclopedia libera che ha rivoluzionato la modalità di fruire e fornire conoscenza nel nuovo millennio, nel 2025 ha stabilito, come ha raccontato ARS Technica, un accordo con molte grandi società – tra cui Microsoft, Meta e Amazon – per...', 'https://www.miamammausalinux.org/2026/01/il-futuro-di-wikipedia-passa-dal-supporto-economico-delle-aziende-che-la-usano-per-lia/', 'https://www.miamammausalinux.org/wp-content/uploads/2014/01/wikipedia.png', 'Raoul Scarazzini', '2026-01-26 07:00:00', '2026-01-31 16:41:50', '2026-01-31 16:41:50', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('42', '3', 'La fondazione Python, dopo il rifiuto dei fondi federali, ha ricevuto 1,5 milioni da Anthropic', 'la-fondazione-python-dopo-il-rifiuto-dei-fondi-federali-ha-ricevuto-15-milioni-da-anthropic', 'Lo scorso novembre avevamo riportato una notizia quanto mai particolare: la Python Software Foundation (PSF) si era trovata costretta a rifiutare 1,5 milioni di dollari delle sovvenzioni che aveva richiesto (e che erano stati approvati) alla National...', 'https://www.miamammausalinux.org/2026/01/la-fondazione-python-dopo-il-rifiuto-dei-fondi-federali-ha-ricevuto-15-milioni-da-anthropic/', 'https://www.miamammausalinux.org/wp-content/uploads/2020/05/python_logo-1024x1024.png', 'Raoul Scarazzini', '2026-01-23 07:00:00', '2026-01-31 16:41:50', '2026-01-31 16:41:50', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('43', '3', 'Il progetto Gentoo valuta di migrare i propri mirror da GitHub, stanco di sentirsi forzare all’uso di Copilot', 'il-progetto-gentoo-valuta-di-migrare-i-propri-mirror-da-github-stanco-di-sentirsi-forzare-alluso-di-', 'All’inizio di quest’anno, precisamente il 5 di gennaio, il progetto Gentoo ha pubblicato una lunga retrospettiva sull’anno appena concluso, sempre interessante in quanto si parla di un progetto estremamente longevo, insieme ad una serie di annunci a ...', 'https://www.miamammausalinux.org/2026/01/il-progetto-gentoo-valuta-di-migrare-i-propri-mirror-da-github-stanco-di-sentirsi-forzare-alluso-di-copilot/', 'https://www.miamammausalinux.org/wp-content/uploads/2018/07/gentoo-logo.png', 'Raoul Scarazzini', '2026-01-22 07:00:00', '2026-01-31 16:41:50', '2026-01-31 16:41:50', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('44', '3', 'Nessun commit in tre mesi: la versione open-source di MySQL è stata (finalmente) abbandonata da Oracle?', 'nessun-commit-in-tre-mesi-la-versione-open-source-di-mysql-e-stata-finalmente-abbandonata-da-oracle', 'La storia di MySQL, il “database open-source più popolare al mondo” (almeno secondo il repository GitHub), parte da lontanissimo ed ha attraversato tantissime fasi. Sviluppato nel 1995 come un semplice database open-source, all’inizio degli anni duem...', 'https://www.miamammausalinux.org/2026/01/nessun-commit-in-tre-mesi-la-versione-open-source-di-mysql-e-stata-finalmente-abbandonata-da-oracle/', 'https://www.miamammausalinux.org/wp-content/uploads/2017/11/Oracle-Linux-7.jpg', 'Raoul Scarazzini', '2026-01-21 07:00:00', '2026-01-31 16:41:50', '2026-01-31 16:41:50', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('45', '3', 'VoidLink, un malware Linux basato su un framework inedito, avanzato e difficile da rilevare', 'voidlink-un-malware-linux-basato-su-un-framework-inedito-avanzato-e-difficile-da-rilevare', 'Da qualche tempo emergono segnali di attività insolite nel panorama dei malware. Recentemente è stato individuato un nuovo framework chiamato VoidLink, caratterizzato da funzionalità molto più sofisticate rispetto ai malware tradizionali. I ricercato...', 'https://www.miamammausalinux.org/2026/01/voidlink-un-malware-linux-basato-su-un-framework-inedito-avanzato-e-difficile-da-rilevare/', 'https://www.miamammausalinux.org/wp-content/uploads/2020/11/botnet.jpg', 'Patrick Di Fazio', '2026-01-20 07:00:00', '2026-01-31 16:41:50', '2026-01-31 16:41:50', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('46', '3', 'Vi siete mai chiesti quanto ci mette ad essere identificato un Bug del Kernel Linux? Eccovi la risposta!', 'vi-siete-mai-chiesti-quanto-ci-mette-ad-essere-identificato-un-bug-del-kernel-linux-eccovi-la-rispos', 'Given enough eyeballs, all bugs are shallow Con sufficienti sguardi, tutti i bug emergono È la Linus’s law: l’affermazione del creatore del Kernel Linux secondo il quale non esistono bug impossibili da scovare e, considerato che a parlare è chi gesti...', 'https://www.miamammausalinux.org/2026/01/vi-siete-mai-chiesti-quanto-ci-mette-ad-essere-identificato-un-bug-del-kernel-linux-eccovi-la-risposta/', 'https://www.miamammausalinux.org/wp-content/uploads/2016/11/linux_big-e1559572730939.png', 'Raoul Scarazzini', '2026-01-19 07:00:00', '2026-01-31 16:41:50', '2026-01-31 16:41:50', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('47', '2', 'Recensione NordVPN: sicurezza, velocità e affidabilità', 'recensione-nordvpn-sicurezza-velocita-e-affidabilita', 'NordVPN unisce velocità e sicurezza avanzata grazie al protocollo NordLynx e alla politica no-log certificata. Include funzioni evolute come Threat Protection Pro e Meshnet per una protezione completa e connessioni sicure tra dispositivi\nL\'articolo R...', 'https://www.cybersecurity360.it/cultura-cyber/recensione-nordvpn-sicurezza-velocita-e-affidabilita/', NULL, 'redazione affiliazioni Nextwork360', '2026-01-30 19:30:54', '2026-01-31 16:41:51', '2026-01-31 16:41:51', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('48', '2', 'Guida VPN: come funzionano e quale scegliere per la massima sicurezza online', 'guida-vpn-come-funzionano-e-quale-scegliere-per-la-massima-sicurezza-online', 'Una VPN crea un tunnel criptato che protegge la privacy nascondendo l’indirizzo IP e cifrando il traffico dati garantendo sicurezza e anonimato online. Servizi come NordVPN, Surfshark ed ExpressVPN offrono funzioni avanzate per bypassare restrizioni ...', 'https://www.cybersecurity360.it/cultura-cyber/guida-vpn-come-funziona-e-quale-scegliere/', NULL, 'Redazione Cybersecurity360.it', '2026-01-30 18:13:59', '2026-01-31 16:41:51', '2026-01-31 16:41:51', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('49', '2', 'NIS2 e CdA: governance e obblighi previsti dal D.lgs. 138', 'nis2-e-cda-governance-e-obblighi-previsti-dal-dlgs-138', 'La NIS2 richiede che i CdA si registrino sulla piattaforma e assumano responsabilità formali su governance, budget e training. ACN spiega il fondamento normativo dietro questa scelta\nL\'articolo NIS2 e CdA: governance e obblighi previsti dal D.lgs. 13...', 'https://www.cybersecurity360.it/legal/nis2-e-cda-governance-e-obblighi-previsti-dal-d-lgs-138/', NULL, 'Mattia Lanzarone', '2026-01-30 17:26:43', '2026-01-31 16:41:51', '2026-01-31 16:41:51', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('50', '2', 'La sicurezza cyber non è una one-shot', 'la-sicurezza-cyber-non-e-una-one-shot', 'Affrontare la complessità della sicurezza cyber richiede un approccio che sappia abbandonare l\'illusione che tutto possa risolversi in una logica di one-shot, anche perché le minacce e gli imprevisti tendono ad avere una persistenza decisamente più e...', 'https://www.cybersecurity360.it/cultura-cyber/la-sicurezza-cyber-non-e-una-one-shot/', NULL, 'Redazione Cybersecurity360.it', '2026-01-30 16:00:00', '2026-01-31 16:41:51', '2026-01-31 16:41:51', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('51', '2', 'Malware diffusi su LinkedIn. Cosa sapere e a cosa fare attenzione', 'malware-diffusi-su-linkedin-cosa-sapere-e-a-cosa-fare-attenzione', 'I cyber criminali sfruttano i messaggi privati di LinkedIn per diffondere malware contenuti in finti documenti aziendali dai nomi pertinenti con le aziende e le attività professionali delle vittime designate. Cosa sapere, cosa fare e cosa non fare\nL\'...', 'https://www.cybersecurity360.it/news/malware-linkedin-cosa-sapere/', NULL, 'Giuditta Mosca', '2026-01-30 11:00:00', '2026-01-31 16:41:51', '2026-01-31 16:41:51', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('52', '2', 'ROSI: ecco perché il ritorno sull’investimento cyber non è un esercizio universitario', 'rosi-ecco-perche-il-ritorno-sullinvestimento-cyber-non-e-un-esercizio-universitario', 'Nella cyber security c’è un paradosso: il ROI è comodo, il ROSI sembra un incubo. Ma se fatto bene, il ritorno sull\'investimento in sicurezza diventa un traduttore simultaneo tra linguaggio del rischio e linguaggio del business. Ecco perché\nL\'articol...', 'https://www.cybersecurity360.it/soluzioni-aziendali/rosi-ecco-perche-il-ritorno-sullinvestimento-cyber-non-e-un-esercizio-universitario/', NULL, 'Sandro Sana', '2026-01-30 10:00:00', '2026-01-31 16:41:51', '2026-01-31 16:41:51', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('53', '2', 'La crisi di senso del GDPR: dal mito della privacy al governo del potere informativo', 'la-crisi-di-senso-del-gdpr-dal-mito-della-privacy-al-governo-del-potere-informativo', 'Il GDPR non è una semplice normativa sulla privacy, associata a vincoli, adempimenti e complessità burocratiche, bensì un sistema di protezione delle persone e di legittimazione del potere esercitato attraverso i dati. Ecco come superare questo frain...', 'https://www.cybersecurity360.it/legal/privacy-dati-personali/la-crisi-di-senso-del-gdpr-dal-mito-della-privacy-al-governo-del-potere-informativo/', NULL, 'Giuseppe Alverone', '2026-01-30 09:00:00', '2026-01-31 16:41:51', '2026-01-31 16:41:51', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('54', '2', 'Clawdbot diventa Moltbot: come mitigare il rischio dell’infostealer camuffato da Agentic AI', 'clawdbot-diventa-moltbot-come-mitigare-il-rischio-dellinfostealer-camuffato-da-agentic-ai', 'Le preoccupazioni relative alla sicurezza del nuovo strumento di Agentic AI, precedentemente noto come Clawdbot, permangono, nonostante il rebranding richiesto da Anthropic per motivi legati al marchio registrato. Ecco i principali rischi di un malwa...', 'https://www.cybersecurity360.it/news/clawdbot-diventa-moltbot-linfostealer-camuffato-da-agentic-ai-cambia-pelle-come-proteggersi/', NULL, 'Mirella Castigli', '2026-01-29 16:56:21', '2026-01-31 16:41:51', '2026-01-31 16:41:51', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('55', '2', 'Sicurezza e resilienza: la mitigazione possibile degli eventi “cigno nero”', 'sicurezza-e-resilienza-la-mitigazione-possibile-degli-eventi-cigno-nero', 'Progettare sicurezza e resilienza anche per gli eventi a bassa probabilità e ad alto impatto è ora possibile a costi contenuti. Ecco come\nL\'articolo Sicurezza e resilienza: la mitigazione possibile degli eventi “cigno nero” proviene da Cyber Security...', 'https://www.cybersecurity360.it/nuove-minacce/sicurezza-e-resilienza-la-mitigazione-possibile-degli-eventi-cigno-nero/', NULL, 'Alessia Valentini', '2026-01-29 15:36:13', '2026-01-31 16:41:51', '2026-01-31 16:41:51', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('56', '2', 'Telecamere private: quando la sicurezza diventa sorveglianza illegittima', 'telecamere-private-quando-la-sicurezza-diventa-sorveglianza-illegittima', 'Con il provvedimento del 23 ottobre 2025, il Garante Privacy, intervenendo su un caso di videosorveglianza privata protrattasi per anni, in assenza di un reale presidio giuridico e tecnico, ha ribadito le regole per le telecamere private. Ecco cosa c...', 'https://www.cybersecurity360.it/legal/privacy-dati-personali/telecamere-private-quando-la-sicurezza-diventa-sorveglianza-illegittima/', NULL, 'Stefano Manzelli', '2026-01-29 12:37:08', '2026-01-31 16:41:51', '2026-01-31 16:41:51', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('57', '1', 'Deepfake nudi e pregiudizi, si fa strada l\'IA femminista', 'deepfake-nudi-e-pregiudizi-si-fa-strada-lia-femminista', 'Nascono reti in America Latina e Africa. Esperta, \'tecnologia per trasformare la società\'', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/31/deepfake-nudi-e-pregiudizi-si-fa-strada-lia-femminista_92ee2be8-6fe9-4b0c-a63b-9c2080370d35.html', NULL, NULL, '2026-01-31 14:08:43', '2026-01-31 16:47:45', '2026-01-31 16:47:45', '0');
INSERT INTO `cmsmio_rss_news` VALUES ('58', '1', '\'SpaceX e xAI valutano fusione, rivoluzione nell\'impero di Musk\'', 'spacex-e-xai-valutano-fusione-rivoluzione-nellimpero-di-musk', 'Wsj, alcuni investitori sono stati informati', 'https://www.ansa.it/canale_tecnologia/notizie/tecnologia/2026/01/31/spacex-e-xai-valutano-fusione-rivoluzione-nellimpero-di-musk_87a19bbc-1e0c-4c2a-bf77-2f0214e8d60a.html', NULL, NULL, '2026-01-31 12:12:50', '2026-01-31 16:47:45', '2026-01-31 16:47:45', '0');



-- Struttura tabella `cmsmio_scheduled_tasks`
DROP TABLE IF EXISTS `cmsmio_scheduled_tasks`;
CREATE TABLE `cmsmio_scheduled_tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `scheduled_at` datetime NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `status` enum('pending','running','completed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status_scheduled` (`status`,`scheduled_at`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_scheduled_tasks`
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('1', 'publish_post', '{\"post_id\":true}', '2026-01-18 18:00:00', '2026-01-18 18:09:42', 'completed', NULL, '2026-01-18 19:54:40');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('3', 'publish_post', '{\"post_id\":\"6\"}', '2026-01-18 18:05:00', '2026-01-18 18:09:42', 'completed', NULL, '2026-01-18 20:03:56');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('4', 'publish_post', '{\"post_id\":\"7\"}', '2026-01-19 03:04:00', '2026-01-19 09:30:00', 'completed', NULL, '2026-01-18 21:59:42');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('5', 'publish_post', '{\"post_id\":\"8\"}', '2026-01-20 12:42:00', '2026-01-20 12:43:11', 'completed', NULL, '2026-01-20 14:41:03');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('6', 'publish_post', '{\"post_id\":\"9\"}', '2026-01-20 13:22:00', NULL, 'running', NULL, '2026-01-20 15:20:59');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('7', 'publish_post', '{\"post_id\":\"25\"}', '2026-01-22 10:58:00', NULL, 'running', NULL, '2026-01-22 12:56:24');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('8', 'publish_post', '{\"post_id\":\"25\"}', '2026-01-22 11:07:00', NULL, 'running', NULL, '2026-01-22 13:04:34');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('9', 'publish_post', '{\"post_id\":\"25\"}', '2026-01-22 11:12:00', '2026-01-22 11:23:39', 'completed', NULL, '2026-01-22 13:10:35');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('10', 'publish_post', '{\"post_id\":\"25\"}', '2026-01-22 11:28:00', '2026-01-22 11:28:27', 'completed', NULL, '2026-01-22 13:26:24');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('11', 'publish_post', '{\"post_id\":\"25\"}', '2026-01-22 11:47:00', '2026-01-22 11:47:17', 'failed', 'SQLSTATE[42S02]: Base table or view not found: 1146 Table \'bsalvati_smal.cmsmio_posts23\' doesn\'t exist', '2026-01-22 13:46:06');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('12', 'publish_post', '{\"post_id\":\"25\"}', '2026-01-22 12:08:00', '2026-01-22 12:08:12', 'completed', NULL, '2026-01-22 14:06:35');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('13', 'rss_import', '{\"feed_id\":1}', '2026-01-31 10:01:37', '2026-01-31 10:03:28', 'failed', 'Task type: rss_import', '2026-01-31 10:01:37');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('14', 'rss_import', '{\"feed_id\":2}', '2026-01-31 10:01:51', '2026-01-31 10:03:28', 'failed', 'Task type: rss_import', '2026-01-31 10:01:51');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('15', 'rss_import', '{\"feed_id\":3}', '2026-01-31 10:27:21', '2026-01-31 11:09:59', 'failed', 'Task type: rss_import', '2026-01-31 10:27:21');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('16', 'rss_import', '{\"feed_id\":1}', '2026-01-31 10:28:31', '2026-01-31 11:09:59', 'failed', 'Task type: rss_import', '2026-01-31 10:28:31');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('17', 'rss_import', '{\"feed_id\":2}', '2026-01-31 12:26:20', '2026-01-31 16:42:08', 'failed', 'Task type: rss_import', '2026-01-31 12:26:20');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('18', 'rss_import', '{\"feed_id\":3}', '2026-01-31 12:27:13', '2026-01-31 16:42:08', 'failed', 'Task type: rss_import', '2026-01-31 12:27:13');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('19', 'rss_import', '{\"feed_id\":4}', '2026-01-31 12:27:15', '2026-01-31 16:42:08', 'failed', 'Task type: rss_import', '2026-01-31 12:27:15');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('20', 'rss_import', '{\"feed_id\":1}', '2026-01-31 16:38:08', '2026-01-31 16:42:08', 'failed', 'Task type: rss_import', '2026-01-31 16:38:08');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('21', 'rss_import', '{\"feed_id\":1}', '2026-02-01 09:34:36', '2026-02-01 09:34:50', 'failed', 'Task type: rss_import', '2026-02-01 09:34:36');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('22', 'rss_import', '{\"feed_id\":2}', '2026-02-01 09:34:36', '2026-02-01 09:34:50', 'failed', 'Task type: rss_import', '2026-02-01 09:34:36');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('23', 'rss_import', '{\"feed_id\":3}', '2026-02-01 09:34:36', '2026-02-01 09:34:50', 'failed', 'Task type: rss_import', '2026-02-01 09:34:36');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('24', 'rss_import', '{\"feed_id\":4}', '2026-02-01 09:34:36', '2026-02-01 09:34:50', 'failed', 'Task type: rss_import', '2026-02-01 09:34:36');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('25', 'rss_import', '{\"feed_id\":1}', '2026-02-01 12:35:41', '2026-02-01 12:36:51', 'failed', 'Task type: rss_import', '2026-02-01 12:35:41');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('26', 'rss_import', '{\"feed_id\":2}', '2026-02-01 12:35:41', '2026-02-01 12:36:51', 'failed', 'Task type: rss_import', '2026-02-01 12:35:41');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('27', 'rss_import', '{\"feed_id\":3}', '2026-02-01 12:35:41', '2026-02-01 12:36:51', 'failed', 'Task type: rss_import', '2026-02-01 12:35:41');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('28', 'rss_import', '{\"feed_id\":4}', '2026-02-01 12:35:41', '2026-02-01 12:36:51', 'failed', 'Task type: rss_import', '2026-02-01 12:35:41');



-- Struttura tabella `cmsmio_settings`
DROP TABLE IF EXISTS `cmsmio_settings`;
CREATE TABLE `cmsmio_settings` (
  `setting_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_settings`
INSERT INTO `cmsmio_settings` VALUES ('active_theme', 'aurora');
INSERT INTO `cmsmio_settings` VALUES ('backup_max_limit', '10');
INSERT INTO `cmsmio_settings` VALUES ('cron_last_run', '1769945811');
INSERT INTO `cmsmio_settings` VALUES ('ga_property_id', '521312344');
INSERT INTO `cmsmio_settings` VALUES ('ga_service_account_json', 'ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAibXljbXMtNDg1MzIwIiwKICAicHJpdmF0ZV9rZXlfaWQiOiAiNzQ2N2JhY2E2MzAzMjYyOTc5MWJiMWMzZDM4NWYwMjc5NzVjODk0YSIsCiAgInByaXZhdGVfa2V5IjogIi0tLS0tQkVHSU4gUFJJVkFURSBLRVktLS0tLVxuTUlJRXZRSUJBREFOQmdrcWhraUc5dzBCQVFFRkFBU0NCS2N3Z2dTakFnRUFBb0lCQVFDVjB2alpGOWNjczNvNFxua2hIdDBmT2g1L2M2QnowRjVtWS9CTWkzU0JGTDVibWQyZmV0dHNjeWpQVXBEdUloc2M2Q2x6c0ZzakRUbklKMFxuSzFBYitZL3VNZFpyU0Y2dW1pRnBzWTU3UDBqY2YxcmwyemFGT3k4VXFQUEZ5M2lqNGUwMFdnWHhoaE80bG9NeFxuZGMvWEdGZG40R3hjdEo2alpHWC80eEo1OW8yN3hPMC96bHk4WndjaHJsN0RtREluTGxmT2x4d2FwVDZrbXUzSlxuMkdtRDZQVmNXQndNZVVxZjEwMVVzZUR2KzJSMzhQenVEWE42MXdDa2orWWQyb0J2YStqcVZLVXc5T1JPR2tyRlxuOGhuQU16NmZ2RWNEZTJub0tQMzFGZVMwRTRtNVVHa2JYVGFWbld4dGpOR2o2ckxjb3FvNkdMNVlFbGVxSDZ3cVxuT0lkd0ZUWVJBZ01CQUFFQ2dnRUFBUFUzV0JQNThSb0lwd0h3RVdndE9aVEZGTjdrK2Z1dUhhc0hLWHpSZDMzQlxuZ1F2YXRJYWRvV29iU3lib05UVHcwQTd0ZHF6YitRNWdHeVFId0JZSllJbDdDSGFPL0dqSjIrWWh0aElDZ2o1aVxuTTJRQ1pqbXBwR1VzSWRDYU5kb0U3VGZDSC9ndWVoaWtRQld6dlhsUFhiK21rYlVwNm51QzEyNzVPRVJtUlpRTFxuT29MNFNvWUxWS3ZhUmh0d1pvZmRWbW1DeWNBZWNvVzFEWmJ1QmN1blNwaldJNmhVNVZCamJ5Q1VxVFlVZ295NFxuWjF6ZFdFK0JWN0djd1hHUm4zTFJjdUVyam9DNDRVc1FiVmRqY0JwQVgrbU50NHNIb080RXlUWG1iLys2UC81Q1xudE5CcHhWYUU3eDVBa1dzSU8yN3R4VVEyS0lwTFZXUDRYMEZISTJ5UVVRS0JnUUROQXVPM3lhYmhyeGZwTFh5K1xudUJxQ21Wd2gyb0lwZjE1TG1RSlpNbUlQOC9ZSHhzSlBZNDRrYjg2aldodDRBRkROWHRVTkRSS1JwWmZONVVOblxuMTBaek5BdmZKL2czQnlMaGg0dnZQbzEvdXZidTU4aS9McDdtU29kRFdBZ2t4dDdvK29MTmtjT3hsOCtQSXhobVxud3FQNzV2SmR4cmVLWFpIb1dxay9iNG5MNVFLQmdRQzdGazIrVHMydFFqVlk1UmxPeXl4YU8yN0owdFFsUUFhRVxuRWt3NE15MEhlRERBUkRTQlFaeG9IdXZzYzlGdU9JM2lob0NzbFpLTGx5RmQxcUtwL3lIYlViU21kc3IwSkN5OFxuc3pWc01UQWxjQjRwbldGTlJRN1pOTlIvTk5kUEgxZ0Y0eEpEU1ZNbCtrakJMVnlYbEJlYlErTG85N2o0UXBldlxuZ3NwT1MwUVd2UUtCZ1FDR2w4a00xalRib2VwZXllQkdEZlJKblltaEtDV2dQT0NaWFNEdmttWlM5dVdsZ1ZYOFxuYk8zYStoUjlwaUMyamU1K2hpMzFYWW05V1N2cW53TzczdGQvdHNHOGhpOHRZV0FERk14SUM1YzJMbmNEcmVSYlxucjYzZW56dGllUjhQbGdpdWlCanNBVENySFhIRkZWTmwwNDk1Ujh3Q3lQMml0MytkUGpnelQzVDJDUUtCZ0R4RlxuVml4ZUJNS3hWckJadGdwOVI3K1Y3LzRTN29kRmxoUE1OVVBSc01yOFBIQmtTS252Y2l6VThWcE9nWVRKc1dQdFxud3R4V24weGJGc085VnMvL1FtLytaWDFQUGRqaklvcDBEVnphaDlFM3ltL0xwTUlZNGt5MVNWUGx1UXRqWko4WVxuNVJSS05nQkJIbGtsZlBQVUlMckRad0Y0ZE4xOXpwSWJSNE5ybWZiZEFvR0FlaEY0T0RpcFI3QzMxcTRGM1poaVxuaHBNYjMvSGd2VUZhVC9rQTRiSkhNaytrbGpFREZXUGd3eEhldmhWa24xU0NCQ1JzbkJjTFUzaG4xVGM3R1g5VlxuYzlTcktRb1hpQ1VKajBsSmhiQ1hNZU00Y2tLOHArMm1hZzFIMCswVmlqdDRkMnRWa0h0cWFjaDllS0NqQm1hOVxuUGV6Mi9RYTAzVk13RHVPNVNoYmJVS2c9XG4tLS0tLUVORCBQUklWQVRFIEtFWS0tLS0tXG4iLAogICJjbGllbnRfZW1haWwiOiAiYW5hbHl0aWNzLXJlYWRlckBteWNtcy00ODUzMjAuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJjbGllbnRfaWQiOiAiMTEyNjQ1NzgyMTc1OTQ5MDIyMTE3IiwKICAiYXV0aF91cmkiOiAiaHR0cHM6Ly9hY2NvdW50cy5nb29nbGUuY29tL28vb2F1dGgyL2F1dGgiLAogICJ0b2tlbl91cmkiOiAiaHR0cHM6Ly9vYXV0aDIuZ29vZ2xlYXBpcy5jb20vdG9rZW4iLAogICJhdXRoX3Byb3ZpZGVyX3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vb2F1dGgyL3YxL2NlcnRzIiwKICAiY2xpZW50X3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vcm9ib3QvdjEvbWV0YWRhdGEveDUwOS9hbmFseXRpY3MtcmVhZGVyJTQwbXljbXMtNDg1MzIwLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAidW5pdmVyc2VfZG9tYWluIjogImdvb2dsZWFwaXMuY29tIgp9Cg==');
INSERT INTO `cmsmio_settings` VALUES ('google_analytics', 'G-R22C2G6T0Z');
INSERT INTO `cmsmio_settings` VALUES ('posts_per_page', '10');
INSERT INTO `cmsmio_settings` VALUES ('registrazioni_attive', '0');
INSERT INTO `cmsmio_settings` VALUES ('search_engine_visibility', '1');
INSERT INTO `cmsmio_settings` VALUES ('seo_author', 'smal');
INSERT INTO `cmsmio_settings` VALUES ('seo_keywords', 'cms, php, custom');
INSERT INTO `cmsmio_settings` VALUES ('site_description', 'NexaCMS è un CMS leggero e flessibile per sviluppatori. Core orientato agli oggetti, routing dinamico, sistema di hook estendibile, pannello admin con backup database e gestione completa dei contenuti.');
INSERT INTO `cmsmio_settings` VALUES ('site_favicon', '69771d4e0a00b_1769413966.png');
INSERT INTO `cmsmio_settings` VALUES ('site_logo', '69771d20df05f_1769413920.png');
INSERT INTO `cmsmio_settings` VALUES ('site_motto', 'NexaCMS è un CMS leggero e flessibile per sviluppatori.');
INSERT INTO `cmsmio_settings` VALUES ('site_title', 'Nexa CMS');



-- Struttura tabella `cmsmio_theme_widget_areas`
DROP TABLE IF EXISTS `cmsmio_theme_widget_areas`;
CREATE TABLE `cmsmio_theme_widget_areas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `area_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `widget_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_area` (`area_name`),
  KEY `idx_position` (`position`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_theme_widget_areas`
INSERT INTO `cmsmio_theme_widget_areas` VALUES ('4', 'footer', 'text', '2', '1', '{\"title\":\"Titolo widget Provola\",\"content\":\"Widget di provola\"}');
INSERT INTO `cmsmio_theme_widget_areas` VALUES ('5', 'footer', 'recent_posts', '3', '1', '{\"title\":\"Post Recenti\",\"limit\":5,\"show_date\":false,\"show_excerpt\":false}');
INSERT INTO `cmsmio_theme_widget_areas` VALUES ('6', 'footer', 'menu', '1', '1', '{\"menu_id\":1,\"title\":\"Menu\"}');
INSERT INTO `cmsmio_theme_widget_areas` VALUES ('7', 'sidebar', 'text', '1', '1', '{\"title\":\"Widget Sidebar\",\"content\":\"Testo\"}');
INSERT INTO `cmsmio_theme_widget_areas` VALUES ('8', 'sidebar', 'recent_posts', '2', '1', '{\"title\":\"Post Recenti\",\"limit\":5,\"show_date\":false,\"show_excerpt\":false}');
INSERT INTO `cmsmio_theme_widget_areas` VALUES ('10', 'sidebarpost', 'text', '7', '1', '{\"title\":\"Proviamo\",\"content\":\"qwery\"}');
INSERT INTO `cmsmio_theme_widget_areas` VALUES ('12', 'sidebar', 'auth', '3', '1', '{\"title\":\"Area Utente\"}');
INSERT INTO `cmsmio_theme_widget_areas` VALUES ('13', 'sidebarpost', 'auth', '8', '1', '{\"title\":\"Area Utente\"}');



-- Struttura tabella `cmsmio_uploads`
DROP TABLE IF EXISTS `cmsmio_uploads`;
CREATE TABLE `cmsmio_uploads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int NOT NULL,
  `uploaded_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uploaded_by` (`uploaded_by`),
  CONSTRAINT `cmsmio_uploads_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `cmsmio_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_uploads`
INSERT INTO `cmsmio_uploads` VALUES ('22', '69734fe60bac0_1769164774.png', 'Copilot_20251210_122109 (Custom).png', 'image/png', '341495', '1', '2026-01-23 13:39:34', NULL);
INSERT INTO `cmsmio_uploads` VALUES ('23', '6973b1ec654bf_1769189868.png', 'nuvola.png', 'image/png', '262892', '1', '2026-01-23 20:37:48', NULL);
INSERT INTO `cmsmio_uploads` VALUES ('24', '6973b2216f855_1769189921.png', 'nuvola.png', 'image/png', '47882', '1', '2026-01-23 20:38:41', NULL);
INSERT INTO `cmsmio_uploads` VALUES ('25', '69771d20df05f_1769413920.png', 'logo.png', 'image/png', '295714', '1', '2026-01-26 10:52:00', NULL);
INSERT INTO `cmsmio_uploads` VALUES ('26', '69771d4e0a00b_1769413966.png', 'favicon.png', 'image/png', '55944', '1', '2026-01-26 10:52:46', NULL);



-- Struttura tabella `cmsmio_users`
DROP TABLE IF EXISTS `cmsmio_users`;
CREATE TABLE `cmsmio_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('amministratore','gestore','registrato') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'registrato',
  `is_active` tinyint(1) DEFAULT '1',
  `activation_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `idx_name_unique` (`name`),
  KEY `idx_email` (`email`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_users`
INSERT INTO `cmsmio_users` VALUES ('1', 'smal', 'b25fngur0@mozmail.com', '$2y$10$/0WZnJFWO1bed8sSHsPMouAp7D7Y7KdVTpBJdIbEpemt9r4BFiF5K', '7bdf6c5a196c519d80116ff9fec716911745ad9697b71638881dc126416f4aec', 'amministratore', '1', NULL, NULL, NULL, '2026-02-01 09:34:40', '2026-01-17 19:17:11');
INSERT INTO `cmsmio_users` VALUES ('2', 'toto', 'smaldiscord@gmail.com', '$2y$10$G2R5KrkiYIWjNoBiNXH0uuq6eJaC.yH7tMzaMgOAFwOGJ6lNR3LBS', NULL, 'gestore', '1', NULL, NULL, NULL, '2026-01-17 17:42:10', '2026-01-17 19:40:42');

