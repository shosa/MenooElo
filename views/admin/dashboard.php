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
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-gray-600 mt-1"><?= htmlspecialchars($restaurant['name']) ?></p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>/restaurant/<?= $restaurant['slug'] ?>" 
                       class="inline-flex items-center gap-2 px-4 py-2 border border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-600 hover:text-white transition-colors duration-200" 
                       target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visualizza Menu</span>
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
                            <p class="text-blue-100 text-sm font-medium">Categorie</p>
                            <p class="text-3xl font-bold text-white"><?= $stats['total_categories'] ?></p>
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
                            <p class="text-3xl font-bold text-white"><?= $stats['total_items'] ?></p>
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
                            <p class="text-3xl font-bold text-white"><?= $stats['active_items'] ?></p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-check-circle text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">In Evidenza</p>
                            <p class="text-3xl font-bold text-white"><?= $stats['featured_items'] ?></p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-star text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
                
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-blue-600"></i>
                    Azioni Rapide
                </h2>
                
                <div class="flex flex-wrap gap-3">
                    <a href="<?= BASE_URL ?>/admin/menu/item/add" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-plus"></i>
                        <span>Aggiungi Piatto</span>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/categories/add" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-folder-plus"></i>
                        <span>Aggiungi Categoria</span>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/settings" 
                       class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-cog"></i>
                        <span>Impostazioni</span>
                    </a>
                </div>
            </div>
                
            <!-- Recent Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-clock text-blue-600"></i>
                    Piatti Aggiunti di Recente
                </h2>
                
                <?php if (empty($recent_items)): ?>
                <div class="text-center py-12">
                    <div class="mb-6">
                        <i class="fas fa-utensils text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Nessun piatto ancora aggiunto</h3>
                    <p class="text-gray-500 mb-6">Inizia creando la tua prima categoria e i tuoi primi piatti!</p>
                    <a href="<?= BASE_URL ?>/admin/categories/add" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-folder-plus"></i>
                        <span>Crea Prima Categoria</span>
                    </a>
                </div>
                <?php else: ?>
                <div class="overflow-x-auto -mx-6 px-6">
                    <table class="w-full min-w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Piatto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Categoria</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prezzo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Stato</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Aggiunto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($recent_items as $item): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <?php if ($item['image_url']): ?>
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="<?= BASE_URL ?>/uploads/<?= $item['image_url'] ?>" 
                                                 alt="<?= htmlspecialchars($item['name']) ?>">
                                            <?php else: ?>
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-utensils text-gray-500"></i>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4">
                                            <div class="flex items-center gap-2">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?= htmlspecialchars($item['name']) ?>
                                                </div>
                                                <?php if ($item['is_featured']): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-star"></i>
                                                </span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($item['description']): ?>
                                            <div class="text-sm text-gray-500">
                                                <?= htmlspecialchars(substr($item['description'], 0, 50)) ?>...
                                            </div>
                                            <?php endif; ?>
                                            <!-- Mobile category -->
                                            <div class="mt-1 lg:hidden">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <?= htmlspecialchars($item['category_name']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?= htmlspecialchars($item['category_name']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    €<?= number_format($item['price'], 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                    <?php if ($item['is_available']): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Disponibile
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Non disponibile
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                    <?= date('d/m/Y', strtotime($item['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="<?= BASE_URL ?>/admin/menu/item/edit/<?= $item['id'] ?>" 
                                       class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 text-right">
                    <a href="<?= BASE_URL ?>/admin/menu" 
                       class="inline-flex items-center gap-2 px-4 py-2 text-blue-600 border border-blue-600 rounded-lg font-medium hover:bg-blue-600 hover:text-white transition-colors duration-200">
                        Vedi tutti i piatti
                    </a>
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