<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Impostazioni Sistema</h1>
                    <p class="text-gray-600 mt-1">Configurazione globale del sistema MenooElo</p>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <div class="max-w-4xl mx-auto">
                <?php if (defined('DEBUG_MODE') && DEBUG_MODE): ?>
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-4 rounded-lg mb-6 text-xs">
                    <strong>Debug Info:</strong> 
                    app_url = "<?= htmlspecialchars($settings['app_url'] ?? 'NOT SET') ?>", 
                    system_email = "<?= htmlspecialchars($settings['system_email'] ?? 'NOT SET') ?>",
                    max_image_size = "<?= htmlspecialchars($settings['max_image_size'] ?? 'NOT SET') ?>"
                </div>
                <?php endif; ?>
                
                <!-- Success/Error Messages -->
                <?php if (isset($success)): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-6 flex items-start gap-3">
                    <i class="fas fa-check-circle mt-0.5"></i>
                    <span><?= htmlspecialchars($success) ?></span>
                </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle mt-0.5"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
                <?php endif; ?>

                <!-- Tab Navigation -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <nav class="flex space-x-8">
                            <button onclick="showSettingsTab('general')" class="settings-tab-btn py-2 px-1 border-b-2 font-medium text-sm transition-colors border-red-500 text-red-600" data-tab="general">
                                <i class="fas fa-cog mr-2"></i>Generale
                            </button>
                            <button onclick="showSettingsTab('security')" class="settings-tab-btn py-2 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700" data-tab="security">
                                <i class="fas fa-shield-alt mr-2"></i>Sicurezza
                            </button>
                            <button onclick="showSettingsTab('uploads')" class="settings-tab-btn py-2 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700" data-tab="uploads">
                                <i class="fas fa-upload mr-2"></i>Upload
                            </button>
                            <button onclick="showSettingsTab('performance')" class="settings-tab-btn py-2 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700" data-tab="performance">
                                <i class="fas fa-tachometer-alt mr-2"></i>Performance
                            </button>
                        </nav>
                    </div>
                </div>

                <form method="POST" class="space-y-8">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <!-- General Settings Tab -->
                    <div id="settings-tab-general" class="settings-tab-content">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                                <i class="fas fa-cog text-red-600"></i>
                                Impostazioni Generali
                            </h2>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nome del Sistema
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="settings[app_name]" 
                                           value="<?= htmlspecialchars($settings['app_name'] ?? 'MenooElo') ?>"
                                           placeholder="MenooElo"
                                           data-required="true"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Nome visualizzato nell'header e nei titoli</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Email di Sistema
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           name="settings[system_email]" 
                                           value="<?= htmlspecialchars($settings['system_email'] ?? 'admin@menooelo.com') ?>"
                                           placeholder="admin@menooelo.com"
                                           data-required="true"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Email utilizzata per notifiche sistema</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        URL del Sistema
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <?php $appUrl = $settings['app_url'] ?? BASE_URL; ?>
                                    <input type="url" 
                                           name="settings[app_url]" 
                                           value="<?= htmlspecialchars($appUrl ?: BASE_URL) ?>"
                                           placeholder="<?= BASE_URL ?>"
                                           data-required="true"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">URL base per link e redirect</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Fuso Orario
                                    </label>
                                    <select name="settings[timezone]" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <option value="Europe/Rome" <?= ($settings['timezone'] ?? 'Europe/Rome') === 'Europe/Rome' ? 'selected' : '' ?>>Europa/Roma (GMT+1)</option>
                                        <option value="UTC" <?= ($settings['timezone'] ?? '') === 'UTC' ? 'selected' : '' ?>>UTC (GMT+0)</option>
                                        <option value="America/New_York" <?= ($settings['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' ?>>America/New York (GMT-5)</option>
                                        <option value="Europe/London" <?= ($settings['timezone'] ?? '') === 'Europe/London' ? 'selected' : '' ?>>Europa/Londra (GMT+0)</option>
                                        <option value="Europe/Paris" <?= ($settings['timezone'] ?? '') === 'Europe/Paris' ? 'selected' : '' ?>>Europa/Parigi (GMT+1)</option>
                                        <option value="Europe/Berlin" <?= ($settings['timezone'] ?? '') === 'Europe/Berlin' ? 'selected' : '' ?>>Europa/Berlino (GMT+1)</option>
                                        <option value="Asia/Tokyo" <?= ($settings['timezone'] ?? '') === 'Asia/Tokyo' ? 'selected' : '' ?>>Asia/Tokyo (GMT+9)</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Lingua Predefinita
                                    </label>
                                    <select name="settings[default_language]" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <option value="it" <?= ($settings['default_language'] ?? 'it') === 'it' ? 'selected' : '' ?>>Italiano</option>
                                        <option value="en" <?= ($settings['default_language'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                                        <option value="fr" <?= ($settings['default_language'] ?? '') === 'fr' ? 'selected' : '' ?>>Français</option>
                                        <option value="de" <?= ($settings['default_language'] ?? '') === 'de' ? 'selected' : '' ?>>Deutsch</option>
                                        <option value="es" <?= ($settings['default_language'] ?? '') === 'es' ? 'selected' : '' ?>>Español</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Valuta Predefinita
                                    </label>
                                    <select name="settings[default_currency]" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <option value="EUR" <?= ($settings['default_currency'] ?? 'EUR') === 'EUR' ? 'selected' : '' ?>>Euro (€)</option>
                                        <option value="USD" <?= ($settings['default_currency'] ?? '') === 'USD' ? 'selected' : '' ?>>US Dollar ($)</option>
                                        <option value="GBP" <?= ($settings['default_currency'] ?? '') === 'GBP' ? 'selected' : '' ?>>British Pound (£)</option>
                                        <option value="CHF" <?= ($settings['default_currency'] ?? '') === 'CHF' ? 'selected' : '' ?>>Swiss Franc (CHF)</option>
                                        <option value="JPY" <?= ($settings['default_currency'] ?? '') === 'JPY' ? 'selected' : '' ?>>Japanese Yen (¥)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Descrizione Sistema
                                </label>
                                <textarea name="settings[app_description]" 
                                          rows="3"
                                          placeholder="Sistema di gestione menu digitali per ristoranti"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"><?= htmlspecialchars($settings['app_description'] ?? '') ?></textarea>
                                <p class="text-xs text-gray-500 mt-1">Descrizione visualizzata nelle meta tag e footer</p>
                            </div>
                            
                            <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[maintenance_mode]" 
                                               <?= ($settings['maintenance_mode'] ?? false) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-wrench text-orange-600 mr-2"></i>
                                            Modalità Manutenzione
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Disabilita l'accesso per tutti tranne super admin</p>
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[registration_enabled]" 
                                               <?= ($settings['registration_enabled'] ?? true) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-user-plus text-green-600 mr-2"></i>
                                            Registrazione Abilitata
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Permetti nuove registrazioni ristoranti</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings Tab -->
                    <div id="settings-tab-security" class="settings-tab-content hidden">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                                <i class="fas fa-shield-alt text-red-600"></i>
                                Impostazioni Sicurezza
                            </h2>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Durata Sessione (minuti)
                                    </label>
                                    <input type="number" 
                                           name="settings[session_timeout_minutes]" 
                                           value="<?= htmlspecialchars(($settings['session_timeout'] ?? 3600) / 60) ?>"
                                           min="10"
                                           max="1440"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Tempo prima del logout automatico (10-1440 minuti)</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tentativi di Login Max
                                    </label>
                                    <input type="number" 
                                           name="settings[max_login_attempts]" 
                                           value="<?= htmlspecialchars($settings['max_login_attempts'] ?? 5) ?>"
                                           min="3"
                                           max="20"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Numero tentativi prima del blocco temporaneo</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Blocco IP (minuti)
                                    </label>
                                    <input type="number" 
                                           name="settings[ip_block_duration]" 
                                           value="<?= htmlspecialchars($settings['ip_block_duration'] ?? 15) ?>"
                                           min="5"
                                           max="1440"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Durata blocco IP dopo tentativi falliti</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Costo Password Bcrypt
                                    </label>
                                    <input type="number" 
                                           name="settings[password_cost]" 
                                           value="<?= htmlspecialchars($settings['password_cost'] ?? 12) ?>"
                                           min="10"
                                           max="15"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Complessità hash password (10=veloce, 15=sicuro)</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Lunghezza Password Min
                                    </label>
                                    <input type="number" 
                                           name="settings[min_password_length]" 
                                           value="<?= htmlspecialchars($settings['min_password_length'] ?? 8) ?>"
                                           min="6"
                                           max="20"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Lunghezza minima password utenti</p>
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[require_password_complexity]" 
                                               <?= ($settings['require_password_complexity'] ?? false) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            Password Complesse
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Richiedi maiuscole, numeri e simboli</p>
                                </div>
                            </div>
                            
                            <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[two_factor_enabled]" 
                                               <?= ($settings['two_factor_enabled'] ?? false) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-mobile-alt text-blue-600 mr-2"></i>
                                            Autenticazione 2FA
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Abilita autenticazione a due fattori</p>
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[force_https]" 
                                               <?= ($settings['force_https'] ?? false) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-lock text-green-600 mr-2"></i>
                                            Forza HTTPS
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Redirect automatico a HTTPS</p>
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[log_failed_logins]" 
                                               <?= ($settings['log_failed_logins'] ?? true) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-eye text-orange-600 mr-2"></i>
                                            Log Tentativi Falliti
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Registra tentativi di login falliti</p>
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[cookie_secure]" 
                                               <?= ($settings['cookie_secure'] ?? false) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-cookie-bite text-purple-600 mr-2"></i>
                                            Cookie Sicuri
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Cookie solo su connessioni HTTPS</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Settings Tab -->
                    <div id="settings-tab-uploads" class="settings-tab-content hidden">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                                <i class="fas fa-upload text-red-600"></i>
                                Gestione File Upload
                            </h2>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Dimensione Max Immagini (MB)
                                    </label>
                                    <?php 
                                    $maxImageSize = $settings['max_image_size'] ?? 5242880;
                                    $maxImageSizeMB = $maxImageSize > 0 ? round($maxImageSize / 1048576, 1) : 5;
                                    ?>
                                    <input type="number" 
                                           name="settings[max_image_size_mb]" 
                                           value="<?= $maxImageSizeMB ?>"
                                           min="1"
                                           max="100"
                                           step="0.5"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Dimensione massima file immagini</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Dimensione Max Font (MB)
                                    </label>
                                    <?php 
                                    $maxFontSize = $settings['max_font_size'] ?? 2097152;
                                    $maxFontSizeMB = $maxFontSize > 0 ? round($maxFontSize / 1048576, 1) : 2;
                                    ?>
                                    <input type="number" 
                                           name="settings[max_font_size_mb]" 
                                           value="<?= $maxFontSizeMB ?>"
                                           min="0.5"
                                           max="10"
                                           step="0.5"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Dimensione massima file font</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Formati Immagini Consentiti
                                    </label>
                                    <div class="space-y-2">
                                        <?php 
                                        $allowed_formats = explode(',', $settings['allowed_image_formats'] ?? 'jpg,jpeg,png,webp');
                                        $all_formats = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'bmp'];
                                        ?>
                                        <?php foreach ($all_formats as $format): ?>
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   name="settings[allowed_image_formats][]" 
                                                   value="<?= $format ?>"
                                                   <?= in_array($format, $allowed_formats) ? 'checked' : '' ?>
                                                   class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                            <span class="ml-3 text-sm text-gray-700 uppercase"><?= $format ?></span>
                                        </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Formati Font Consentiti
                                    </label>
                                    <div class="space-y-2">
                                        <?php 
                                        $allowed_font_formats = explode(',', $settings['allowed_font_formats'] ?? 'ttf,otf,woff,woff2');
                                        $all_font_formats = ['ttf', 'otf', 'woff', 'woff2', 'eot'];
                                        ?>
                                        <?php foreach ($all_font_formats as $format): ?>
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   name="settings[allowed_font_formats][]" 
                                                   value="<?= $format ?>"
                                                   <?= in_array($format, $allowed_font_formats) ? 'checked' : '' ?>
                                                   class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                            <span class="ml-3 text-sm text-gray-700 uppercase"><?= $format ?></span>
                                        </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[auto_image_optimization]" 
                                               <?= ($settings['auto_image_optimization'] ?? true) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-compress-alt text-green-600 mr-2"></i>
                                            Ottimizzazione Auto
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Comprimi automaticamente le immagini</p>
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[generate_thumbnails]" 
                                               <?= ($settings['generate_thumbnails'] ?? true) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-images text-purple-600 mr-2"></i>
                                            Genera Thumbnail
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Crea versioni ridotte automaticamente</p>
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[allow_hotlinking]" 
                                               <?= ($settings['allow_hotlinking'] ?? false) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-link text-orange-600 mr-2"></i>
                                            Permetti Hotlinking
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Consenti URL esterni per immagini</p>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-chart-bar text-blue-600"></i>
                                    Statistiche Upload
                                </h3>
                                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-blue-600" id="total-files-count">-</p>
                                        <p class="text-sm text-gray-600">File Totali</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-green-600" id="total-size">-</p>
                                        <p class="text-sm text-gray-600">Spazio Utilizzato</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-orange-600" id="orphaned-files">-</p>
                                        <p class="text-sm text-gray-600">File Orfani</p>
                                    </div>
                                    <div class="text-center">
                                        <button onclick="location.href='/superadmin/file-manager'" 
                                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                            <i class="fas fa-folder-open mr-2"></i>
                                            Gestione File
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Settings Tab -->
                    <div id="settings-tab-performance" class="settings-tab-content hidden">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                                <i class="fas fa-tachometer-alt text-red-600"></i>
                                Ottimizzazione Performance
                            </h2>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="flex items-center mb-4">
                                        <input type="checkbox" 
                                               name="settings[cache_enabled]" 
                                               <?= ($settings['cache_enabled'] ?? true) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-rocket text-green-600 mr-2"></i>
                                            Cache Abilitata
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Abilita sistema di cache interno</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Durata Cache (minuti)
                                    </label>
                                    <input type="number" 
                                           name="settings[cache_duration]" 
                                           value="<?= htmlspecialchars($settings['cache_duration'] ?? 60) ?>"
                                           min="5"
                                           max="1440"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Durata cache in minuti</p>
                                </div>
                                
                                <div>
                                    <label class="flex items-center mb-4">
                                        <input type="checkbox" 
                                               name="settings[gzip_compression]" 
                                               <?= ($settings['gzip_compression'] ?? true) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-compress text-blue-600 mr-2"></i>
                                            Compressione GZIP
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Comprimi output HTML per velocità</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Limite Query Database
                                    </label>
                                    <input type="number" 
                                           name="settings[db_query_limit]" 
                                           value="<?= htmlspecialchars($settings['db_query_limit'] ?? 1000) ?>"
                                           min="100"
                                           max="10000"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Max record per query SELECT</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Timeout Connessione DB (secondi)
                                    </label>
                                    <input type="number" 
                                           name="settings[db_timeout]" 
                                           value="<?= htmlspecialchars($settings['db_timeout'] ?? 30) ?>"
                                           min="5"
                                           max="300"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Timeout connessioni database</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Livello Log
                                    </label>
                                    <select name="settings[log_level]" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <option value="error" <?= ($settings['log_level'] ?? 'error') === 'error' ? 'selected' : '' ?>>Solo Errori</option>
                                        <option value="warning" <?= ($settings['log_level'] ?? '') === 'warning' ? 'selected' : '' ?>>Warning + Errori</option>
                                        <option value="info" <?= ($settings['log_level'] ?? '') === 'info' ? 'selected' : '' ?>>Info + Warning + Errori</option>
                                        <option value="debug" <?= ($settings['log_level'] ?? '') === 'debug' ? 'selected' : '' ?>>Debug (Tutto)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[minify_html]" 
                                               <?= ($settings['minify_html'] ?? false) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-code text-purple-600 mr-2"></i>
                                            Minifica HTML
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Rimuovi spazi e commenti HTML</p>
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[lazy_loading]" 
                                               <?= ($settings['lazy_loading'] ?? true) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-image text-indigo-600 mr-2"></i>
                                            Lazy Loading
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Carica immagini solo quando visibili</p>
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[debug_mode]" 
                                               <?= ($settings['debug_mode'] ?? false) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-bug text-red-600 mr-2"></i>
                                            Modalità Debug
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Mostra errori dettagliati (SOLO sviluppo)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Backup Settings Tab -->
                    <div id="settings-tab-backups" class="settings-tab-content hidden">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                                <i class="fas fa-database text-red-600"></i>
                                Configurazione Backup Automatici
                            </h2>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="flex items-center mb-4">
                                        <input type="checkbox" 
                                               name="settings[auto_backup_enabled]" 
                                               <?= ($settings['auto_backup_enabled'] ?? false) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-clock text-green-600 mr-2"></i>
                                            Backup Automatici
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Esegui backup automatici programmati</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Frequenza Backup
                                    </label>
                                    <select name="settings[backup_frequency]" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <option value="daily" <?= ($settings['backup_frequency'] ?? 'daily') === 'daily' ? 'selected' : '' ?>>Giornaliero</option>
                                        <option value="weekly" <?= ($settings['backup_frequency'] ?? '') === 'weekly' ? 'selected' : '' ?>>Settimanale</option>
                                        <option value="monthly" <?= ($settings['backup_frequency'] ?? '') === 'monthly' ? 'selected' : '' ?>>Mensile</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Ora Backup (24h)
                                    </label>
                                    <input type="time" 
                                           name="settings[backup_time]" 
                                           value="<?= htmlspecialchars($settings['backup_time'] ?? '02:00') ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Orario esecuzione backup automatico</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Backup da Mantenere
                                    </label>
                                    <input type="number" 
                                           name="settings[backup_retention]" 
                                           value="<?= htmlspecialchars($settings['backup_retention'] ?? 30) ?>"
                                           min="1"
                                           max="365"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Giorni di conservazione backup</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Percorso Backup
                                    </label>
                                    <input type="text" 
                                           name="settings[backup_path]" 
                                           value="<?= htmlspecialchars($settings['backup_path'] ?? UPLOADS_PATH . 'backups/') ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <p class="text-xs text-gray-500 mt-1">Directory per salvataggio backup</p>
                                </div>
                                
                                <div>
                                    <label class="flex items-center mb-4">
                                        <input type="checkbox" 
                                               name="settings[backup_compress]" 
                                               <?= ($settings['backup_compress'] ?? true) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-file-archive text-purple-600 mr-2"></i>
                                            Comprimi Backup
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Comprimi backup per risparmiare spazio</p>
                                </div>
                            </div>
                            
                            <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[backup_include_uploads]" 
                                               <?= ($settings['backup_include_uploads'] ?? true) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-images text-blue-600 mr-2"></i>
                                            Includi File Upload
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Includi directory uploads nei backup</p>
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="settings[backup_email_notification]" 
                                               <?= ($settings['backup_email_notification'] ?? false) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            <i class="fas fa-envelope text-orange-600 mr-2"></i>
                                            Notifica Email
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 ml-6">Invia email di conferma backup</p>
                                </div>
                                
                                <div>
                                    <button type="button" onclick="runManualBackup()" 
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-play mr-2"></i>
                                        Backup Manuale
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6">
                        <button type="submit" 
                                class="px-8 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 focus:ring-4 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Salva Impostazioni
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.field-error {
    animation: shake 0.3s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.border-red-500 {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

/* Improved tab focus for validation */
.settings-tab-btn.tab-error {
    background-color: rgba(239, 68, 68, 0.1);
    border-color: #ef4444 !important;
    color: #ef4444 !important;
}

.settings-tab-btn.tab-error::after {
    content: ' ⚠';
}
</style>

<script>
// Tab Management
function showSettingsTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.settings-tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active state from all tab buttons
    document.querySelectorAll('.settings-tab-btn').forEach(btn => {
        btn.classList.remove('border-red-500', 'text-red-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab
    document.getElementById(`settings-tab-${tabName}`).classList.remove('hidden');
    
    // Activate selected tab button
    const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
    activeBtn.classList.remove('border-transparent', 'text-gray-500');
    activeBtn.classList.add('border-red-500', 'text-red-600');
    
    // Save active tab in localStorage
    localStorage.setItem('activeSettingsTab', tabName);
    
    // Update tab error indicators
    updateTabErrorIndicators();
}

// Update tab error indicators
function updateTabErrorIndicators() {
    document.querySelectorAll('.settings-tab-btn').forEach(btn => {
        btn.classList.remove('tab-error');
        
        const tabName = btn.dataset.tab;
        const tabContent = document.getElementById(`settings-tab-${tabName}`);
        
        if (tabContent && tabContent.querySelectorAll('.border-red-500').length > 0) {
            btn.classList.add('tab-error');
        }
    });
}

// SMTP Settings Toggle
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form fields with default values
    initializeFormDefaults();
    
    // Restore active tab
    const activeTab = localStorage.getItem('activeSettingsTab') || 'general';
    showSettingsTab(activeTab);
    
    // Add real-time validation to required fields
    const requiredFields = document.querySelectorAll('[data-required="true"]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            validateField(this);
        });
        field.addEventListener('input', function() {
            // Clear error state on input
            this.classList.remove('border-red-500');
            this.classList.add('border-gray-300');
        });
    });
    
    // Load upload statistics
    loadUploadStats();
});

// Initialize form with default values
function initializeFormDefaults() {
    // Ensure critical fields have values
    const appUrlField = document.querySelector('[name="settings[app_url]"]');
    if (appUrlField && (!appUrlField.value || appUrlField.value.trim() === '')) {
        appUrlField.value = '<?= BASE_URL ?>';
    }
    
    
    const appNameField = document.querySelector('[name="settings[app_name]"]');
    if (appNameField && (!appNameField.value || appNameField.value.trim() === '')) {
        appNameField.value = 'MenooElo';
    }
    
    // Fix numeric fields that might be 0 or empty
    const maxImageSizeField = document.querySelector('[name="settings[max_image_size_mb]"]');
    if (maxImageSizeField && (!maxImageSizeField.value || parseFloat(maxImageSizeField.value) <= 0)) {
        maxImageSizeField.value = '5';
    }
    
    const maxFontSizeField = document.querySelector('[name="settings[max_font_size_mb]"]');
    if (maxFontSizeField && (!maxFontSizeField.value || parseFloat(maxFontSizeField.value) <= 0)) {
        maxFontSizeField.value = '2';
    }
}

// Load Upload Statistics
function loadUploadStats() {
    fetch('<?= BASE_URL ?>/superadmin/upload-stats', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            csrf_token: document.querySelector('[name="csrf_token"]').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('total-files-count').textContent = data.stats.total_files.toLocaleString();
            document.getElementById('total-size').textContent = formatFileSize(data.stats.total_size);
            document.getElementById('orphaned-files').textContent = data.stats.orphaned_files.toLocaleString();
        }
    })
    .catch(error => {
        console.error('Error loading upload stats:', error);
    });
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Validate individual field
function validateField(field) {
    let isValid = true;
    let errorMessage = '';
    
    // Check if field is required and empty
    if (field.hasAttribute('data-required') && !field.value.trim()) {
        isValid = false;
        const label = field.closest('div').querySelector('label').textContent.replace('*', '').trim();
        errorMessage = `Il campo "${label}" è obbligatorio`;
    }
    
    // Email validation
    if (field.type === 'email' && field.value.trim()) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value)) {
            isValid = false;
            errorMessage = 'Inserire un indirizzo email valido';
        }
    }
    
    // URL validation
    if (field.type === 'url' && field.value.trim()) {
        try {
            new URL(field.value);
        } catch {
            isValid = false;
            errorMessage = 'Inserire un URL valido';
        }
    }
    
    // Number validation
    if (field.type === 'number' && field.value.trim()) {
        const value = parseFloat(field.value);
        const min = parseFloat(field.min);
        const max = parseFloat(field.max);
        
        if (isNaN(value)) {
            isValid = false;
            errorMessage = 'Inserire un numero valido';
        } else if (min !== undefined && value < min) {
            isValid = false;
            errorMessage = `Il valore deve essere almeno ${min}`;
        } else if (max !== undefined && value > max) {
            isValid = false;
            errorMessage = `Il valore deve essere massimo ${max}`;
        }
    }
    
    // Update field appearance
    if (isValid) {
        field.classList.remove('border-red-500');
        field.classList.add('border-gray-300');
        removeFieldError(field);
    } else {
        field.classList.remove('border-gray-300');
        field.classList.add('border-red-500');
        showFieldError(field, errorMessage);
    }
    
    // Update tab error indicators
    setTimeout(updateTabErrorIndicators, 100);
    
    return isValid;
}

// Show field error
function showFieldError(field, message) {
    removeFieldError(field);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error text-red-500 text-xs mt-1';
    errorDiv.textContent = message;
    
    field.parentNode.insertBefore(errorDiv, field.nextSibling);
}

// Remove field error
function removeFieldError(field) {
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Manual Backup
function runManualBackup() {
    if (!confirm('Eseguire un backup manuale del database?')) return;
    
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Backup in corso...';
    btn.disabled = true;
    
    fetch('<?= BASE_URL ?>/superadmin/manual-backup', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            csrf_token: document.querySelector('[name="csrf_token"]').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Backup completato con successo!');
            if (data.download_url) {
                const link = document.createElement('a');
                link.href = data.download_url;
                link.download = data.filename;
                link.click();
            }
        } else {
            alert('Errore durante il backup: ' + data.error);
        }
    })
    .catch(error => {
        alert('Errore durante il backup: ' + error.message);
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Form validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    // Force default values for critical fields before validation
    const appUrlField = document.querySelector('[name="settings[app_url]"]');
    if (appUrlField && !appUrlField.value.trim()) {
        appUrlField.value = '<?= BASE_URL ?>';
    }
    
    const systemEmailField = document.querySelector('[name="settings[system_email]"]');
    if (systemEmailField && !systemEmailField.value.trim()) {
        systemEmailField.value = 'admin@menooelo.com';
    }
    
    const appNameField = document.querySelector('[name="settings[app_name]"]');
    if (appNameField && !appNameField.value.trim()) {
        appNameField.value = 'MenooElo';
    }
    
    // Fix numeric fields
    document.querySelectorAll('input[type="number"]').forEach(field => {
        if (!field.value || field.value === '0' || isNaN(parseFloat(field.value))) {
            const fieldName = field.name;
            if (fieldName.includes('max_image_size_mb')) {
                field.value = '5';
            } else if (fieldName.includes('max_font_size_mb')) {
                field.value = '2';
            } else if (fieldName.includes('session_timeout_minutes')) {
                field.value = '60';
            } else if (fieldName.includes('max_login_attempts')) {
                field.value = '5';
            }
        }
    });
    
    // Now validate all required fields
    const requiredFields = document.querySelectorAll('[data-required="true"]');
    let firstErrorField = null;
    
    for (const field of requiredFields) {
        if (!field.value.trim()) {
            const label = field.closest('div').querySelector('label').textContent.replace('*', '').trim();
            if (!firstErrorField) {
                firstErrorField = field;
                // Show the tab containing the error field
                const tabContent = field.closest('.settings-tab-content');
                if (tabContent && tabContent.classList.contains('hidden')) {
                    const tabId = tabContent.id.replace('settings-tab-', '');
                    showSettingsTab(tabId);
                }
                setTimeout(() => {
                    field.focus();
                    alert(`Il campo "${label}" è obbligatorio.`);
                }, 100);
            }
            e.preventDefault();
            return;
        }
    }
    
    // Validate email format
    const emailField = document.querySelector('[name="settings[system_email]"]');
    if (emailField && emailField.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailField.value)) {
            // Show the tab containing the email field
            const tabContent = emailField.closest('.settings-tab-content');
            if (tabContent && tabContent.classList.contains('hidden')) {
                const tabId = tabContent.id.replace('settings-tab-', '');
                showSettingsTab(tabId);
            }
            setTimeout(() => {
                emailField.focus();
                alert('Inserire un indirizzo email valido.');
            }, 100);
            e.preventDefault();
            return;
        }
    }
    
    // Validate URL format
    const urlField = document.querySelector('[name="settings[app_url]"]');
    if (urlField && urlField.value) {
        try {
            new URL(urlField.value);
        } catch {
            // Show the tab containing the URL field
            const tabContent = urlField.closest('.settings-tab-content');
            if (tabContent && tabContent.classList.contains('hidden')) {
                const tabId = tabContent.id.replace('settings-tab-', '');
                showSettingsTab(tabId);
            }
            setTimeout(() => {
                urlField.focus();
                alert('Inserire un URL valido.');
            }, 100);
            e.preventDefault();
            return;
        }
    }
});
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>