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
                    <h1 class="text-3xl font-bold text-gray-900">
                        <?= isset($admin) ? 'Modifica Admin' : 'Nuovo Admin' ?>
                    </h1>
                    <p class="text-gray-600 mt-1">
                        <?= isset($admin) ? 'Aggiorna le informazioni dell\'admin' : 'Crea un nuovo account admin per un ristorante' ?>
                    </p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>/superadmin/admins" 
                       class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-arrow-left"></i>
                        <span>Torna alla Lista</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <div class="max-w-4xl mx-auto">
                <?php if (isset($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle mt-0.5"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-8">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <!-- Admin Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-user text-red-600"></i>
                            Informazioni Admin
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome Completo *
                                </label>
                                <input type="text" 
                                       name="full_name" 
                                       required 
                                       value="<?= htmlspecialchars($admin['full_name'] ?? '') ?>"
                                       placeholder="Mario Rossi"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ristorante *
                                </label>
                                <select name="restaurant_id" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">Seleziona un ristorante...</option>
                                    <?php foreach ($restaurants as $restaurant): ?>
                                    <option value="<?= $restaurant['id'] ?>" 
                                            <?= (isset($admin) && $admin['restaurant_id'] == $restaurant['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($restaurant['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-key text-red-600"></i>
                            Credenziali di Accesso
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Username *
                                </label>
                                <input type="text" 
                                       name="username" 
                                       required 
                                       value="<?= htmlspecialchars($admin['username'] ?? '') ?>"
                                       placeholder="mario_rossi"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <p class="text-xs text-gray-500 mt-1">Username per il login (solo lettere, numeri e underscore)</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email *
                                </label>
                                <input type="email" 
                                       name="email" 
                                       required 
                                       value="<?= htmlspecialchars($admin['email'] ?? '') ?>"
                                       placeholder="mario@ristorante.com"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Password <?= isset($admin) ? '(lascia vuoto per mantenerla)' : '*' ?>
                                </label>
                                <input type="password" 
                                       name="password" 
                                       <?= !isset($admin) ? 'required' : '' ?>
                                       placeholder="Password sicura"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <?php if (isset($admin)): ?>
                                <p class="text-xs text-gray-500 mt-1">Lascia vuoto per mantenere la password attuale</p>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ruolo
                                </label>
                                <select name="role" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="admin" <?= (!isset($admin) || $admin['role'] === 'admin') ? 'selected' : '' ?>>
                                        Admin - Accesso completo al ristorante
                                    </option>
                                    <option value="owner" <?= (isset($admin) && $admin['role'] === 'owner') ? 'selected' : '' ?>>
                                        Owner - Proprietario del ristorante
                                    </option>
                                    <option value="staff" <?= (isset($admin) && $admin['role'] === 'staff') ? 'selected' : '' ?>>
                                        Staff - Accesso limitato
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Current Info (only for edit) -->
                    <?php if (isset($admin)): ?>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <h2 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            Informazioni Attuali
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-blue-600 font-medium">Ristorante:</span>
                                <div class="text-blue-800"><?= htmlspecialchars($admin['restaurant_name']) ?></div>
                            </div>
                            <div>
                                <span class="text-blue-600 font-medium">Creato il:</span>
                                <div class="text-blue-800"><?= date('d/m/Y H:i', strtotime($admin['created_at'])) ?></div>
                            </div>
                            <div>
                                <span class="text-blue-600 font-medium">Ultimo aggiornamento:</span>
                                <div class="text-blue-800"><?= date('d/m/Y H:i', strtotime($admin['updated_at'])) ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button type="submit" 
                                class="flex-1 sm:flex-initial px-8 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 focus:ring-4 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-200">
                            <i class="fas fa-save mr-2"></i>
                            <?= isset($admin) ? 'Aggiorna Admin' : 'Crea Admin' ?>
                        </button>
                        <a href="<?= BASE_URL ?>/superadmin/admins" 
                           class="flex-1 sm:flex-initial px-8 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 text-center transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Annulla
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>