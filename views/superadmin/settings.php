<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 overflow-x-hidden" x-data="settingsManager()">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64 min-w-0">
        <!-- Modern Header -->
        <div class="bg-white/80 backdrop-blur-xl border-b border-white/20 shadow-sm sticky top-0 z-30">
            <div class="px-6 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">
                            Configurazione Sistema
                        </h1>
                        <p class="text-slate-600 mt-2 flex items-center gap-2">
                            <i class="fas fa-cogs text-blue-500"></i>
                            Gestione completa delle impostazioni MenooElo
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <div class="max-w-7xl mx-auto">
                
                <!-- Enhanced Success/Error Messages -->
                <?php if (isset($success)): ?>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 mb-6 shadow-sm" 
                     x-data="{ show: true }" 
                     x-show="show" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-check-circle text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-green-900 mb-1">Configurazione Salvata</h3>
                                <p class="text-green-700"><?= htmlspecialchars($success) ?></p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-green-400 hover:text-green-600 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-2xl p-6 mb-6 shadow-sm"
                     x-data="{ show: true }" 
                     x-show="show" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-red-900 mb-1">Errore di Configurazione</h3>
                                <p class="text-red-700"><?= htmlspecialchars($error) ?></p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Enhanced Tab Navigation -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 mb-8 overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-sliders-h text-white text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">Pannelli Configurazione</h2>
                                <p class="text-slate-500 mt-1">Seleziona una categoria per modificare le impostazioni</p>
                            </div>
                        </div>
                        
                        <nav class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <button @click="activeTab = 'general'" 
                                    :class="activeTab === 'general' ? 'bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg transform scale-105' : 'bg-white/70 text-slate-700 hover:bg-white hover:shadow-md'"
                                    class="inline-flex items-center gap-3 p-4 rounded-xl font-semibold transition-all duration-300">
                                <i class="fas fa-cog text-lg"></i>
                                <span>Generale</span>
                            </button>
                            <button @click="activeTab = 'security'" 
                                    :class="activeTab === 'security' ? 'bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg transform scale-105' : 'bg-white/70 text-slate-700 hover:bg-white hover:shadow-md'"
                                    class="inline-flex items-center gap-3 p-4 rounded-xl font-semibold transition-all duration-300">
                                <i class="fas fa-shield-alt text-lg"></i>
                                <span>Sicurezza</span>
                            </button>
                            <button @click="activeTab = 'uploads'" 
                                    :class="activeTab === 'uploads' ? 'bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg transform scale-105' : 'bg-white/70 text-slate-700 hover:bg-white hover:shadow-md'"
                                    class="inline-flex items-center gap-3 p-4 rounded-xl font-semibold transition-all duration-300">
                                <i class="fas fa-cloud-upload-alt text-lg"></i>
                                <span>Upload</span>
                            </button>
                            <button @click="activeTab = 'performance'" 
                                    :class="activeTab === 'performance' ? 'bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg transform scale-105' : 'bg-white/70 text-slate-700 hover:bg-white hover:shadow-md'"
                                    class="inline-flex items-center gap-3 p-4 rounded-xl font-semibold transition-all duration-300">
                                <i class="fas fa-tachometer-alt text-lg"></i>
                                <span>Performance</span>
                            </button>
                        </nav>
                    </div>
                </div>

                <form method="POST" @submit="validateForm">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <!-- General Settings Tab -->
                    <div x-show="activeTab === 'general'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-8 mb-8">
                        
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-xl">
                                <i class="fas fa-cog text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-900">Impostazioni Generali</h3>
                                <p class="text-slate-500 mt-1">Configurazione base del sistema</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-tag text-blue-500 mr-2"></i>
                                    Nome del Sistema
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="settings[app_name]" 
                                       value="<?= htmlspecialchars($settings['app_name'] ?? 'MenooElo') ?>"
                                       placeholder="MenooElo"
                                       required
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    Nome visualizzato nell'interfaccia
                                </p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-envelope text-green-500 mr-2"></i>
                                    Email di Sistema
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       name="settings[system_email]" 
                                       value="<?= htmlspecialchars($settings['system_email'] ?? '') ?>"
                                       placeholder="Inserisci email di sistema"
                                       required
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    Email per notifiche sistema
                                </p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-link text-purple-500 mr-2"></i>
                                    URL del Sistema
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="url" 
                                       name="settings[app_url]" 
                                       value="<?= htmlspecialchars($settings['app_url'] ?? BASE_URL) ?>"
                                       placeholder="<?= BASE_URL ?>"
                                       required
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    URL base del sistema
                                </p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-globe text-indigo-500 mr-2"></i>
                                    Fuso Orario
                                </label>
                                <select name="settings[timezone]" 
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                    <option value="Europe/Rome" <?= ($settings['timezone'] ?? 'Europe/Rome') === 'Europe/Rome' ? 'selected' : '' ?>>Europa/Roma (GMT+1)</option>
                                    <option value="UTC" <?= ($settings['timezone'] ?? '') === 'UTC' ? 'selected' : '' ?>>UTC (GMT+0)</option>
                                    <option value="America/New_York" <?= ($settings['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' ?>>America/New York (GMT-5)</option>
                                    <option value="Europe/London" <?= ($settings['timezone'] ?? '') === 'Europe/London' ? 'selected' : '' ?>>Europa/Londra (GMT+0)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <label class="block text-sm font-semibold text-slate-700 mb-3">
                                <i class="fas fa-align-left text-slate-500 mr-2"></i>
                                Descrizione Sistema
                            </label>
                            <textarea name="settings[app_description]" 
                                      rows="4"
                                      placeholder="Sistema di gestione menu digitali per ristoranti..."
                                      class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white/70 backdrop-blur-sm resize-none"><?= htmlspecialchars($settings['app_description'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-br from-amber-50 to-yellow-50 p-6 rounded-2xl border border-amber-200">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-yellow-500 rounded-xl flex items-center justify-center mr-3">
                                        <i class="fas fa-tools text-white"></i>
                                    </div>
                                    <h4 class="text-lg font-semibold text-amber-900">Modalità Manutenzione</h4>
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[maintenance_mode]" 
                                           <?= ($settings['maintenance_mode'] ?? false) ? 'checked' : '' ?>
                                           class="rounded border-amber-300 text-amber-600 focus:ring-amber-500 mr-3">
                                    <span class="text-sm font-medium text-amber-800">Attiva modalità manutenzione</span>
                                </label>
                                <p class="text-xs text-amber-700 mt-2">Disabilita l'accesso pubblico al sistema</p>
                            </div>
                            
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-200">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-3">
                                        <i class="fas fa-user-plus text-white"></i>
                                    </div>
                                    <h4 class="text-lg font-semibold text-green-900">Registrazioni</h4>
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[registration_enabled]" 
                                           <?= ($settings['registration_enabled'] ?? true) ? 'checked' : '' ?>
                                           class="rounded border-green-300 text-green-600 focus:ring-green-500 mr-3">
                                    <span class="text-sm font-medium text-green-800">Permetti nuove registrazioni</span>
                                </label>
                                <p class="text-xs text-green-700 mt-2">Consenti la creazione di nuovi ristoranti</p>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings Tab -->
                    <div x-show="activeTab === 'security'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-8 mb-8">
                        
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-xl">
                                <i class="fas fa-shield-alt text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-900">Sicurezza Sistema</h3>
                                <p class="text-slate-500 mt-1">Configurazioni di protezione e accesso</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-clock text-blue-500 mr-2"></i>
                                    Durata Sessione (minuti)
                                </label>
                                <input type="number" 
                                       name="settings[session_timeout_minutes]" 
                                       value="<?= htmlspecialchars(($settings['session_timeout'] ?? 3600) / 60) ?>"
                                       min="10"
                                       max="1440"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-ban text-red-500 mr-2"></i>
                                    Tentativi Login Max
                                </label>
                                <input type="number" 
                                       name="settings[max_login_attempts]" 
                                       value="<?= htmlspecialchars($settings['max_login_attempts'] ?? 5) ?>"
                                       min="3"
                                       max="20"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                            </div>
                        </div>
                        
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-lock text-2xl text-blue-600 mr-3"></i>
                                    <h4 class="text-lg font-semibold text-blue-900">HTTPS</h4>
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[force_https]" 
                                           <?= ($settings['force_https'] ?? false) ? 'checked' : '' ?>
                                           class="rounded border-blue-300 text-blue-600 focus:ring-blue-500 mr-3">
                                    <span class="text-sm font-medium text-blue-800">Forza HTTPS</span>
                                </label>
                            </div>
                            
                            <div class="bg-gradient-to-br from-purple-50 to-violet-50 p-6 rounded-2xl border border-purple-200">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-mobile-alt text-2xl text-purple-600 mr-3"></i>
                                    <h4 class="text-lg font-semibold text-purple-900">2FA</h4>
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[two_factor_enabled]" 
                                           <?= ($settings['two_factor_enabled'] ?? false) ? 'checked' : '' ?>
                                           class="rounded border-purple-300 text-purple-600 focus:ring-purple-500 mr-3">
                                    <span class="text-sm font-medium text-purple-800">Autenticazione 2FA</span>
                                </label>
                            </div>
                            
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-200">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-eye text-2xl text-green-600 mr-3"></i>
                                    <h4 class="text-lg font-semibold text-green-900">Logging</h4>
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[log_failed_logins]" 
                                           <?= ($settings['log_failed_logins'] ?? true) ? 'checked' : '' ?>
                                           class="rounded border-green-300 text-green-600 focus:ring-green-500 mr-3">
                                    <span class="text-sm font-medium text-green-800">Log tentativi falliti</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Settings Tab -->
                    <div x-show="activeTab === 'uploads'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-8 mb-8">
                        
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-xl">
                                <i class="fas fa-cloud-upload-alt text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-900">Gestione Upload</h3>
                                <p class="text-slate-500 mt-1">Configurazione caricamento file</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-images text-blue-500 mr-2"></i>
                                    Max Dimensione Immagini (MB)
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
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-file-alt text-purple-500 mr-2"></i>
                                    Formati Consentiti
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <?php 
                                    $allowed_formats = explode(',', $settings['allowed_image_formats'] ?? 'jpg,jpeg,png,webp');
                                    $all_formats = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
                                    ?>
                                    <?php foreach ($all_formats as $format): ?>
                                    <label class="flex items-center p-3 bg-white/50 rounded-lg border border-slate-200">
                                        <input type="checkbox" 
                                               name="settings[allowed_image_formats][]" 
                                               value="<?= $format ?>"
                                               <?= in_array($format, $allowed_formats) ? 'checked' : '' ?>
                                               class="rounded border-slate-300 text-green-600 focus:ring-green-500 mr-3">
                                        <span class="text-sm font-medium text-slate-700 uppercase"><?= $format ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-compress-alt text-2xl text-blue-600 mr-3"></i>
                                    <h4 class="text-lg font-semibold text-blue-900">Ottimizzazione</h4>
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[auto_image_optimization]" 
                                           <?= ($settings['auto_image_optimization'] ?? true) ? 'checked' : '' ?>
                                           class="rounded border-blue-300 text-blue-600 focus:ring-blue-500 mr-3">
                                    <span class="text-sm font-medium text-blue-800">Auto ottimizzazione</span>
                                </label>
                            </div>
                            
                            <div class="bg-gradient-to-br from-purple-50 to-violet-50 p-6 rounded-2xl border border-purple-200">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-th-large text-2xl text-purple-600 mr-3"></i>
                                    <h4 class="text-lg font-semibold text-purple-900">Thumbnails</h4>
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[generate_thumbnails]" 
                                           <?= ($settings['generate_thumbnails'] ?? true) ? 'checked' : '' ?>
                                           class="rounded border-purple-300 text-purple-600 focus:ring-purple-500 mr-3">
                                    <span class="text-sm font-medium text-purple-800">Genera thumbnail</span>
                                </label>
                            </div>
                            
                            <div class="bg-gradient-to-br from-orange-50 to-red-50 p-6 rounded-2xl border border-orange-200">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-link text-2xl text-orange-600 mr-3"></i>
                                    <h4 class="text-lg font-semibold text-orange-900">Hotlinking</h4>
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[allow_hotlinking]" 
                                           <?= ($settings['allow_hotlinking'] ?? false) ? 'checked' : '' ?>
                                           class="rounded border-orange-300 text-orange-600 focus:ring-orange-500 mr-3">
                                    <span class="text-sm font-medium text-orange-800">Permetti hotlinking</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Settings Tab -->
                    <div x-show="activeTab === 'performance'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-8 mb-8">
                        
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-xl">
                                <i class="fas fa-tachometer-alt text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-900">Ottimizzazione Performance</h3>
                                <p class="text-slate-500 mt-1">Configurazioni per migliorare le prestazioni</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-database text-blue-500 mr-2"></i>
                                    Durata Cache (minuti)
                                </label>
                                <input type="number" 
                                       name="settings[cache_duration]" 
                                       value="<?= htmlspecialchars($settings['cache_duration'] ?? 60) ?>"
                                       min="5"
                                       max="1440"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-list-ol text-green-500 mr-2"></i>
                                    Limite Query DB
                                </label>
                                <input type="number" 
                                       name="settings[db_query_limit]" 
                                       value="<?= htmlspecialchars($settings['db_query_limit'] ?? 1000) ?>"
                                       min="100"
                                       max="10000"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                            </div>
                        </div>
                        
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-200">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-rocket text-2xl text-green-600 mr-3"></i>
                                    <h4 class="text-lg font-semibold text-green-900">Cache</h4>
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[cache_enabled]" 
                                           <?= ($settings['cache_enabled'] ?? true) ? 'checked' : '' ?>
                                           class="rounded border-green-300 text-green-600 focus:ring-green-500 mr-3">
                                    <span class="text-sm font-medium text-green-800">Cache abilitata</span>
                                </label>
                            </div>
                            
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-compress text-2xl text-blue-600 mr-3"></i>
                                    <h4 class="text-lg font-semibold text-blue-900">GZIP</h4>
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[gzip_compression]" 
                                           <?= ($settings['gzip_compression'] ?? true) ? 'checked' : '' ?>
                                           class="rounded border-blue-300 text-blue-600 focus:ring-blue-500 mr-3">
                                    <span class="text-sm font-medium text-blue-800">Compressione</span>
                                </label>
                            </div>
                            
                            <div class="bg-gradient-to-br from-purple-50 to-violet-50 p-6 rounded-2xl border border-purple-200">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-code text-2xl text-purple-600 mr-3"></i>
                                    <h4 class="text-lg font-semibold text-purple-900">Minify HTML</h4>
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[minify_html]" 
                                           <?= ($settings['minify_html'] ?? false) ? 'checked' : '' ?>
                                           class="rounded border-purple-300 text-purple-600 focus:ring-purple-500 mr-3">
                                    <span class="text-sm font-medium text-purple-800">Minifica HTML</span>
                                </label>
                            </div>
                            
                            <div class="bg-gradient-to-br from-red-50 to-pink-50 p-6 rounded-2xl border border-red-200">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-bug text-2xl text-red-600 mr-3"></i>
                                    <h4 class="text-lg font-semibold text-red-900">Debug</h4>
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[debug_mode]" 
                                           <?= ($settings['debug_mode'] ?? false) ? 'checked' : '' ?>
                                           class="rounded border-red-300 text-red-600 focus:ring-red-500 mr-3">
                                    <span class="text-sm font-medium text-red-800">Modalità debug</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-8">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="text-center sm:text-left">
                                <h4 class="text-lg font-semibold text-slate-900">Salva Configurazione</h4>
                                <p class="text-sm text-slate-500 mt-1">Le modifiche verranno applicate immediatamente</p>
                            </div>
                            <button type="submit" 
                                    class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-xl font-semibold hover:from-orange-700 hover:to-red-700 focus:ring-4 focus:ring-orange-500 focus:ring-opacity-50 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                <i class="fas fa-save text-lg"></i>
                                <span>Salva Tutte le Impostazioni</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
function settingsManager() {
    return {
        activeTab: 'general',
        
        
        validateForm(event) {
            const form = event.target;
            const requiredFields = form.querySelectorAll('[required]');
            
            for (let field of requiredFields) {
                if (!field.value.trim()) {
                    field.focus();
                    this.showNotification(`Il campo "${field.previousElementSibling.textContent.replace('*', '').trim()}" è obbligatorio`, 'error');
                    event.preventDefault();
                    return false;
                }
            }
            
            return true;
        },
        
        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-xl text-white transform transition-all duration-300 ${
                type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-500' : 
                type === 'error' ? 'bg-gradient-to-r from-red-500 to-pink-500' : 
                'bg-gradient-to-r from-blue-500 to-indigo-500'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
                    <span class="font-medium">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => notification.style.transform = 'translateX(0)', 10);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    }
}
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>