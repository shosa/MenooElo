<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-0">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestione Ristoranti</h1>
                    <p class="text-gray-600 mt-1">Totale: <?= $total ?> ristoranti</p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>/superadmin/restaurant/add" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors duration-200">
                        <i class="fas fa-plus"></i>
                        <span>Nuovo Ristorante</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <form method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:items-end sm:gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cerca ristorante</label>
                        <input type="text" 
                               name="search" 
                               value="<?= htmlspecialchars($search) ?>" 
                               placeholder="Nome, slug o email..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stato</label>
                        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Tutti</option>
                            <option value="1" <?= $status === '1' ? 'selected' : '' ?>>Attivi</option>
                            <option value="0" <?= $status === '0' ? 'selected' : '' ?>>Inattivi</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php if ($search || $status !== ''): ?>
                        <a href="<?= BASE_URL ?>/superadmin/restaurants" 
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-6 flex items-start gap-3">
                <i class="fas fa-check-circle mt-0.5"></i>
                <span>
                    <?php if ($_GET['success'] === 'created'): ?>
                        Ristorante creato con successo!
                    <?php elseif ($_GET['success'] === 'updated'): ?>
                        Ristorante aggiornato con successo!
                    <?php elseif ($_GET['success'] === 'deleted'): ?>
                        Ristorante eliminato con successo!
                    <?php endif; ?>
                </span>
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 flex items-start gap-3">
                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                <span>
                    <?php if ($_GET['error'] === 'not_found'): ?>
                        Ristorante non trovato!
                    <?php elseif ($_GET['error'] === 'delete_failed'): ?>
                        Errore durante l'eliminazione del ristorante!
                    <?php endif; ?>
                </span>
            </div>
            <?php endif; ?>

            <!-- Restaurants Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <?php if (empty($restaurants)): ?>
                <div class="text-center py-12">
                    <div class="mb-6">
                        <i class="fas fa-store text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Nessun ristorante trovato</h3>
                    <p class="text-gray-500 mb-6">Inizia creando il tuo primo ristorante!</p>
                    <a href="<?= BASE_URL ?>/superadmin/restaurant/add" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors duration-200">
                        <i class="fas fa-plus"></i>
                        <span>Crea Primo Ristorante</span>
                    </a>
                </div>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ristorante</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Contatto</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Piatti</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Creato</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($restaurants as $restaurant): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($restaurant['name']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500"><?= $restaurant['slug'] ?></div>
                                        <!-- Mobile contact info -->
                                        <div class="mt-1 lg:hidden">
                                            <?php if ($restaurant['email']): ?>
                                            <div class="text-xs text-gray-500"><?= htmlspecialchars($restaurant['email']) ?></div>
                                            <?php endif; ?>
                                            <?php if ($restaurant['phone']): ?>
                                            <div class="text-xs text-gray-500"><?= htmlspecialchars($restaurant['phone']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                    <div class="text-sm text-gray-900">
                                        <?= htmlspecialchars($restaurant['email'] ?? 'N/A') ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= htmlspecialchars($restaurant['phone'] ?? 'N/A') ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?= $restaurant['admin_count'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <?= $restaurant['menu_items_count'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($restaurant['is_active']): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Attivo
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Inattivo
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                    <?= date('d/m/Y', strtotime($restaurant['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="<?= BASE_URL ?>/restaurant/<?= $restaurant['slug'] ?>" 
                                           class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50 transition-colors"
                                           target="_blank"
                                           title="Visualizza menu">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/superadmin/restaurant/edit/<?= $restaurant['id'] ?>" 
                                           class="text-green-600 hover:text-green-900 p-2 rounded hover:bg-green-50 transition-colors"
                                           title="Modifica">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmDelete(<?= $restaurant['id'] ?>, '<?= addslashes($restaurant['name']) ?>')" 
                                                class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50 transition-colors"
                                                title="Elimina">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="px-6 py-3 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Pagina <?= $page ?> di <?= $total_pages ?> (<?= $total ?> ristoranti totali)
                        </div>
                        <nav class="flex items-center gap-2">
                            <?php if ($page > 1): ?>
                            <a href="<?= BASE_URL ?>/superadmin/restaurants?page=<?= $page - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status !== '' ? '&status=' . $status : '' ?>" 
                               class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $page + 2);
                            
                            for ($i = $start_page; $i <= $end_page; $i++):
                            ?>
                            <a href="<?= BASE_URL ?>/superadmin/restaurants?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status !== '' ? '&status=' . $status : '' ?>" 
                               class="px-3 py-2 <?= $i === $page ? 'bg-red-600 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50' ?> rounded-lg transition-colors">
                                <?= $i ?>
                            </a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                            <a href="<?= BASE_URL ?>/superadmin/restaurants?page=<?= $page + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status !== '' ? '&status=' . $status : '' ?>" 
                               class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6">
        <div class="text-center">
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle text-6xl text-red-500"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Conferma Eliminazione</h3>
            <p class="text-gray-600 mb-6">
                Sei sicuro di voler eliminare il ristorante <strong id="restaurantName"></strong>?
                <br><br>
                <span class="text-red-600 font-medium">Questa azione non può essere annullata!</span>
            </p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Annulla
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Elimina
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(restaurantId, restaurantName) {
    document.getElementById('restaurantName').textContent = restaurantName;
    document.getElementById('deleteForm').action = '<?= BASE_URL ?>/superadmin/restaurants/delete/' + restaurantId;
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

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>