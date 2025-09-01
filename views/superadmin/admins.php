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
                    <h1 class="text-3xl font-bold text-gray-900">Admin Ristoranti</h1>
                    <p class="text-gray-600 mt-1">Totale: <?= $total ?> admin</p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>/superadmin/admin/add" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors duration-200">
                        <i class="fas fa-plus"></i>
                        <span>Nuovo Admin</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Success/Error Messages -->
            <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-6 flex items-start gap-3">
                <i class="fas fa-check-circle mt-0.5"></i>
                <span>
                    <?php if ($_GET['success'] === 'created'): ?>
                        Admin creato con successo!
                    <?php elseif ($_GET['success'] === 'updated'): ?>
                        Admin aggiornato con successo!
                    <?php elseif ($_GET['success'] === 'deleted'): ?>
                        Admin eliminato con successo!
                    <?php endif; ?>
                </span>
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 flex items-start gap-3">
                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                <span>
                    <?php if ($_GET['error'] === 'not_found'): ?>
                        Admin non trovato!
                    <?php elseif ($_GET['error'] === 'delete_failed'): ?>
                        Errore durante l'eliminazione dell'admin!
                    <?php elseif ($_GET['error'] === 'csrf'): ?>
                        Token di sicurezza non valido!
                    <?php endif; ?>
                </span>
            </div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <form method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:items-end sm:gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cerca admin</label>
                        <input type="text" 
                               name="search" 
                               value="<?= htmlspecialchars($search) ?>" 
                               placeholder="Username, email o nome..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ristorante</label>
                        <select name="restaurant" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Tutti</option>
                            <?php foreach ($restaurants as $restaurant): ?>
                            <option value="<?= $restaurant['id'] ?>" <?= $selected_restaurant == $restaurant['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($restaurant['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php if ($search || $selected_restaurant): ?>
                        <a href="<?= BASE_URL ?>/superadmin/admins" 
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Admins Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <?php if (empty($admins)): ?>
                <div class="text-center py-12">
                    <div class="mb-6">
                        <i class="fas fa-users text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Nessun admin trovato</h3>
                    <p class="text-gray-500">Gli admin verranno mostrati qui una volta creati.</p>
                </div>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ristorante</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruolo</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Creato</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($admins as $admin): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($admin['full_name']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">@<?= htmlspecialchars($admin['username']) ?></div>
                                        <!-- Mobile email -->
                                        <div class="mt-1 lg:hidden">
                                            <div class="text-xs text-gray-500"><?= htmlspecialchars($admin['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="<?= BASE_URL ?>/restaurant/<?= $admin['restaurant_slug'] ?>" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                       target="_blank">
                                        <?= htmlspecialchars($admin['restaurant_name']) ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                    <div class="text-sm text-gray-900">
                                        <?= htmlspecialchars($admin['email']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($admin['role'] === 'owner'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-crown mr-1"></i>
                                        Owner
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-user mr-1"></i>
                                        Staff
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                    <?= date('d/m/Y', strtotime($admin['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="<?= BASE_URL ?>/superadmin/admin/edit/<?= $admin['id'] ?>" 
                                           class="text-green-600 hover:text-green-900 p-2 rounded hover:bg-green-50 transition-colors"
                                           title="Modifica">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmDeleteAdmin(<?= $admin['id'] ?>, '<?= addslashes($admin['full_name']) ?>')" 
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
                            Pagina <?= $page ?> di <?= $total_pages ?> (<?= $total ?> admin totali)
                        </div>
                        <nav class="flex items-center gap-2">
                            <?php if ($page > 1): ?>
                            <a href="<?= BASE_URL ?>/superadmin/admins?page=<?= $page - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $selected_restaurant ? '&restaurant=' . $selected_restaurant : '' ?>" 
                               class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $page + 2);
                            
                            for ($i = $start_page; $i <= $end_page; $i++):
                            ?>
                            <a href="<?= BASE_URL ?>/superadmin/admins?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $selected_restaurant ? '&restaurant=' . $selected_restaurant : '' ?>" 
                               class="px-3 py-2 <?= $i === $page ? 'bg-red-600 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50' ?> rounded-lg transition-colors">
                                <?= $i ?>
                            </a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                            <a href="<?= BASE_URL ?>/superadmin/admins?page=<?= $page + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $selected_restaurant ? '&restaurant=' . $selected_restaurant : '' ?>" 
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
<div id="deleteAdminModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6">
        <div class="text-center">
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle text-6xl text-red-500"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Conferma Eliminazione</h3>
            <p class="text-gray-600 mb-6">
                Sei sicuro di voler eliminare l'admin <strong id="adminName"></strong>?
                <br><br>
                <span class="text-red-600 font-medium">Questa azione non può essere annullata!</span>
            </p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteAdminModal()" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Annulla
                </button>
                <form id="deleteAdminForm" method="POST" class="inline">
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
function confirmDeleteAdmin(adminId, adminName) {
    document.getElementById('adminName').textContent = adminName;
    document.getElementById('deleteAdminForm').action = '<?= BASE_URL ?>/superadmin/admin/delete/' + adminId;
    document.getElementById('deleteAdminModal').classList.remove('hidden');
}

function closeDeleteAdminModal() {
    document.getElementById('deleteAdminModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteAdminModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteAdminModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteAdminModal();
    }
});
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>