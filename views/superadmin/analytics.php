<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 overflow-x-hidden" x-data="analyticsManager()">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64 min-w-0">
        <!-- Modern Header -->
        <div class="bg-white/80 backdrop-blur-xl border-b border-white/20 shadow-sm sticky top-0 z-30">
            <div class="px-6 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">
                            Analytics Dashboard
                        </h1>
                        <p class="text-slate-600 mt-2 flex items-center gap-2">
                            <i class="fas fa-chart-line text-emerald-500"></i>
                            Panoramica completa e statistiche del sistema MenooElo
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <select x-model="timeRange" @change="updateStats()" 
                                class="px-4 py-2 bg-white/70 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all duration-200">
                            <option value="7">Ultimi 7 giorni</option>
                            <option value="30">Ultimi 30 giorni</option>
                            <option value="90">Ultimi 3 mesi</option>
                            <option value="365">Ultimo anno</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Enhanced Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between text-white">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-2">Ristoranti Totali</p>
                            <p class="text-4xl font-bold"><?= $stats['total_restaurants'] ?></p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-store text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between text-white">
                        <div>
                            <p class="text-emerald-100 text-sm font-medium mb-2">Admin Ristoranti</p>
                            <p class="text-4xl font-bold"><?= $stats['total_restaurant_admins'] ?></p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-user-shield text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between text-white">
                        <div>
                            <p class="text-purple-100 text-sm font-medium mb-2">Menu Items Totali</p>
                            <p class="text-4xl font-bold"><?= $stats['total_menu_items'] ?></p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-utensils text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between text-white">
                        <div>
                            <p class="text-orange-100 text-sm font-medium mb-2">Ristoranti Attivi</p>
                            <p class="text-4xl font-bold"><?= $stats['active_restaurants'] ?></p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-chart-line text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Registrations Chart -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Nuove Registrazioni</h3>
                            <p class="text-sm text-slate-500 mt-1">Trend registrazioni ristoranti</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-chart-area text-white"></i>
                        </div>
                    </div>
                    
                    <div class="h-64 flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl">
                        <div class="text-center">
                            <i class="fas fa-chart-line text-4xl text-blue-500 mb-4"></i>
                            <p class="text-slate-600 font-medium">Grafici Avanzati</p>
                            <p class="text-sm text-slate-500 mt-2">Funzionalità disponibile in un prossimo aggiornamento</p>
                        </div>
                    </div>
                </div>

                <!-- Activity Chart -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Attività Sistema</h3>
                            <p class="text-sm text-slate-500 mt-1">Log attività ultimi giorni</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-activity text-white"></i>
                        </div>
                    </div>
                    
                    <div class="h-64 flex items-center justify-center bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl">
                        <div class="text-center">
                            <i class="fas fa-pulse text-4xl text-emerald-500 mb-4"></i>
                            <p class="text-slate-600 font-medium">Grafici Avanzati</p>
                            <p class="text-sm text-slate-500 mt-2">Funzionalità disponibile in un prossimo aggiornamento</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Top Restaurants -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Activity -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Attività Recenti</h3>
                            <p class="text-sm text-slate-500 mt-1">Ultime azioni nel sistema</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-history text-white"></i>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <?php if (!empty($recent_activity)): ?>
                            <?php foreach (array_slice($recent_activity, 0, 5) as $activity): ?>
                            <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <div class="w-10 h-10 bg-gradient-to-br from-slate-400 to-slate-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900">
                                        <?= htmlspecialchars($activity['description']) ?>
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-slate-500">
                                            <?= date('d/m/Y H:i', strtotime($activity['created_at'])) ?>
                                        </span>
                                        <?php if ($activity['restaurant_name']): ?>
                                        <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full">
                                            <?= htmlspecialchars($activity['restaurant_name']) ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-clock text-3xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500">Nessuna attività recente</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <a href="<?= BASE_URL ?>/superadmin/logs" 
                           class="inline-flex items-center gap-2 px-4 py-2 text-purple-600 hover:text-purple-700 font-medium transition-colors duration-200">
                            <span>Vedi tutti i log</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Top Restaurants -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Top Ristoranti</h3>
                            <p class="text-sm text-slate-500 mt-1">Ristoranti più attivi</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-crown text-white"></i>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <?php if (!empty($top_restaurants)): ?>
                            <?php foreach ($top_restaurants as $index => $restaurant): ?>
                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-red-400 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-bold text-sm"><?= $index + 1 ?></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-900 truncate">
                                        <?= htmlspecialchars($restaurant['name']) ?>
                                    </p>
                                    <div class="flex items-center gap-4 mt-1 text-xs text-slate-500">
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-utensils"></i>
                                            <?= $restaurant['menu_items_count'] ?> piatti
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-calendar"></i>
                                            <?= date('d/m/Y', strtotime($restaurant['created_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <?php if ($restaurant['is_active']): ?>
                                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                                        Attivo
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">
                                        <div class="w-2 h-2 bg-red-500 rounded-full mr-1"></div>
                                        Inattivo
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-store text-3xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500">Nessun ristorante registrato</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <a href="<?= BASE_URL ?>/superadmin/restaurants" 
                           class="inline-flex items-center gap-2 px-4 py-2 text-orange-600 hover:text-orange-700 font-medium transition-colors duration-200">
                            <span>Vedi tutti i ristoranti</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
function analyticsManager() {
    return {
        timeRange: '30',
        
        updateStats() {
            this.showNotification(`Aggiornamento statistiche per ultimi ${this.timeRange} giorni...`, 'info');
            
            fetch(`<?= BASE_URL ?>/superadmin/analytics/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    timeRange: this.timeRange,
                    csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showNotification('Statistiche aggiornate con successo', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showNotification('Errore durante l\'aggiornamento', 'error');
                }
            })
            .catch(error => {
                this.showNotification('Errore di connessione', 'error');
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
            
            setTimeout(() => notification.style.transform = 'translateX(0)', 10);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    }
}
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>