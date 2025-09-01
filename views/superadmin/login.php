<?php 
$content = ob_start(); 
?>

<div class="min-h-screen bg-gradient-to-br from-red-600 to-red-800 flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-crown text-2xl text-red-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">MenooElo</h1>
            <p class="text-red-100">Super Admin Panel</p>
        </div>
        
        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <?php if (isset($error)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6 flex items-start gap-3">
                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Username / Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="username" 
                               required 
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                               placeholder="Super Admin username"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-colors">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" 
                               name="password" 
                               required 
                               placeholder="Super Admin password"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-colors">
                    </div>
                </div>
                
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 focus:ring-4 focus:ring-red-500 focus:ring-opacity-50 flex items-center justify-center gap-2">
                    <i class="fas fa-crown"></i>
                    <span>Accedi come Super Admin</span>
                </button>
            </form>
            
            <div class="text-center mt-6">
                <p class="text-sm text-gray-500">
                    Accesso riservato esclusivamente ai Super Admin
                </p>
            </div>
        </div>
        
        <!-- Footer Links -->
        <div class="text-center mt-6">
            <a href="<?= BASE_URL ?>" 
               class="text-red-100 hover:text-white text-sm transition-colors flex items-center justify-center gap-1">
                <i class="fas fa-arrow-left"></i>
                <span>Torna alla Home</span>
            </a>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/base.php'; 
?>