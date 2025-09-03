<?php 
$content = ob_start(); 

// Parse features JSON safely
$features = [];
if (isset($restaurant['features']) && !empty($restaurant['features'])) {
    $features = json_decode($restaurant['features'], true) ?? [];
}
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'views/admin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64">
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
                                            <img src="<?= BASE_URL ?>/uploads/<?= $restaurant['logo_url'] ?>" 
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
                                            <img src="<?= BASE_URL ?>/uploads/<?= $restaurant['cover_image_url'] ?>" 
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
                            
                            <!-- Advanced Theme Builder -->
                            <div class="border border-gray-200 rounded-xl p-6 bg-gradient-to-br from-blue-50 to-indigo-50">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-palette text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Theme Builder</h3>
                                        <p class="text-sm text-gray-600">Personalizza l'aspetto del tuo menu</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Color Scheme -->
                                    <div class="space-y-4">
                                        <h4 class="font-semibold text-gray-800">Schema Colori</h4>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Colore Primario
                                            </label>
                                            <div class="flex items-center gap-3">
                                                <input type="color" 
                                                       id="primary-color-picker"
                                                       name="primary_color" 
                                                       value="<?= $restaurant['theme_color'] ?? '#3b82f6' ?>"
                                                       class="w-16 h-10 border border-gray-300 rounded cursor-pointer"
                                                       onchange="updateThemePreview()">
                                                <input type="text" 
                                                       id="primary-color-hex"
                                                       name="primary_color_hex" 
                                                       value="<?= $restaurant['theme_color'] ?? '#3b82f6' ?>"
                                                       placeholder="#3b82f6"
                                                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-24"
                                                       onchange="updateThemePreview()">
                                            </div>
                                        </div>
                                        
                                        <!-- Preset Colors -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Preset Colori</label>
                                            <div class="grid grid-cols-8 gap-2">
                                                <?php 
                                                $presetColors = [
                                                    '#3b82f6' => 'Blu',
                                                    '#ef4444' => 'Rosso',
                                                    '#10b981' => 'Verde',
                                                    '#f59e0b' => 'Arancione',
                                                    '#8b5cf6' => 'Viola',
                                                    '#ec4899' => 'Rosa',
                                                    '#06b6d4' => 'Ciano',
                                                    '#84cc16' => 'Lime'
                                                ];
                                                foreach ($presetColors as $color => $name): ?>
                                                    <button type="button" 
                                                            onclick="selectPresetColor('<?= $color ?>')"
                                                            class="w-8 h-8 rounded-lg border-2 border-gray-300 hover:border-gray-400 transition-colors"
                                                            style="background-color: <?= $color ?>"
                                                            title="<?= $name ?>">
                                                    </button>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        
                                        
                                        
                                        <!-- Typography -->
                                        <div class="space-y-3">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Font Principale</label>
                                            <select name="primary_font" id="font-selector" class="w-full px-3 py-2 border border-gray-300 rounded-lg" onchange="updateFontPreview()">
                                                <option value="Inter" <?= ($restaurant['primary_font'] ?? 'Inter') === 'Inter' ? 'selected' : '' ?>>Inter (Default)</option>
                                                <option value="Roboto" <?= ($restaurant['primary_font'] ?? '') === 'Roboto' ? 'selected' : '' ?>>Roboto</option>
                                                <option value="Open Sans" <?= ($restaurant['primary_font'] ?? '') === 'Open Sans' ? 'selected' : '' ?>>Open Sans</option>
                                                <option value="Poppins" <?= ($restaurant['primary_font'] ?? '') === 'Poppins' ? 'selected' : '' ?>>Poppins</option>
                                                <option value="Montserrat" <?= ($restaurant['primary_font'] ?? '') === 'Montserrat' ? 'selected' : '' ?>>Montserrat</option>
                                                <option value="Playfair Display" <?= ($restaurant['primary_font'] ?? '') === 'Playfair Display' ? 'selected' : '' ?>>Playfair Display</option>
                                                <option value="Dancing Script" <?= ($restaurant['primary_font'] ?? '') === 'Dancing Script' ? 'selected' : '' ?>>Dancing Script</option>
                                                <option value="custom" <?= ($restaurant['primary_font'] ?? '') === 'custom' ? 'selected' : '' ?>>Font Personalizzato</option>
                                            </select>
                                            
                                            <!-- Custom Font Upload -->
                                            <div id="custom-font-section" class="<?= ($restaurant['primary_font'] ?? '') === 'custom' ? '' : 'hidden' ?>">
                                                <?php if (!empty($restaurant['custom_font_name'])): ?>
                                                <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <p class="text-sm font-medium text-green-800">Font Attuale: <?= htmlspecialchars($restaurant['custom_font_name']) ?></p>
                                                            <p class="text-xs text-green-600"><?= htmlspecialchars($restaurant['custom_font_path']) ?></p>
                                                        </div>
                                                        <i class="fas fa-check-circle text-green-600"></i>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                
                                                <div class="border border-dashed border-gray-300 rounded-lg p-4 text-center">
                                                    <input type="file" id="custom-font-upload" name="custom_font" accept=".woff,.woff2,.ttf,.otf" class="hidden" onchange="handleFontUpload(this)">
                                                    <div onclick="document.getElementById('custom-font-upload').click()" class="cursor-pointer">
                                                        <i class="fas fa-upload text-2xl text-gray-400 mb-2"></i>
                                                        <p class="text-sm text-gray-600"><?= !empty($restaurant['custom_font_name']) ? 'Sostituisci Font Personalizzato' : 'Carica Font Personalizzato' ?></p>
                                                        <p class="text-xs text-gray-500 mt-1">WOFF, WOFF2, TTF, OTF (max 2MB)</p>
                                                    </div>
                                                    <div id="font-upload-status" class="mt-2 text-sm hidden"></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Font Preview -->
                                            <div class="border border-gray-200 rounded-lg p-3">
                                                <p id="font-preview-text" class="text-lg" style="font-family: Inter;">
                                                    Anteprima Font - Ristorante MenooElo
                                                </p>
                                                <p id="font-preview-small" class="text-sm text-gray-600 mt-1" style="font-family: Inter;">
                                                    Spaghetti alla Carbonara €12.50
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Live Preview -->
                                    <div class="space-y-4">
                                        <h4 class="font-semibold text-gray-800">Anteprima Live</h4>
                                        
                                        <div id="theme-preview" class="border border-gray-300 rounded-xl p-4 bg-white">
                                            <!-- Mock Menu Item Preview -->
                                            <div class="space-y-4">
                                                <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: rgba(59, 130, 246, 0.1);">
                                                    <div class="w-3 h-3 rounded-full" id="preview-color-dot" style="background-color: #3b82f6;"></div>
                                                    <h5 class="font-semibold" id="preview-restaurant-name"><?= htmlspecialchars($restaurant['name'] ?? 'Nome Ristorante') ?></h5>
                                                </div>
                                                
                                                <div class="border border-gray-200 rounded-lg p-3">
                                                    <h6 class="font-medium text-gray-800 mb-2">Pasta</h6>
                                                    <div class="flex justify-between items-center">
                                                        <div>
                                                            <p class="font-medium">Spaghetti Carbonara</p>
                                                            <p class="text-sm text-gray-600">Pancetta, uova, pecorino romano</p>
                                                        </div>
                                                        <span class="font-bold" id="preview-price" style="color: #3b82f6;">€12.50</span>
                                                    </div>
                                                </div>
                                                
                                                <button type="button" 
                                                        id="preview-button"
                                                        class="w-full py-2 px-4 rounded-lg text-white font-medium transition-colors"
                                                        style="background-color: #3b82f6;">
                                                    Aggiungi al Carrello
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Theme Templates -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Template Predefiniti</label>
                                            <div class="grid grid-cols-2 gap-2">
                                                <button type="button" onclick="applyThemeTemplate('elegant')" 
                                                        class="p-2 border border-gray-200 rounded-lg hover:border-gray-300 text-left">
                                                    <div class="text-sm font-medium">Elegante</div>
                                                    <div class="text-xs text-gray-500">Blu navy e oro</div>
                                                </button>
                                                <button type="button" onclick="applyThemeTemplate('warm')" 
                                                        class="p-2 border border-gray-200 rounded-lg hover:border-gray-300 text-left">
                                                    <div class="text-sm font-medium">Caldo</div>
                                                    <div class="text-xs text-gray-500">Arancione e rosso</div>
                                                </button>
                                                <button type="button" onclick="applyThemeTemplate('nature')" 
                                                        class="p-2 border border-gray-200 rounded-lg hover:border-gray-300 text-left">
                                                    <div class="text-sm font-medium">Natura</div>
                                                    <div class="text-xs text-gray-500">Verde e marrone</div>
                                                </button>
                                                <button type="button" onclick="applyThemeTemplate('modern')" 
                                                        class="p-2 border border-gray-200 rounded-lg hover:border-gray-300 text-left">
                                                    <div class="text-sm font-medium">Moderno</div>
                                                    <div class="text-xs text-gray-500">Grigio e viola</div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
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
                                           <?= ($features['show_prices'] ?? true) ? 'checked' : '' ?>
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                    <div>
                                        <div class="font-medium text-gray-800">Mostra Prezzi</div>
                                        <div class="text-sm text-gray-600">I prezzi saranno visibili nel menu</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" 
                                           name="local_cart_enabled" 
                                           <?= ($features['local_cart_enabled'] ?? false) ? 'checked' : '' ?>
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                    <div>
                                        <div class="font-medium text-gray-800">Attiva Carrello Locale</div>
                                        <div class="text-sm text-gray-600">I clienti potranno aggiungere piatti al carrello temporaneo</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" 
                                           name="qrcode" 
                                           <?= ($features['qrcode'] ?? false) ? 'checked' : '' ?>
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                    <div>
                                        <div class="font-medium text-gray-800">Abilita QR Code Menu</div>
                                        <div class="text-sm text-gray-600">Mostra bottone QR per condividere il menu</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" 
                                           name="show_descriptions" 
                                           <?= ($features['show_descriptions'] ?? true) ? 'checked' : '' ?>
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                    <div>
                                        <div class="font-medium text-gray-800">Mostra Descrizioni</div>
                                        <div class="text-sm text-gray-600">Le descrizioni dei piatti saranno visibili</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" 
                                           name="show_images" 
                                           <?= ($features['show_images'] ?? true) ? 'checked' : '' ?>
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

// Theme Builder Functions
function updateThemePreview() {
    const colorPicker = document.getElementById('primary-color-picker');
    const colorHex = document.getElementById('primary-color-hex');
    const color = colorPicker.value;
    
    // Sync inputs
    colorHex.value = color;
    
    // Update preview elements
    const previewDot = document.getElementById('preview-color-dot');
    const previewPrice = document.getElementById('preview-price');
    const previewButton = document.getElementById('preview-button');
    const themePreview = document.getElementById('theme-preview');
    
    if (previewDot) previewDot.style.backgroundColor = color;
    if (previewPrice) previewPrice.style.color = color;
    if (previewButton) previewButton.style.backgroundColor = color;
    
    // Update header background
    const headerBg = themePreview.querySelector('.p-3');
    if (headerBg) {
        headerBg.style.backgroundColor = `${color}20`; // 20 = 12.5% opacity in hex
    }
}

function selectPresetColor(color) {
    const colorPicker = document.getElementById('primary-color-picker');
    const colorHex = document.getElementById('primary-color-hex');
    
    colorPicker.value = color;
    colorHex.value = color;
    updateThemePreview();
}

function applyThemeTemplate(template) {
    const templates = {
        elegant: {
            color: '#1e3a8a',
            name: 'Elegante'
        },
        warm: {
            color: '#ea580c',
            name: 'Caldo'
        },
        nature: {
            color: '#059669',
            name: 'Natura'
        },
        modern: {
            color: '#7c3aed',
            name: 'Moderno'
        }
    };
    
    const themeData = templates[template];
    if (themeData) {
        selectPresetColor(themeData.color);
        
        // Show confirmation
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = `Template "${themeData.name}" applicato!`;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 3000);
    }
}

// Font Management Functions
function updateFontPreview() {
    const fontSelector = document.getElementById('font-selector');
    const customFontSection = document.getElementById('custom-font-section');
    const previewText = document.getElementById('font-preview-text');
    const previewSmall = document.getElementById('font-preview-small');
    
    const selectedFont = fontSelector.value;
    
    if (selectedFont === 'custom') {
        customFontSection.classList.remove('hidden');
    } else {
        customFontSection.classList.add('hidden');
        
        // Update preview with Google Fonts
        if (selectedFont !== 'Inter') {
            loadGoogleFont(selectedFont);
        }
        
        previewText.style.fontFamily = selectedFont;
        previewSmall.style.fontFamily = selectedFont;
    }
}

function loadGoogleFont(fontName) {
    const link = document.createElement('link');
    link.href = `https://fonts.googleapis.com/css2?family=${fontName.replace(' ', '+')}:wght@400;500;600;700&display=swap`;
    link.rel = 'stylesheet';
    
    // Remove previous Google Font links
    document.querySelectorAll('link[href*="googleapis.com/css"]').forEach(link => {
        if (link.href.includes(fontName.replace(' ', '+'))) return;
        link.remove();
    });
    
    document.head.appendChild(link);
}

function handleFontUpload(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const statusDiv = document.getElementById('font-upload-status');
        
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            statusDiv.className = 'mt-2 text-sm text-red-600';
            statusDiv.textContent = 'File troppo grande. Max 2MB.';
            statusDiv.classList.remove('hidden');
            return;
        }
        
        // Validate file type
        const validTypes = ['.woff', '.woff2', '.ttf', '.otf'];
        const fileExt = '.' + file.name.split('.').pop().toLowerCase();
        if (!validTypes.includes(fileExt)) {
            statusDiv.className = 'mt-2 text-sm text-red-600';
            statusDiv.textContent = 'Formato non supportato. Usa WOFF, WOFF2, TTF o OTF.';
            statusDiv.classList.remove('hidden');
            return;
        }
        
        // Show success and preview
        statusDiv.className = 'mt-2 text-sm text-green-600';
        statusDiv.textContent = `Font "${file.name}" caricato con successo!`;
        statusDiv.classList.remove('hidden');
        
        // Create font face for preview
        const fontName = 'CustomFont_' + Date.now();
        const fontUrl = URL.createObjectURL(file);
        
        const style = document.createElement('style');
        style.textContent = `
            @font-face {
                font-family: '${fontName}';
                src: url('${fontUrl}') format('${getFontFormat(fileExt)}');
            }
        `;
        document.head.appendChild(style);
        
        // Update preview
        const previewText = document.getElementById('font-preview-text');
        const previewSmall = document.getElementById('font-preview-small');
        previewText.style.fontFamily = fontName;
        previewSmall.style.fontFamily = fontName;
        
        setTimeout(() => {
            statusDiv.classList.add('hidden');
        }, 5000);
    }
}

function getFontFormat(extension) {
    const formats = {
        '.woff': 'woff',
        '.woff2': 'woff2',
        '.ttf': 'truetype',
        '.otf': 'opentype'
    };
    return formats[extension] || 'truetype';
}


// Initialize theme preview on page load
document.addEventListener('DOMContentLoaded', function() {
    updateThemePreview();
    updateFontPreview();
    
    // Add event listeners for better UX
    const colorPicker = document.getElementById('primary-color-picker');
    const colorHex = document.getElementById('primary-color-hex');
    
    if (colorPicker) {
        colorPicker.addEventListener('input', updateThemePreview);
    }
    
    if (colorHex) {
        colorHex.addEventListener('input', function() {
            if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                const colorPicker = document.getElementById('primary-color-picker');
                if (colorPicker) {
                    colorPicker.value = this.value;
                    updateThemePreview();
                }
            }
        });
    }
});
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>