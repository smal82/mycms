-- Backup Database MyCMS
-- Data: 2026-02-07 12:43:45

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
INSERT INTO `cmsmio_custom_post_types` VALUES ('1', 'portfolio', 'Progetto', 'Portfolio', 'portfolio', 'Gestisci progetti del portfolio', 'briefcase', '[\"title\", \"content\", \"featured_image\"]', '5', '1', '1', '1', '0', 'portfolio', '2026-01-19 18:17:12', '2026-02-04 17:07:51');
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_menu_items`
INSERT INTO `cmsmio_menu_items` VALUES ('1', '1', NULL, 'Home', '/', '_self', '0');
INSERT INTO `cmsmio_menu_items` VALUES ('3', '1', NULL, 'Blog', '/blog', '_self', '8');
INSERT INTO `cmsmio_menu_items` VALUES ('11', '1', NULL, '                                Chi siamo                            ', '/page/chi-siamo', '_self', '6');
INSERT INTO `cmsmio_menu_items` VALUES ('18', '1', '1', '                                Prova separazioni                            ', '/page/prova-separazioni', '_self', '1');
INSERT INTO `cmsmio_menu_items` VALUES ('19', '1', '1', '                                Pagina di prova                            ', '/page/pagina-di-prova', '_self', '2');
INSERT INTO `cmsmio_menu_items` VALUES ('20', '1', '1', '                                Chi siamo                            ', '/page/chi-siamo', '_self', '3');
INSERT INTO `cmsmio_menu_items` VALUES ('21', '1', '11', '                                Eccoci                            ', '/page/eccoci', '_self', '7');
INSERT INTO `cmsmio_menu_items` VALUES ('23', '1', '24', 'Portfolio', '/portfolio', '_self', '0');
INSERT INTO `cmsmio_menu_items` VALUES ('24', '1', NULL, 'CPT', '#', '_self', '4');
INSERT INTO `cmsmio_menu_items` VALUES ('25', '1', '24', 'Testimonianze', '/testimonianze', '_self', '9');



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
) ENGINE=InnoDB AUTO_INCREMENT=622 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
INSERT INTO `cmsmio_page_visits` VALUES ('529', '/', 'Nexa CMS', '', '2026-02-01', '2026-02-01 21:21:13', '1280', '720', '18.206.234.104', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/138.0.7204.23 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('530', '/', 'Nexa CMS', '', '2026-02-02', '2026-02-02 17:52:22', '800', '600', '205.169.39.26', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/127.0.6533.119 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('531', '/', 'Nexa CMS', '', '2026-02-02', '2026-02-02 17:56:11', '800', '600', '205.169.39.11', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.5938.132 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('532', '/', 'Nexa CMS', '', '2026-02-02', '2026-02-02 18:38:45', '270', '600', '176.201.246.63', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('533', '/', 'Nexa CMS', '', '2026-02-02', '2026-02-02 18:45:20', '270', '600', '176.201.246.63', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('534', '/portfolio', 'Portfolio - Nexa CMS', '', '2026-02-04', '2026-02-04 17:40:23', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('535', '/portfolio', 'Portfolio - Nexa CMS', '', '2026-02-04', '2026-02-04 17:40:55', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('536', '/portfolio', 'Portfolio - Nexa CMS', '', '2026-02-04', '2026-02-04 17:41:44', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('537', '/portfolio', 'Portfolio - Nexa CMS', '', '2026-02-04', '2026-02-04 17:41:47', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('538', '/portfolio', 'Portfolio - Nexa CMS', '', '2026-02-04', '2026-02-04 17:42:05', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('539', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 17:42:10', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('540', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 17:42:27', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('541', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 17:42:49', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('542', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 17:43:25', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('543', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 17:44:12', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('544', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 17:44:21', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('545', '/page/chi-siamo', 'Chi siamo - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 17:44:25', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('546', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/page/chi-siamo', '2026-02-04', '2026-02-04 20:09:44', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('547', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:10:00', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('548', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:11:24', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('549', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:12:31', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('550', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:20:32', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('551', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 20:25:22', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('552', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:25:24', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('553', '/page/chi-siamo', 'Chi siamo - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-02-04', '2026-02-04 20:25:32', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('554', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/page/chi-siamo', '2026-02-04', '2026-02-04 20:25:41', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('555', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:25:42', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('556', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:26:17', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('557', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:27:25', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('558', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:27:26', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('559', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:27:27', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('560', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:29:12', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('561', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:32:25', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('562', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 20:35:04', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('563', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-02-04', '2026-02-04 20:35:06', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('564', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:35:07', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('565', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-02-04', '2026-02-04 20:43:01', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('566', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 20:43:03', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('567', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-02-04', '2026-02-04 20:43:06', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('568', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:43:10', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('569', '/page/chi-siamo', 'Chi siamo - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 20:43:18', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('570', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:43:20', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('571', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-02-04', '2026-02-04 20:43:23', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('572', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:43:27', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('573', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:48:11', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('574', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 20:48:16', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('575', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:48:18', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('576', '/page/chi-siamo', 'Chi siamo - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 20:48:28', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('577', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/page/chi-siamo', '2026-02-04', '2026-02-04 20:48:30', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('578', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/page/chi-siamo', '2026-02-04', '2026-02-04 20:49:14', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('579', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:49:19', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('580', '/page/chi-siamo', 'Chi siamo - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 20:49:22', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('581', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/page/chi-siamo', '2026-02-04', '2026-02-04 20:49:24', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('582', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/page/chi-siamo', '2026-02-04', '2026-02-04 20:49:46', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('583', '/page/chi-siamo', 'Chi siamo - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:49:48', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('584', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/page/chi-siamo', '2026-02-04', '2026-02-04 20:49:49', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('585', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/page/chi-siamo', '2026-02-04', '2026-02-04 20:50:56', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('586', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:51:00', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('587', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:51:02', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('588', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:51:03', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('589', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 20:51:07', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('590', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:51:11', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('591', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:51:28', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('592', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 20:51:34', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('593', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 20:52:10', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('594', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 20:54:04', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('595', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 20:54:21', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('596', '/post/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 21:09:32', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('597', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/post/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 21:09:40', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('598', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-04', '2026-02-04 21:09:42', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('599', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-04', '2026-02-04 21:09:50', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('600', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-02-04', '2026-02-04 21:17:50', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('601', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog', '2026-02-04', '2026-02-04 21:17:56', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('602', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog?page=5', '2026-02-04', '2026-02-04 21:18:06', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('603', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog?page=5', '2026-02-04', '2026-02-04 21:18:11', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('604', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog?page=1', '2026-02-04', '2026-02-04 21:18:15', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('605', '/blog', 'Blog - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog?page=1', '2026-02-04', '2026-02-04 21:18:17', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('606', '/page/chi-siamo', 'Chi siamo - Nexa CMS', 'https://mycms.salvatoremaltese.it/blog?page=5', '2026-02-04', '2026-02-04 21:18:29', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('607', '/post/post-programmato-2', 'Post programmato 2 - Nexa CMS', 'https://mycms.salvatoremaltese.it/page/chi-siamo', '2026-02-04', '2026-02-04 21:18:32', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('608', '/', 'Nexa CMS', '', '2026-02-05', '2026-02-05 11:32:24', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('609', '/portfolio', 'Portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/', '2026-02-05', '2026-02-05 11:32:29', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('610', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Nexa CMS', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-05', '2026-02-05 11:32:35', '270', '600', '93.44.195.238', 'Mozilla/5.0 (Android 12; Mobile; rv:147.0) Gecko/147.0 Firefox/147.0');
INSERT INTO `cmsmio_page_visits` VALUES ('611', '/', 'Hesperos', '', '2026-02-05', '2026-02-05 13:40:44', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('612', '/page/chi-siamo', 'Chi siamo - Hesperos', 'https://mycms.salvatoremaltese.it/', '2026-02-05', '2026-02-05 13:40:48', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('613', '/blog', 'Blog - Hesperos', 'https://mycms.salvatoremaltese.it/page/chi-siamo', '2026-02-05', '2026-02-05 13:40:49', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('614', '/blog', 'Blog - Hesperos', 'https://mycms.salvatoremaltese.it/page/chi-siamo', '2026-02-05', '2026-02-05 13:41:45', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('615', '/page/eccoci', 'Eccoci - Hesperos', 'https://mycms.salvatoremaltese.it/blog', '2026-02-05', '2026-02-05 13:41:50', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('616', '/post/post-programmato-2', 'Post programmato 2 - Hesperos', 'https://mycms.salvatoremaltese.it/page/eccoci', '2026-02-05', '2026-02-05 13:41:54', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('617', '/portfolio', 'Portfolio - Hesperos', 'https://mycms.salvatoremaltese.it/post/post-programmato-2', '2026-02-05', '2026-02-05 13:41:58', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('618', '/portfolio/primo-progetto-del-portfolio', 'Primo progetto del portfolio - Hesperos', 'https://mycms.salvatoremaltese.it/portfolio', '2026-02-05', '2026-02-05 13:41:59', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('619', '/page/prova-separazioni', 'Prova separazioni - Hesperos', 'https://mycms.salvatoremaltese.it/portfolio/primo-progetto-del-portfolio', '2026-02-05', '2026-02-05 13:42:01', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('620', '/', 'Hesperos', 'https://mycms.salvatoremaltese.it/page/prova-separazioni', '2026-02-05', '2026-02-05 13:42:03', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');
INSERT INTO `cmsmio_page_visits` VALUES ('621', '/prova-separazioni', 'Prova separazioni - Hesperos', '', '2026-02-05', '2026-02-05 14:39:16', '1366', '768', '93.44.195.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36');



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
INSERT INTO `cmsmio_post_categories` VALUES ('95', '3');
INSERT INTO `cmsmio_post_categories` VALUES ('96', '3');
INSERT INTO `cmsmio_post_categories` VALUES ('97', '3');



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
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
INSERT INTO `cmsmio_posts` VALUES ('95', 'post', 'Post programmato 1', 'post-programmato-1', '<p>Provola</p>', 'prova', '69771d20df05f_1769413920.png', '1', 'pubblicato', '2026-02-01 19:26:08', '2026-02-01 19:28:30', NULL);
INSERT INTO `cmsmio_posts` VALUES ('96', 'post', 'Post programmato 2', 'post-programmato-2', '<p>ciao</p>', 'ciao', NULL, '1', 'pubblicato', '2026-02-02 10:10:50', '2026-02-02 11:28:16', NULL);
INSERT INTO `cmsmio_posts` VALUES ('97', 'post', 'Strano', 'strano', '<p>orario</p>', 'strano', NULL, '1', 'pubblicato', '2026-02-02 16:58:17', '2026-02-02 17:31:30', NULL);
INSERT INTO `cmsmio_posts` VALUES ('99', 'portfolio', 'Primo progetto del portfolio', 'primo-progetto-del-portfolio', 'prova progetto', '', '', '1', 'pubblicato', '2026-02-05 10:18:27', '2026-02-05 10:18:27', NULL);
INSERT INTO `cmsmio_posts` VALUES ('100', 'testimonial', 'Prima testimonianza', 'prima-testimonianza', 'contenuto', '', NULL, '1', 'pubblicato', '2026-02-05 16:28:19', '2026-02-05 16:28:19', NULL);



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
) ENGINE=InnoDB AUTO_INCREMENT=212 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_scheduled_tasks`
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('1', 'publish_post', '{\"post_id\":true}', '2026-01-18 18:00:00', '2026-01-18 18:09:42', 'completed', NULL, '2026-01-18 19:54:40');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('3', 'publish_post', '{\"post_id\":\"6\"}', '2026-01-18 18:05:00', '2026-01-18 18:09:42', 'completed', NULL, '2026-01-18 20:03:56');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('4', 'publish_post', '{\"post_id\":\"7\"}', '2026-01-19 03:04:00', '2026-01-19 09:30:00', 'completed', NULL, '2026-01-18 21:59:42');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('5', 'publish_post', '{\"post_id\":\"8\"}', '2026-01-20 12:42:00', '2026-01-20 12:43:11', 'completed', NULL, '2026-01-20 14:41:03');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('9', 'publish_post', '{\"post_id\":\"25\"}', '2026-01-22 11:12:00', '2026-01-22 11:23:39', 'completed', NULL, '2026-01-22 13:10:35');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('10', 'publish_post', '{\"post_id\":\"25\"}', '2026-01-22 11:28:00', '2026-01-22 11:28:27', 'completed', NULL, '2026-01-22 13:26:24');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('11', 'publish_post', '{\"post_id\":\"25\"}', '2026-01-22 11:47:00', '2026-01-22 11:47:17', 'failed', 'SQLSTATE[42S02]: Base table or view not found: 1146 Table \'bsalvati_smal.cmsmio_posts23\' doesn\'t exist', '2026-01-22 13:46:06');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('12', 'publish_post', '{\"post_id\":\"25\"}', '2026-01-22 12:08:00', '2026-01-22 12:08:12', 'completed', NULL, '2026-01-22 14:06:35');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('33', 'publish_post', '{\"post_id\":\"95\"}', '2026-02-01 19:27:00', '2026-02-01 19:28:30', 'completed', NULL, '2026-02-01 19:26:08');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('42', 'publish_post', '{\"post_id\":\"96\"}', '2026-02-02 11:12:00', '2026-02-02 11:28:16', 'completed', NULL, '2026-02-02 10:10:50');
INSERT INTO `cmsmio_scheduled_tasks` VALUES ('51', 'publish_post', '{\"post_id\":\"97\"}', '2026-02-02 17:10:00', '2026-02-02 17:31:30', 'completed', NULL, '2026-02-02 16:58:17');



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
INSERT INTO `cmsmio_settings` VALUES ('cron_last_run', '1770464471');
INSERT INTO `cmsmio_settings` VALUES ('ga_property_id', '521312344');
INSERT INTO `cmsmio_settings` VALUES ('ga_service_account_json', 'ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAibXljbXMtNDg1MzIwIiwKICAicHJpdmF0ZV9rZXlfaWQiOiAiNzQ2N2JhY2E2MzAzMjYyOTc5MWJiMWMzZDM4NWYwMjc5NzVjODk0YSIsCiAgInByaXZhdGVfa2V5IjogIi0tLS0tQkVHSU4gUFJJVkFURSBLRVktLS0tLVxuTUlJRXZRSUJBREFOQmdrcWhraUc5dzBCQVFFRkFBU0NCS2N3Z2dTakFnRUFBb0lCQVFDVjB2alpGOWNjczNvNFxua2hIdDBmT2g1L2M2QnowRjVtWS9CTWkzU0JGTDVibWQyZmV0dHNjeWpQVXBEdUloc2M2Q2x6c0ZzakRUbklKMFxuSzFBYitZL3VNZFpyU0Y2dW1pRnBzWTU3UDBqY2YxcmwyemFGT3k4VXFQUEZ5M2lqNGUwMFdnWHhoaE80bG9NeFxuZGMvWEdGZG40R3hjdEo2alpHWC80eEo1OW8yN3hPMC96bHk4WndjaHJsN0RtREluTGxmT2x4d2FwVDZrbXUzSlxuMkdtRDZQVmNXQndNZVVxZjEwMVVzZUR2KzJSMzhQenVEWE42MXdDa2orWWQyb0J2YStqcVZLVXc5T1JPR2tyRlxuOGhuQU16NmZ2RWNEZTJub0tQMzFGZVMwRTRtNVVHa2JYVGFWbld4dGpOR2o2ckxjb3FvNkdMNVlFbGVxSDZ3cVxuT0lkd0ZUWVJBZ01CQUFFQ2dnRUFBUFUzV0JQNThSb0lwd0h3RVdndE9aVEZGTjdrK2Z1dUhhc0hLWHpSZDMzQlxuZ1F2YXRJYWRvV29iU3lib05UVHcwQTd0ZHF6YitRNWdHeVFId0JZSllJbDdDSGFPL0dqSjIrWWh0aElDZ2o1aVxuTTJRQ1pqbXBwR1VzSWRDYU5kb0U3VGZDSC9ndWVoaWtRQld6dlhsUFhiK21rYlVwNm51QzEyNzVPRVJtUlpRTFxuT29MNFNvWUxWS3ZhUmh0d1pvZmRWbW1DeWNBZWNvVzFEWmJ1QmN1blNwaldJNmhVNVZCamJ5Q1VxVFlVZ295NFxuWjF6ZFdFK0JWN0djd1hHUm4zTFJjdUVyam9DNDRVc1FiVmRqY0JwQVgrbU50NHNIb080RXlUWG1iLys2UC81Q1xudE5CcHhWYUU3eDVBa1dzSU8yN3R4VVEyS0lwTFZXUDRYMEZISTJ5UVVRS0JnUUROQXVPM3lhYmhyeGZwTFh5K1xudUJxQ21Wd2gyb0lwZjE1TG1RSlpNbUlQOC9ZSHhzSlBZNDRrYjg2aldodDRBRkROWHRVTkRSS1JwWmZONVVOblxuMTBaek5BdmZKL2czQnlMaGg0dnZQbzEvdXZidTU4aS9McDdtU29kRFdBZ2t4dDdvK29MTmtjT3hsOCtQSXhobVxud3FQNzV2SmR4cmVLWFpIb1dxay9iNG5MNVFLQmdRQzdGazIrVHMydFFqVlk1UmxPeXl4YU8yN0owdFFsUUFhRVxuRWt3NE15MEhlRERBUkRTQlFaeG9IdXZzYzlGdU9JM2lob0NzbFpLTGx5RmQxcUtwL3lIYlViU21kc3IwSkN5OFxuc3pWc01UQWxjQjRwbldGTlJRN1pOTlIvTk5kUEgxZ0Y0eEpEU1ZNbCtrakJMVnlYbEJlYlErTG85N2o0UXBldlxuZ3NwT1MwUVd2UUtCZ1FDR2w4a00xalRib2VwZXllQkdEZlJKblltaEtDV2dQT0NaWFNEdmttWlM5dVdsZ1ZYOFxuYk8zYStoUjlwaUMyamU1K2hpMzFYWW05V1N2cW53TzczdGQvdHNHOGhpOHRZV0FERk14SUM1YzJMbmNEcmVSYlxucjYzZW56dGllUjhQbGdpdWlCanNBVENySFhIRkZWTmwwNDk1Ujh3Q3lQMml0MytkUGpnelQzVDJDUUtCZ0R4RlxuVml4ZUJNS3hWckJadGdwOVI3K1Y3LzRTN29kRmxoUE1OVVBSc01yOFBIQmtTS252Y2l6VThWcE9nWVRKc1dQdFxud3R4V24weGJGc085VnMvL1FtLytaWDFQUGRqaklvcDBEVnphaDlFM3ltL0xwTUlZNGt5MVNWUGx1UXRqWko4WVxuNVJSS05nQkJIbGtsZlBQVUlMckRad0Y0ZE4xOXpwSWJSNE5ybWZiZEFvR0FlaEY0T0RpcFI3QzMxcTRGM1poaVxuaHBNYjMvSGd2VUZhVC9rQTRiSkhNaytrbGpFREZXUGd3eEhldmhWa24xU0NCQ1JzbkJjTFUzaG4xVGM3R1g5VlxuYzlTcktRb1hpQ1VKajBsSmhiQ1hNZU00Y2tLOHArMm1hZzFIMCswVmlqdDRkMnRWa0h0cWFjaDllS0NqQm1hOVxuUGV6Mi9RYTAzVk13RHVPNVNoYmJVS2c9XG4tLS0tLUVORCBQUklWQVRFIEtFWS0tLS0tXG4iLAogICJjbGllbnRfZW1haWwiOiAiYW5hbHl0aWNzLXJlYWRlckBteWNtcy00ODUzMjAuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJjbGllbnRfaWQiOiAiMTEyNjQ1NzgyMTc1OTQ5MDIyMTE3IiwKICAiYXV0aF91cmkiOiAiaHR0cHM6Ly9hY2NvdW50cy5nb29nbGUuY29tL28vb2F1dGgyL2F1dGgiLAogICJ0b2tlbl91cmkiOiAiaHR0cHM6Ly9vYXV0aDIuZ29vZ2xlYXBpcy5jb20vdG9rZW4iLAogICJhdXRoX3Byb3ZpZGVyX3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vb2F1dGgyL3YxL2NlcnRzIiwKICAiY2xpZW50X3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vcm9ib3QvdjEvbWV0YWRhdGEveDUwOS9hbmFseXRpY3MtcmVhZGVyJTQwbXljbXMtNDg1MzIwLmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAidW5pdmVyc2VfZG9tYWluIjogImdvb2dsZWFwaXMuY29tIgp9Cg==');
INSERT INTO `cmsmio_settings` VALUES ('google_analytics', 'G-R22C2G6T0Z');
INSERT INTO `cmsmio_settings` VALUES ('posts_per_page', '10');
INSERT INTO `cmsmio_settings` VALUES ('registrazioni_attive', '0');
INSERT INTO `cmsmio_settings` VALUES ('search_engine_visibility', '1');
INSERT INTO `cmsmio_settings` VALUES ('seo_author', 'smal');
INSERT INTO `cmsmio_settings` VALUES ('seo_keywords', 'Hesperos, Hesperos cms, aurora, cms, php, custom');
INSERT INTO `cmsmio_settings` VALUES ('site_description', 'Hesperos è un CMS leggero e flessibile per sviluppatori. Core orientato agli oggetti, routing dinamico, sistema di hook estendibile, pannello admin con backup database e gestione completa dei contenuti.');
INSERT INTO `cmsmio_settings` VALUES ('site_favicon', '69848a23c979a_1770293795.png');
INSERT INTO `cmsmio_settings` VALUES ('site_logo', '69848a2a07fe1_1770293802.png');
INSERT INTO `cmsmio_settings` VALUES ('site_motto', 'Hesperos è un CMS leggero e flessibile per sviluppatori.');
INSERT INTO `cmsmio_settings` VALUES ('site_title', 'Hesperos');



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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dati tabella `cmsmio_uploads`
INSERT INTO `cmsmio_uploads` VALUES ('22', '69734fe60bac0_1769164774.png', 'Copilot_20251210_122109 (Custom).png', 'image/png', '341495', '1', '2026-01-23 13:39:34', NULL);
INSERT INTO `cmsmio_uploads` VALUES ('23', '6973b1ec654bf_1769189868.png', 'nuvola.png', 'image/png', '262892', '1', '2026-01-23 20:37:48', NULL);
INSERT INTO `cmsmio_uploads` VALUES ('24', '6973b2216f855_1769189921.png', 'nuvola.png', 'image/png', '47882', '1', '2026-01-23 20:38:41', NULL);
INSERT INTO `cmsmio_uploads` VALUES ('25', '69771d20df05f_1769413920.png', 'logo.png', 'image/png', '295714', '1', '2026-01-26 10:52:00', NULL);
INSERT INTO `cmsmio_uploads` VALUES ('26', '69771d4e0a00b_1769413966.png', 'favicon.png', 'image/png', '55944', '1', '2026-01-26 10:52:46', NULL);
INSERT INTO `cmsmio_uploads` VALUES ('27', '69848a1c6ff3a_1770293788.png', 'favicon2.png', 'image/png', '90607', '1', '2026-02-05 13:16:28', NULL);
INSERT INTO `cmsmio_uploads` VALUES ('28', '69848a23c979a_1770293795.png', 'favicon2b.png', 'image/png', '54297', '1', '2026-02-05 13:16:35', NULL);
INSERT INTO `cmsmio_uploads` VALUES ('29', '69848a2a07fe1_1770293802.png', 'nome2.png', 'image/png', '505291', '1', '2026-02-05 13:16:42', NULL);



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
INSERT INTO `cmsmio_users` VALUES ('1', 'smal', 'b25fngur0@mozmail.com', '$2y$10$/0WZnJFWO1bed8sSHsPMouAp7D7Y7KdVTpBJdIbEpemt9r4BFiF5K', '892c030194543021f5fb074f3846990d11d471e7ca1a9791c862df393557f781', 'amministratore', '1', NULL, NULL, NULL, '2026-02-07 11:45:27', '2026-01-17 19:17:11');
INSERT INTO `cmsmio_users` VALUES ('2', 'toto', 'smaldiscord@gmail.com', '$2y$10$G2R5KrkiYIWjNoBiNXH0uuq6eJaC.yH7tMzaMgOAFwOGJ6lNR3LBS', NULL, 'gestore', '1', NULL, NULL, NULL, '2026-01-17 17:42:10', '2026-01-17 19:40:42');

