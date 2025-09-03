<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 overflow-x-hidden" x-data="restaurantFormManager()">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64 min-w-0">
        <!-- Modern Header -->
        <div class="bg-white/80 backdrop-blur-xl border-b border-white/20 shadow-sm sticky top-0 z-30">
            <div class="px-6 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">
                            <?= isset($restaurant) ? 'Modifica Ristorante' : 'Nuovo Ristorante' ?>
                        </h1>
                        <p class="text-slate-600 mt-2 flex items-center gap-2">
                            <i class="fas fa-utensils text-orange-500"></i>
                            <?= isset($restaurant) ? 'Aggiorna le informazioni del ristorante' : 'Crea un nuovo ristorante nel sistema' ?>
                        </p>
                    </div>
                    <div>
                        <a href="<?= BASE_URL ?>/superadmin/restaurants" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-arrow-left"></i>
                            <span>Torna alla Lista</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <div class="max-w-6xl mx-auto">
                
                <!-- Enhanced Error Message -->
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
                                <h3 class="text-lg font-semibold text-red-900 mb-1">Errore</h3>
                                <p class="text-red-700"><?= htmlspecialchars($error) ?></p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php endif; ?>

                <form method="POST" @submit="validateForm" class="space-y-8">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <!-- Basic Information -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-xl">
                                <i class="fas fa-info-circle text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">Informazioni Base</h2>
                                <p class="text-slate-500 mt-1">Dati principali del ristorante</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-store text-orange-500 mr-2"></i>
                                    Nome Ristorante
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       required 
                                       value="<?= htmlspecialchars($restaurant['name'] ?? '') ?>"
                                       placeholder="Es. Pizzeria da Mario"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    Nome pubblico del ristorante
                                </p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                    Email
                                </label>
                                <input type="email" 
                                       name="email" 
                                       value="<?= htmlspecialchars($restaurant['email'] ?? '') ?>"
                                       placeholder="info@ristorante.com"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    Email di contatto del ristorante
                                </p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-phone text-green-500 mr-2"></i>
                                    Telefono
                                </label>
                                <input type="text" 
                                       name="phone" 
                                       value="<?= htmlspecialchars($restaurant['phone'] ?? '') ?>"
                                       placeholder="+39 123 456 7890"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    Numero di telefono per i clienti
                                </p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-globe text-purple-500 mr-2"></i>
                                    Sito Web
                                </label>
                                <input type="url" 
                                       name="website" 
                                       value="<?= htmlspecialchars($restaurant['website'] ?? '') ?>"
                                       placeholder="https://www.ristorante.com"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    URL del sito web ufficiale
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <label class="block text-sm font-semibold text-slate-700 mb-3">
                                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                Indirizzo
                            </label>
                            <textarea name="address" 
                                      rows="2"
                                      placeholder="Via Roma 123, 00100 Roma RM"
                                      class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white/70 backdrop-blur-sm resize-none"><?= htmlspecialchars($restaurant['address'] ?? '') ?></textarea>
                            <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>
                                Indirizzo completo del ristorante
                            </p>
                        </div>
                        
                        <div class="mt-8">
                            <label class="block text-sm font-semibold text-slate-700 mb-3">
                                <i class="fas fa-align-left text-slate-500 mr-2"></i>
                                Descrizione
                            </label>
                            <textarea name="description" 
                                      rows="4"
                                      placeholder="Breve descrizione del ristorante, specialità, atmosfera..."
                                      class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white/70 backdrop-blur-sm resize-none"><?= htmlspecialchars($restaurant['description'] ?? '') ?></textarea>
                            <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>
                                Descrizione che apparirà nel menu pubblico
                            </p>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-xl">
                                <i class="fas fa-share-alt text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">Social Media</h2>
                                <p class="text-slate-500 mt-1">Collegamenti ai profili social</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fab fa-facebook text-blue-600 mr-2"></i>
                                    Facebook
                                </label>
                                <input type="url" 
                                       name="social_facebook" 
                                       value="<?= htmlspecialchars($restaurant['social_facebook'] ?? '') ?>"
                                       placeholder="https://facebook.com/ristorante"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    Link alla pagina Facebook
                                </p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fab fa-instagram text-pink-600 mr-2"></i>
                                    Instagram
                                </label>
                                <input type="url" 
                                       name="social_instagram" 
                                       value="<?= htmlspecialchars($restaurant['social_instagram'] ?? '') ?>"
                                       placeholder="https://instagram.com/ristorante"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    Link al profilo Instagram
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-xl">
                                <i class="fas fa-cog text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">Configurazione</h2>
                                <p class="text-slate-500 mt-1">Impostazioni tema e funzionalità</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-palette text-indigo-500 mr-2"></i>
                                    Colore Tema
                                </label>
                                <div class="flex items-center gap-3">
                                    <input type="color" 
                                           name="theme_color" 
                                           value="<?= htmlspecialchars($restaurant['theme_color'] ?? '#3273dc') ?>"
                                           class="w-16 h-16 border border-slate-200 rounded-xl cursor-pointer shadow-sm">
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">Colore principale</p>
                                        <p class="text-xs text-slate-500">Usato per il tema del menu</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-euro-sign text-green-500 mr-2"></i>
                                    Valuta
                                </label>
                                <select name="currency" 
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                    <option value="EUR" <?= ($restaurant['currency'] ?? 'EUR') === 'EUR' ? 'selected' : '' ?>>EUR (€)</option>
                                    <option value="USD" <?= ($restaurant['currency'] ?? '') === 'USD' ? 'selected' : '' ?>>USD ($)</option>
                                    <option value="GBP" <?= ($restaurant['currency'] ?? '') === 'GBP' ? 'selected' : '' ?>>GBP (£)</option>
                                </select>
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    Valuta per i prezzi del menu
                                </p>
                            </div>
                        </div>
                        
                        <!-- Features -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-slate-700 mb-4">
                                <i class="fas fa-toggle-on text-emerald-500 mr-2"></i>
                                Funzionalità Abilitate
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="features[menu]" 
                                               <?= isset($restaurant['features']['menu']) && $restaurant['features']['menu'] ? 'checked' : '' ?>
                                               class="rounded border-green-300 text-green-600 focus:ring-green-500">
                                        <div class="ml-3">
                                            <div class="flex items-center gap-2 mb-1">
                                                <i class="fas fa-utensils text-green-600"></i>
                                                <span class="text-sm font-semibold text-green-900">Menu Digitale</span>
                                            </div>
                                            <p class="text-xs text-green-700">Visualizzazione menu online</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="features[orders]" 
                                               <?= isset($restaurant['features']['orders']) && $restaurant['features']['orders'] ? 'checked' : '' ?>
                                               class="rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                                        <div class="ml-3">
                                            <div class="flex items-center gap-2 mb-1">
                                                <i class="fas fa-shopping-cart text-blue-600"></i>
                                                <span class="text-sm font-semibold text-blue-900">Ordinazioni Online</span>
                                            </div>
                                            <p class="text-xs text-blue-700">Ordini diretti dal menu</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <div class="bg-gradient-to-br from-purple-50 to-violet-50 border border-purple-200 rounded-xl p-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="features[qrcode]" 
                                               <?= isset($restaurant['features']['qrcode']) && $restaurant['features']['qrcode'] ? 'checked' : '' ?>
                                               class="rounded border-purple-300 text-purple-600 focus:ring-purple-500">
                                        <div class="ml-3">
                                            <div class="flex items-center gap-2 mb-1">
                                                <i class="fas fa-qrcode text-purple-600"></i>
                                                <span class="text-sm font-semibold text-purple-900">QR Code Menu</span>
                                            </div>
                                            <p class="text-xs text-purple-700">Accesso rapido via QR</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="bg-gradient-to-br from-amber-50 to-yellow-50 border border-amber-200 rounded-xl p-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="is_active" 
                                       <?= isset($restaurant['is_active']) && $restaurant['is_active'] ? 'checked' : '' ?>
                                       class="rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                                <div class="ml-4">
                                    <div class="flex items-center gap-2 mb-1">
                                        <i class="fas fa-power-off text-amber-600"></i>
                                        <span class="text-lg font-semibold text-amber-900">Ristorante Attivo</span>
                                    </div>
                                    <p class="text-sm text-amber-700">Il ristorante sarà visibile e accessibile ai clienti</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Admin Account (only for new restaurants) -->
                    <?php if (!isset($restaurant)): ?>
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-xl">
                                <i class="fas fa-user-shield text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">Account Admin (Opzionale)</h2>
                                <p class="text-slate-500 mt-1">Crea un account admin per questo ristorante</p>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 mb-8">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-blue-900">Informazione</h4>
                                    <p class="text-blue-700 text-sm mt-1">Se lasci vuoti questi campi, potrai creare l'account admin successivamente dalla sezione "Admin Ristoranti".</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-user text-blue-500 mr-2"></i>
                                    Nome Completo
                                </label>
                                <input type="text" 
                                       name="admin_full_name" 
                                       value="<?= htmlspecialchars($_POST['admin_full_name'] ?? '') ?>"
                                       placeholder="Mario Rossi"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-envelope text-green-500 mr-2"></i>
                                    Email Admin
                                </label>
                                <input type="email" 
                                       name="admin_email" 
                                       value="<?= htmlspecialchars($_POST['admin_email'] ?? '') ?>"
                                       placeholder="mario@ristorante.com"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-at text-purple-500 mr-2"></i>
                                    Username Admin
                                </label>
                                <input type="text" 
                                       name="admin_username" 
                                       value="<?= htmlspecialchars($_POST['admin_username'] ?? '') ?>"
                                       placeholder="mario_rossi"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-lock text-red-500 mr-2"></i>
                                    Password Admin
                                </label>
                                <input type="password" 
                                       name="admin_password" 
                                       placeholder="Password sicura"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Submit Buttons -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-8">
                        <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                            <div class="text-center lg:text-left">
                                <h4 class="text-lg font-semibold text-slate-900">Salva Modifiche</h4>
                                <p class="text-sm text-slate-500 mt-1">Le modifiche verranno applicate immediatamente</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                                <button type="submit" 
                                        class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-xl font-semibold hover:from-orange-700 hover:to-red-700 focus:ring-4 focus:ring-orange-500 focus:ring-opacity-50 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                    <i class="fas fa-save text-lg"></i>
                                    <span><?= isset($restaurant) ? 'Aggiorna Ristorante' : 'Crea Ristorante' ?></span>
                                </button>
                                <a href="<?= BASE_URL ?>/superadmin/restaurants" 
                                   class="inline-flex items-center justify-center gap-3 px-8 py-4 border border-slate-300 text-slate-700 rounded-xl font-semibold hover:bg-slate-50 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-times text-lg"></i>
                                    <span>Annulla</span>
                                </a>
                            </div>
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
function restaurantFormManager() {
    return {
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
            
            // Validate email format
            const emails = form.querySelectorAll('input[type="email"]');
            for (let email of emails) {
                if (email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                    email.focus();
                    this.showNotification('Inserisci un indirizzo email valido', 'error');
                    event.preventDefault();
                    return false;
                }
            }
            
            // Validate URL format
            const urls = form.querySelectorAll('input[type="url"]');
            for (let url of urls) {
                if (url.value && !/^https?:\/\/.+/.test(url.value)) {
                    url.focus();
                    this.showNotification('Inserisci un URL valido (deve iniziare con http:// o https://)', 'error');
                    event.preventDefault();
                    return false;
                }
            }
            
            // Validate admin fields consistency (only for new restaurants)
            <?php if (!isset($restaurant)): ?>
            const adminFields = [
                form.querySelector('[name="admin_full_name"]'),
                form.querySelector('[name="admin_email"]'),
                form.querySelector('[name="admin_username"]'),
                form.querySelector('[name="admin_password"]')
            ];
            
            const hasAnyAdminField = adminFields.some(field => field && field.value.trim());
            const hasAllAdminFields = adminFields.every(field => field && field.value.trim());
            
            if (hasAnyAdminField && !hasAllAdminFields) {
                this.showNotification('Se vuoi creare un admin, compila tutti i campi admin o lasciali tutti vuoti', 'error');
                event.preventDefault();
                return false;
            }
            <?php endif; ?>
            
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