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
                    <h1 class="text-3xl font-bold text-gray-900">Log Attività</h1>
                    <p class="text-gray-600 mt-1">Totale: <?= $total ?> attività registrate</p>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <form method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:items-end sm:gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cerca azione</label>
                        <input type="text" 
                               name="action" 
                               value="<?= htmlspecialchars($action) ?>" 
                               placeholder="Nome azione..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo utente</label>
                        <select name="user_type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Tutti</option>
                            <option value="super_admin" <?= $user_type === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                            <option value="restaurant_admin" <?= $user_type === 'restaurant_admin' ? 'selected' : '' ?>>Admin Ristorante</option>
                        </select>
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
                        <?php if ($action || $user_type || $selected_restaurant): ?>
                        <a href="<?= BASE_URL ?>/superadmin/logs" 
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                        <?php endif; ?>
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

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>