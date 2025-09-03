<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 overflow-x-hidden" x-data="logsManager()">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64 min-w-0">
        <!-- Modern Header -->
        <div class="bg-white/80 backdrop-blur-xl border-b border-white/20 shadow-sm sticky top-0 z-30">
            <div class="px-6 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">
                            Log Attività Sistema
                        </h1>
                        <p class="text-slate-600 mt-2 flex items-center gap-2">
                            <i class="fas fa-list-alt text-purple-500"></i>
                            Totale: <span class="font-semibold text-purple-600"><?= $total ?></span> attività registrate
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button @click="clearLogs()" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-xl font-medium hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-trash-alt"></i>
                            <span>Pulisci Log</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Enhanced Filters -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-6 mb-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-filter text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Filtri di Ricerca</h2>
                        <p class="text-sm text-slate-500 mt-1">Filtra i log delle attività</p>
                    </div>
                </div>
                
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end" x-data="{ hasFilters: <?= $action || $user_type || $selected_restaurant ? 'true' : 'false' ?> }">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-search text-slate-400 mr-1"></i>
                            Cerca azione
                        </label>
                        <input type="text" 
                               name="action" 
                               value="<?= htmlspecialchars($action) ?>" 
                               placeholder="Nome azione..."
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 bg-white/50 backdrop-blur-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-user-tag text-slate-400 mr-1"></i>
                            Tipo utente
                        </label>
                        <select name="user_type" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 bg-white/50 backdrop-blur-sm">
                            <option value="">Tutti i tipi</option>
                            <option value="super_admin" <?= $user_type === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                            <option value="restaurant_admin" <?= $user_type === 'restaurant_admin' ? 'selected' : '' ?>>Admin Ristorante</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-store text-slate-400 mr-1"></i>
                            Ristorante
                        </label>
                        <select name="restaurant" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 bg-white/50 backdrop-blur-sm">
                            <option value="">Tutti i ristoranti</option>
                            <?php foreach ($restaurants as $restaurant): ?>
                            <option value="<?= $restaurant['id'] ?>" <?= $selected_restaurant == $restaurant['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($restaurant['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl font-medium hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-search"></i>
                            <span class="hidden sm:inline">Cerca</span>
                        </button>
                        <a href="<?= BASE_URL ?>/superadmin/logs" 
                           x-show="hasFilters"
                           x-transition
                           class="inline-flex items-center justify-center px-4 py-3 border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 transition-all duration-200"
                           title="Reset filtri">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Logs Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <?php if (empty($logs)): ?>
                <div class="text-center py-12">
                    <div class="mb-6">
                        <i class="fas fa-file-alt text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Nessun log trovato</h3>
                    <p class="text-gray-500">Le attività del sistema verranno mostrate qui.</p>
                </div>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utente</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azione</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ristorante</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data/Ora</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($logs as $log): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <?php if ($log['user_type'] === 'super_admin'): ?>
                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user-shield text-red-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Super Admin</div>
                                            <div class="text-xs text-gray-500">Sistema</div>
                                        </div>
                                        <?php else: ?>
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Admin Ristorante</div>
                                            <div class="text-xs text-gray-500">ID: <?= $log['user_id'] ?></div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <?= htmlspecialchars(str_replace('_', ' ', $log['action'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($log['restaurant_name']): ?>
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($log['restaurant_name']) ?></div>
                                    <?php else: ?>
                                    <span class="text-xs text-gray-500">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    <?= htmlspecialchars($log['ip_address'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div><?= date('d/m/Y', strtotime($log['created_at'])) ?></div>
                                    <div class="text-xs text-gray-400"><?= date('H:i:s', strtotime($log['created_at'])) ?></div>
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
                            Pagina <?= $page ?> di <?= $total_pages ?> (<?= $total ?> log totali)
                        </div>
                        <nav class="flex items-center gap-2">
                            <?php if ($page > 1): ?>
                            <a href="<?= BASE_URL ?>/superadmin/logs?page=<?= $page - 1 ?><?= $action ? '&action=' . urlencode($action) : '' ?><?= $user_type ? '&user_type=' . $user_type : '' ?><?= $selected_restaurant ? '&restaurant=' . $selected_restaurant : '' ?>" 
                               class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $page + 2);
                            
                            for ($i = $start_page; $i <= $end_page; $i++):
                            ?>
                            <a href="<?= BASE_URL ?>/superadmin/logs?page=<?= $i ?><?= $action ? '&action=' . urlencode($action) : '' ?><?= $user_type ? '&user_type=' . $user_type : '' ?><?= $selected_restaurant ? '&restaurant=' . $selected_restaurant : '' ?>" 
                               class="px-3 py-2 <?= $i === $page ? 'bg-red-600 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50' ?> rounded-lg transition-colors">
                                <?= $i ?>
                            </a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                            <a href="<?= BASE_URL ?>/superadmin/logs?page=<?= $page + 1 ?><?= $action ? '&action=' . urlencode($action) : '' ?><?= $user_type ? '&user_type=' . $user_type : '' ?><?= $selected_restaurant ? '&restaurant=' . $selected_restaurant : '' ?>" 
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

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
function logsManager() {
    return {
        // State
        showClearModal: false,
        
        // Methods
        
        clearLogs() {
            this.showClearModal = true;
        },
        
        confirmClearLogs() {
            this.showClearModal = false;
            
            fetch('<?= BASE_URL ?>/superadmin/logs/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showNotification('Log eliminati con successo', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    this.showNotification('Errore durante l\'eliminazione: ' + data.error, 'error');
                }
            })
            .catch(error => {
                this.showNotification('Errore di rete: ' + error.message, 'error');
            });
        },
        
        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-xl text-white transform transition-all duration-300 ${
                type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-500' : 
                type === 'error' ? 'bg-gradient-to-r from-red-500 to-pink-500' : 
                'bg-gradient-to-r from-blue-500 to-indigo-500'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
                    <span class="font-medium">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => notification.style.transform = 'translateX(0)', 10);
            
            // Remove after delay
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    }
}

// Handle Escape key for modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const component = document.querySelector('[x-data*="logsManager"]')?.__x?.$data;
        if (component?.showClearModal) {
            component.showClearModal = false;
        }
    }
});
</script>

<!-- Clear Logs Confirmation Modal -->
<div x-show="showClearModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="showClearModal = false"
     class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
     x-cloak>
    <div @click.stop 
         class="bg-white rounded-2xl max-w-lg w-full shadow-2xl"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-trash-alt text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Elimina Tutti i Log</h3>
                    <p class="text-slate-500 text-sm mt-1">Questa azione non può essere annullata</p>
                </div>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl p-4 mb-6">
                <p class="text-slate-700 text-center">
                    Sei sicuro di voler eliminare <strong class="text-red-700">TUTTI</strong> i log delle attività?
                </p>
                <div class="mt-4 flex items-center justify-center gap-2 text-sm text-red-600">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="font-medium">Tutti i record di attività verranno persi definitivamente!</span>
                </div>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 p-6 border-t border-slate-100 bg-slate-50 rounded-b-2xl">
            <button @click="showClearModal = false" 
                    class="inline-flex items-center gap-2 px-6 py-3 border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-100 transition-all duration-200">
                <i class="fas fa-times"></i>
                <span>Annulla</span>
            </button>
            <button @click="confirmClearLogs()" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-xl font-medium hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-trash-alt"></i>
                <span>Elimina Tutto</span>
            </button>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>