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
                    <h1 class="text-3xl font-bold text-gray-900">
                        <?= isset($restaurant) ? 'Modifica Ristorante' : 'Nuovo Ristorante' ?>
                    </h1>
                    <p class="text-gray-600 mt-1">
                        <?= isset($restaurant) ? 'Aggiorna le informazioni del ristorante' : 'Crea un nuovo ristorante nel sistema' ?>
                    </p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>/superadmin/restaurants" 
                       class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-arrow-left"></i>
                        <span>Torna alla Lista</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <div class="max-w-4xl mx-auto">
                <?php if (isset($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle mt-0.5"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-8">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <!-- Basic Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-info-circle text-red-600"></i>
                            Informazioni Base
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome Ristorante *
                                </label>
                                <input type="text" 
                                       name="name" 
                                       required 
                                       value="<?= htmlspecialchars($restaurant['name'] ?? '') ?>"
                                       placeholder="Es. Pizzeria da Mario"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input type="email" 
                                       name="email" 
                                       value="<?= htmlspecialchars($restaurant['email'] ?? '') ?>"
                                       placeholder="info@ristorante.com"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Telefono
                                </label>
                                <input type="text" 
                                       name="phone" 
                                       value="<?= htmlspecialchars($restaurant['phone'] ?? '') ?>"
                                       placeholder="+39 123 456 7890"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Sito Web
                                </label>
                                <input type="url" 
                                       name="website" 
                                       value="<?= htmlspecialchars($restaurant['website'] ?? '') ?>"
                                       placeholder="https://www.ristorante.com"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Indirizzo
                            </label>
                            <textarea name="address" 
                                      rows="2"
                                      placeholder="Via Roma 123, 00100 Roma RM"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"><?= htmlspecialchars($restaurant['address'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Descrizione
                            </label>
                            <textarea name="description" 
                                      rows="3"
                                      placeholder="Breve descrizione del ristorante..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"><?= htmlspecialchars($restaurant['description'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-share-alt text-red-600"></i>
                            Social Media
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fab fa-facebook text-blue-600"></i>
                                    Facebook
                                </label>
                                <input type="url" 
                                       name="social_facebook" 
                                       value="<?= htmlspecialchars($restaurant['social_facebook'] ?? '') ?>"
                                       placeholder="https://facebook.com/ristorante"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fab fa-instagram text-pink-600"></i>
                                    Instagram
                                </label>
                                <input type="url" 
                                       name="social_instagram" 
                                       value="<?= htmlspecialchars($restaurant['social_instagram'] ?? '') ?>"
                                       placeholder="https://instagram.com/ristorante"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-cog text-red-600"></i>
                            Configurazione
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Colore Tema
                                </label>
                                <input type="color" 
                                       name="theme_color" 
                                       value="<?= htmlspecialchars($restaurant['theme_color'] ?? '#3273dc') ?>"
                                       class="w-20 h-12 border border-gray-300 rounded-lg cursor-pointer">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Valuta
                                </label>
                                <select name="currency" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="EUR" <?= ($restaurant['currency'] ?? 'EUR') === 'EUR' ? 'selected' : '' ?>>EUR (€)</option>
                                    <option value="USD" <?= ($restaurant['currency'] ?? '') === 'USD' ? 'selected' : '' ?>>USD ($)</option>
                                    <option value="GBP" <?= ($restaurant['currency'] ?? '') === 'GBP' ? 'selected' : '' ?>>GBP (£)</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Features -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-4">
                                Funzionalità Abilitate
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="features[menu]" 
                                           <?= isset($restaurant['features']['menu']) && $restaurant['features']['menu'] ? 'checked' : '' ?>
                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-3 text-sm text-gray-700">
                                        <i class="fas fa-utensils text-green-600"></i>
                                        Menu Digitale
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="features[orders]" 
                                           <?= isset($restaurant['features']['orders']) && $restaurant['features']['orders'] ? 'checked' : '' ?>
                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-3 text-sm text-gray-700">
                                        <i class="fas fa-shopping-cart text-blue-600"></i>
                                        Ordinazioni Online
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="features[qrcode]" 
                                           <?= isset($restaurant['features']['qrcode']) && $restaurant['features']['qrcode'] ? 'checked' : '' ?>
                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-3 text-sm text-gray-700">
                                        <i class="fas fa-qrcode text-purple-600"></i>
                                        QR Code Menu
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="mt-6">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       <?= isset($restaurant['is_active']) && $restaurant['is_active'] ? 'checked' : '' ?>
                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <span class="ml-3 text-sm font-medium text-gray-700">
                                    Ristorante attivo
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Admin Account (only for new restaurants) -->
                    <?php if (!isset($restaurant)): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-user-shield text-red-600"></i>
                            Account Admin (Opzionale)
                        </h2>
                        <p class="text-sm text-gray-600 mb-6">Crea un account admin per questo ristorante. Se lasci vuoto, potrai crearlo successivamente.</p>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome Completo
                                </label>
                                <input type="text" 
                                       name="admin_full_name" 
                                       value="<?= htmlspecialchars($_POST['admin_full_name'] ?? '') ?>"
                                       placeholder="Mario Rossi"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Admin
                                </label>
                                <input type="email" 
                                       name="admin_email" 
                                       value="<?= htmlspecialchars($_POST['admin_email'] ?? '') ?>"
                                       placeholder="mario@ristorante.com"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Username Admin
                                </label>
                                <input type="text" 
                                       name="admin_username" 
                                       value="<?= htmlspecialchars($_POST['admin_username'] ?? '') ?>"
                                       placeholder="mario_rossi"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Admin
                                </label>
                                <input type="password" 
                                       name="admin_password" 
                                       placeholder="Password sicura"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button type="submit" 
                                class="flex-1 sm:flex-initial px-8 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 focus:ring-4 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-200">
                            <i class="fas fa-save mr-2"></i>
                            <?= isset($restaurant) ? 'Aggiorna Ristorante' : 'Crea Ristorante' ?>
                        </button>
                        <a href="<?= BASE_URL ?>/superadmin/restaurants" 
                           class="flex-1 sm:flex-initial px-8 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 text-center transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Annulla
                        </a>
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