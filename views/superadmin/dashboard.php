<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 overflow-x-hidden" x-data="dashboard()">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64 min-w-0">
        <!-- Modern Header -->
        <div class="bg-white/80 backdrop-blur-xl border-b border-white/20 shadow-sm sticky top-0 z-30">
            <div class="px-6 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">
                            Super Admin Dashboard
                        </h1>
                        <p class="text-slate-600 mt-2 flex items-center gap-2">
                            <i class="fas fa-chart-line text-blue-500"></i>
                            Panoramica del sistema MenooElo
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button @click="refreshStats()" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-refresh" :class="{ 'animate-spin': loading }"></i>
                            <span>Aggiorna</span>
                        </button>
                        <a href="<?= BASE_URL ?>/superadmin/restaurant/add" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-plus"></i>
                            <span>Nuovo Ristorante</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Enhanced Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <div class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-white/20 overflow-hidden transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-store text-white text-lg"></i>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-slate-900"><?= $stats['total_restaurants'] ?></div>
                                <div class="text-sm text-slate-500">Ristoranti</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-medium">Totali</span>
                        </div>
                    </div>
                </div>
                
                <div class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-white/20 overflow-hidden transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-check-circle text-white text-lg"></i>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-slate-900"><?= $stats['active_restaurants'] ?></div>
                                <div class="text-sm text-slate-500">Attivi</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-medium">Ristoranti</span>
                        </div>
                    </div>
                </div>
                
                <div class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-white/20 overflow-hidden transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-users text-white text-lg"></i>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-slate-900"><?= $stats['total_admins'] ?></div>
                                <div class="text-sm text-slate-500">Admins</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-medium">Totali</span>
                        </div>
                    </div>
                </div>
                
                <div class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-white/20 overflow-hidden transform hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-orange-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-utensils text-white text-lg"></i>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-slate-900"><?= $stats['total_menu_items'] ?></div>
                                <div class="text-sm text-slate-500">Menu Items</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-medium">Totali</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions with Alpine.js -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-6 mb-8">
                <h2 class="text-xl font-semibold text-slate-900 mb-6 flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bolt text-white text-sm"></i>
                    </div>
                    Azioni Rapide
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="<?= BASE_URL ?>/superadmin/restaurant/add" 
                       class="group flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 hover:shadow-md transform hover:-translate-y-0.5">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Nuovo Ristorante</div>
                            <div class="text-sm text-slate-600">Aggiungi un nuovo ristorante</div>
                        </div>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/superadmin/analytics" 
                       class="group flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 hover:from-green-100 hover:to-emerald-100 transition-all duration-200 hover:shadow-md transform hover:-translate-y-0.5">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Analytics</div>
                            <div class="text-sm text-slate-600">Visualizza le statistiche</div>
                        </div>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/superadmin/database" 
                       class="group flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-100 hover:from-purple-100 hover:to-indigo-100 transition-all duration-200 hover:shadow-md transform hover:-translate-y-0.5">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl">
                            <i class="fas fa-database text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Database</div>
                            <div class="text-sm text-slate-600">Gestione del database</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Enhanced Content Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Recent Restaurants with Alpine.js -->
                <div x-data="{ activeTab: 'recent' }" class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-slate-900 flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-red-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-store text-white text-sm"></i>
                                </div>
                                Ristoranti
                            </h2>
                        </div>
                        
                        <!-- Tabs -->
                        <div class="flex space-x-1 bg-slate-100 rounded-lg p-1">
                            <button @click="activeTab = 'recent'" 
                                    :class="activeTab === 'recent' ? 'bg-white shadow-sm' : 'hover:bg-slate-200'"
                                    class="flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200">
                                Recenti
                            </button>
                            <button @click="activeTab = 'active'" 
                                    :class="activeTab === 'active' ? 'bg-white shadow-sm' : 'hover:bg-slate-200'"
                                    class="flex-1 py-2 px-3 rounded-md text-sm font-medium transition-all duration-200">
                                Attivi
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <?php if (empty($recent_restaurants)): ?>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-store text-2xl text-slate-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-700 mb-2">Nessun ristorante creato</h3>
                            <p class="text-slate-500 mb-6">Inizia creando il tuo primo ristorante!</p>
                            <a href="<?= BASE_URL ?>/superadmin/restaurant/add" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-plus"></i>
                                <span>Crea Primo Ristorante</span>
                            </a>
                        </div>
                        <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($recent_restaurants as $restaurant): ?>
                            <div class="group flex items-center gap-4 p-4 rounded-xl hover:bg-slate-50 transition-all duration-200 border border-transparent hover:border-slate-200">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                                    <i class="fas fa-utensils text-white text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-slate-900 truncate">
                                        <?= htmlspecialchars($restaurant['name']) ?>
                                    </div>
                                    <div class="text-sm text-slate-500"><?= $restaurant['slug'] ?></div>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium <?= $restaurant['is_active'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                            <?= $restaurant['is_active'] ? 'Attivo' : 'Inattivo' ?>
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            <?= $restaurant['admin_count'] ?> admin
                                        </span>
                                    </div>
                                </div>
                                <div class="text-xs text-slate-400 flex-shrink-0">
                                    <?= date('d/m/Y', strtotime($restaurant['created_at'])) ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-6 text-center">
                            <a href="<?= BASE_URL ?>/superadmin/restaurants" 
                               class="inline-flex items-center gap-2 px-4 py-2 text-blue-600 hover:text-blue-700 font-medium transition-colors duration-200">
                                Vedi tutti i ristoranti
                                <i class="fas fa-arrow-right text-sm"></i>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent Activity with Enhanced Styling -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h2 class="text-xl font-semibold text-slate-900 flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-history text-white text-sm"></i>
                            </div>
                            Attività Recente
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        <?php if (empty($recent_activity)): ?>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-history text-2xl text-slate-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-700 mb-2">Nessuna attività recente</h3>
                            <p class="text-slate-500">Le attività del sistema appariranno qui.</p>
                        </div>
                        <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($recent_activity as $activity): ?>
                            <div class="flex items-start gap-4 p-4 rounded-xl hover:bg-slate-50 transition-all duration-200">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm
                                    <?= $activity['user_type'] === 'super_admin' ? 'bg-gradient-to-br from-red-500 to-pink-500' : 'bg-gradient-to-br from-blue-500 to-indigo-500' ?>">
                                    <i class="fas <?= $activity['user_type'] === 'super_admin' ? 'fa-user-shield' : 'fa-user' ?> text-white text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-slate-900">
                                        <?= ucfirst(str_replace('_', ' ', $activity['action'])) ?>
                                    </div>
                                    <?php if ($activity['restaurant_name']): ?>
                                    <div class="text-sm text-slate-600 mt-1">
                                        <?= htmlspecialchars($activity['restaurant_name']) ?>
                                    </div>
                                    <?php endif; ?>
                                    <div class="text-xs text-slate-400 mt-1">
                                        <?= date('d/m/Y H:i', strtotime($activity['created_at'])) ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-6 text-center">
                            <a href="<?= BASE_URL ?>/superadmin/logs" 
                               class="inline-flex items-center gap-2 px-4 py-2 text-indigo-600 hover:text-indigo-700 font-medium transition-colors duration-200">
                                Vedi tutti i log
                                <i class="fas fa-arrow-right text-sm"></i>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function dashboard() {
    return {
        loading: false,
        
        refreshStats() {
            this.loading = true;
            setTimeout(() => {
                this.loading = false;
                // In a real app, you would make an API call here
                console.log('Stats refreshed');
            }, 1500);
        }
    }
}
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>