<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Database Manager</h1>
                    <p class="text-gray-600 mt-1">Gestore completo del database - Tabelle, Query SQL, CRUD e molto altro</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                        <i class="fas fa-database mr-1"></i>
                        Connesso
                    </span>
                    <button onclick="refreshPage()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Navigation Tabs -->
        <div class="bg-white border-b border-gray-200">
            <div class="px-6">
                <nav class="flex space-x-8">
                    <button onclick="showTab('overview')" class="tab-btn py-4 px-1 border-b-2 font-medium text-sm transition-colors border-blue-500 text-blue-600" data-tab="overview">
                        <i class="fas fa-tachometer-alt mr-2"></i>Panoramica
                    </button>
                    <button onclick="showTab('tables')" class="tab-btn py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700" data-tab="tables">
                        <i class="fas fa-table mr-2"></i>Tabelle
                    </button>
                    <button onclick="showTab('query')" class="tab-btn py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700" data-tab="query">
                        <i class="fas fa-code mr-2"></i>Query SQL
                    </button>
                    <button onclick="showTab('import')" class="tab-btn py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700" data-tab="import">
                        <i class="fas fa-upload mr-2"></i>Import/Export
                    </button>
                    <button onclick="showTab('maintenance')" class="tab-btn py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700" data-tab="maintenance">
                        <i class="fas fa-tools mr-2"></i>Manutenzione
                    </button>
                </nav>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <div id="messages" class="px-6 pt-6">
            <?php if (isset($success)): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-6 flex items-start gap-3">
                <i class="fas fa-check-circle mt-0.5"></i>
                <span><?= htmlspecialchars($success) ?></span>
            </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 flex items-start gap-3">
                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tab Contents -->
        <div class="p-6">
            <!-- Overview Tab -->
            <div id="tab-overview" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Database Stats Cards -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tabelle Totali</p>
                                <p class="text-3xl font-bold text-gray-900" id="total-tables">-</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-table text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Dimensione DB</p>
                                <p class="text-3xl font-bold text-gray-900" id="db-size">-</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hdd text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Versione MySQL</p>
                                <p class="text-3xl font-bold text-gray-900" id="mysql-version">-</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-database text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Collazione</p>
                                <p class="text-lg font-bold text-gray-900" id="db-collation">-</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-sort-alpha-down text-orange-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tables Overview -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Tabelle del Database</h3>
                        <p class="text-sm text-gray-600">Panoramica delle tabelle principali</p>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Nome Tabella</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Righe</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Dimensione</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Engine</th>
                                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody id="tables-overview">
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-gray-500">
                                            <i class="fas fa-spinner fa-spin mr-2"></i>
                                            Caricamento tabelle...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tables Tab -->
            <div id="tab-tables" class="tab-content hidden">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Tables List -->
                    <div class="lg:w-1/3">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Liste Tabelle</h3>
                                <div class="mt-3">
                                    <input type="text" id="table-search" placeholder="Cerca tabella..." 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <div id="tables-list" class="p-2">
                                    <div class="text-center py-8 text-gray-500">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>
                                        Caricamento...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Details -->
                    <div class="lg:w-2/3">
                        <div id="table-details" class="bg-white rounded-xl shadow-sm border border-gray-200 min-h-96">
                            <div class="flex items-center justify-center h-96 text-gray-500">
                                <div class="text-center">
                                    <i class="fas fa-table text-4xl mb-4"></i>
                                    <p>Seleziona una tabella per visualizzarne i dettagli</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SQL Query Tab -->
            <div id="tab-query" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Query Editor -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Editor SQL</h3>
                                <div class="flex items-center gap-2">
                                    <button onclick="formatSQL()" class="px-3 py-1 bg-gray-100 text-gray-700 rounded text-sm hover:bg-gray-200">
                                        <i class="fas fa-magic mr-1"></i>Formatta
                                    </button>
                                    <button onclick="clearSQL()" class="px-3 py-1 bg-gray-100 text-gray-700 rounded text-sm hover:bg-gray-200">
                                        <i class="fas fa-eraser mr-1"></i>Cancella
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <textarea id="sql-editor" rows="12" 
                                          class="w-full font-mono text-sm border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="-- Scrivi la tua query SQL qui...&#10;SELECT * FROM restaurants LIMIT 10;"></textarea>
                                <div class="mt-4 flex items-center gap-3">
                                    <button onclick="executeSQL()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                                        <i class="fas fa-play mr-2"></i>Esegui Query
                                    </button>
                                    <button onclick="explainSQL()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                        <i class="fas fa-search mr-2"></i>EXPLAIN
                                    </button>
                                    <span id="query-time" class="text-sm text-gray-600"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Query Results -->
                        <div id="query-results" class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 hidden">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Risultati Query</h3>
                            </div>
                            <div class="p-4">
                                <div id="results-content"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Query Helpers -->
                    <div>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Query Rapide</h3>
                            </div>
                            <div class="p-4 space-y-3">
                                <button onclick="insertQuery('SHOW TABLES;')" class="w-full text-left px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-table mr-2 text-blue-600"></i>SHOW TABLES
                                </button>
                                <button onclick="insertQuery('SHOW DATABASES;')" class="w-full text-left px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-database mr-2 text-green-600"></i>SHOW DATABASES
                                </button>
                                <button onclick="insertQuery('SELECT VERSION();')" class="w-full text-left px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-info-circle mr-2 text-purple-600"></i>Versione MySQL
                                </button>
                                <button onclick="insertQuery('SHOW PROCESSLIST;')" class="w-full text-left px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-list mr-2 text-orange-600"></i>Processi Attivi
                                </button>
                                <hr class="my-3">
                                <button onclick="insertQuery('SELECT * FROM restaurants LIMIT 10;')" class="w-full text-left px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-store mr-2 text-red-600"></i>Ristoranti
                                </button>
                                <button onclick="insertQuery('SELECT * FROM restaurant_admins LIMIT 10;')" class="w-full text-left px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-users mr-2 text-yellow-600"></i>Admin
                                </button>
                                <button onclick="insertQuery('SELECT * FROM menu_categories LIMIT 10;')" class="w-full text-left px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-tags mr-2 text-cyan-600"></i>Categorie
                                </button>
                            </div>
                        </div>

                        <!-- Query History -->
                        <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Cronologia Query</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <div id="query-history" class="p-4">
                                    <p class="text-sm text-gray-500 text-center py-4">Nessuna query eseguita</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Import/Export Tab -->
            <div id="tab-import" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Import -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-upload text-blue-600"></i>
                                Import Database
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Importa file SQL nel database</p>
                        </div>
                        <div class="p-6">
                            <form id="import-form" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">File SQL</label>
                                    <input type="file" name="sql_file" accept=".sql" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <p class="text-xs text-gray-500 mt-1">Supporta file .sql (max 50MB)</p>
                                </div>
                                <div class="mb-4">
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="ignore_errors" class="rounded">
                                        <span class="text-sm text-gray-700">Ignora errori durante l'importazione</span>
                                    </label>
                                </div>
                                <button type="button" onclick="importSQL()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-upload mr-2"></i>Importa SQL
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Export -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-download text-green-600"></i>
                                Export Database
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Esporta database o singole tabelle</p>
                        </div>
                        <div class="p-6">
                            <form id="export-form">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo Export</label>
                                    <select name="export_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                        <option value="full">Database Completo</option>
                                        <option value="structure">Solo Struttura</option>
                                        <option value="data">Solo Dati</option>
                                        <option value="custom">Tabelle Selezionate</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Formato</label>
                                    <select name="export_format" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                        <option value="sql">SQL</option>
                                        <option value="csv">CSV</option>
                                        <option value="json">JSON</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="add_drop_table" checked class="rounded">
                                        <span class="text-sm text-gray-700">Aggiungi DROP TABLE</span>
                                    </label>
                                </div>
                                <button type="button" onclick="exportDB()" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    <i class="fas fa-download mr-2"></i>Esporta Database
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Quick Backups -->
                <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Backup Rapidi</h3>
                        <p class="text-sm text-gray-600 mt-1">Crea backup automatici del database</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <button onclick="quickBackup('full')" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-center">
                                <i class="fas fa-database text-blue-600 text-xl mb-2"></i>
                                <p class="font-medium">Backup Completo</p>
                                <p class="text-xs text-gray-500">Struttura + Dati</p>
                            </button>
                            <button onclick="quickBackup('structure')" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-center">
                                <i class="fas fa-sitemap text-green-600 text-xl mb-2"></i>
                                <p class="font-medium">Solo Struttura</p>
                                <p class="text-xs text-gray-500">CREATE TABLE</p>
                            </button>
                            <button onclick="quickBackup('data')" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-center">
                                <i class="fas fa-table text-purple-600 text-xl mb-2"></i>
                                <p class="font-medium">Solo Dati</p>
                                <p class="text-xs text-gray-500">INSERT INTO</p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Tab -->
            <div id="tab-maintenance" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Database Operations -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-tools text-orange-600"></i>
                                Operazioni Database
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <button onclick="optimizeDB()" class="w-full p-4 text-left border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">Ottimizza Database</p>
                                        <p class="text-sm text-gray-600">Ottimizza tutte le tabelle</p>
                                    </div>
                                    <i class="fas fa-tachometer-alt text-blue-600"></i>
                                </div>
                            </button>
                            
                            <button onclick="repairDB()" class="w-full p-4 text-left border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">Ripara Database</p>
                                        <p class="text-sm text-gray-600">Verifica e ripara tabelle corrotte</p>
                                    </div>
                                    <i class="fas fa-wrench text-green-600"></i>
                                </div>
                            </button>
                            
                            <button onclick="checkDB()" class="w-full p-4 text-left border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">Controlla Integrità</p>
                                        <p class="text-sm text-gray-600">Verifica integrità delle tabelle</p>
                                    </div>
                                    <i class="fas fa-shield-alt text-purple-600"></i>
                                </div>
                            </button>
                            
                            <button onclick="analyzeDB()" class="w-full p-4 text-left border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">Analizza Statistiche</p>
                                        <p class="text-sm text-gray-600">Aggiorna statistiche tabelle</p>
                                    </div>
                                    <i class="fas fa-chart-line text-indigo-600"></i>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- System Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-info-circle text-blue-600"></i>
                                Informazioni Sistema
                            </h3>
                        </div>
                        <div class="p-6">
                            <div id="system-info" class="space-y-4">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Versione MySQL:</span>
                                    <span class="font-medium" id="sys-mysql-version">-</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Versione PHP:</span>
                                    <span class="font-medium"><?= PHP_VERSION ?></span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Charset Default:</span>
                                    <span class="font-medium" id="sys-charset">-</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Max Upload:</span>
                                    <span class="font-medium"><?= ini_get('upload_max_filesize') ?></span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Max Execution:</span>
                                    <span class="font-medium"><?= ini_get('max_execution_time') ?>s</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Memory Limit:</span>
                                    <span class="font-medium"><?= ini_get('memory_limit') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Operations Log -->
                <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Log Operazioni Recenti</h3>
                    </div>
                    <div class="p-6">
                        <div id="operations-log" class="max-h-64 overflow-y-auto">
                            <p class="text-sm text-gray-500 text-center py-4">Nessuna operazione recente</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loading-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
        <div class="text-center">
            <i class="fas fa-spinner fa-spin text-3xl text-blue-600 mb-4"></i>
            <h3 class="text-lg font-semibold mb-2">Elaborazione in corso...</h3>
            <p class="text-gray-600 text-sm">Attendere prego, non chiudere questa finestra.</p>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center gap-3 mb-4">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl"></i>
            <h3 class="text-lg font-semibold">Conferma Operazione</h3>
        </div>
        <p id="confirm-message" class="text-gray-600 mb-6">Sei sicuro di voler eseguire questa operazione?</p>
        <div class="flex gap-3 justify-end">
            <button onclick="closeModal('confirm-modal')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                Annulla
            </button>
            <button id="confirm-action" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                Conferma
            </button>
        </div>
    </div>
</div>

<script>
// Global variables
let currentTable = null;
let queryHistory = JSON.parse(localStorage.getItem('dbManager_queryHistory') || '[]');
const csrfToken = '<?= $csrf_token ?? '' ?>';

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadDatabaseStats();
    loadTablesList();
    loadQueryHistory();
    loadSystemInfo();
});

// Tab Management
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active state from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-blue-500', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab
    document.getElementById(`tab-${tabName}`).classList.remove('hidden');
    
    // Activate selected tab button
    const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
    activeBtn.classList.remove('border-transparent', 'text-gray-500');
    activeBtn.classList.add('border-blue-500', 'text-blue-600');
}

// Load database statistics
async function loadDatabaseStats() {
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/database-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'get_stats', csrf_token: csrfToken })
        });
        
        const data = await response.json();
        if (data.success) {
            document.getElementById('total-tables').textContent = data.stats.table_count;
            document.getElementById('db-size').textContent = data.stats.db_size;
            document.getElementById('mysql-version').textContent = data.stats.mysql_version;
            document.getElementById('db-collation').textContent = data.stats.collation;
            
            // Update system info
            document.getElementById('sys-mysql-version').textContent = data.stats.mysql_version;
            document.getElementById('sys-charset').textContent = data.stats.charset;
            
            // Load tables overview
            loadTablesOverview(data.stats.tables);
        }
    } catch (error) {
        console.error('Error loading database stats:', error);
    }
}

// Load tables overview
function loadTablesOverview(tables) {
    const tbody = document.getElementById('tables-overview');
    if (!tables || tables.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-gray-500">Nessuna tabella trovata</td></tr>';
        return;
    }
    
    tbody.innerHTML = tables.map(table => `
        <tr class="border-b border-gray-100 hover:bg-gray-50">
            <td class="py-3 px-4 font-medium">${table.name}</td>
            <td class="py-3 px-4">${table.rows.toLocaleString()}</td>
            <td class="py-3 px-4">${table.size}</td>
            <td class="py-3 px-4">
                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">${table.engine}</span>
            </td>
            <td class="py-3 px-4 text-right">
                <button onclick="viewTable('${table.name}')" class="px-3 py-1 bg-gray-100 text-gray-700 rounded text-sm hover:bg-gray-200">
                    <i class="fas fa-eye mr-1"></i>Visualizza
                </button>
            </td>
        </tr>
    `).join('');
}

// Load tables list
async function loadTablesList() {
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/database-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'get_tables', csrf_token: csrfToken })
        });
        
        const data = await response.json();
        if (data.success) {
            const container = document.getElementById('tables-list');
            container.innerHTML = data.tables.map(table => `
                <button onclick="selectTable('${table.name}')" 
                        class="w-full text-left px-3 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors table-item"
                        data-table="${table.name}">
                    <div class="flex items-center justify-between">
                        <span class="font-medium">${table.name}</span>
                        <span class="text-xs text-gray-500">${table.rows}</span>
                    </div>
                </button>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading tables list:', error);
    }
}

// Select table and load details
async function selectTable(tableName) {
    currentTable = tableName;
    
    // Update active state
    document.querySelectorAll('.table-item').forEach(item => {
        item.classList.remove('bg-blue-100', 'text-blue-700');
    });
    document.querySelector(`[data-table="${tableName}"]`).classList.add('bg-blue-100', 'text-blue-700');
    
    // Load table details
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/database-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'get_table_details', table: tableName, csrf_token: csrfToken })
        });
        
        const data = await response.json();
        if (data.success) {
            displayTableDetails(data.details);
        }
    } catch (error) {
        console.error('Error loading table details:', error);
    }
}

// Display table details
function displayTableDetails(details) {
    const container = document.getElementById('table-details');
    container.innerHTML = `
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">${details.name}</h3>
                <div class="flex items-center gap-2">
                    <button onclick="browseTable('${details.name}')" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                        <i class="fas fa-search mr-1"></i>Sfoglia
                    </button>
                    <button onclick="exportTable('${details.name}')" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                        <i class="fas fa-download mr-1"></i>Esporta
                    </button>
                    <div class="relative">
                        <button onclick="toggleTableMenu('${details.name}')" class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div id="table-menu-${details.name}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                            <button onclick="optimizeTable('${details.name}')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Ottimizza</button>
                            <button onclick="repairTable('${details.name}')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Ripara</button>
                            <button onclick="truncateTable('${details.name}')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 text-red-600">Svuota</button>
                            <button onclick="dropTable('${details.name}')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 text-red-600">Elimina</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <p class="text-sm text-gray-600">Righe</p>
                    <p class="text-lg font-bold">${details.rows.toLocaleString()}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <p class="text-sm text-gray-600">Dimensione</p>
                    <p class="text-lg font-bold">${details.size}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <p class="text-sm text-gray-600">Engine</p>
                    <p class="text-lg font-bold">${details.engine}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <p class="text-sm text-gray-600">Collation</p>
                    <p class="text-lg font-bold">${details.collation}</p>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h4 class="font-semibold text-gray-900">Struttura Tabella</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left py-2 px-4 font-medium text-gray-700">Campo</th>
                                <th class="text-left py-2 px-4 font-medium text-gray-700">Tipo</th>
                                <th class="text-left py-2 px-4 font-medium text-gray-700">Null</th>
                                <th class="text-left py-2 px-4 font-medium text-gray-700">Chiave</th>
                                <th class="text-left py-2 px-4 font-medium text-gray-700">Default</th>
                                <th class="text-left py-2 px-4 font-medium text-gray-700">Extra</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${details.columns.map(col => `
                                <tr class="border-b border-gray-100">
                                    <td class="py-2 px-4 font-medium">${col.Field}</td>
                                    <td class="py-2 px-4 font-mono text-sm">${col.Type}</td>
                                    <td class="py-2 px-4">${col.Null === 'YES' ? 'Sì' : 'No'}</td>
                                    <td class="py-2 px-4">
                                        ${col.Key ? `<span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">${col.Key}</span>` : ''}
                                    </td>
                                    <td class="py-2 px-4">${col.Default || '-'}</td>
                                    <td class="py-2 px-4">${col.Extra || '-'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
}

// SQL Query functions
function insertQuery(query) {
    document.getElementById('sql-editor').value = query;
    document.getElementById('sql-editor').focus();
}

function formatSQL() {
    const editor = document.getElementById('sql-editor');
    let sql = editor.value.trim();
    
    if (!sql) return;
    
    // Basic SQL formatting
    sql = sql.replace(/\b(SELECT|FROM|WHERE|ORDER BY|GROUP BY|HAVING|INSERT|UPDATE|DELETE|CREATE|ALTER|DROP|SHOW)\b/gi, (match) => {
        return '\n' + match.toUpperCase();
    });
    
    sql = sql.replace(/,/g, ',\n    ');
    sql = sql.trim();
    
    editor.value = sql;
}

function clearSQL() {
    document.getElementById('sql-editor').value = '';
    document.getElementById('query-results').classList.add('hidden');
}

async function executeSQL() {
    const query = document.getElementById('sql-editor').value.trim();
    if (!query) return;
    
    showModal('loading-modal');
    const startTime = Date.now();
    
    try {
        console.log('Sending request with CSRF token:', csrfToken);
        const response = await fetch('<?= BASE_URL ?>/superadmin/database-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'execute_query', query: query, csrf_token: csrfToken })
        });
        
        const data = await response.json();
        console.log('API Response:', data);
        const executionTime = Date.now() - startTime;
        
        hideModal('loading-modal');
        
        if (!response.ok) {
            console.error('API Error:', response.status, data);
        }
        
        displayQueryResults(data, query, executionTime);
        addToQueryHistory(query, data.success, executionTime);
        
    } catch (error) {
        hideModal('loading-modal');
        console.error('Error executing query:', error);
        showMessage('Errore durante l\'esecuzione della query', 'error');
    }
}

async function explainSQL() {
    const query = document.getElementById('sql-editor').value.trim();
    if (!query) {
        showMessage('Inserisci una query SQL da analizzare', 'error');
        return;
    }
    
    // Check if it's a SELECT query
    if (!query.toLowerCase().trim().startsWith('select')) {
        showMessage('EXPLAIN funziona solo con query SELECT', 'error');
        return;
    }
    
    showModal('loading-modal');
    const startTime = Date.now();
    
    try {
        const explainQuery = 'EXPLAIN ' + query;
        console.log('Sending EXPLAIN request:', explainQuery);
        
        const response = await fetch('<?= BASE_URL ?>/superadmin/database-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'execute_query', query: explainQuery, csrf_token: csrfToken })
        });
        
        const data = await response.json();
        const executionTime = Date.now() - startTime;
        
        hideModal('loading-modal');
        
        if (data.success && data.results) {
            displayQueryResults({
                success: true,
                type: 'select',
                results: data.results,
                count: data.count,
                execution_time: data.execution_time
            }, explainQuery, executionTime);
        } else {
            showMessage('Errore nell\'esecuzione di EXPLAIN: ' + (data.error || 'Errore sconosciuto'), 'error');
        }
        
    } catch (error) {
        hideModal('loading-modal');
        console.error('Error executing EXPLAIN:', error);
        showMessage('Errore durante l\'analisi della query', 'error');
    }
}

function displayQueryResults(data, query, executionTime) {
    const resultsContainer = document.getElementById('query-results');
    const contentDiv = document.getElementById('results-content');
    
    document.getElementById('query-time').textContent = `Eseguito in ${executionTime}ms`;
    
    if (data.success) {
        if (data.type === 'select' && data.results) {
            contentDiv.innerHTML = createResultsTable(data.results);
        } else {
            contentDiv.innerHTML = `
                <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    Query eseguita con successo. ${data.affected_rows ? `Righe interessate: ${data.affected_rows}` : ''}
                </div>
            `;
        }
    } else {
        contentDiv.innerHTML = `
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Errore: ${data.error}
            </div>
        `;
    }
    
    resultsContainer.classList.remove('hidden');
}

function createResultsTable(results) {
    if (!results || results.length === 0) {
        return '<p class="text-gray-500 text-center py-4">Nessun risultato</p>';
    }
    
    const columns = Object.keys(results[0]);
    
    return `
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        ${columns.map(col => `<th class="text-left py-2 px-4 font-medium text-gray-700 border-b">${col}</th>`).join('')}
                    </tr>
                </thead>
                <tbody>
                    ${results.slice(0, 100).map((row, i) => `
                        <tr class="${i % 2 === 0 ? 'bg-white' : 'bg-gray-50'}">
                            ${columns.map(col => `<td class="py-2 px-4 border-b text-sm">${row[col] !== null ? row[col] : '<em class="text-gray-400">NULL</em>'}</td>`).join('')}
                        </tr>
                    `).join('')}
                </tbody>
            </table>
            ${results.length > 100 ? `<p class="text-sm text-gray-500 mt-2">Mostrati i primi 100 risultati di ${results.length}</p>` : ''}
        </div>
    `;
}

// Query History
function addToQueryHistory(query, success, executionTime) {
    const historyItem = {
        query: query.substring(0, 200) + (query.length > 200 ? '...' : ''),
        success,
        executionTime,
        timestamp: new Date().toLocaleString()
    };
    
    queryHistory.unshift(historyItem);
    queryHistory = queryHistory.slice(0, 20); // Keep only last 20
    
    localStorage.setItem('dbManager_queryHistory', JSON.stringify(queryHistory));
    loadQueryHistory();
}

function loadQueryHistory() {
    const container = document.getElementById('query-history');
    
    if (queryHistory.length === 0) {
        container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Nessuna query eseguita</p>';
        return;
    }
    
    container.innerHTML = queryHistory.map(item => `
        <div class="mb-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50" onclick="insertQuery('${item.query.replace(/'/g, "\\'")}')">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs ${item.success ? 'text-green-600' : 'text-red-600'}">
                    <i class="fas ${item.success ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-1"></i>
                    ${item.success ? 'Successo' : 'Errore'} • ${item.executionTime}ms
                </span>
                <span class="text-xs text-gray-500">${item.timestamp}</span>
            </div>
            <p class="text-sm font-mono text-gray-700 truncate">${item.query}</p>
        </div>
    `).join('');
}

// Utility functions
function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function hideModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function closeModal(modalId) {
    hideModal(modalId);
}

function showMessage(message, type = 'info') {
    const messagesDiv = document.getElementById('messages');
    const alertClass = type === 'error' ? 'bg-red-50 border-red-200 text-red-700' : 'bg-green-50 border-green-200 text-green-700';
    const icon = type === 'error' ? 'fa-exclamation-triangle' : 'fa-check-circle';
    
    messagesDiv.innerHTML = `
        <div class="${alertClass} p-4 rounded-lg mb-6 flex items-start gap-3">
            <i class="fas ${icon} mt-0.5"></i>
            <span>${message}</span>
        </div>
    `;
}

function refreshPage() {
    location.reload();
}

// Load system information
function loadSystemInfo() {
    // System info is partially loaded from PHP, additional AJAX calls can be made here
}

// Maintenance functions (stubs - implement based on your needs)
async function optimizeDB() {
    if (!confirm('Ottimizzare tutte le tabelle del database?')) return;
    // Implementation here
}

async function repairDB() {
    if (!confirm('Riparare tutte le tabelle del database?')) return;
    // Implementation here
}

async function checkDB() {
    // Implementation here
}

async function analyzeDB() {
    // Implementation here
}

// Import/Export functions
async function importSQL() {
    showMessage('Funzionalità di import non ancora implementata', 'error');
}

async function exportDB() {
    const form = document.getElementById('export-form');
    const formData = new FormData(form);
    
    const exportType = formData.get('export_type');
    const format = formData.get('export_format');
    
    if (!confirm(`Esportare il database in formato ${format.toUpperCase()}?`)) {
        return;
    }
    
    showModal('loading-modal');
    
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/database-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'export_database',
                export_type: exportType,
                format: format,
                csrf_token: csrfToken
            })
        });
        
        const data = await response.json();
        hideModal('loading-modal');
        
        if (data.success) {
            showMessage(`Export completato! File: ${data.filename} (${(data.size / 1024).toFixed(2)} KB)`, 'success');
            
            // Create download link
            const downloadLink = document.createElement('a');
            downloadLink.href = data.download_url;
            downloadLink.download = data.filename;
            downloadLink.click();
        } else {
            showMessage('Errore durante l\'export: ' + data.error, 'error');
        }
        
    } catch (error) {
        hideModal('loading-modal');
        console.error('Export error:', error);
        showMessage('Errore durante l\'export del database', 'error');
    }
}

async function quickBackup(type) {
    if (!confirm(`Creare backup ${type} del database?`)) {
        return;
    }
    
    showModal('loading-modal');
    
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/database-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'backup_database',
                backup_type: type,
                csrf_token: csrfToken
            })
        });
        
        const data = await response.json();
        hideModal('loading-modal');
        
        if (data.success) {
            showMessage(`Backup ${type} completato! File: ${data.filename}`, 'success');
            
            // Create download link
            const downloadLink = document.createElement('a');
            downloadLink.href = data.download_url;
            downloadLink.download = data.filename;
            downloadLink.click();
        } else {
            showMessage('Errore durante il backup: ' + data.error, 'error');
        }
        
    } catch (error) {
        hideModal('loading-modal');
        console.error('Backup error:', error);
        showMessage('Errore durante il backup del database', 'error');
    }
}

// Table-specific functions (stubs)
function viewTable(tableName) {
    showTab('tables');
    setTimeout(() => selectTable(tableName), 100);
}

function browseTable(tableName) {
    insertQuery(`SELECT * FROM ${tableName} LIMIT 50;`);
    showTab('query');
}

async function exportTable(tableName) {
    if (!confirm(`Esportare la tabella ${tableName}?`)) {
        return;
    }
    
    showModal('loading-modal');
    
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/database-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'export_database',
                export_type: 'custom',
                format: 'sql',
                tables: [tableName],
                csrf_token: csrfToken
            })
        });
        
        const data = await response.json();
        hideModal('loading-modal');
        
        if (data.success) {
            const downloadLink = document.createElement('a');
            downloadLink.href = data.download_url;
            downloadLink.download = data.filename;
            downloadLink.click();
            showMessage(`Tabella ${tableName} esportata con successo!`, 'success');
        } else {
            showMessage('Errore durante l\'export: ' + data.error, 'error');
        }
        
    } catch (error) {
        hideModal('loading-modal');
        console.error('Export table error:', error);
        showMessage('Errore durante l\'export della tabella', 'error');
    }
}

function toggleTableMenu(tableName) {
    document.getElementById(`table-menu-${tableName}`).classList.toggle('hidden');
}

async function optimizeTable(tableName) {
    if (!confirm(`Ottimizzare la tabella ${tableName}?`)) {
        return;
    }
    
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/database-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'optimize_table',
                table: tableName,
                csrf_token: csrfToken
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage(data.message, 'success');
            loadTablesList(); // Refresh
        } else {
            showMessage('Errore: ' + data.error, 'error');
        }
        
    } catch (error) {
        console.error('Optimize table error:', error);
        showMessage('Errore durante l\'ottimizzazione', 'error');
    }
}

async function repairTable(tableName) {
    if (!confirm(`Riparare la tabella ${tableName}?`)) {
        return;
    }
    
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/database-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'repair_table',
                table: tableName,
                csrf_token: csrfToken
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage(data.message, 'success');
        } else {
            showMessage('Errore: ' + data.error, 'error');
        }
        
    } catch (error) {
        console.error('Repair table error:', error);
        showMessage('Errore durante la riparazione', 'error');
    }
}

async function truncateTable(tableName) {
    if (!confirm(`ATTENZIONE: Svuotare completamente la tabella ${tableName}? Tutti i dati saranno persi!`)) {
        return;
    }
    
    if (!confirm('Sei sicuro? Questa operazione non può essere annullata!')) {
        return;
    }
    
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/database-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'truncate_table',
                table: tableName,
                csrf_token: csrfToken
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage(data.message, 'success');
            loadTablesList(); // Refresh
            if (currentTable === tableName) {
                selectTable(tableName); // Refresh details
            }
        } else {
            showMessage('Errore: ' + data.error, 'error');
        }
        
    } catch (error) {
        console.error('Truncate table error:', error);
        showMessage('Errore durante lo svuotamento', 'error');
    }
}

async function dropTable(tableName) {
    if (!confirm(`ATTENZIONE: Eliminare completamente la tabella ${tableName}? La tabella e tutti i suoi dati saranno persi!`)) {
        return;
    }
    
    if (!confirm('Sei ASSOLUTAMENTE sicuro? Questa operazione non può essere annullata!')) {
        return;
    }
    
    try {
        const response = await fetch('<?= BASE_URL ?>/superadmin/database-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'drop_table',
                table: tableName,
                csrf_token: csrfToken
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage(data.message, 'success');
            loadTablesList(); // Refresh
            // Clear details if this table was selected
            if (currentTable === tableName) {
                document.getElementById('table-details').innerHTML = `
                    <div class="flex items-center justify-center h-96 text-gray-500">
                        <div class="text-center">
                            <i class="fas fa-table text-4xl mb-4"></i>
                            <p>Seleziona una tabella per visualizzarne i dettagli</p>
                        </div>
                    </div>
                `;
                currentTable = null;
            }
        } else {
            showMessage('Errore: ' + data.error, 'error');
        }
        
    } catch (error) {
        console.error('Drop table error:', error);
        showMessage('Errore durante l\'eliminazione', 'error');
    }
}

// Table search functionality
document.getElementById('table-search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.table-item').forEach(item => {
        const tableName = item.dataset.table.toLowerCase();
        if (tableName.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>