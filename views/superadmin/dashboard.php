<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gray-100 overflow-x-hidden">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64 min-w-0">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Super Admin Dashboard</h1>
                    <p class="text-gray-600 mt-1">Panoramica del sistema MenooElo</p>
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
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Ristoranti Totali</p>
                            <p class="text-3xl font-bold text-white"><?= $stats['total_restaurants'] ?></p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-store text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Ristoranti Attivi</p>
                            <p class="text-3xl font-bold text-white"><?= $stats['active_restaurants'] ?></p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-check-circle text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Admin Totali</p>
                            <p class="text-3xl font-bold text-white"><?= $stats['total_admins'] ?></p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-users text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Menu Items</p>
                            <p class="text-3xl font-bold text-white"><?= $stats['total_menu_items'] ?></p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-utensils text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
                
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-red-600"></i>
                    Azioni Rapide
                </h2>
                
                <div class="flex flex-wrap gap-3">
                    <a href="<?= BASE_URL ?>/superadmin/restaurant/add" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors duration-200">
                        <i class="fas fa-plus"></i>
                        <span>Nuovo Ristorante</span>
                    </a>
                    <a href="<?= BASE_URL ?>/superadmin/analytics" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                    <a href="<?= BASE_URL ?>/superadmin/database" 
                       class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-database"></i>
                        <span>Gestione DB</span>
                    </a>
                </div>
            </div>

            <!-- Recent Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Restaurants -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-store text-red-600"></i>
                        Ristoranti Recenti
                    </h2>
                    
                    <?php if (empty($recent_restaurants)): ?>
                    <div class="text-center py-12">
                        <div class="mb-6">
                            <i class="fas fa-store text-6xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Nessun ristorante ancora creato</h3>
                        <p class="text-gray-500 mb-6">Inizia creando il tuo primo ristorante!</p>
                        <a href="<?= BASE_URL ?>/superadmin/restaurant/add" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors duration-200">
                            <i class="fas fa-plus"></i>
                            <span>Crea Primo Ristorante</span>
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="overflow-x-auto -mx-6 px-6">
                        <table class="w-full min-w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ristorante</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Admin</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Creato</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($recent_restaurants as $restaurant): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($restaurant['name']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500"><?= $restaurant['slug'] ?></div>
                                            <!-- Mobile admin count -->
                                            <div class="mt-1 lg:hidden">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <?= $restaurant['admin_count'] ?> admin
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap hidden lg:table-cell">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?= $restaurant['admin_count'] ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <?php if ($restaurant['is_active']): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Attivo
                                        </span>
                                        <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Inattivo
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                        <?= date('d/m/Y', strtotime($restaurant['created_at'])) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-right">
                        <a href="<?= BASE_URL ?>/superadmin/restaurants" 
                           class="inline-flex items-center gap-2 px-4 py-2 text-red-600 border border-red-600 rounded-lg font-medium hover:bg-red-600 hover:text-white transition-colors duration-200">
                            Vedi tutti i ristoranti
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-history text-red-600"></i>
                        Attività Recente
                    </h2>
                    
                    <?php if (empty($recent_activity)): ?>
                    <div class="text-center py-12">
                        <div class="mb-6">
                            <i class="fas fa-history text-6xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Nessuna attività recente</h3>
                        <p class="text-gray-500">Le attività del sistema verranno mostrate qui.</p>
                    </div>
                    <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($recent_activity as $activity): ?>
                        <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50">
                            <div class="flex-shrink-0">
                                <?php if ($activity['user_type'] === 'super_admin'): ?>
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-shield text-red-600 text-sm"></i>
                                </div>
                                <?php else: ?>
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    <?= ucfirst(str_replace('_', ' ', $activity['action'])) ?>
                                </p>
                                <?php if ($activity['restaurant_name']): ?>
                                <p class="text-sm text-gray-500">
                                    <?= htmlspecialchars($activity['restaurant_name']) ?>
                                </p>
                                <?php endif; ?>
                                <p class="text-xs text-gray-400">
                                    <?= date('d/m/Y H:i', strtotime($activity['created_at'])) ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-4 text-right">
                        <a href="<?= BASE_URL ?>/superadmin/logs" 
                           class="inline-flex items-center gap-2 px-4 py-2 text-red-600 border border-red-600 rounded-lg font-medium hover:bg-red-600 hover:text-white transition-colors duration-200">
                            Vedi tutti i log
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>