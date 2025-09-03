<?php 
$content = ob_start(); 
?>

<div class="flex min-h-screen bg-gray-100">
    <?php include 'views/admin/_sidebar.php'; ?>
    
    <div class="flex-1 lg:ml-64">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestione Utenti</h1>
                    <p class="text-gray-600 mt-1">Gestisci il team del tuo ristorante</p>
                </div>
                <div>
                    <button onclick="openUserModal()" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-user-plus"></i>
                        <span>Aggiungi Utente</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="p-6">
            <!-- Messages -->
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

            <!-- Users Grid -->
            <?php if (!empty($users)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <?php foreach ($users as $user): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                            <?= strtoupper(substr($user['full_name'], 0, 2)) ?>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($user['full_name']) ?></h3>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($user['email']) ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            <?php if ($user['role'] === 'owner'): ?>
                                bg-blue-100 text-blue-800
                            <?php elseif ($user['role'] === 'manager'): ?>
                                bg-purple-100 text-purple-800
                            <?php else: ?>
                                bg-gray-100 text-gray-800
                            <?php endif; ?>">
                            <i class="fas <?= $user['role'] === 'owner' ? 'fa-crown' : ($user['role'] === 'manager' ? 'fa-user-tie' : 'fa-user') ?> mr-1"></i>
                            <?= ucfirst($user['role'] === 'owner' ? 'Proprietario' : ($user['role'] === 'manager' ? 'Manager' : 'Staff')) ?>
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            <?= $user['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <i class="fas fa-circle mr-1"></i>
                            <?= $user['is_active'] ? 'Attivo' : 'Inattivo' ?>
                        </span>
                    </div>
                    
                    <div class="text-sm text-gray-600 mb-4">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-user w-4"></i>
                            <span>Username: <?= htmlspecialchars($user['username']) ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-alt w-4"></i>
                            <span>Dal: <?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <button onclick="editUser(<?= $user['id'] ?>)" 
                                class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 text-blue-600 border border-blue-600 rounded-lg font-medium hover:bg-blue-600 hover:text-white transition-colors duration-200">
                            <i class="fas fa-edit"></i>
                            <span>Modifica</span>
                        </button>
                        <button onclick="deleteUser(<?= $user['id'] ?>, '<?= addslashes($user['full_name']) ?>')" 
                                class="px-3 py-2 text-red-600 border border-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-colors duration-200"
                                title="Rimuovi utente"
                                <?= $user['id'] == $current_user_id ? 'disabled' : '' ?>>
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-12 mb-8">
                <div class="mb-6">
                    <i class="fas fa-users text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Nessun utente presente</h3>
                <p class="text-gray-500 mb-6">Aggiungi il primo membro del tuo team!</p>
                <button onclick="openUserModal()" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-user-plus"></i>
                    <span>Aggiungi Primo Utente</span>
                </button>
            </div>
            <?php endif; ?>
            
            <!-- Permissions Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-blue-600"></i>
                    Ruoli e Permessi
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-crown text-blue-600"></i>
                            <h3 class="font-semibold text-blue-800">Proprietario</h3>
                        </div>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Accesso completo a tutte le funzioni</li>
                            <li>• Gestione utenti e permessi</li>
                            <li>• Modifica impostazioni ristorante</li>
                            <li>• Visualizzazione statistiche avanzate</li>
                        </ul>
                    </div>
                    
                    <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-user-tie text-purple-600"></i>
                            <h3 class="font-semibold text-purple-800">Manager</h3>
                        </div>
                        <ul class="text-sm text-purple-700 space-y-1">
                            <li>• Gestione menu e categorie</li>
                            <li>• Visualizzazione statistiche</li>
                            <li>• Modifica orari di apertura</li>
                            <li>• Gestione QR Code</li>
                        </ul>
                    </div>
                    
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-user text-gray-600"></i>
                            <h3 class="font-semibold text-gray-800">Staff</h3>
                        </div>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• Modifica disponibilità piatti</li>
                            <li>• Visualizzazione dashboard base</li>
                            <li>• Accesso limitato alle impostazioni</li>
                            <li>• Supporto clienti base</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Modal -->
<div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Aggiungi Nuovo Utente</h3>
            <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="action" value="add_user" id="modalAction">
            <input type="hidden" name="user_id" id="modalUserId">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nome Completo <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="full_name" 
                       required 
                       id="modalFullName"
                       placeholder="Es. Mario Rossi"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       required 
                       id="modalEmail"
                       placeholder="mario@ristorante.it"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Username <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="username" 
                       required 
                       id="modalUsername"
                       placeholder="mario_rossi"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors">
            </div>
            
            <div id="passwordField">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                       name="password" 
                       required 
                       id="modalPassword"
                       placeholder="Password sicura"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Ruolo <span class="text-red-500">*</span>
                </label>
                <select name="role" 
                        required 
                        id="modalRole"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors">
                    <option value="">Seleziona ruolo</option>
                    <option value="owner">Proprietario</option>
                    <option value="manager">Manager</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Stato
                </label>
                <select name="status" 
                        id="modalStatus"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors">
                    <option value="active">Attivo</option>
                    <option value="suspended">Sospeso</option>
                    <option value="inactive">Inattivo</option>
                </select>
            </div>
            
            <div class="flex items-center gap-3 pt-4">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200"
                        id="modalSubmitBtn">
                    Aggiungi Utente
                </button>
                <button type="button" 
                        onclick="closeUserModal()" 
                        class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Annulla
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Rimuovi Utente</h3>
                <p class="text-sm text-gray-500">Questa azione non può essere annullata</p>
            </div>
        </div>
        
        <p class="text-gray-700 mb-6">
            Sei sicuro di voler rimuovere <strong id="userToDelete"></strong> dal team? 
            L'utente perderà immediatamente l'accesso al sistema.
        </p>
        
        <div class="flex items-center gap-3 justify-end">
            <button onclick="closeDeleteModal()" 
                    class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Annulla
            </button>
            <form method="POST" id="deleteForm" class="inline">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="action" value="delete_user">
                <input type="hidden" name="user_id" id="userIdToDelete">
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Rimuovi
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function openUserModal(isEdit = false, userData = {}) {
    const modal = document.getElementById('userModal');
    const title = document.getElementById('modalTitle');
    const action = document.getElementById('modalAction');
    const submitBtn = document.getElementById('modalSubmitBtn');
    const passwordField = document.getElementById('passwordField');
    
    if (isEdit) {
        title.textContent = 'Modifica Utente';
        action.value = 'edit_user';
        submitBtn.textContent = 'Aggiorna Utente';
        passwordField.style.display = 'none';
        
        // Fill form with user data
        document.getElementById('modalUserId').value = userData.id || '';
        document.getElementById('modalFullName').value = userData.name || '';
        document.getElementById('modalEmail').value = userData.email || '';
        document.getElementById('modalUsername').value = userData.username || '';
        document.getElementById('modalRole').value = userData.role || '';
        document.getElementById('modalStatus').value = userData.status || 'active';
    } else {
        title.textContent = 'Aggiungi Nuovo Utente';
        action.value = 'add_user';
        submitBtn.textContent = 'Aggiungi Utente';
        passwordField.style.display = 'block';
        
        // Reset form
        document.getElementById('modalUserId').value = '';
        document.getElementById('modalFullName').value = '';
        document.getElementById('modalEmail').value = '';
        document.getElementById('modalUsername').value = '';
        document.getElementById('modalPassword').value = '';
        document.getElementById('modalRole').value = '';
        document.getElementById('modalStatus').value = 'active';
    }
    
    modal.classList.remove('hidden');
}

function closeUserModal() {
    document.getElementById('userModal').classList.add('hidden');
}

function editUser(userId) {
    // Get user data from PHP and pass to modal
    const users = <?= json_encode($users) ?>;
    const userData = users.find(u => u.id == userId);
    
    if (userData) {
        openUserModal(true, {
            id: userData.id,
            name: userData.full_name,
            email: userData.email,
            username: userData.username,
            role: userData.role,
            status: userData.is_active ? 'active' : 'inactive'
        });
    }
}

function deleteUser(id, name) {
    const currentUserId = <?= $current_user_id ?>;
    if (id == currentUserId) {
        alert('Non puoi rimuovere il tuo account!');
        return;
    }
    
    document.getElementById('userToDelete').textContent = name;
    document.getElementById('userIdToDelete').value = id;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('userModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUserModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>