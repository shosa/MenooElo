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
                    <h1 class="text-3xl font-bold text-gray-900">Gestione Database</h1>
                    <p class="text-gray-600 mt-1">Strumenti di manutenzione e statistiche del database</p>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Success/Error Messages -->
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Database Statistics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-red-600"></i>
                        Statistiche Database
                    </h2>
                    
                    <div class="space-y-4">
                        <?php foreach ($stats as $label => $count): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($label) ?></span>
                            </div>
                            <span class="text-lg font-semibold text-gray-900"><?= number_format($count) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Database Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-tools text-red-600"></i>
                        Operazioni Database
                    </h2>
                    
                    <div class="space-y-4">
                        <!-- Optimize Database -->
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        <i class="fas fa-wrench text-blue-600"></i>
                                        Ottimizza Database
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Ottimizza tutte le tabelle del database per migliorare le performance
                                    </p>
                                </div>
                                <form method="POST" class="ml-4">
                                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                    <input type="hidden" name="action" value="optimize">
                                    <button type="submit" 
                                            onclick="return confirm('Sei sicuro di voler ottimizzare il database?')"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-play mr-1"></i>
                                        Ottimizza
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Backup Database -->
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        <i class="fas fa-download text-green-600"></i>
                                        Backup Database
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Crea un backup completo del database (funzionalità in sviluppo)
                                    </p>
                                </div>
                                <form method="POST" class="ml-4">
                                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                    <input type="hidden" name="action" value="backup">
                                    <button type="submit" 
                                            onclick="return confirm('Sei sicuro di voler creare un backup?')"
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                                            disabled>
                                        <i class="fas fa-download mr-1"></i>
                                        Backup
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- System Info -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">
                                <i class="fas fa-info-circle text-gray-600"></i>
                                Informazioni Sistema
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">PHP Version:</span>
                                    <span class="font-medium"><?= PHP_VERSION ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">MySQL Version:</span>
                                    <span class="font-medium">8.0+</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Server:</span>
                                    <span class="font-medium"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Data/Ora:</span>
                                    <span class="font-medium"><?= date('d/m/Y H:i:s') ?></span>
                                </div>
                            </div>
                        </div>
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