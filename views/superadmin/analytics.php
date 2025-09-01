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
                    <h1 class="text-3xl font-bold text-gray-900">Analytics</h1>
                    <p class="text-gray-600 mt-1">Panoramica e statistiche del sistema MenooElo</p>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
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
                            <p class="text-yellow-100 text-sm font-medium">Categorie</p>
                            <p class="text-3xl font-bold text-white"><?= $stats['total_categories'] ?></p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-list text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium">Piatti Totali</p>
                            <p class="text-3xl font-bold text-white"><?= $stats['total_items'] ?></p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-utensils text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Restaurants -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-trophy text-yellow-600"></i>
                        Ristoranti più Attivi
                    </h2>
                    
                    <?php if (empty($top_restaurants)): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Nessun dato disponibile</p>
                    </div>
                    <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($top_restaurants as $index => $restaurant): ?>
                        <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <?php if ($index === 0): ?>
                                <div class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                    1
                                </div>
                                <?php elseif ($index === 1): ?>
                                <div class="w-8 h-8 bg-gray-400 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                    2
                                </div>
                                <?php elseif ($index === 2): ?>
                                <div class="w-8 h-8 bg-yellow-700 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                    3
                                </div>
                                <?php else: ?>
                                <div class="w-8 h-8 bg-gray-300 text-gray-700 rounded-full flex items-center justify-center font-bold text-sm">
                                    <?= $index + 1 ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">
                                    <?= htmlspecialchars($restaurant['name']) ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?= $restaurant['items_count'] ?> piatti nel menu
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="<?= BASE_URL ?>/restaurant/<?= $restaurant['slug'] ?>" 
                                   class="text-blue-600 hover:text-blue-800 text-sm"
                                   target="_blank">
                                    Visualizza <i class="fas fa-external-link-alt ml-1"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Monthly Growth -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-chart-line text-green-600"></i>
                        Crescita Mensile
                    </h2>
                    
                    <?php if (empty($monthly_growth)): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-chart-line text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Nessun dato di crescita disponibile</p>
                    </div>
                    <?php else: ?>
                    <div class="space-y-4">
                        <?php 
                        $max_count = max(array_column($monthly_growth, 'count'));
                        foreach ($monthly_growth as $month): 
                        $percentage = $max_count > 0 ? ($month['count'] / $max_count) * 100 : 0;
                        ?>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">
                                    <?= date('M Y', strtotime($month['month'] . '-01')) ?>
                                </span>
                                <span class="text-sm text-gray-600">
                                    <?= $month['count'] ?> ristoranti
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                     style="width: <?= $percentage ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- System Overview -->
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i>
                    Panoramica Sistema
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                        <div class="mb-4">
                            <i class="fas fa-percentage text-3xl text-blue-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-blue-700">
                            <?= $stats['total_restaurants'] > 0 ? round(($stats['active_restaurants'] / $stats['total_restaurants']) * 100, 1) : 0 ?>%
                        </div>
                        <div class="text-sm text-blue-600 mt-1">Ristoranti attivi</div>
                    </div>
                    
                    <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                        <div class="mb-4">
                            <i class="fas fa-calculator text-3xl text-green-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-green-700">
                            <?= $stats['total_restaurants'] > 0 ? round($stats['total_items'] / $stats['total_restaurants'], 1) : 0 ?>
                        </div>
                        <div class="text-sm text-green-600 mt-1">Piatti per ristorante (media)</div>
                    </div>
                    
                    <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                        <div class="mb-4">
                            <i class="fas fa-users-cog text-3xl text-purple-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-purple-700">
                            <?= $stats['total_restaurants'] > 0 ? round($stats['total_admins'] / $stats['total_restaurants'], 1) : 0 ?>
                        </div>
                        <div class="text-sm text-purple-600 mt-1">Admin per ristorante (media)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>