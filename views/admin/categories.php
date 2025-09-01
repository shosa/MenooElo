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
                    <h1 class="text-3xl font-bold text-gray-900">Gestione Categorie</h1>
                    <p class="text-gray-600 mt-1">Organizza il tuo menu in categorie</p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>/admin/categories/add" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-plus"></i>
                        <span>Aggiungi Categoria</span>
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

            <!-- Categories Grid -->
            <?php if (empty($categories)): ?>
            <div class="text-center py-12">
                <div class="mb-6">
                    <i class="fas fa-list text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Nessuna categoria ancora creata</h3>
                <p class="text-gray-500 mb-6">Inizia creando la tua prima categoria per organizzare il menu!</p>
                <a href="<?= BASE_URL ?>/admin/categories/add" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Crea Prima Categoria</span>
                </a>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($categories as $category): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <!-- Category Image -->
                    <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                        <?php if ($category['image_url']): ?>
                        <img src="<?= BASE_URL ?>/uploads/<?= $category['image_url'] ?>" 
                             alt="<?= htmlspecialchars($category['name']) ?>"
                             class="w-full h-48 object-cover">
                        <?php else: ?>
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-list text-4xl text-gray-400"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Category Content -->
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($category['name']) ?></h3>
                            <div class="flex items-center gap-1">
                                <?php if ($category['is_active']): ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Attiva
                                </span>
                                <?php else: ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Inattiva
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($category['description']): ?>
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2"><?= htmlspecialchars($category['description']) ?></p>
                        <?php endif; ?>
                        
                        <!-- Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span class="flex items-center gap-1">
                                <i class="fas fa-utensils"></i>
                                <?= $category['items_count'] ?> piatti
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-calendar"></i>
                                <?= date('d/m/Y', strtotime($category['created_at'])) ?>
                            </span>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center gap-2">
                            <a href="<?= BASE_URL ?>/admin/categories/edit/<?= $category['id'] ?>" 
                               class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 text-blue-600 border border-blue-600 rounded-lg font-medium hover:bg-blue-600 hover:text-white transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                                <span>Modifica</span>
                            </a>
                            <a href="<?= BASE_URL ?>/admin/menu/category/<?= $category['id'] ?>" 
                               class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-utensils"></i>
                                <span>Piatti</span>
                            </a>
                            <button onclick="deleteCategory(<?= $category['id'] ?>, '<?= addslashes($category['name']) ?>')" 
                                    class="px-3 py-2 text-red-600 border border-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-colors duration-200">
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

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Elimina Categoria</h3>
                <p class="text-sm text-gray-500">Questa azione non può essere annullata</p>
            </div>
        </div>
        
        <p class="text-gray-700 mb-6">
            Sei sicuro di voler eliminare la categoria <strong id="categoryToDelete"></strong>? 
            Tutti i piatti associati verranno eliminati.
        </p>
        
        <div class="flex items-center gap-3 justify-end">
            <button onclick="closeDeleteModal()" 
                    class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Annulla
            </button>
            <form method="POST" id="deleteForm" class="inline">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="category_id" id="categoryIdToDelete">
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Elimina
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function deleteCategory(id, name) {
    document.getElementById('categoryToDelete').textContent = name;
    document.getElementById('categoryIdToDelete').value = id;
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