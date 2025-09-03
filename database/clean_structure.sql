-- MenooElo - Struttura Database Pulita
-- Generata il 2025-09-03 dopo audit delle impostazioni
-- Contiene solo funzionalità implementate

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Database
CREATE DATABASE IF NOT EXISTS menooelo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE menooelo;

-- Tabella Super Admin
DROP TABLE IF EXISTS `super_admins`;
CREATE TABLE `super_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabella Ristoranti (Multi-Tenant)
DROP TABLE IF EXISTS `restaurants`;
CREATE TABLE `restaurants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `cover_image_url` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `social_facebook` varchar(255) DEFAULT NULL,
  `social_instagram` varchar(255) DEFAULT NULL,
  `opening_hours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`opening_hours`)),
  `theme_color` varchar(7) DEFAULT '#3273dc',
  `currency` varchar(3) DEFAULT 'EUR',
  `is_active` tinyint(1) DEFAULT 1,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Moduli attivi: {"menu": true, "orders": false, "qrcode": true}' CHECK (json_valid(`features`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `primary_font` varchar(100) DEFAULT 'Inter',
  `custom_font_name` varchar(255) DEFAULT NULL,
  `custom_font_path` varchar(255) DEFAULT NULL,
  `font_weights` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`font_weights`)),
  `theme_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`theme_settings`)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabella Admin Ristoranti
DROP TABLE IF EXISTS `restaurant_admins`;
CREATE TABLE `restaurant_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('owner','manager','staff') DEFAULT 'staff',
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Permessi specifici' CHECK (json_valid(`permissions`)),
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_restaurant_username` (`restaurant_id`,`username`),
  UNIQUE KEY `unique_restaurant_email` (`restaurant_id`,`email`),
  KEY `idx_restaurant_active` (`restaurant_id`,`is_active`),
  CONSTRAINT `restaurant_admins_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabella Categorie Menu
DROP TABLE IF EXISTS `menu_categories`;
CREATE TABLE `menu_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_restaurant_active` (`restaurant_id`,`is_active`),
  KEY `idx_sort_order` (`restaurant_id`,`sort_order`),
  CONSTRAINT `menu_categories_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabella Prodotti/Piatti
DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `ingredients` text DEFAULT NULL,
  `allergens` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Lista allergeni' CHECK (json_valid(`allergens`)),
  `nutritional_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Valori nutrizionali' CHECK (json_valid(`nutritional_info`)),
  `is_available` tinyint(1) DEFAULT 1,
  `is_featured` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_restaurant_category` (`restaurant_id`,`category_id`),
  KEY `idx_featured` (`restaurant_id`,`is_featured`),
  KEY `idx_available` (`restaurant_id`,`is_available`),
  KEY `idx_sort_order` (`category_id`,`sort_order`),
  CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_items_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabella Varianti Prodotti (es. piccola/media/grande)
DROP TABLE IF EXISTS `menu_item_variants`;
CREATE TABLE `menu_item_variants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price_modifier` decimal(10,2) DEFAULT 0.00,
  `is_default` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_item_default` (`item_id`,`is_default`),
  CONSTRAINT `menu_item_variants_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabella Extra/Aggiunte
DROP TABLE IF EXISTS `menu_item_extras`;
CREATE TABLE `menu_item_extras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `is_available` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_item_available` (`item_id`,`is_available`),
  CONSTRAINT `menu_item_extras_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabella Impostazioni Sistema (Solo Implementate)
DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabella Log Attività
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` enum('super_admin','restaurant_admin') NOT NULL,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_type`,`user_id`),
  KEY `idx_restaurant` (`restaurant_id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserimento Dati Iniziali

-- Super Admin di default (password: password - da cambiare!)
INSERT INTO super_admins (username, email, password_hash, full_name) VALUES 
('superadmin', 'admin@menooelo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator');

-- Impostazioni Sistema (Solo Funzionalità Implementate)
INSERT INTO system_settings (setting_key, setting_value, description) VALUES
-- Impostazioni Generali
('app_name', 'MenooElo', 'Nome dell\'applicazione'),
('app_url', 'http://localhost/menooelo', 'URL base dell\'applicazione'),
('timezone', 'Europe/Rome', 'Fuso orario del sistema'),
('default_language', 'it', 'Lingua predefinita'),
('default_currency', 'EUR', 'Valuta predefinita'),
('app_description', 'Sistema di gestione menu digitali per ristoranti', 'Descrizione dell\'applicazione'),
('maintenance_mode', '0', 'Modalità manutenzione (0=off, 1=on)'),
('registration_enabled', '1', 'Registrazione nuovi ristoranti abilitata'),

-- Impostazioni Sicurezza (Base)
('session_timeout', '3600', 'Durata sessione in secondi'),
('password_cost', '12', 'Costo hash password bcrypt'),
('min_password_length', '8', 'Lunghezza minima password'),
('require_password_complexity', '0', 'Richiedi password complesse (maiuscole, numeri, simboli)'),
('max_login_attempts', '5', 'Numero massimo tentativi login'),
('ip_block_duration', '15', 'Durata blocco IP in minuti dopo tentativi falliti'),
('force_https', '0', 'Forza connessioni HTTPS'),
('log_failed_logins', '1', 'Log tentativi login falliti'),
('cookie_secure', '0', 'Cookie sicuri (solo HTTPS)'),
('two_factor_enabled', '0', 'Abilita autenticazione a due fattori'),

-- Impostazioni Upload (Implementate)
('max_image_size', '5242880', 'Dimensione max immagini in bytes (5MB)'),
('max_font_size', '2097152', 'Dimensione max font in bytes (2MB)'),
('allowed_image_formats', 'jpg,jpeg,png,webp', 'Formati immagini consentiti'),
('allowed_font_formats', 'ttf,otf,woff,woff2', 'Formati font consentiti'),
('auto_image_optimization', '1', 'Ottimizzazione automatica immagini'),
('generate_thumbnails', '1', 'Generazione automatica thumbnail'),
('allow_hotlinking', '0', 'Permetti URL esterni per immagini'),

-- Impostazioni Performance (Base)
('cache_enabled', '1', 'Abilita sistema di cache interno'),
('cache_duration', '60', 'Durata cache in minuti'),
('gzip_compression', '1', 'Compressione GZIP'),
('minify_html', '0', 'Minifica HTML rimuovendo spazi e commenti'),
('db_query_limit', '1000', 'Limite record per query SELECT'),
('db_timeout', '30', 'Timeout connessione database'),
('log_level', 'error', 'Livello di logging (error, warning, info, debug)'),
('lazy_loading', '1', 'Lazy loading immagini'),
('debug_mode', '0', 'Modalità debug (solo sviluppo)'),

-- Impostazioni Sistema
('system_email', 'admin@menooelo.com', 'Email di sistema per notifiche'),

-- Impostazioni Backup
('auto_backup_enabled', '0', 'Abilita backup automatici programmati'),
('backup_frequency', 'daily', 'Frequenza backup (daily, weekly, monthly)'),
('backup_time', '02:00', 'Ora esecuzione backup automatico'),
('backup_retention', '30', 'Giorni di conservazione backup'),
('backup_path', 'uploads/backups/', 'Directory per salvataggio backup'),
('backup_compress', '1', 'Comprimi backup per risparmiare spazio'),
('backup_include_uploads', '1', 'Includi directory uploads nei backup'),
('backup_email_notification', '0', 'Invia email di conferma backup');

SET FOREIGN_KEY_CHECKS = 1;

-- Note di utilizzo:
-- 1. Questo schema contiene solo le funzionalità effettivamente implementate
-- 2. Password di default del super admin: 'password' - CAMBIARE IMMEDIATAMENTE!
-- 3. Tutte le impostazioni in system_settings corrispondono a codice funzionante
-- 4. La struttura supporta multi-tenancy tramite restaurant_id
-- 5. Le colonne JSON utilizzano CHECK (json_valid()) per validazione