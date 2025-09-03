<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'views/admin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <?= $item ? 'Modifica Piatto' : 'Aggiungi Piatto' ?>
                    </h1>
                    <p class="text-gray-600 mt-1">
                        <?= $item ? 'Modifica le informazioni del piatto' : 'Aggiungi un nuovo piatto al menu' ?>
                    </p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>/admin/menu" 
                       class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-arrow-left"></i>
                        <span>Torna al Menu</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Messages -->
            <?php if (isset($error)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 flex items-start gap-3">
                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>
                
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <!-- Main Form -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <form method="POST" enctype="multipart/form-data" class="space-y-6">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nome Piatto <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           required 
                                           value="<?= htmlspecialchars($item['name'] ?? '') ?>"
                                           placeholder="Es. Spaghetti alla Carbonara"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors"
                                           onkeyup="updatePreview()">
                                    <p class="text-sm text-gray-500 mt-1">Nome che apparirà nel menu</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Prezzo <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</span>
                                        <input type="number" 
                                               name="price" 
                                               required 
                                               step="0.01"
                                               min="0"
                                               value="<?= $item['price'] ?? '' ?>"
                                               placeholder="12.50"
                                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors"
                                               onkeyup="updatePreview()">
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Categoria <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors">
                                    <option value="">Seleziona una categoria</option>
                                    <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" 
                                            <?= (isset($item['category_id']) && $item['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Descrizione
                                </label>
                                <textarea name="description" 
                                          rows="4"
                                          placeholder="Descrizione appetitosa del piatto, ingredienti principali, metodo di cottura..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors resize-y"
                                          onkeyup="updatePreview()"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
                                <p class="text-sm text-gray-500 mt-1">Descrizione che aiuterà i clienti a scegliere</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Immagine Piatto
                                </label>
                                
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-600 bg-gray-50 transition-colors cursor-pointer" onclick="document.querySelector('input[name=image]').click()">
                                    <input type="file" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                                    
                                    <?php if (isset($item['image_url']) && $item['image_url']): ?>
                                    <div class="text-center">
                                        <img src="<?= BASE_URL ?>/uploads/<?= $item['image_url'] ?>" 
                                             alt="Immagine piatto" 
                                             class="mx-auto rounded-lg shadow-sm max-w-full max-h-32 object-cover mb-3" id="imagePreview">
                                        <p class="text-gray-600 text-sm">Click per cambiare</p>
                                    </div>
                                    <?php else: ?>
                                    <div class="text-center text-gray-500">
                                        <i class="fas fa-cloud-upload-alt text-3xl mb-3"></i>
                                        <p class="font-medium text-sm">Carica Immagine</p>
                                        <p class="text-xs">o trascina qui il file</p>
                                        <img class="mx-auto rounded-lg shadow-sm max-w-full max-h-32 object-cover mt-3 hidden" id="imagePreview">
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">JPG, PNG, WEBP. Max 5MB.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Ingredienti
                                </label>
                                <textarea name="ingredients" 
                                          rows="2"
                                          placeholder="Es. Pomodoro, mozzarella, basilico, olio extravergine..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors resize-y"><?= htmlspecialchars($item['ingredients'] ?? '') ?></textarea>
                                <p class="text-sm text-gray-500 mt-1">Elenco degli ingredienti principali</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Allergeni
                                </label>
                                <?php 
                                $itemAllergens = [];
                                if (isset($item['allergens'])) {
                                    $itemAllergens = is_string($item['allergens']) ? json_decode($item['allergens'], true) ?? [] : $item['allergens'];
                                }
                                
                                $commonAllergens = [
                                    'glutine' => 'Glutine',
                                    'crostacei' => 'Crostacei',
                                    'uova' => 'Uova',
                                    'pesce' => 'Pesce',
                                    'arachidi' => 'Arachidi',
                                    'soia' => 'Soia',
                                    'latte' => 'Latte',
                                    'frutta_secca' => 'Frutta a guscio',
                                    'sedano' => 'Sedano',
                                    'senape' => 'Senape',
                                    'sesamo' => 'Sesamo',
                                    'solfiti' => 'Solfiti',
                                    'lupini' => 'Lupini',
                                    'molluschi' => 'Molluschi'
                                ];
                                ?>
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                    <?php foreach ($commonAllergens as $key => $label): ?>
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="checkbox" 
                                               name="allergens[]" 
                                               value="<?= $key ?>"
                                               <?= in_array($key, $itemAllergens) ? 'checked' : '' ?>
                                               class="rounded border-gray-300 text-orange-600 focus:ring-orange-600 focus:ring-2">
                                        <span class="text-gray-700"><?= $label ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Seleziona gli allergeni presenti nel piatto per informare i clienti</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Varianti del Piatto
                                </label>
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                    <div id="variants-container">
                                        <?php if (isset($variants) && !empty($variants)): ?>
                                            <?php foreach ($variants as $index => $variant): ?>
                                                <div class="variant-item flex gap-3 items-end mb-3 p-3 bg-white rounded-lg border border-gray-200">
                                                    <div class="flex-1">
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nome Variante</label>
                                                        <input type="text" 
                                                               name="variants[<?= $index ?>][name]" 
                                                               value="<?= htmlspecialchars($variant['name'] ?? '') ?>"
                                                               placeholder="Es. Piccola, Media, Grande"
                                                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-600 focus:border-blue-600">
                                                    </div>
                                                    <div class="w-32">
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Modifica Prezzo</label>
                                                        <div class="relative">
                                                            <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">€</span>
                                                            <input type="number" 
                                                                   name="variants[<?= $index ?>][price_modifier]" 
                                                                   value="<?= $variant['price_modifier'] ?? '0' ?>"
                                                                   step="0.01"
                                                                   placeholder="0.00"
                                                                   class="w-full pl-6 pr-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-600 focus:border-blue-600">
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <label class="flex items-center gap-1 text-xs">
                                                            <input type="checkbox" 
                                                                   name="variants[<?= $index ?>][is_default]" 
                                                                   <?= ($variant['is_default'] ?? 0) ? 'checked' : '' ?>
                                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                                            <span class="text-gray-700">Default</span>
                                                        </label>
                                                    </div>
                                                    <button type="button" onclick="removeVariant(this)" class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="variant-item flex gap-3 items-end mb-3 p-3 bg-white rounded-lg border border-gray-200">
                                                <div class="flex-1">
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nome Variante</label>
                                                    <input type="text" 
                                                           name="variants[0][name]" 
                                                           placeholder="Es. Piccola, Media, Grande"
                                                           class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-600 focus:border-blue-600">
                                                </div>
                                                <div class="w-32">
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Modifica Prezzo</label>
                                                    <div class="relative">
                                                        <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">€</span>
                                                        <input type="number" 
                                                               name="variants[0][price_modifier]" 
                                                               value="0"
                                                               step="0.01"
                                                               placeholder="0.00"
                                                               class="w-full pl-6 pr-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-600 focus:border-blue-600">
                                                    </div>
                                                </div>
                                                <div class="flex items-center">
                                                    <label class="flex items-center gap-1 text-xs">
                                                        <input type="checkbox" 
                                                               name="variants[0][is_default]" 
                                                               checked
                                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                                        <span class="text-gray-700">Default</span>
                                                    </label>
                                                </div>
                                                <button type="button" onclick="removeVariant(this)" class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" onclick="addVariant()" class="inline-flex items-center gap-2 px-3 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-plus"></i>
                                        Aggiungi Variante
                                    </button>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Le varianti permettono di offrire diverse taglie o opzioni per lo stesso piatto (es. Pizza piccola/grande, Porzione normale/abbondante)</p>
                            </div>
                            
                            <div class="space-y-3">
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" 
                                           name="is_available" 
                                           <?= ($item['is_available'] ?? 1) ? 'checked' : '' ?>
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                    <span class="text-sm font-medium text-gray-700">Piatto disponibile</span>
                                </label>
                                <p class="text-sm text-gray-500 ml-6">I piatti non disponibili non saranno visibili nel menu pubblico</p>
                                
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" 
                                           name="is_featured" 
                                           <?= ($item['is_featured'] ?? 0) ? 'checked' : '' ?>
                                           class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-600">
                                    <span class="text-sm font-medium text-gray-700">Metti in evidenza</span>
                                </label>
                                <p class="text-sm text-gray-500 ml-6">I piatti in evidenza avranno un badge speciale nel menu</p>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-6">
                                <div class="flex flex-wrap gap-3">
                                    <button type="submit" 
                                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-save"></i>
                                        <span><?= $item ? 'Aggiorna Piatto' : 'Crea Piatto' ?></span>
                                    </button>
                                    <a href="<?= BASE_URL ?>/admin/menu" 
                                       class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                                        Annulla
                                    </a>
                                    <?php if ($item): ?>
                                    <button type="button" 
                                            onclick="duplicateItem()" 
                                            class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors duration-200">
                                        <i class="fas fa-copy"></i>
                                        <span>Duplica Piatto</span>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                    
                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Preview -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-eye text-blue-600"></i>
                            Anteprima
                        </h3>
                        
                        <div class="menu-item-card" id="previewCard">
                            <div class="<?= (isset($item['image_url']) && $item['image_url']) ? '' : 'hidden' ?>" id="previewImageContainer">
                                <img src="<?= isset($item['image_url']) && $item['image_url'] ? BASE_URL . '/uploads/' . $item['image_url'] : '' ?>" 
                                     alt="Anteprima piatto" 
                                     id="previewImage"
                                     class="w-full h-32 object-cover rounded-lg mb-3">
                            </div>
                            
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="text-lg font-semibold text-gray-900" id="previewName">
                                    <?= htmlspecialchars($item['name'] ?? 'Nome Piatto') ?>
                                </h4>
                                <span class="text-xl font-bold text-blue-600 ml-2" id="previewPrice">
                                    <?= $app_settings['currency_symbol'] ?>?< isset($item['price']) ? number_format($item['price'], 2) : '0.00' ?>
                                </span>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-3 <?= (isset($item['description']) && $item['description']) ? '' : 'hidden' ?>" 
                               id="previewDescription">
                                <?= htmlspecialchars($item['description'] ?? '') ?>
                            </p>
                            
                            <div class="flex flex-wrap gap-1 mb-3" id="previewBadges">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 hidden" id="badgeFeatured">
                                    <i class="fas fa-star mr-1"></i> In Evidenza
                                </span>
                            </div>
                        </div>
                        
                        <p class="text-center text-sm text-gray-500 mt-4">
                            Così apparirà il piatto nel menu
                        </p>
                    </div>
                    
                    <!-- Tips -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-yellow-500"></i>
                            Suggerimenti
                        </h3>
                        
                        <div class="space-y-4 text-sm">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check text-green-500 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold">Nome accattivante</p>
                                    <p class="text-gray-600">Usa nomi che fanno venire l'acquolina in bocca</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check text-green-500 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold">Descrizioni dettagliate</p>
                                    <p class="text-gray-600">Includi ingredienti principali e metodo di cottura</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check text-green-500 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold">Foto di qualità</p>
                                    <p class="text-gray-600">Immagini ben illuminate e composte aumentano le vendite</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check text-green-500 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold">Prezzi onesti</p>
                                    <p class="text-gray-600">Mantieni un buon rapporto qualità-prezzo</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updatePreview() {
    // Update name
    const name = document.querySelector('input[name="name"]').value || 'Nome Piatto';
    document.getElementById('previewName').textContent = name;
    
    // Update price
    const price = parseFloat(document.querySelector('input[name="price"]').value) || 0;
    document.getElementById('previewPrice').textContent = '€' + price.toFixed(2);
    
    // Update description
    const description = document.querySelector('textarea[name="description"]').value;
    const previewDesc = document.getElementById('previewDescription');
    if (description.trim()) {
        previewDesc.textContent = description;
        previewDesc.classList.remove('hidden');
    } else {
        previewDesc.classList.add('hidden');
    }
    
    // Update badges
    updateBadges();
}

function updateBadges() {
    const featured = document.querySelector('input[name="is_featured"]').checked;
    document.getElementById('badgeFeatured').classList.toggle('hidden', !featured);
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImage = document.getElementById('previewImage');
            const previewImageContainer = document.getElementById('previewImageContainer');
            const imagePreview = document.getElementById('imagePreview');
            
            if (imagePreview) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
            }
            if (previewImage) {
                previewImage.src = e.target.result;
                previewImageContainer.classList.remove('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}


// Variants Management Functions
let variantIndex = <?= isset($variants) ? count($variants) : 1 ?>;

function addVariant() {
    const container = document.getElementById('variants-container');
    const variantHTML = `
        <div class="variant-item flex gap-3 items-end mb-3 p-3 bg-white rounded-lg border border-gray-200">
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-700 mb-1">Nome Variante</label>
                <input type="text" 
                       name="variants[${variantIndex}][name]" 
                       placeholder="Es. Piccola, Media, Grande"
                       class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-600 focus:border-blue-600">
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-gray-700 mb-1">Modifica Prezzo</label>
                <div class="relative">
                    <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">€</span>
                    <input type="number" 
                           name="variants[${variantIndex}][price_modifier]" 
                           value="0"
                           step="0.01"
                           placeholder="0.00"
                           class="w-full pl-6 pr-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-600 focus:border-blue-600">
                </div>
            </div>
            <div class="flex items-center">
                <label class="flex items-center gap-1 text-xs">
                    <input type="checkbox" 
                           name="variants[${variantIndex}][is_default]" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                    <span class="text-gray-700">Default</span>
                </label>
            </div>
            <button type="button" onclick="removeVariant(this)" class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', variantHTML);
    variantIndex++;
}

function removeVariant(button) {
    const variantItem = button.closest('.variant-item');
    const container = document.getElementById('variants-container');
    
    // Don't remove if it's the last variant
    if (container.children.length > 1) {
        variantItem.remove();
    } else {
        alert('Deve rimanere almeno una variante. Lascia vuoto il nome per non creare varianti.');
    }
}

function duplicateItem() {
    if (confirm('Vuoi creare una copia di questo piatto?')) {
        // Create a form to duplicate the item
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/admin/menu/item/duplicate/<?= $item['id'] ?? 0 ?>';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = 'csrf_token';
        csrfToken.value = '<?= $csrf_token ?>';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize preview and badge updates
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateBadges);
    });
    
    // Initial badge update
    updateBadges();
});
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>