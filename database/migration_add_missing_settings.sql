-- Migrazione: Aggiunta impostazioni mancanti
-- Eseguire questo script sui database esistenti per aggiungere le nuove impostazioni
-- Generato il: 2025-09-03

USE menooelo;

-- Inserisci nuove impostazioni di sicurezza (se non esistono già)
INSERT IGNORE INTO system_settings (setting_key, setting_value, description) VALUES
('require_password_complexity', '0', 'Richiedi password complesse (maiuscole, numeri, simboli)'),
('max_login_attempts', '5', 'Numero massimo tentativi login'),
('ip_block_duration', '15', 'Durata blocco IP in minuti dopo tentativi falliti'),
('two_factor_enabled', '0', 'Abilita autenticazione a due fattori');

-- Inserisci nuove impostazioni upload
INSERT IGNORE INTO system_settings (setting_key, setting_value, description) VALUES
('max_font_size', '2097152', 'Dimensione max font in bytes (2MB)'),
('allowed_font_formats', 'ttf,otf,woff,woff2', 'Formati font consentiti'),
('allow_hotlinking', '0', 'Permetti URL esterni per immagini');

-- Inserisci nuove impostazioni performance
INSERT IGNORE INTO system_settings (setting_key, setting_value, description) VALUES
('cache_enabled', '1', 'Abilita sistema di cache interno'),
('cache_duration', '60', 'Durata cache in minuti'),
('minify_html', '0', 'Minifica HTML rimuovendo spazi e commenti');

-- Inserisci impostazioni sistema
INSERT IGNORE INTO system_settings (setting_key, setting_value, description) VALUES
('system_email', 'admin@menooelo.com', 'Email di sistema per notifiche');

-- Inserisci impostazioni backup
INSERT IGNORE INTO system_settings (setting_key, setting_value, description) VALUES
('auto_backup_enabled', '0', 'Abilita backup automatici programmati'),
('backup_frequency', 'daily', 'Frequenza backup (daily, weekly, monthly)'),
('backup_time', '02:00', 'Ora esecuzione backup automatico'),
('backup_retention', '30', 'Giorni di conservazione backup'),
('backup_path', 'uploads/backups/', 'Directory per salvataggio backup'),
('backup_compress', '1', 'Comprimi backup per risparmiare spazio'),
('backup_include_uploads', '1', 'Includi directory uploads nei backup'),
('backup_email_notification', '0', 'Invia email di conferma backup');

-- Verifica che tutte le impostazioni siano presenti
SELECT 
    COUNT(*) as total_settings,
    COUNT(CASE WHEN setting_key LIKE '%password%' THEN 1 END) as password_settings,
    COUNT(CASE WHEN setting_key LIKE '%upload%' OR setting_key LIKE '%image%' OR setting_key LIKE '%font%' THEN 1 END) as upload_settings,
    COUNT(CASE WHEN setting_key LIKE '%backup%' THEN 1 END) as backup_settings
FROM system_settings;

-- Mostra tutte le impostazioni per verifica
-- SELECT setting_key, setting_value, description FROM system_settings ORDER BY setting_key;