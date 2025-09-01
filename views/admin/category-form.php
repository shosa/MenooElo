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
                        <?= $category ? 'Modifica Categoria' : 'Aggiungi Categoria' ?>
                    </h1>
                    <p class="text-gray-600 mt-1">
                        <?= $category ? 'Modifica le informazioni della categoria' : 'Crea una nuova categoria per il menu' ?>
                    </p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>/admin/categories" 
                       class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-arrow-left"></i>
                        <span>Torna alle Categorie</span>
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
                    <!-- Category Form -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <form method="POST" enctype="multipart/form-data" class="space-y-6">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nome Categoria <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       required 
                                       value="<?= htmlspecialchars($category['name'] ?? '') ?>"
                                       placeholder="Es. Antipasti, Primi Piatti, Pizza..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors">
                                <p class="text-sm text-gray-500 mt-1">Nome della categoria che apparirà nel menu</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Descrizione
                                </label>
                                <textarea name="description" 
                                          rows="3"
                                          placeholder="Breve descrizione della categoria (opzionale)"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors resize-y"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                                <p class="text-sm text-gray-500 mt-1">Descrizione opzionale mostrata sotto il nome della categoria</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Immagine Categoria
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-600 bg-gray-50 transition-colors cursor-pointer" onclick="document.querySelector('input[type=file]').click()">
                                    <input type="file" name="image" accept="image/*" class="hidden">
                                    
                                    <?php if (isset($category['image_url']) && $category['image_url']): ?>
                                    <div class="text-center">
                                        <img src="<?= BASE_URL ?>/uploads/<?= $category['image_url'] ?>" 
                                             alt="Immagine categoria" 
                                             class="mx-auto rounded-lg shadow-sm max-w-48 max-h-48 object-cover mb-4" id="imagePreview">
                                        <p class="text-gray-600">Click per cambiare immagine</p>
                                    </div>
                                    <?php else: ?>
                                    <div class="text-center text-gray-500">
                                        <i class="fas fa-cloud-upload-alt text-4xl mb-4"></i>
                                        <p class="font-medium">Click per caricare un'immagine</p>
                                        <p class="text-sm">o trascina qui il file</p>
                                        <img class="mx-auto rounded-lg shadow-sm max-w-48 max-h-48 object-cover mt-4 hidden" id="imagePreview">
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Immagine rappresentativa della categoria (opzionale). Formati: JPG, PNG, WEBP. Max 5MB.</p>
                            </div>
                            
                            <div>
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           <?= ($category['is_active'] ?? 1) ? 'checked' : '' ?>
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                    <span class="text-sm font-medium text-gray-700">Categoria attiva</span>
                                </label>
                                <p class="text-sm text-gray-500 mt-1">Le categorie inattive non saranno visibili nel menu pubblico</p>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-6">
                                <div class="flex flex-wrap gap-3">
                                    <button type="submit" 
                                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-save"></i>
                                        <span><?= $category ? 'Aggiorna Categoria' : 'Crea Categoria' ?></span>
                                    </button>
                                    <a href="<?= BASE_URL ?>/admin/categories" 
                                       class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                                        Annulla
                                    </a>
                                    <?php if ($category): ?>
                                    <a href="<?= BASE_URL ?>/admin/menu/category/<?= $category['id'] ?>" 
                                       class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors duration-200">
                                        <i class="fas fa-utensils"></i>
                                        <span>Gestisci Piatti</span>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                    
                <div>
                    <!-- Preview -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-eye text-blue-600"></i>
                            Anteprima
                        </h3>
                        
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="<?= (isset($category['image_url']) && $category['image_url']) ? '' : 'hidden' ?>" id="previewImageContainer">
                                <img src="<?= isset($category['image_url']) && $category['image_url'] ? BASE_URL . '/uploads/' . $category['image_url'] : '' ?>" 
                                     alt="Anteprima categoria" 
                                     id="previewImage"
                                     class="w-full h-32 object-cover">
                            </div>
                            <div class="p-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2" id="previewName">
                                    <?= htmlspecialchars($category['name'] ?? 'Nome Categoria') ?>
                                </h4>
                                <p class="text-gray-600 text-sm <?= (isset($category['description']) && $category['description']) ? '' : 'hidden' ?>" 
                                   id="previewDescription">
                                    <?= htmlspecialchars($category['description'] ?? '') ?>
                                </p>
                            </div>
                        </div>
                        
                        <p class="text-center text-sm text-gray-500 mt-4">
                            Così apparirà la categoria nel tuo menu
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
                                    <p class="font-semibold">Nome chiaro</p>
                                    <p class="text-gray-600">Usa nomi facilmente comprensibili (es. "Antipasti" invece di "Starter")</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check text-green-500 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold">Descrizioni brevi</p>
                                    <p class="text-gray-600">Massimo 2-3 righe per non appesantire il menu</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check text-green-500 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold">Immagini di qualità</p>
                                    <p class="text-gray-600">Usa foto nitide e ben illuminate</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check text-green-500 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold">Ordine logico</p>
                                    <p class="text-gray-600">Le categorie seguiranno l'ordine del pasto tradizionale</p>
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
// Real-time preview update
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.querySelector('input[name="name"]');
    const descInput = document.querySelector('textarea[name="description"]');
    const previewName = document.getElementById('previewName');
    const previewDesc = document.getElementById('previewDescription');
    
    nameInput.addEventListener('input', function() {
        previewName.textContent = this.value || 'Nome Categoria';
    });
    
    descInput.addEventListener('input', function() {
        if (this.value.trim()) {
            previewDesc.textContent = this.value;
            previewDesc.classList.remove('hidden');
        } else {
            previewDesc.classList.add('hidden');
        }
    });
    
    // Image preview
    const fileInput = document.querySelector('input[type="file"]');
    const previewImage = document.getElementById('previewImage');
    const previewImageContainer = document.getElementById('previewImageContainer');
    const imagePreview = document.getElementById('imagePreview');
    
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (imagePreview) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                }
                if (previewImage) {
                    previewImage.src = e.target.result;
                    previewImageContainer.classList.remove('hidden');
                }
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
});
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>