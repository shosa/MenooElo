<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 overflow-x-hidden" x-data="restaurantsManager()">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64 min-w-0">
        <!-- Modern Header -->
        <div class="bg-white/80 backdrop-blur-xl border-b border-white/20 shadow-sm sticky top-0 z-30">
            <div class="px-6 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">
                            Gestione Ristoranti
                        </h1>
                        <p class="text-slate-600 mt-2 flex items-center gap-2">
                            <i class="fas fa-store text-orange-500"></i>
                            Totale: <span class="font-semibold text-orange-600"><?= $total ?></span> ristoranti
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="<?= BASE_URL ?>/superadmin/restaurant/add" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-xl font-medium hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-plus"></i>
                            <span>Nuovo Ristorante</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Enhanced Filters -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-6 mb-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-filter text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Filtri di Ricerca</h2>
                        <p class="text-sm text-slate-500 mt-1">Trova e filtra i ristoranti</p>
                    </div>
                </div>
                
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end" x-data="{ hasFilters: <?= $search || $status !== '' ? 'true' : 'false' ?> }">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-search text-slate-400 mr-1"></i>
                            Cerca ristorante
                        </label>
                        <input type="text" 
                               name="search" 
                               value="<?= htmlspecialchars($search) ?>" 
                               placeholder="Nome, slug o email..."
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 bg-white/50 backdrop-blur-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-toggle-on text-slate-400 mr-1"></i>
                            Stato
                        </label>
                        <select name="status" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 bg-white/50 backdrop-blur-sm">
                            <option value="">Tutti gli stati</option>
                            <option value="1" <?= $status === '1' ? 'selected' : '' ?>>Attivi</option>
                            <option value="0" <?= $status === '0' ? 'selected' : '' ?>>Inattivi</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-xl font-medium hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-search"></i>
                            <span class="hidden sm:inline">Cerca</span>
                        </button>
                        <a href="<?= BASE_URL ?>/superadmin/restaurants" 
                           x-show="hasFilters"
                           x-transition
                           class="inline-flex items-center justify-center px-4 py-3 border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 transition-all duration-200"
                           title="Reset filtri">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Enhanced Success/Error Messages -->
            <?php if (isset($_GET['success'])): ?>
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 mb-6 shadow-sm" 
                 x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-green-900 mb-1">Operazione Completata</h3>
                            <p class="text-green-700">
                                <?php if ($_GET['success'] === 'created'): ?>
                                    Ristorante creato con successo! Ora puoi iniziare a configurarlo.
                                <?php elseif ($_GET['success'] === 'updated'): ?>
                                    Ristorante aggiornato con successo! Le modifiche sono ora attive.
                                <?php elseif ($_GET['success'] === 'deleted'): ?>
                                    Ristorante eliminato con successo dal sistema.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-green-400 hover:text-green-600 transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
            <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-2xl p-6 mb-6 shadow-sm"
                 x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-red-900 mb-1">Errore Rilevato</h3>
                            <p class="text-red-700">
                                <?php if ($_GET['error'] === 'not_found'): ?>
                                    Il ristorante richiesto non è stato trovato nel sistema.
                                <?php elseif ($_GET['error'] === 'delete_failed'): ?>
                                    Errore durante l'eliminazione del ristorante. Riprova più tardi.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <!-- Enhanced Restaurants Table -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 overflow-hidden">
                <?php if (empty($restaurants)): ?>
                <div class="text-center py-20">
                    <div class="w-24 h-24 bg-gradient-to-br from-orange-100 to-red-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-store text-4xl text-orange-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">Nessun ristorante trovato</h3>
                    <p class="text-slate-500 mb-8 max-w-md mx-auto">
                        <?= $search || $status !== '' ? 'Modifica i filtri di ricerca per trovare altri ristoranti.' : 'Inizia creando il tuo primo ristorante nel sistema!' ?>
                    </p>
                    <?php if (!$search && $status === ''): ?>
                    <a href="<?= BASE_URL ?>/superadmin/restaurant/add" 
                       class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-xl font-medium hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-plus text-lg"></i>
                        <span>Crea Primo Ristorante</span>
                    </a>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <!-- Table Header -->
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                            <i class="fas fa-table text-slate-500"></i>
                            Lista Ristoranti
                        </h2>
                        <div class="text-sm text-slate-500">
                            <?= count($restaurants) ?> di <?= $total ?> risultati
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Ristorante</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider hidden lg:table-cell">Contatto</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider">Admin</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider">Piatti</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider">Stato</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider hidden lg:table-cell">Creato</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/50 backdrop-blur-sm divide-y divide-slate-100">
                            <?php foreach ($restaurants as $restaurant): ?>
                            <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                                            <i class="fas fa-utensils text-white"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-semibold text-slate-900 truncate text-base">
                                                <?= htmlspecialchars($restaurant['name']) ?>
                                            </div>
                                            <div class="text-sm text-slate-500 flex items-center gap-2 mt-1">
                                                <i class="fas fa-link text-xs"></i>
                                                <span class="font-mono"><?= $restaurant['slug'] ?></span>
                                            </div>
                                            <!-- Mobile contact info -->
                                            <div class="mt-2 lg:hidden space-y-1">
                                                <?php if ($restaurant['email']): ?>
                                                <div class="text-xs text-slate-500 flex items-center gap-1">
                                                    <i class="fas fa-envelope text-xs"></i>
                                                    <?= htmlspecialchars($restaurant['email']) ?>
                                                </div>
                                                <?php endif; ?>
                                                <?php if ($restaurant['phone']): ?>
                                                <div class="text-xs text-slate-500 flex items-center gap-1">
                                                    <i class="fas fa-phone text-xs"></i>
                                                    <?= htmlspecialchars($restaurant['phone']) ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 hidden lg:table-cell">
                                    <div class="space-y-1">
                                        <div class="text-sm font-medium text-slate-900 flex items-center gap-2">
                                            <i class="fas fa-envelope text-slate-400 text-xs"></i>
                                            <?= htmlspecialchars($restaurant['email'] ?? 'Non specificata') ?>
                                        </div>
                                        <div class="text-sm text-slate-500 flex items-center gap-2">
                                            <i class="fas fa-phone text-slate-400 text-xs"></i>
                                            <?= htmlspecialchars($restaurant['phone'] ?? 'Non specificato') ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="inline-flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 rounded-xl text-sm font-semibold shadow-sm">
                                        <i class="fas fa-users text-xs"></i>
                                        <span><?= $restaurant['admin_count'] ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="inline-flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 rounded-xl text-sm font-semibold shadow-sm">
                                        <i class="fas fa-utensils text-xs"></i>
                                        <span><?= $restaurant['menu_items_count'] ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <?php if ($restaurant['is_active']): ?>
                                    <div class="inline-flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 rounded-xl font-semibold shadow-sm">
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm">Attivo</span>
                                    </div>
                                    <?php else: ?>
                                    <div class="inline-flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-red-100 to-pink-100 text-red-800 rounded-xl font-semibold shadow-sm">
                                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                        <span class="text-sm">Inattivo</span>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 hidden lg:table-cell">
                                    <div class="text-sm text-slate-600 flex items-center gap-2">
                                        <i class="fas fa-calendar text-slate-400 text-xs"></i>
                                        <span class="font-medium"><?= date('d/m/Y', strtotime($restaurant['created_at'])) ?></span>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        <?= date('H:i', strtotime($restaurant['created_at'])) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?= BASE_URL ?>/restaurant/<?= $restaurant['slug'] ?>" 
                                           class="w-9 h-9 bg-blue-100 hover:bg-blue-200 text-blue-600 hover:text-blue-700 rounded-lg flex items-center justify-center transition-all duration-200 shadow-sm hover:shadow-md group"
                                           target="_blank"
                                           title="Visualizza menu pubblico">
                                            <i class="fas fa-external-link-alt text-sm group-hover:scale-110 transition-transform duration-200"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/superadmin/restaurant/edit/<?= $restaurant['id'] ?>" 
                                           class="w-9 h-9 bg-green-100 hover:bg-green-200 text-green-600 hover:text-green-700 rounded-lg flex items-center justify-center transition-all duration-200 shadow-sm hover:shadow-md group"
                                           title="Modifica ristorante">
                                            <i class="fas fa-edit text-sm group-hover:scale-110 transition-transform duration-200"></i>
                                        </a>
                                        <button onclick="confirmDelete(<?= $restaurant['id'] ?>, '<?= addslashes($restaurant['name']) ?>')" 
                                                class="w-9 h-9 bg-red-100 hover:bg-red-200 text-red-600 hover:text-red-700 rounded-lg flex items-center justify-center transition-all duration-200 shadow-sm hover:shadow-md group"
                                                title="Elimina ristorante">
                                            <i class="fas fa-trash text-sm group-hover:scale-110 transition-transform duration-200"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Enhanced Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="px-6 py-4 border-t border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="text-sm text-slate-600 flex items-center gap-2">
                            <i class="fas fa-info-circle text-slate-400"></i>
                            <span>Pagina <strong class="text-slate-900"><?= $page ?></strong> di <strong class="text-slate-900"><?= $total_pages ?></strong></span>
                            <span class="text-slate-400">•</span>
                            <span><strong class="text-slate-900"><?= $total ?></strong> ristoranti totali</span>
                        </div>
                        <nav class="flex items-center gap-1">
                            <?php if ($page > 1): ?>
                            <a href="<?= BASE_URL ?>/superadmin/restaurants?page=<?= $page - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status !== '' ? '&status=' . $status : '' ?>" 
                               class="inline-flex items-center justify-center w-10 h-10 border border-slate-300 text-slate-600 rounded-xl hover:bg-white hover:border-slate-400 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-chevron-left text-sm"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $page + 2);
                            
                            for ($i = $start_page; $i <= $end_page; $i++):
                            ?>
                            <a href="<?= BASE_URL ?>/superadmin/restaurants?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status !== '' ? '&status=' . $status : '' ?>" 
                               class="inline-flex items-center justify-center w-10 h-10 <?= $i === $page ? 'bg-gradient-to-r from-orange-600 to-red-600 text-white shadow-lg' : 'border border-slate-300 text-slate-700 hover:bg-white hover:border-slate-400 hover:shadow-md' ?> rounded-xl font-medium transition-all duration-200">
                                <?= $i ?>
                            </a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                            <a href="<?= BASE_URL ?>/superadmin/restaurants?page=<?= $page + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status !== '' ? '&status=' . $status : '' ?>" 
                               class="inline-flex items-center justify-center w-10 h-10 border border-slate-300 text-slate-600 rounded-xl hover:bg-white hover:border-slate-400 transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-chevron-right text-sm"></i>
                            </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

    <!-- Enhanced Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeDeleteModal()"
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         x-cloak>
        <div @click.stop 
             class="bg-white rounded-2xl max-w-lg w-full shadow-2xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <!-- Modal Header -->
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Conferma Eliminazione</h3>
                        <p class="text-slate-500 text-sm mt-1">Questa azione non può essere annullata</p>
                    </div>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl p-4 mb-6">
                    <p class="text-slate-700 text-center">
                        Sei sicuro di voler eliminare il ristorante
                        <br>
                        <strong class="text-red-700 text-lg" x-text="deleteTarget?.name"></strong>?
                    </p>
                    <div class="mt-4 flex items-center justify-center gap-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle"></i>
                        <span class="font-medium">Tutti i dati associati verranno persi definitivamente!</span>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm text-slate-600 mb-6">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-trash text-slate-400"></i>
                        <span>Tutti i menu e le categorie del ristorante</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-users text-slate-400"></i>
                        <span>Tutti gli admin associati al ristorante</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-images text-slate-400"></i>
                        <span>Tutte le immagini caricate per questo ristorante</span>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 p-6 border-t border-slate-100 bg-slate-50 rounded-b-2xl">
                <button @click="closeDeleteModal()" 
                        class="inline-flex items-center gap-2 px-6 py-3 border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-100 transition-all duration-200">
                    <i class="fas fa-times"></i>
                    <span>Annulla</span>
                </button>
                <button @click="executeDelete()" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-xl font-medium hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-trash"></i>
                    <span>Elimina Definitivamente</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
function restaurantsManager() {
    return {
        // State
        showDeleteModal: false,
        deleteTarget: null,
        
        // Methods
        confirmDelete(restaurantId, restaurantName) {
            this.deleteTarget = {
                id: restaurantId,
                name: restaurantName
            };
            this.showDeleteModal = true;
        },
        
        closeDeleteModal() {
            this.showDeleteModal = false;
            this.deleteTarget = null;
        },
        
        executeDelete() {
            if (this.deleteTarget) {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `<?= BASE_URL ?>/superadmin/restaurant/delete/${this.deleteTarget.id}`;
                
                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = '<?= $_SESSION['csrf_token'] ?? '' ?>';
                form.appendChild(csrfInput);
                
                // Submit form
                document.body.appendChild(form);
                form.submit();
            }
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
            
            // Animate in
            setTimeout(() => notification.style.transform = 'translateX(0)', 10);
            
            // Remove after delay
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    }
}

// Make confirmDelete available globally for onclick handlers
window.confirmDelete = function(restaurantId, restaurantName) {
    // Get the Alpine component instance
    const component = document.querySelector('[x-data="restaurantsManager()"]').__x.$data;
    component.confirmDelete(restaurantId, restaurantName);
};

// Handle Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const component = document.querySelector('[x-data="restaurantsManager()"]')?.__x?.$data;
        if (component?.showDeleteModal) {
            component.closeDeleteModal();
        }
    }
});
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>