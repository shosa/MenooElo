<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-0">
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

                <form method="POST" class="space-y-8">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <!-- General Settings -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-cog text-red-600"></i>
                            Impostazioni Generali
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome del Sistema
                                </label>
                                <input type="text" 
                                       name="settings[app_name]" 
                                       value="<?= htmlspecialchars($settings['app_name'] ?? 'MenooElo') ?>"
                                       placeholder="MenooElo"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email di Sistema
                                </label>
                                <input type="email" 
                                       name="settings[system_email]" 
                                       value="<?= htmlspecialchars($settings['system_email'] ?? '') ?>"
                                       placeholder="admin@menooelo.com"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    URL del Sistema
                                </label>
                                <input type="url" 
                                       name="settings[app_url]" 
                                       value="<?= htmlspecialchars($settings['app_url'] ?? '') ?>"
                                       placeholder="https://menooelo.com"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Fuso Orario
                                </label>
                                <select name="settings[timezone]" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="Europe/Rome" <?= ($settings['timezone'] ?? 'Europe/Rome') === 'Europe/Rome' ? 'selected' : '' ?>>Europa/Roma</option>
                                    <option value="UTC" <?= ($settings['timezone'] ?? '') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                                    <option value="America/New_York" <?= ($settings['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' ?>>America/New York</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
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
                                       name="settings[session_timeout_seconds]" 
                                       value="<?= htmlspecialchars(($settings['session_timeout'] ?? 7200) / 60) ?>"
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
                                       max="10"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="settings[maintenance_mode]" 
                                       <?= ($settings['maintenance_mode'] ?? false) ? 'checked' : '' ?>
                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <span class="ml-3 text-sm font-medium text-gray-700">
                                    <i class="fas fa-wrench text-orange-600"></i>
                                    Modalità Manutenzione
                                </span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1 ml-6">Disabilita l'accesso per tutti gli utenti tranne i super admin</p>
                        </div>
                    </div>

                    <!-- File Upload Settings -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-upload text-red-600"></i>
                            Impostazioni Upload
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Dimensione Max File (MB)
                                </label>
                                <input type="number" 
                                       name="settings[max_image_size]" 
                                       value="<?= htmlspecialchars(($settings['max_image_size'] ?? 5242880) / 1048576) ?>"
                                       min="1"
                                       max="50"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <p class="text-xs text-gray-500 mt-1">Dimensione massima per le immagini</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Formati Consentiti
                                </label>
                                <div class="space-y-2">
                                    <?php 
                                    $allowed_formats = explode(',', $settings['allowed_image_formats'] ?? 'jpg,jpeg,png,webp');
                                    $all_formats = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
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
                        </div>
                    </div>

                    <!-- API Settings -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-code text-red-600"></i>
                            Impostazioni API
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="settings[api_enabled]" 
                                           <?= ($settings['api_enabled'] ?? true) ? 'checked' : '' ?>
                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">
                                        API Abilitata
                                    </span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1 ml-6">Permette l'accesso alle API REST</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Rate Limit (req/min)
                                </label>
                                <input type="number" 
                                       name="settings[api_rate_limit]" 
                                       value="<?= htmlspecialchars($settings['api_rate_limit'] ?? 60) ?>"
                                       min="10"
                                       max="1000"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <p class="text-xs text-gray-500 mt-1">Numero massimo di richieste per minuto per IP</p>
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

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>