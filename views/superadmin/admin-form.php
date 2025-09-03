<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 overflow-x-hidden" x-data="adminFormManager()">
    <?php include 'views/superadmin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64 min-w-0">
        <!-- Modern Header -->
        <div class="bg-white/80 backdrop-blur-xl border-b border-white/20 shadow-sm sticky top-0 z-30">
            <div class="px-6 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">
                            <?= isset($admin) ? 'Modifica Admin' : 'Nuovo Admin' ?>
                        </h1>
                        <p class="text-slate-600 mt-2 flex items-center gap-2">
                            <i class="fas fa-user-shield text-indigo-500"></i>
                            <?= isset($admin) ? 'Aggiorna le informazioni dell\'admin' : 'Crea un nuovo account admin per un ristorante' ?>
                        </p>
                    </div>
                    <div>
                        <a href="<?= BASE_URL ?>/superadmin/admins" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-arrow-left"></i>
                            <span>Torna alla Lista</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <div class="max-w-5xl mx-auto">
                
                <!-- Enhanced Error Message -->
                <?php if (isset($error)): ?>
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
                                <h3 class="text-lg font-semibold text-red-900 mb-1">Errore</h3>
                                <p class="text-red-700"><?= htmlspecialchars($error) ?></p>
                            </div>
                        </div>
                        <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php endif; ?>

                <form method="POST" @submit="validateForm" class="space-y-8">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <!-- Admin Information -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-xl">
                                <i class="fas fa-user text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">Informazioni Admin</h2>
                                <p class="text-slate-500 mt-1">Dettagli personali dell'amministratore</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-user text-blue-500 mr-2"></i>
                                    Nome Completo
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="full_name" 
                                       required 
                                       value="<?= htmlspecialchars($admin['full_name'] ?? '') ?>"
                                       placeholder="Mario Rossi"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    Nome e cognome dell'admin
                                </p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-store text-orange-500 mr-2"></i>
                                    Ristorante
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="restaurant_id" 
                                        required
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                    <option value="">Seleziona un ristorante...</option>
                                    <?php foreach ($restaurants as $restaurant): ?>
                                    <option value="<?= $restaurant['id'] ?>" 
                                            <?= (isset($admin) && $admin['restaurant_id'] == $restaurant['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($restaurant['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    Ristorante di appartenenza
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-500 rounded-2xl flex items-center justify-center shadow-xl">
                                <i class="fas fa-key text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">Credenziali di Accesso</h2>
                                <p class="text-slate-500 mt-1">Informazioni per l'accesso al sistema</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-at text-purple-500 mr-2"></i>
                                    Username
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="username" 
                                       required 
                                       value="<?= htmlspecialchars($admin['username'] ?? '') ?>"
                                       placeholder="mario_rossi"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    Solo lettere, numeri e underscore
                                </p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-envelope text-cyan-500 mr-2"></i>
                                    Email
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       required 
                                       value="<?= htmlspecialchars($admin['email'] ?? '') ?>"
                                       placeholder="mario@ristorante.com"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    Email per notifiche e recupero password
                                </p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-lock text-red-500 mr-2"></i>
                                    Password <?= isset($admin) ? '(lascia vuoto per mantenerla)' : '' ?>
                                    <?= !isset($admin) ? '<span class="text-red-500">*</span>' : '' ?>
                                </label>
                                <input type="password" 
                                       name="password" 
                                       <?= !isset($admin) ? 'required' : '' ?>
                                       placeholder="Password sicura"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    <?= isset($admin) ? 'Lascia vuoto per mantenere la password attuale' : 'Password di almeno 8 caratteri' ?>
                                </p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    <i class="fas fa-user-tag text-amber-500 mr-2"></i>
                                    Ruolo
                                </label>
                                <select name="role" 
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/70 backdrop-blur-sm">
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
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>
                                    Livello di accesso alle funzionalità
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Current Info (only for edit) -->
                    <?php if (isset($admin)): ?>
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-blue-900">Informazioni Attuali</h2>
                                <p class="text-blue-600 mt-1">Dati esistenti dell'admin</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="bg-white/50 rounded-xl p-4">
                                <span class="text-blue-600 font-semibold text-sm flex items-center gap-2 mb-2">
                                    <i class="fas fa-store"></i>
                                    Ristorante
                                </span>
                                <div class="text-blue-900 font-medium"><?= htmlspecialchars($admin['restaurant_name']) ?></div>
                            </div>
                            <div class="bg-white/50 rounded-xl p-4">
                                <span class="text-blue-600 font-semibold text-sm flex items-center gap-2 mb-2">
                                    <i class="fas fa-calendar-plus"></i>
                                    Creato il
                                </span>
                                <div class="text-blue-900 font-medium"><?= date('d/m/Y H:i', strtotime($admin['created_at'])) ?></div>
                            </div>
                            <div class="bg-white/50 rounded-xl p-4">
                                <span class="text-blue-600 font-semibold text-sm flex items-center gap-2 mb-2">
                                    <i class="fas fa-calendar-check"></i>
                                    Ultimo aggiornamento
                                </span>
                                <div class="text-blue-900 font-medium"><?= date('d/m/Y H:i', strtotime($admin['updated_at'])) ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Submit Buttons -->
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-white/20 p-8">
                        <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                            <div class="text-center lg:text-left">
                                <h4 class="text-lg font-semibold text-slate-900">Salva Modifiche</h4>
                                <p class="text-sm text-slate-500 mt-1">Le modifiche verranno applicate immediatamente</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                                <button type="submit" 
                                        class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                    <i class="fas fa-save text-lg"></i>
                                    <span><?= isset($admin) ? 'Aggiorna Admin' : 'Crea Admin' ?></span>
                                </button>
                                <a href="<?= BASE_URL ?>/superadmin/admins" 
                                   class="inline-flex items-center justify-center gap-3 px-8 py-4 border border-slate-300 text-slate-700 rounded-xl font-semibold hover:bg-slate-50 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-times text-lg"></i>
                                    <span>Annulla</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
function adminFormManager() {
    return {
        validateForm(event) {
            const form = event.target;
            const requiredFields = form.querySelectorAll('[required]');
            
            for (let field of requiredFields) {
                if (!field.value.trim()) {
                    field.focus();
                    this.showNotification(`Il campo "${field.previousElementSibling.textContent.replace('*', '').trim()}" è obbligatorio`, 'error');
                    event.preventDefault();
                    return false;
                }
            }
            
            // Validate username format
            const username = form.querySelector('[name="username"]');
            if (username && username.value && !/^[a-zA-Z0-9_]+$/.test(username.value)) {
                username.focus();
                this.showNotification('Lo username può contenere solo lettere, numeri e underscore', 'error');
                event.preventDefault();
                return false;
            }
            
            // Validate email format
            const email = form.querySelector('[name="email"]');
            if (email && email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                email.focus();
                this.showNotification('Inserisci un indirizzo email valido', 'error');
                event.preventDefault();
                return false;
            }
            
            return true;
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