<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gray-100 overflow-x-hidden">
    <?php include 'views/admin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64 min-w-0">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestione Menu</h1>
                    <p class="text-gray-600 mt-1">
                        <?php if ($selected_category): ?>
                            Piatti della categoria: <strong><?= htmlspecialchars($selected_category['name']) ?></strong>
                        <?php else: ?>
                            Tutti i piatti del menu
                        <?php endif; ?>
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <?php if ($selected_category): ?>
                    <a href="<?= BASE_URL ?>/admin/menu" 
                       class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-list"></i>
                        <span>Tutti i piatti</span>
                    </a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/admin/menu/item/add<?= $selected_category ? '?category=' . $selected_category['id'] : '' ?>" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-plus"></i>
                        <span>Aggiungi Piatto</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Categoria:</label>
                        <select onchange="filterByCategory(this.value)" class="px-3 py-1 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                            <option value="">Tutte le categorie</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($selected_category && $selected_category['id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Stato:</label>
                        <select onchange="filterByStatus(this.value)" class="px-3 py-1 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                            <option value="">Tutti</option>
                            <option value="available">Disponibili</option>
                            <option value="unavailable">Non disponibili</option>
                            <option value="featured">In evidenza</option>
                        </select>
                    </div>
                </div>
            </div>

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

            <!-- Menu Items -->
            <?php if (empty($items)): ?>
            <div class="text-center py-12">
                <div class="mb-6">
                    <i class="fas fa-utensils text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">
                    <?php if ($selected_category): ?>
                        Nessun piatto in questa categoria
                    <?php else: ?>
                        Nessun piatto ancora aggiunto
                    <?php endif; ?>
                </h3>
                <p class="text-gray-500 mb-6">
                    <?php if ($selected_category): ?>
                        Aggiungi il primo piatto a questa categoria!
                    <?php else: ?>
                        Inizia aggiungendo i tuoi primi piatti al menu!
                    <?php endif; ?>
                </p>
                <a href="<?= BASE_URL ?>/admin/menu/item/add<?= $selected_category ? '?category=' . $selected_category['id'] : '' ?>" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Aggiungi Primo Piatto</span>
                </a>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($items as $item): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <!-- Item Image -->
                    <div class="aspect-w-16 aspect-h-9 bg-gray-100 relative">
                        <?php if ($item['image_url']): ?>
                        <img src="<?= BASE_URL ?>/uploads/<?= $item['image_url'] ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>"
                             class="w-full h-48 object-cover">
                        <?php else: ?>
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-utensils text-4xl text-gray-400"></i>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Featured Badge -->
                        <?php if ($item['is_featured']): ?>
                        <div class="absolute top-2 right-2 bg-yellow-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">
                            <i class="fas fa-star"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Item Content -->
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-1"><?= htmlspecialchars($item['name']) ?></h3>
                            <span class="text-xl font-bold text-blue-600 ml-2"><?= $app_settings['currency_symbol'] ?><?= number_format($item['price'], 2) ?></span>
                        </div>
                        
                        <?php if ($item['description']): ?>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2"><?= htmlspecialchars($item['description']) ?></p>
                        <?php endif; ?>
                        
                        <!-- Category & Status -->
                        <div class="flex items-center justify-between text-sm mb-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?= htmlspecialchars($item['category_name']) ?>
                            </span>
                            <?php if ($item['is_available']): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Disponibile
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Non disponibile
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center gap-2">
                            <a href="<?= BASE_URL ?>/admin/menu/item/edit/<?= $item['id'] ?>" 
                               class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 text-blue-600 border border-blue-600 rounded-lg font-medium hover:bg-blue-600 hover:text-white transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                                <span>Modifica</span>
                            </a>
                            <button onclick="toggleAvailability(<?= $item['id'] ?>, <?= $item['is_available'] ? 'false' : 'true' ?>)" 
                                    class="px-3 py-2 <?= $item['is_available'] ? 'text-red-600 border-red-600 hover:bg-red-600' : 'text-green-600 border-green-600 hover:bg-green-600' ?> border rounded-lg hover:text-white transition-colors duration-200"
                                    title="<?= $item['is_available'] ? 'Rendi non disponibile' : 'Rendi disponibile' ?>">
                                <i class="fas fa-<?= $item['is_available'] ? 'eye-slash' : 'eye' ?>"></i>
                            </button>
                            <button onclick="deleteItem(<?= $item['id'] ?>, '<?= addslashes($item['name']) ?>')" 
                                    class="px-3 py-2 text-red-600 border border-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-colors duration-200"
                                    title="Elimina piatto">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Elimina Piatto</h3>
                <p class="text-sm text-gray-500">Questa azione non può essere annullata</p>
            </div>
        </div>
        
        <p class="text-gray-700 mb-6">
            Sei sicuro di voler eliminare il piatto <strong id="itemToDelete"></strong>?
        </p>
        
        <div class="flex items-center gap-3 justify-end">
            <button onclick="closeDeleteModal()" 
                    class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Annulla
            </button>
            <form method="POST" id="deleteForm" class="inline">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="item_id" id="itemIdToDelete">
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Elimina
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function filterByCategory(categoryId) {
    const url = new URL(window.location);
    if (categoryId) {
        url.searchParams.set('category', categoryId);
    } else {
        url.searchParams.delete('category');
    }
    window.location = url;
}

function filterByStatus(status) {
    const url = new URL(window.location);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location = url;
}

function toggleAvailability(itemId, isAvailable) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = `
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="action" value="toggle_availability">
        <input type="hidden" name="item_id" value="${itemId}">
        <input type="hidden" name="is_available" value="${isAvailable}">
    `;
    document.body.appendChild(form);
    form.submit();
}

function deleteItem(id, name) {
    document.getElementById('itemToDelete').textContent = name;
    document.getElementById('itemIdToDelete').value = id;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>