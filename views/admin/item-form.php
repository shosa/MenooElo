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
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Upload Section -->
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700 mb-3">Carica dal Computer</h3>
                                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-600 bg-gray-50 transition-colors cursor-pointer" onclick="document.querySelector('input[name=image]').click()">
                                            <input type="file" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                                            <input type="hidden" name="selected_suggestion_image" id="selectedSuggestionImage">
                                            
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
                                    
                                    <!-- Image Suggestions Section -->
                                    <div>
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-sm font-medium text-gray-700">Suggerimenti AI</h3>
                                            <button type="button" onclick="searchSuggestedImages()" 
                                                    class="text-xs bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition-colors">
                                                <i class="fas fa-search mr-1"></i>Cerca
                                            </button>
                                        </div>
                                        
                                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                                            <div id="imageSuggestions" class="text-center text-gray-500 text-sm">
                                                <i class="fas fa-magic mb-2 text-2xl"></i>
                                                <p>Inserisci il nome del piatto e clicca "Cerca" per vedere suggerimenti di immagini</p>
                                            </div>
                                            
                                            <!-- Loading State -->
                                            <div id="suggestionsLoading" class="text-center text-gray-500 text-sm hidden">
                                                <i class="fas fa-spinner fa-spin mb-2 text-2xl"></i>
                                                <p>Cercando immagini suggerite...</p>
                                            </div>
                                            
                                            <!-- Suggestions Grid - Migliorato -->
                                            <div id="suggestionsGrid" class="grid grid-cols-2 gap-3 hidden"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">Clicca su un'immagine per selezionarla</p>
                                    </div>
                                </div>
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

// Image Suggestions Functions
async function searchSuggestedImages() {
    const nameInput = document.querySelector('input[name="name"]');
    const query = nameInput.value.trim();
    
    if (!query) {
        alert('Inserisci il nome del piatto per cercare suggerimenti di immagini');
        nameInput.focus();
        return;
    }
    
    const suggestionsDiv = document.getElementById('imageSuggestions');
    const loadingDiv = document.getElementById('suggestionsLoading');
    const gridDiv = document.getElementById('suggestionsGrid');
    
    // Show loading state
    suggestionsDiv.classList.add('hidden');
    gridDiv.classList.add('hidden');
    loadingDiv.classList.remove('hidden');
    
    try {
        const response = await fetch(`<?= BASE_URL ?>/api/images/search?q=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.images && data.images.length > 0) {
            displayImageSuggestions(data.images);
        } else {
            showNoResultsMessage();
        }
    } catch (error) {
        console.error('Error fetching image suggestions:', error);
        showErrorMessage();
    } finally {
        loadingDiv.classList.add('hidden');
    }
}

function displayImageSuggestions(images) {
    const gridDiv = document.getElementById('suggestionsGrid');
    
    gridDiv.innerHTML = '';
    
    images.forEach((image, index) => {
        const imageItem = document.createElement('div');
        imageItem.className = 'relative group cursor-pointer';
        
        // Store image data in a global variable to avoid JSON encoding issues
        window[`imageData_${index}`] = image;
        
        imageItem.innerHTML = `
            <div class="suggestion-image-container border-2 border-gray-200 rounded-xl overflow-hidden hover:border-blue-500 hover:shadow-lg transition-all duration-200 cursor-pointer bg-white"
                 data-index="${index}">
                <img src="${image.thumb}" 
                     alt="${image.description}"
                     class="w-full h-32 sm:h-36 object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                    <div class="bg-white bg-opacity-90 rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <i class="fas fa-check text-blue-600 text-lg"></i>
                    </div>
                </div>
                ${image.source === 'unsplash' ? `
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent text-white text-xs p-2">
                        <div class="flex items-center gap-1">
                            <i class="fas fa-camera text-xs opacity-75"></i>
                            <a href="${image.photographer_profile}" target="_blank" class="hover:underline font-medium">${image.photographer_name}</a>
                        </div>
                    </div>
                ` : ''}
                <div class="p-2 bg-white">
                    <p class="text-xs text-gray-600 truncate font-medium">${image.description}</p>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-xs text-blue-600 font-medium">Clicca per selezionare</span>
                        ${image.source === 'unsplash' ? `
                            <a href="https://unsplash.com" target="_blank" class="text-xs text-gray-400 hover:text-gray-600">
                                <i class="fab fa-unsplash"></i>
                            </a>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        
        // Add click event listener to the container
        const imgContainer = imageItem.querySelector('.suggestion-image-container');
        if (imgContainer) {
            imgContainer.addEventListener('click', function() {
                console.log('🔥 Image clicked! Index:', index);
                selectSuggestedImage(image);
            });
        }
        
        gridDiv.appendChild(imageItem);
    });
    
    gridDiv.classList.remove('hidden');
}

async function selectSuggestedImage(imageData) {
    console.log('🖼️ selectSuggestedImage called!');
    console.log('Selecting image:', imageData);
    
    const loadingDiv = document.getElementById('suggestionsLoading');
    const gridDiv = document.getElementById('suggestionsGrid');
    
    // Show loading
    gridDiv.classList.add('hidden');
    loadingDiv.classList.remove('hidden');
    
    try {
        console.log('Sending request to:', `<?= BASE_URL ?>/api/images/select`);
        
        const response = await fetch(`<?= BASE_URL ?>/api/images/select`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ imageData: imageData })
        });
        
        console.log('Response status:', response.status);
        const data = await response.json();
        console.log('Response data:', data);
        
        if (data.success) {
            // Update preview with selected image
            const imagePreview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');
            const previewImageContainer = document.getElementById('previewImageContainer');
            
            const imageUrl = data.external_url || data.url || imageData.url;
            console.log('🖼️ Using image URL:', imageUrl);
            
            if (imagePreview) {
                imagePreview.src = imageUrl;
                imagePreview.classList.remove('hidden');
                // Also clear any previous file selection
                document.querySelector('input[name="image"]').value = '';
            }
            if (previewImage) {
                previewImage.src = imageUrl;
                previewImageContainer.classList.remove('hidden');
            }
            
            // Store image data for form submission
            document.getElementById('selectedSuggestionImage').value = JSON.stringify(data.image_data || imageData);
            
            // Show success message
            showSuccessMessage('Immagine selezionata con successo!');
            
            // Hide suggestions and show success state with attribution
            showImageSelectedState(data.image_data || imageData);
        } else {
            throw new Error(data.error || 'Errore durante la selezione dell\'immagine');
        }
    } catch (error) {
        console.error('Error selecting image:', error);
        alert('Errore durante la selezione dell\'immagine: ' + error.message);
    } finally {
        loadingDiv.classList.add('hidden');
        gridDiv.classList.remove('hidden');
    }
}

function showNoResultsMessage() {
    const suggestionsDiv = document.getElementById('imageSuggestions');
    suggestionsDiv.innerHTML = `
        <i class="fas fa-search mb-2 text-2xl text-gray-400"></i>
        <p>Nessuna immagine trovata per questo piatto. Prova con un nome diverso.</p>
    `;
    suggestionsDiv.classList.remove('hidden');
}

function showErrorMessage() {
    const suggestionsDiv = document.getElementById('imageSuggestions');
    suggestionsDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle mb-2 text-2xl text-red-400"></i>
        <p class="text-red-600">Errore durante la ricerca delle immagini. Riprova più tardi.</p>
    `;
    suggestionsDiv.classList.remove('hidden');
}

function showImageSelectedState(imageData = null) {
    const suggestionsDiv = document.getElementById('imageSuggestions');
    
    let attributionHtml = '';
    if (imageData && imageData.source === 'unsplash') {
        attributionHtml = `
            <div class="mt-3 p-2 bg-blue-50 rounded-lg text-xs">
                <p class="text-blue-800">
                    Photo by <a href="${imageData.photographer_profile}" target="_blank" class="font-medium hover:underline">${imageData.photographer_name}</a> 
                    on <a href="https://unsplash.com" target="_blank" class="font-medium hover:underline">Unsplash</a>
                </p>
            </div>
        `;
    }
    
    suggestionsDiv.innerHTML = `
        <i class="fas fa-check-circle mb-2 text-2xl text-green-500"></i>
        <p class="text-green-600">Immagine selezionata! Puoi cercare altre immagini o salvare il piatto.</p>
        ${attributionHtml}
    `;
    suggestionsDiv.classList.remove('hidden');
}

function showSuccessMessage(message) {
    // Create temporary success toast
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        document.body.removeChild(toast);
    }, 3000);
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