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
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-600 bg-gray-50 transition-colors cursor-pointer" onclick="document.querySelector('input[name=image]').click()">
                                    <input type="file" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                                    
                                    <?php if (isset($item['image_url']) && $item['image_url']): ?>
                                    <div class="text-center">
                                        <img src="<?= BASE_URL ?>/uploads/<?= $item['image_url'] ?>" 
                                             alt="Immagine piatto" 
                                             class="mx-auto rounded-lg shadow-sm max-w-64 max-h-48 object-cover mb-4" id="imagePreview">
                                        <p class="text-gray-600">Click per cambiare immagine</p>
                                    </div>
                                    <?php else: ?>
                                    <div class="text-center text-gray-500">
                                        <i class="fas fa-cloud-upload-alt text-4xl mb-4"></i>
                                        <p class="font-medium">Click per caricare un'immagine</p>
                                        <p class="text-sm">o trascina qui il file</p>
                                        <img class="mx-auto rounded-lg shadow-sm max-w-64 max-h-48 object-cover mt-4 hidden" id="imagePreview">
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Foto appetitosa del piatto. Formati: JPG, PNG, WEBP. Max 5MB.</p>
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
                                    €<?= isset($item['price']) ? number_format($item['price'], 2) : '0.00' ?>
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