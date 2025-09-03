<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'views/admin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Statistiche</h1>
                    <p class="text-gray-600 mt-1">Analisi delle performance del tuo menu digitale</p>
                </div>
                <div class="flex gap-3">
                    <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <option value="7">Ultimi 7 giorni</option>
                        <option value="30">Ultimi 30 giorni</option>
                        <option value="90">Ultimi 3 mesi</option>
                    </select>
                    <button onclick="window.print()" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-file-export"></i>
                        <span>Esporta</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6 space-y-6">
            <!-- Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Categorie</p>
                            <p class="text-3xl font-bold text-white"><?= $menu_stats['total_categories'] ?></p>
                            <div class="flex items-center mt-2 text-blue-100 text-sm">
                                <i class="fas fa-list mr-1"></i>
                                <span>Categorie totali</span>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-list text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Piatti Totali</p>
                            <p class="text-3xl font-bold text-white"><?= $menu_stats['total_items'] ?></p>
                            <div class="flex items-center mt-2 text-green-100 text-sm">
                                <i class="fas fa-utensils mr-1"></i>
                                <span>Nel menu</span>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-utensils text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Disponibili</p>
                            <p class="text-3xl font-bold text-white"><?= $menu_stats['active_items'] ?></p>
                            <div class="flex items-center mt-2 text-purple-100 text-sm">
                                <i class="fas fa-check-circle mr-1"></i>
                                <span>Attualmente attivi</span>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-check-circle text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">In Evidenza</p>
                            <p class="text-3xl font-bold text-white"><?= $menu_stats['featured_items'] ?></p>
                            <div class="flex items-center mt-2 text-orange-100 text-sm">
                                <i class="fas fa-star mr-1"></i>
                                <span>Piatti featured</span>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-star text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Category Distribution -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-blue-600"></i>
                        Distribuzione per Categoria
                    </h3>
                    
                    <?php if (!empty($category_distribution)): ?>
                    <div class="space-y-3">
                        <?php foreach ($category_distribution as $category): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm text-gray-700"><?= htmlspecialchars($category['name']) ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900"><?= $category['items_count'] ?> piatti</span>
                                <div class="w-24 h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-blue-500 rounded-full" style="width: <?= $menu_stats['total_items'] > 0 ? ($category['items_count'] / $menu_stats['total_items'] * 100) : 0 ?>%"></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-chart-pie text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Nessuna categoria presente</p>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Price Ranges -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-euro-sign text-blue-600"></i>
                        Distribuzione Prezzi
                    </h3>
                    
                    <?php if (!empty($price_ranges)): ?>
                    <div class="space-y-3">
                        <?php 
                        $totalItems = array_sum(array_column($price_ranges, 'count'));
                        foreach ($price_ranges as $range): 
                        ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-700"><?= htmlspecialchars($range['price_range']) ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900"><?= $range['count'] ?> piatti</span>
                                <div class="w-24 h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-green-500 rounded-full" style="width: <?= $totalItems > 0 ? ($range['count'] / $totalItems * 100) : 0 ?>%"></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-euro-sign text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Nessun piatto presente</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-history text-blue-600"></i>
                    Attività Recente
                </h3>
                
                <?php if (!empty($recent_activity)): ?>
                <div class="space-y-4">
                    <?php foreach (array_slice($recent_activity, 0, 10) as $activity): ?>
                    <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900"><?= htmlspecialchars($activity['action']) ?></div>
                            <?php if ($activity['description']): ?>
                            <div class="text-sm text-gray-600"><?= htmlspecialchars($activity['description']) ?></div>
                            <?php endif; ?>
                            <div class="text-xs text-gray-500 mt-1">
                                <?= date('d/m/Y H:i', strtotime($activity['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-history text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Nessuna attività recente</p>
                    <p class="text-sm text-gray-400 mt-1">Le attività del tuo ristorante appariranno qui</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>