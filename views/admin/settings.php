<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'views/admin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-0">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Impostazioni Ristorante</h1>
                    <p class="text-gray-600 mt-1">Gestisci le informazioni e l'aspetto del tuo ristorante</p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>/restaurant/<?= $restaurant['slug'] ?? 'slug' ?>" 
                       class="inline-flex items-center gap-2 px-4 py-2 border border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-600 hover:text-white transition-colors duration-200" 
                       target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visualizza Menu</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Messages -->
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
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            Informazioni di Base
                        </h2>
                        
                        <form method="POST" enctype="multipart/form-data" class="space-y-6">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <input type="hidden" name="section" value="basic_info">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nome Ristorante <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           required 
                                           value="<?= htmlspecialchars($restaurant['name'] ?? '') ?>"
                                           placeholder="Il nome del tuo ristorante"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Slug URL <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 py-2 border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm rounded-l-lg">
                                            /restaurant/
                                        </span>
                                        <input type="text" 
                                               name="slug" 
                                               required 
                                               value="<?= htmlspecialchars($restaurant['slug'] ?? '') ?>"
                                               placeholder="nome-ristorante"
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors">
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Solo lettere, numeri e trattini</p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Descrizione
                                </label>
                                <textarea name="description" 
                                          rows="3"
                                          placeholder="Breve descrizione del tuo ristorante"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors resize-y"><?= htmlspecialchars($restaurant['description'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Telefono
                                    </label>
                                    <input type="tel" 
                                           name="phone" 
                                           value="<?= htmlspecialchars($restaurant['phone'] ?? '') ?>"
                                           placeholder="+39 123 456 7890"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           value="<?= htmlspecialchars($restaurant['email'] ?? '') ?>"
                                           placeholder="info@ristorante.it"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Indirizzo
                                </label>
                                <textarea name="address" 
                                          rows="2"
                                          placeholder="Via/Piazza, numero civico, CAP, Città"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors resize-y"><?= htmlspecialchars($restaurant['address'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <button type="submit" 
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-save"></i>
                                    <span>Salva Informazioni</span>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Logo & Branding -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-palette text-blue-600"></i>
                            Logo e Branding
                        </h2>
                        
                        <form method="POST" enctype="multipart/form-data" class="space-y-6">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <input type="hidden" name="section" value="branding">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Logo Section -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Logo del Ristorante
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-600 bg-gray-50 transition-colors cursor-pointer" onclick="document.querySelector('input[name=logo]').click()">
                                        <input type="file" name="logo" accept="image/*" class="hidden" onchange="previewImage(this, 'logoPreview')">
                                        
                                        <?php if (isset($restaurant['logo_url']) && $restaurant['logo_url']): ?>
                                        <div class="text-center">
                                            <img src="<?= BASE_URL ?>/uploads/logos/<?= $restaurant['logo_url'] ?>" 
                                                 alt="Logo attuale" 
                                                 class="mx-auto rounded-lg shadow-sm max-w-24 max-h-24 object-contain mb-3" id="logoPreview">
                                            <p class="text-gray-600 text-sm mb-2">Click per cambiare</p>
                                            <button type="button" onclick="removeLogo()" 
                                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                <i class="fas fa-trash mr-1"></i>Rimuovi Logo
                                            </button>
                                        </div>
                                        <?php else: ?>
                                        <div class="text-center text-gray-500">
                                            <i class="fas fa-image text-3xl mb-3"></i>
                                            <p class="font-medium text-sm">Carica Logo</p>
                                            <img class="mx-auto rounded-lg shadow-sm max-w-24 max-h-24 object-contain mt-3 hidden" id="logoPreview">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Quadrato preferibilmente, max 2MB</p>
                                </div>

                                <!-- Banner Section -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Banner del Ristorante
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-600 bg-gray-50 transition-colors cursor-pointer" onclick="document.querySelector('input[name=banner]').click()">
                                        <input type="file" name="banner" accept="image/*" class="hidden" onchange="previewImage(this, 'bannerPreview')">
                                        
                                        <?php if (isset($restaurant['cover_image_url']) && $restaurant['cover_image_url']): ?>
                                        <div class="text-center">
                                            <img src="<?= BASE_URL ?>/uploads/banners/<?= $restaurant['cover_image_url'] ?>" 
                                                 alt="Banner attuale" 
                                                 class="mx-auto rounded-lg shadow-sm max-w-full h-16 object-cover mb-3" id="bannerPreview">
                                            <p class="text-gray-600 text-sm mb-2">Click per cambiare</p>
                                            <button type="button" onclick="removeBanner()" 
                                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                <i class="fas fa-trash mr-1"></i>Rimuovi Banner
                                            </button>
                                        </div>
                                        <?php else: ?>
                                        <div class="text-center text-gray-500">
                                            <i class="fas fa-panorama text-3xl mb-3"></i>
                                            <p class="font-medium text-sm">Carica Banner</p>
                                            <img class="mx-auto rounded-lg shadow-sm max-w-full h-16 object-cover mt-3 hidden" id="bannerPreview">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Formato panoramico, max 5MB</p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Colore Primario
                                </label>
                                <div class="flex items-center gap-3">
                                    <input type="color" 
                                           name="primary_color" 
                                           value="<?= $restaurant['theme_color'] ?? '#3b82f6' ?>"
                                           class="w-16 h-10 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" 
                                           name="primary_color_hex" 
                                           value="<?= $restaurant['theme_color'] ?? '#3b82f6' ?>"
                                           placeholder="#3b82f6"
                                           class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-24">
                                    <span class="text-sm text-gray-500">Colore principale del menu</span>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <button type="submit" 
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-save"></i>
                                    <span>Salva Branding</span>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Opening Hours -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-clock text-blue-600"></i>
                            Orari di Apertura
                        </h2>
                        
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <input type="hidden" name="section" value="opening_hours">
                            
                            <?php 
                            $days = [
                                'monday' => 'Lunedì',
                                'tuesday' => 'Martedì', 
                                'wednesday' => 'Mercoledì',
                                'thursday' => 'Giovedì',
                                'friday' => 'Venerdì',
                                'saturday' => 'Sabato',
                                'sunday' => 'Domenica'
                            ];
                            ?>
                            
                            <?php foreach ($days as $day => $dayName): ?>
                            <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                                <div class="w-20 text-sm font-medium text-gray-700">
                                    <?= $dayName ?>
                                </div>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" 
                                           name="<?= $day ?>_open" 
                                           checked
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                    <span class="text-sm text-gray-600">Aperto</span>
                                </label>
                                <input type="time" 
                                       name="<?= $day ?>_open_time" 
                                       value="12:00"
                                       class="px-2 py-1 border border-gray-300 rounded text-sm">
                                <span class="text-gray-500">-</span>
                                <input type="time" 
                                       name="<?= $day ?>_close_time" 
                                       value="22:30"
                                       class="px-2 py-1 border border-gray-300 rounded text-sm">
                            </div>
                            <?php endforeach; ?>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <button type="submit" 
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-save"></i>
                                    <span>Salva Orari</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-toggle-on text-green-600"></i>
                            Stato Menu
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                                <div>
                                    <div class="font-medium text-green-800">Menu Online</div>
                                    <div class="text-sm text-green-600">Visibile ai clienti</div>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                </div>
                            </div>
                            
                            <form method="POST" class="space-y-3">
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                <input type="hidden" name="section" value="menu_status">
                                
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" 
                                           name="show_prices" 
                                           checked
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                    <div>
                                        <div class="font-medium text-gray-800">Mostra Prezzi</div>
                                        <div class="text-sm text-gray-600">I prezzi saranno visibili nel menu</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" 
                                           name="show_descriptions" 
                                           checked
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                    <div>
                                        <div class="font-medium text-gray-800">Mostra Descrizioni</div>
                                        <div class="text-sm text-gray-600">Le descrizioni dei piatti saranno visibili</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" 
                                           name="show_images" 
                                           checked
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                    <div>
                                        <div class="font-medium text-gray-800">Mostra Immagini</div>
                                        <div class="text-sm text-gray-600">Le foto dei piatti saranno visibili</div>
                                    </div>
                                </label>
                                
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-save"></i>
                                    <span>Aggiorna</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-pie text-blue-600"></i>
                            Riepilogo Menu
                        </h3>
                        
                        <?php 
                        $restaurantId = $_SESSION['restaurant_id'] ?? 0;
                        $db = Database::getInstance();
                        
                        $stats = [
                            'categories' => $db->selectOne("SELECT COUNT(*) as count FROM menu_categories WHERE restaurant_id = ?", [$restaurantId])['count'] ?? 0,
                            'total_items' => $db->selectOne("SELECT COUNT(*) as count FROM menu_items WHERE restaurant_id = ?", [$restaurantId])['count'] ?? 0,
                            'active_items' => $db->selectOne("SELECT COUNT(*) as count FROM menu_items WHERE restaurant_id = ? AND is_available = 1", [$restaurantId])['count'] ?? 0,
                            'featured_items' => $db->selectOne("SELECT COUNT(*) as count FROM menu_items WHERE restaurant_id = ? AND is_featured = 1", [$restaurantId])['count'] ?? 0
                        ];
                        ?>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Categorie</span>
                                <span class="font-semibold text-gray-900"><?= $stats['categories'] ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Piatti totali</span>
                                <span class="font-semibold text-gray-900"><?= $stats['total_items'] ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Disponibili</span>
                                <span class="font-semibold text-green-600"><?= $stats['active_items'] ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">In evidenza</span>
                                <span class="font-semibold text-yellow-600"><?= $stats['featured_items'] ?></span>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="<?= BASE_URL ?>/admin/analytics" 
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-blue-600 border border-blue-600 rounded-lg font-medium hover:bg-blue-600 hover:text-white transition-colors duration-200">
                                <i class="fas fa-chart-line"></i>
                                <span>Statistiche Complete</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Sync color picker with hex input
document.querySelector('input[name="primary_color"]').addEventListener('change', function() {
    document.querySelector('input[name="primary_color_hex"]').value = this.value;
});

document.querySelector('input[name="primary_color_hex"]').addEventListener('input', function() {
    if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
        document.querySelector('input[name="primary_color"]').value = this.value;
    }
});

// Remove logo function
function removeLogo() {
    if (confirm('Sei sicuro di voler rimuovere il logo?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="section" value="remove_logo">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Remove banner function
function removeBanner() {
    if (confirm('Sei sicuro di voler rimuovere il banner?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="section" value="remove_banner">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>