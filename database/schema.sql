-- Database Schema per Menu Digitale Multi-Tenant
CREATE DATABASE IF NOT EXISTS menooelo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE menooelo;

-- Tabella Super Admin
CREATE TABLE super_admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabella Ristoranti (Tenant)
CREATE TABLE restaurants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    slug VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    logo_url VARCHAR(255),
    cover_image_url VARCHAR(255),
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    website VARCHAR(255),
    social_facebook VARCHAR(255),
    social_instagram VARCHAR(255),
    opening_hours JSON,
    theme_color VARCHAR(7) DEFAULT '#3273dc',
    currency VARCHAR(3) DEFAULT 'EUR',
    is_active BOOLEAN DEFAULT TRUE,
    features JSON COMMENT 'Moduli attivi: {"menu": true, "orders": false, "qrcode": true}',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_active (is_active)
);

-- Tabella Admin Ristoranti
CREATE TABLE restaurant_admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    restaurant_id INT NOT NULL,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('owner', 'manager', 'staff') DEFAULT 'staff',
    permissions JSON COMMENT 'Permessi specifici',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
    UNIQUE KEY unique_restaurant_username (restaurant_id, username),
    UNIQUE KEY unique_restaurant_email (restaurant_id, email),
    INDEX idx_restaurant_active (restaurant_id, is_active)
);

-- Tabella Categorie Menu
CREATE TABLE menu_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    restaurant_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
    INDEX idx_restaurant_active (restaurant_id, is_active),
    INDEX idx_sort_order (restaurant_id, sort_order)
);

-- Tabella Prodotti/Piatti
CREATE TABLE menu_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    restaurant_id INT NOT NULL,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    ingredients TEXT,
    allergens JSON COMMENT 'Lista allergeni',
    nutritional_info JSON COMMENT 'Valori nutrizionali',
    is_available BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES menu_categories(id) ON DELETE CASCADE,
    INDEX idx_restaurant_category (restaurant_id, category_id),
    INDEX idx_featured (restaurant_id, is_featured),
    INDEX idx_available (restaurant_id, is_available),
    INDEX idx_sort_order (category_id, sort_order)
);

-- Tabella Varianti Prodotti (es. piccola/media/grande)
CREATE TABLE menu_item_variants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    price_modifier DECIMAL(10,2) DEFAULT 0.00,
    is_default BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (item_id) REFERENCES menu_items(id) ON DELETE CASCADE,
    INDEX idx_item_default (item_id, is_default)
);

-- Tabella Extra/Aggiunte
CREATE TABLE menu_item_extras (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) DEFAULT 0.00,
    is_available BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (item_id) REFERENCES menu_items(id) ON DELETE CASCADE,
    INDEX idx_item_available (item_id, is_available)
);

-- Tabella Impostazioni Sistema
CREATE TABLE system_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabella Log Attività
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_type ENUM('super_admin', 'restaurant_admin') NOT NULL,
    user_id INT NOT NULL,
    restaurant_id INT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_type, user_id),
    INDEX idx_restaurant (restaurant_id),
    INDEX idx_created (created_at)
);

-- Inserimento Super Admin di default
INSERT INTO super_admins (username, email, password_hash, full_name) VALUES 
('superadmin', 'admin@menooelo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator');

-- Inserimento Impostazioni Sistema
INSERT INTO system_settings (setting_key, setting_value, description) VALUES
('site_name', 'MenooElo', 'Nome del sito'),
('site_description', 'Sistema di Menu Digitali per Ristoranti', 'Descrizione del sito'),
('default_theme_color', '#3273dc', 'Colore tema di default'),
('max_restaurants_per_page', '20', 'Numero massimo ristoranti per pagina'),
('allowed_image_formats', '["jpg", "jpeg", "png", "webp"]', 'Formati immagine consentiti'),
('max_image_size', '5242880', 'Dimensione massima immagini in bytes (5MB)');