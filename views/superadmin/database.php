<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 overflow-x-hidden" x-data="databaseManager()">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64 min-w-0">
        <!-- Modern Header -->
        <div class="bg-white/80 backdrop-blur-xl border-b border-white/20 shadow-sm sticky top-0 z-30">
            <div class="px-6 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">
                            Database Manager
                        </h1>
                        <p class="text-slate-600 mt-2 flex items-center gap-2">
                            <i class="fas fa-database text-cyan-500"></i>
                            Gestione completa database MenooElo
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-xl font-medium">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span>Database Online</span>
                        </div>
                        <button @click="refreshStats()" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-sync-alt"></i>
                            <span>Refresh</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Enhanced Tab Navigation -->
        <div class="bg-white/80 backdrop-blur-xl border-b border-white/20">
            <div class="px-6">
                <nav class="flex flex-wrap gap-2 py-4">
                    <button @click="activeTab = 'overview'" 
                            :class="activeTab === 'overview' ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg' : 'bg-white/70 text-slate-700 hover:bg-white hover:shadow-md'"
                            class="inline-flex items-center gap-2 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                        <i class="fas fa-chart-pie"></i>
                        <span>Panoramica</span>
                    </button>
                    <button @click="activeTab = 'tables'" 
                            :class="activeTab === 'tables' ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg' : 'bg-white/70 text-slate-700 hover:bg-white hover:shadow-md'"
                            class="inline-flex items-center gap-2 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                        <i class="fas fa-table"></i>
                        <span>Tabelle</span>
                    </button>
                    <button @click="activeTab = 'query'" 
                            :class="activeTab === 'query' ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg' : 'bg-white/70 text-slate-700 hover:bg-white hover:shadow-md'"
                            class="inline-flex items-center gap-2 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                        <i class="fas fa-code"></i>
                        <span>Query SQL</span>
                    </button>
                    <button @click="activeTab = 'maintenance'" 
                            :class="activeTab === 'maintenance' ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg' : 'bg-white/70 text-slate-700 hover:bg-white hover:shadow-md'"
                            class="inline-flex items-center gap-2 px-4 py-3 rounded-xl font-medium transition-all duration-200">
                        <i class="fas fa-tools"></i>
                        <span>Manutenzione</span>
                    </button>
                </nav>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Overview Tab -->
            <div x-show="activeTab === 'overview'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0">
                
                <!-- Database Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl p-6 shadow-xl text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-cyan-100 text-sm font-medium mb-2">Tabelle Totali</p>
                                <p class="text-3xl font-bold"><?= $db_stats['total_tables'] ?? 0 ?></p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-table text-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-6 shadow-xl text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-emerald-100 text-sm font-medium mb-2">Dimensione DB</p>
                                <p class="text-3xl font-bold"><?= $db_stats['db_size'] ?? '0 MB' ?></p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-hdd text-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl p-6 shadow-xl text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium mb-2">Record Totali</p>
                                <p class="text-3xl font-bold"><?= $db_stats['total_records'] ?? 0 ?></p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-list text-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl p-6 shadow-xl text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm font-medium mb-2">Ultimo Backup</p>
                                <p class="text-lg font-bold"><?= $db_stats['last_backup'] ?? 'Mai' ?></p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-shield-alt text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tables Overview -->
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-table text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Struttura Database</h3>
                            <p class="text-slate-500 mt-1">Panoramica tabelle principali</p>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Tabella</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Record</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 uppercase">Dimensione</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Ultimo Aggiornamento</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/50 backdrop-blur-sm divide-y divide-slate-100">
                                <?php if (!empty($tables_info)): ?>
                                    <?php foreach ($tables_info as $table): ?>
                                    <tr class="hover:bg-slate-50/80 transition-all duration-200">
                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-database text-white text-xs"></i>
                                                </div>
                                                <span class="font-medium text-slate-900"><?= htmlspecialchars($table['name']) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="inline-flex items-center px-2 py-1 bg-cyan-100 text-cyan-800 rounded-full text-sm font-medium">
                                                <?= number_format($table['records']) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center text-slate-600"><?= $table['size'] ?></td>
                                        <td class="px-4 py-4 text-slate-600"><?= $table['last_update'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">
                                        <i class="fas fa-database text-2xl mb-2"></i>
                                        <p>Nessuna informazione sulle tabelle disponibile</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tables Tab -->
            <div x-show="activeTab === 'tables'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0">
                
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-list text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Gestione Tabelle</h3>
                            <p class="text-slate-500 mt-1">Visualizza e gestisci le tabelle del database</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php if (!empty($tables_list)): ?>
                            <?php foreach ($tables_list as $table): ?>
                            <div class="bg-white/70 rounded-xl border border-slate-200 p-4 hover:shadow-md transition-all duration-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-slate-400 to-slate-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-table text-white text-xs"></i>
                                        </div>
                                        <h4 class="font-semibold text-slate-900"><?= htmlspecialchars($table) ?></h4>
                                    </div>
                                </div>
                                <div class="text-xs text-slate-500 space-y-1">
                                    <div>Tabella del sistema MenooElo</div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <div class="col-span-full text-center py-12">
                            <i class="fas fa-table text-4xl text-slate-300 mb-4"></i>
                            <p class="text-slate-500">Nessuna tabella trovata</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Query Tab -->
            <div x-show="activeTab === 'query'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0">
                
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-code text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Query SQL</h3>
                            <p class="text-slate-500 mt-1">Esegui query personalizzate sul database</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-yellow-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-amber-900">Attenzione</h4>
                                <p class="text-amber-700 text-sm mt-1">Le query SQL possono modificare permanentemente i dati. Usa con cautela.</p>
                            </div>
                        </div>
                    </div>
                    
                    <form @submit.prevent="executeQuery()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <i class="fas fa-terminal text-slate-400 mr-2"></i>
                                Query SQL
                            </label>
                            <textarea x-model="sqlQuery" 
                                      rows="8" 
                                      placeholder="SELECT * FROM restaurants WHERE is_active = 1;"
                                      class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white/70 backdrop-blur-sm font-mono text-sm resize-none"></textarea>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-violet-600 text-white rounded-xl font-medium hover:from-purple-700 hover:to-violet-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-play"></i>
                                <span>Esegui Query</span>
                            </button>
                            <button type="button" @click="sqlQuery = ''" 
                                    class="inline-flex items-center gap-2 px-4 py-3 border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 transition-all duration-200">
                                <i class="fas fa-eraser"></i>
                                <span>Pulisci</span>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Query Results -->
                    <div x-show="queryResults" class="mt-8 bg-slate-50 rounded-xl border border-slate-200 p-4">
                        <h4 class="font-semibold text-slate-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-list-alt text-slate-500"></i>
                            Risultati Query
                        </h4>
                        <pre x-text="queryResults" class="text-sm text-slate-700 font-mono whitespace-pre-wrap"></pre>
                    </div>
                </div>
            </div>

            <!-- Maintenance Tab -->
            <div x-show="activeTab === 'maintenance'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Database Operations -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-tools text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Operazioni Database</h3>
                                <p class="text-slate-500 mt-1">Manutenzione e ottimizzazione</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <button @click="optimizeDatabase()" 
                                    class="w-full flex items-center gap-3 p-4 bg-blue-50 hover:bg-blue-100 rounded-xl border border-blue-200 transition-all duration-200">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-rocket text-white"></i>
                                </div>
                                <div class="text-left">
                                    <h4 class="font-semibold text-blue-900">Ottimizza Database</h4>
                                    <p class="text-sm text-blue-700">Ottimizza tutte le tabelle per migliorare le performance</p>
                                </div>
                            </button>
                            
                            <button @click="cleanupDatabase()" 
                                    class="w-full flex items-center gap-3 p-4 bg-green-50 hover:bg-green-100 rounded-xl border border-green-200 transition-all duration-200">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-broom text-white"></i>
                                </div>
                                <div class="text-left">
                                    <h4 class="font-semibold text-green-900">Pulizia Database</h4>
                                    <p class="text-sm text-green-700">Rimuovi dati temporanei e log vecchi</p>
                                </div>
                            </button>
                            
                            <button @click="repairDatabase()" 
                                    class="w-full flex items-center gap-3 p-4 bg-purple-50 hover:bg-purple-100 rounded-xl border border-purple-200 transition-all duration-200">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-wrench text-white"></i>
                                </div>
                                <div class="text-left">
                                    <h4 class="font-semibold text-purple-900">Ripara Database</h4>
                                    <p class="text-sm text-purple-700">Controlla e ripara eventuali errori nelle tabelle</p>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- System Info -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Informazioni Sistema</h3>
                                <p class="text-slate-500 mt-1">Dettagli configurazione database</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                <span class="text-slate-600">Versione MySQL</span>
                                <span class="font-medium text-slate-900"><?= $system_info['mysql_version'] ?? 'N/A' ?></span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                <span class="text-slate-600">Charset Database</span>
                                <span class="font-medium text-slate-900"><?= $system_info['charset'] ?? '<span class="text-slate-400">N/A</span>' ?></span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                <span class="text-slate-600">Collation</span>
                                <span class="font-medium text-slate-900"><?= $system_info['collation'] ?? '<span class="text-slate-400">N/A</span>' ?></span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                <span class="text-slate-600">Max Connections</span>
                                <span class="font-medium text-slate-900"><?= $system_info['max_connections'] ?? '<span class="text-slate-400">N/A</span>' ?></span>
                            </div>
                        </div>
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
function databaseManager() {
    return {
        activeTab: 'overview',
        sqlQuery: '',
        queryResults: '',
        
        refreshStats() {
            this.showNotification('Aggiornamento statistiche database...', 'info');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        },
        
        executeQuery() {
            if (!this.sqlQuery.trim()) {
                this.showNotification('Inserisci una query SQL valida', 'error');
                return;
            }
            
            this.showNotification('Esecuzione query in corso...', 'info');
            
            fetch('<?= BASE_URL ?>/superadmin/database/query', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    query: this.sqlQuery,
                    csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.queryResults = JSON.stringify(data.results, null, 2);
                    this.showNotification('Query eseguita con successo', 'success');
                } else {
                    this.queryResults = 'Errore: ' + data.error;
                    this.showNotification('Errore nell\'esecuzione della query', 'error');
                }
            })
            .catch(error => {
                this.queryResults = 'Errore di rete: ' + error.message;
                this.showNotification('Errore di connessione', 'error');
            });
        },
        
        optimizeDatabase() {
            if (confirm('Ottimizzare il database? Questa operazione può richiedere alcuni minuti.')) {
                this.showNotification('Ottimizzazione database in corso...', 'info');
                this.performMaintenance('optimize');
            }
        },
        
        cleanupDatabase() {
            if (confirm('Pulire il database? Verranno rimossi dati temporanei e log vecchi.')) {
                this.showNotification('Pulizia database in corso...', 'info');
                this.performMaintenance('cleanup');
            }
        },
        
        repairDatabase() {
            if (confirm('Riparare il database? Verranno controllate e riparate eventuali inconsistenze.')) {
                this.showNotification('Riparazione database in corso...', 'info');
                this.performMaintenance('repair');
            }
        },
        
        performMaintenance(operation) {
            fetch('<?= BASE_URL ?>/superadmin/database/maintenance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    operation: operation,
                    csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showNotification(`${operation} completata con successo`, 'success');
                } else {
                    this.showNotification(`Errore durante ${operation}: ` + data.error, 'error');
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