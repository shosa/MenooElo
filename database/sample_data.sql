-- Sample Data per MenooElo
-- Esegui questo script dopo aver creato il database con schema.sql

USE menooelo;

-- Pulisci i dati esistenti (opzionale)
-- SET FOREIGN_KEY_CHECKS = 0;
-- TRUNCATE TABLE activity_logs;
-- TRUNCATE TABLE menu_item_extras;
-- TRUNCATE TABLE menu_item_variants;
-- TRUNCATE TABLE menu_items;
-- TRUNCATE TABLE menu_categories;
-- TRUNCATE TABLE restaurant_admins;
-- TRUNCATE TABLE restaurants;
-- SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- RISTORANTI DI ESEMPIO
-- =============================================

INSERT INTO restaurants (slug, name, description, address, phone, email, website, social_facebook, social_instagram, theme_color, currency, is_active, features, opening_hours) VALUES
('la-tavola-italiana', 'La Tavola Italiana', 'Autentica cucina italiana nel cuore di Roma. Piatti tradizionali preparati con ingredienti freschi e di qualità.', 'Via del Corso 123, 00186 Roma RM', '+39 06 1234567', 'info@latavolaitaliana.it', 'https://latavolaitaliana.it', 'https://facebook.com/latavolaitaliana', 'https://instagram.com/latavolaitaliana', '#e74c3c', 'EUR', 1, '{"menu": true, "orders": false, "qrcode": true}', '{"lunedi": {"aperto": true, "orari": ["12:00-15:00", "19:00-23:00"]}, "martedi": {"aperto": true, "orari": ["12:00-15:00", "19:00-23:00"]}, "mercoledi": {"aperto": true, "orari": ["12:00-15:00", "19:00-23:00"]}, "giovedi": {"aperto": true, "orari": ["12:00-15:00", "19:00-23:00"]}, "venerdi": {"aperto": true, "orari": ["12:00-15:00", "19:00-24:00"]}, "sabato": {"aperto": true, "orari": ["12:00-15:00", "19:00-24:00"]}, "domenica": {"aperto": true, "orari": ["12:00-15:00", "19:00-23:00"]}}'),

('pizzeria-da-mario', 'Pizzeria da Mario', 'La migliore pizza napoletana della città. Forno a legna e ingredienti selezionati per un\'esperienza autentica.', 'Via Napoli 45, 20121 Milano MI', '+39 02 9876543', 'mario@pizzeriadamario.it', 'https://pizzeriadamario.it', 'https://facebook.com/pizzeriadamario', 'https://instagram.com/pizzeriadamario', '#27ae60', 'EUR', 1, '{"menu": true, "orders": true, "qrcode": true}', '{"lunedi": {"aperto": false}, "martedi": {"aperto": true, "orari": ["18:00-24:00"]}, "mercoledi": {"aperto": true, "orari": ["18:00-24:00"]}, "giovedi": {"aperto": true, "orari": ["18:00-24:00"]}, "venerdi": {"aperto": true, "orari": ["18:00-01:00"]}, "sabato": {"aperto": true, "orari": ["18:00-01:00"]}, "domenica": {"aperto": true, "orari": ["18:00-24:00"]}}'),

('cafe-central', 'Café Central', 'Caffetteria e bistrot moderno. Colazioni, pranzi veloci e aperitivi in un ambiente accogliente.', 'Piazza Centrale 8, 50122 Firenze FI', '+39 055 1122334', 'info@cafecentral.it', 'https://cafecentral.it', 'https://facebook.com/cafecentral', 'https://instagram.com/cafecentral', '#3498db', 'EUR', 1, '{"menu": true, "orders": false, "qrcode": true}', '{"lunedi": {"aperto": true, "orari": ["07:00-20:00"]}, "martedi": {"aperto": true, "orari": ["07:00-20:00"]}, "mercoledi": {"aperto": true, "orari": ["07:00-20:00"]}, "giovedi": {"aperto": true, "orari": ["07:00-20:00"]}, "venerdi": {"aperto": true, "orari": ["07:00-22:00"]}, "sabato": {"aperto": true, "orari": ["08:00-22:00"]}, "domenica": {"aperto": true, "orari": ["08:00-20:00"]}}');

-- =============================================
-- ADMIN RISTORANTI
-- =============================================

INSERT INTO restaurant_admins (restaurant_id, username, email, password_hash, full_name, role, permissions, is_active) VALUES
-- La Tavola Italiana
(1, 'giovanni_rossi', 'giovanni@latavolaitaliana.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Giovanni Rossi', 'owner', NULL, 1),
(1, 'maria_bianchi', 'maria@latavolaitaliana.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maria Bianchi', 'manager', '["manage_categories", "manage_menu_items", "view_analytics"]', 1),
(1, 'luca_verdi', 'luca@latavolaitaliana.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Luca Verdi', 'staff', '["manage_menu_items"]', 1),

-- Pizzeria da Mario
(2, 'mario_ferrari', 'mario@pizzeriadamario.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mario Ferrari', 'owner', NULL, 1),
(2, 'anna_ricci', 'anna@pizzeriadamario.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Anna Ricci', 'staff', '["manage_menu_items"]', 1),

-- Café Central
(3, 'francesco_marino', 'francesco@cafecentral.it', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Francesco Marino', 'owner', NULL, 1);

-- =============================================
-- CATEGORIE MENU
-- =============================================

-- La Tavola Italiana
INSERT INTO menu_categories (restaurant_id, name, description, sort_order, is_active) VALUES
(1, 'Antipasti', 'Selezione di antipasti tradizionali italiani', 1, 1),
(1, 'Primi Piatti', 'Pasta fresca e risotti della casa', 2, 1),
(1, 'Secondi Piatti', 'Carni e pesci preparati secondo la tradizione', 3, 1),
(1, 'Contorni', 'Verdure fresche e contorni di stagione', 4, 1),
(1, 'Dolci', 'Dolci della casa e gelati artigianali', 5, 1),
(1, 'Bevande', 'Vini selezionati e bevande', 6, 1),

-- Pizzeria da Mario
(2, 'Antipasti', 'Antipasti napoletani', 1, 1),
(2, 'Pizze Classiche', 'Le pizze della tradizione napoletana', 2, 1),
(2, 'Pizze Speciali', 'Le nostre creazioni uniche', 3, 1),
(2, 'Calzoni', 'Calzoni ripieni cotti nel forno a legna', 4, 1),
(2, 'Birre', 'Birre artigianali e industriali', 5, 1),
(2, 'Dolci', 'Dolci tipici napoletani', 6, 1),

-- Café Central
(3, 'Colazioni', 'Cornetti, brioche e dolci per la colazione', 1, 1),
(3, 'Panini', 'Panini e toast preparati al momento', 2, 1),
(3, 'Insalate', 'Insalate fresche e piatti leggeri', 3, 1),
(3, 'Caffetteria', 'Caffè e bevande calde', 4, 1),
(3, 'Aperitivi', 'Cocktail e stuzzichini per l\'aperitivo', 5, 1);

-- =============================================
-- MENU ITEMS
-- =============================================

-- La Tavola Italiana - Antipasti
INSERT INTO menu_items (restaurant_id, category_id, name, description, price, ingredients, allergens, is_available, is_featured, sort_order) VALUES
(1, 1, 'Antipasto della Casa', 'Selezione di salumi e formaggi locali, olive e verdure sott\'olio', 14.50, 'Prosciutto crudo, salame, formaggio pecorino, mozzarella, olive, peperoni sott\'olio', '["glutine", "latte"]', 1, 1, 1),
(1, 1, 'Bruschette Miste', 'Bruschette con pomodoro, aglio e basilico (3 pezzi)', 8.90, 'Pane pugliese, pomodori freschi, aglio, basilico, olio EVO', '["glutine"]', 1, 0, 2),
(1, 1, 'Burrata con Pomodorini', 'Burrata pugliese con pomodorini del Piennolo e basilico', 12.00, 'Burrata, pomodorini del Piennolo, basilico, olio EVO', '["latte"]', 1, 1, 3),

-- La Tavola Italiana - Primi Piatti
(1, 2, 'Spaghetti alla Carbonara', 'Spaghetti con guanciale, uova, pecorino e pepe nero', 13.50, 'Spaghetti, guanciale, uova, pecorino romano, pepe nero', '["glutine", "uova", "latte"]', 1, 1, 1),
(1, 2, 'Risotto ai Porcini', 'Risotto cremoso con funghi porcini freschi', 16.00, 'Riso Carnaroli, funghi porcini, cipolla, vino bianco, parmigiano, burro', '["latte"]', 1, 0, 2),
(1, 2, 'Penne all\'Arrabbiata', 'Penne in salsa piccante di pomodoro, aglio e peperoncino', 11.50, 'Penne, pomodoro, aglio, peperoncino, prezzemolo, olio EVO', '["glutine"]', 1, 0, 3),
(1, 2, 'Lasagne della Casa', 'Lasagne con ragù, besciamella e parmigiano', 14.00, 'Pasta all\'uovo, ragù di carne, besciamella, parmigiano', '["glutine", "uova", "latte"]', 1, 1, 4),

-- La Tavola Italiana - Secondi Piatti
(1, 3, 'Bistecca alla Fiorentina', 'Bistecca di manzo alla griglia (1kg circa per 2 persone)', 45.00, 'Bistecca di manzo, rosmarino, sale grosso, olio EVO', '[]', 1, 1, 1),
(1, 3, 'Branzino in Crosta di Sale', 'Branzino fresco cotto in crosta di sale', 24.00, 'Branzino, sale grosso, erbe aromatiche', '["pesce"]', 1, 0, 2),
(1, 3, 'Pollo alle Erbe', 'Pollo ruspante con erbe aromatiche', 18.50, 'Pollo, rosmarino, salvia, aglio, vino bianco', '[]', 1, 0, 3),

-- Pizzeria da Mario - Pizze Classiche
(2, 8, 'Margherita', 'Pomodoro, mozzarella di bufala, basilico', 8.50, 'Impasto, pomodoro San Marzano, mozzarella di bufala, basilico, olio EVO', '["glutine", "latte"]', 1, 1, 1),
(2, 8, 'Marinara', 'Pomodoro, aglio, oregano, basilico', 6.50, 'Impasto, pomodoro San Marzano, aglio, oregano, basilico, olio EVO', '["glutine"]', 1, 0, 2),
(2, 8, 'Napoli', 'Pomodoro, mozzarella, acciughe, capperi', 9.50, 'Impasto, pomodoro San Marzano, mozzarella, acciughe, capperi, origano', '["glutine", "latte", "pesce"]', 1, 0, 3),
(2, 8, 'Quattro Stagioni', 'Pomodoro, mozzarella, prosciutto, funghi, carciofi, olive', 12.00, 'Impasto, pomodoro, mozzarella, prosciutto cotto, funghi champignon, carciofi, olive nere', '["glutine", "latte"]', 1, 1, 4),

-- Pizzeria da Mario - Pizze Speciali
(2, 9, 'Pizza del Pizzaiolo', 'Mozzarella, salsiccia, friarielli, provola affumicata', 14.50, 'Impasto, mozzarella, salsiccia napoletana, friarielli, provola affumicata', '["glutine", "latte"]', 1, 1, 1),
(2, 9, 'Capricciosa Gourmet', 'Pomodoro, mozzarella di bufala, prosciutto crudo, rucola, grana', 15.00, 'Impasto, pomodoro, mozzarella di bufala, prosciutto crudo, rucola, grana padano', '["glutine", "latte"]', 1, 0, 2),

-- Café Central - Colazioni
(3, 13, 'Cornetto Vuoto', 'Cornetto classico appena sfornato', 1.50, 'Farina, burro, uova, zucchero, lievito', '["glutine", "uova", "latte"]', 1, 0, 1),
(3, 13, 'Cornetto alla Crema', 'Cornetto ripieno di crema pasticcera', 2.00, 'Farina, burro, uova, zucchero, lievito, crema pasticcera', '["glutine", "uova", "latte"]', 1, 1, 2),
(3, 13, 'Maritozzo con Panna', 'Maritozzo romano con panna montata', 3.50, 'Farina, uova, burro, zucchero, panna montata', '["glutine", "uova", "latte"]', 1, 1, 3),

-- Café Central - Panini
(3, 14, 'Panino Caprese', 'Mozzarella di bufala, pomodoro, basilico', 7.50, 'Pane pugliese, mozzarella di bufala, pomodoro, basilico, olio EVO', '["glutine", "latte"]', 1, 0, 1),
(3, 14, 'Club Sandwich', 'Pollo, bacon, lattuga, pomodoro, maionese', 9.00, 'Pan carré, petto di pollo, bacon, lattuga, pomodoro, maionese', '["glutine", "uova"]', 1, 1, 2);

-- =============================================
-- VARIANTI PRODOTTI
-- =============================================

-- Varianti per le pizze (dimensioni)
INSERT INTO menu_item_variants (item_id, name, price_modifier, is_default, sort_order) VALUES
-- Margherita
((SELECT id FROM menu_items WHERE name = 'Margherita' AND restaurant_id = 2), 'Baby (20cm)', -2.00, 0, 1),
((SELECT id FROM menu_items WHERE name = 'Margherita' AND restaurant_id = 2), 'Normale (30cm)', 0.00, 1, 2),
((SELECT id FROM menu_items WHERE name = 'Margherita' AND restaurant_id = 2), 'Maxi (40cm)', 4.00, 0, 3),

-- Quattro Stagioni
((SELECT id FROM menu_items WHERE name = 'Quattro Stagioni' AND restaurant_id = 2), 'Baby (20cm)', -2.00, 0, 1),
((SELECT id FROM menu_items WHERE name = 'Quattro Stagioni' AND restaurant_id = 2), 'Normale (30cm)', 0.00, 1, 2),
((SELECT id FROM menu_items WHERE name = 'Quattro Stagioni' AND restaurant_id = 2), 'Maxi (40cm)', 4.00, 0, 3);

-- =============================================
-- EXTRA PRODOTTI
-- =============================================

-- Extra per pizze
INSERT INTO menu_item_extras (item_id, name, price, is_available, sort_order) VALUES
-- Extra per Margherita
((SELECT id FROM menu_items WHERE name = 'Margherita' AND restaurant_id = 2), 'Prosciutto Crudo', 3.00, 1, 1),
((SELECT id FROM menu_items WHERE name = 'Margherita' AND restaurant_id = 2), 'Funghi', 2.00, 1, 2),
((SELECT id FROM menu_items WHERE name = 'Margherita' AND restaurant_id = 2), 'Olive', 1.50, 1, 3),
((SELECT id FROM menu_items WHERE name = 'Margherita' AND restaurant_id = 2), 'Doppia Mozzarella', 2.50, 1, 4),

-- Extra per Quattro Stagioni
((SELECT id FROM menu_items WHERE name = 'Quattro Stagioni' AND restaurant_id = 2), 'Piccante', 0.50, 1, 1),
((SELECT id FROM menu_items WHERE name = 'Quattro Stagioni' AND restaurant_id = 2), 'Gorgonzola', 2.00, 1, 2);

-- =============================================
-- ACTIVITY LOGS
-- =============================================

INSERT INTO activity_logs (user_type, user_id, restaurant_id, action, description, ip_address, user_agent, created_at) VALUES
('restaurant_admin', 1, 1, 'login', 'Login Restaurant Admin', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
('restaurant_admin', 1, 1, 'category_add', 'Aggiunta categoria: Antipasti', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', DATE_SUB(NOW(), INTERVAL 1 HOUR)),
('restaurant_admin', 1, 1, 'item_add', 'Aggiunto piatto: Antipasto della Casa', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', DATE_SUB(NOW(), INTERVAL 1 HOUR)),
('restaurant_admin', 1, 1, 'item_update', 'Aggiornato piatto: Spaghetti alla Carbonara', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', DATE_SUB(NOW(), INTERVAL 30 MINUTE)),
('restaurant_admin', 4, 2, 'login', 'Login Restaurant Admin', '192.168.1.101', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0)', DATE_SUB(NOW(), INTERVAL 3 HOUR)),
('restaurant_admin', 4, 2, 'category_add', 'Aggiunta categoria: Pizze Classiche', '192.168.1.101', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0)', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
('restaurant_admin', 4, 2, 'item_add', 'Aggiunto piatto: Margherita', '192.168.1.101', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0)', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
('restaurant_admin', 6, 3, 'login', 'Login Restaurant Admin', '192.168.1.102', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)', DATE_SUB(NOW(), INTERVAL 4 HOUR)),
('restaurant_admin', 1, 1, 'settings_update', 'Aggiornate impostazioni ristorante', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', DATE_SUB(NOW(), INTERVAL 15 MINUTE)),
('restaurant_admin', 2, 1, 'item_update', 'Aggiornato piatto: Risotto ai Porcini', '192.168.1.103', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', DATE_SUB(NOW(), INTERVAL 10 MINUTE));

-- =============================================
-- IMPOSTAZIONI SISTEMA AGGIUNTIVE
-- =============================================

INSERT INTO system_settings (setting_key, setting_value, description) VALUES
('maintenance_mode', 'false', 'Modalità manutenzione attiva'),
('max_upload_size', '10485760', 'Dimensione massima upload in bytes (10MB)'),
('email_notifications', 'true', 'Attiva notifiche email'),
('default_currency', 'EUR', 'Valuta predefinita del sistema'),
('analytics_enabled', 'true', 'Attiva sistema analytics'),
('backup_frequency', 'daily', 'Frequenza backup automatico');

-- =============================================
-- MESSAGGI DI CONFERMA
-- =============================================

SELECT 'Database popolato con successo!' as Status;
SELECT COUNT(*) as 'Ristoranti creati' FROM restaurants;
SELECT COUNT(*) as 'Admin creati' FROM restaurant_admins;
SELECT COUNT(*) as 'Categorie create' FROM menu_categories;
SELECT COUNT(*) as 'Piatti creati' FROM menu_items;
SELECT COUNT(*) as 'Varianti create' FROM menu_item_variants;
SELECT COUNT(*) as 'Extra creati' FROM menu_item_extras;
SELECT COUNT(*) as 'Log attività' FROM activity_logs;

-- Per testare il login, usa queste credenziali:
-- Restaurant Admin per La Tavola Italiana:
-- Username: giovanni_rossi
-- Password: password

-- Restaurant Admin per Pizzeria da Mario:
-- Username: mario_ferrari  
-- Password: password

-- Restaurant Admin per Café Central:
-- Username: francesco_marino
-- Password: password