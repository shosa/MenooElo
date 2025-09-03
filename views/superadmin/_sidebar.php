<!-- Modern Sidebar with Alpine.js -->
<div x-data="{ mobileOpen: false, activeDropdown: null }" class="relative">
    <!-- Mobile menu button -->
    <button @click="mobileOpen = true" 
            class="lg:hidden fixed top-4 left-4 z-50 bg-gray-900 text-white p-3 rounded-xl shadow-xl hover:bg-gray-800 transition-all duration-200 backdrop-blur-sm border border-gray-700">
        <i class="fas fa-bars text-lg"></i>
    </button>

    <!-- Mobile sidebar overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden" 
         x-cloak></div>

    <!-- Sidebar -->
    <aside x-show="mobileOpen || !$store.mobile.isMobile" 
           x-transition:enter="transform transition ease-in-out duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transform transition ease-in-out duration-300"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="w-64 bg-gradient-to-b from-slate-900 to-slate-800 shadow-2xl flex-shrink-0 fixed left-0 top-0 h-screen z-50 lg:z-40 flex flex-col border-r border-slate-700/50 backdrop-blur-xl">
        
        <!-- Brand Header -->
        <div class="p-6 border-b border-slate-700/50 bg-gradient-to-r from-blue-600/20 to-purple-600/20 backdrop-blur-sm">
            <div class="text-center">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                    <i class="fas fa-crown text-white text-xl"></i>
                </div>
                <h1 class="text-white text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">MenooElo</h1>
                <p class="text-slate-300 text-sm mt-1 font-medium">Super Admin Panel</p>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="p-4 space-y-2 flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-600 scrollbar-track-transparent">
            <!-- Dashboard Section -->
            <div class="text-slate-400 font-semibold text-xs uppercase tracking-wider mt-2 mb-3 px-3 flex items-center">
                <i class="fas fa-grip-horizontal mr-2"></i>
                Dashboard
            </div>
            
            <a href="<?= BASE_URL ?>/superadmin" 
               @click="mobileOpen = false"
               class="group flex items-center gap-3 px-3 py-3 text-slate-300 hover:text-white hover:bg-slate-700/50 rounded-xl transition-all duration-200 text-sm font-medium relative overflow-hidden <?= $_SERVER['REQUEST_URI'] == '/superadmin' ? 'bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-white border border-blue-500/30' : '' ?>">
                <div class="w-8 h-8 rounded-lg bg-slate-700/50 group-hover:bg-blue-500/20 flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-tachometer-alt text-sm"></i>
                </div>
                <span>Dashboard</span>
                <?= $_SERVER['REQUEST_URI'] == '/superadmin' ? '<div class="absolute right-2 w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>' : '' ?>
            </a>
            
            <a href="<?= BASE_URL ?>/superadmin/analytics" 
               @click="mobileOpen = false"
               class="group flex items-center gap-3 px-3 py-3 text-slate-300 hover:text-white hover:bg-slate-700/50 rounded-xl transition-all duration-200 text-sm font-medium relative overflow-hidden <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/analytics') !== false ? 'bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-white border border-blue-500/30' : '' ?>">
                <div class="w-8 h-8 rounded-lg bg-slate-700/50 group-hover:bg-green-500/20 flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-chart-line text-sm"></i>
                </div>
                <span>Analytics</span>
                <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/analytics') !== false ? '<div class="absolute right-2 w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>' : '' ?>
            </a>
            
            <!-- Restaurants Section -->
            <div class="text-slate-400 font-semibold text-xs uppercase tracking-wider mt-6 mb-3 px-3 flex items-center">
                <i class="fas fa-store mr-2"></i>
                Ristoranti
            </div>
            
            <a href="<?= BASE_URL ?>/superadmin/restaurants" 
               @click="mobileOpen = false"
               class="group flex items-center gap-3 px-3 py-3 text-slate-300 hover:text-white hover:bg-slate-700/50 rounded-xl transition-all duration-200 text-sm font-medium relative overflow-hidden <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/restaurants') !== false ? 'bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-white border border-blue-500/30' : '' ?>">
                <div class="w-8 h-8 rounded-lg bg-slate-700/50 group-hover:bg-orange-500/20 flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-utensils text-sm"></i>
                </div>
                <span>Ristoranti</span>
                <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/restaurants') !== false ? '<div class="absolute right-2 w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>' : '' ?>
            </a>
            
            <a href="<?= BASE_URL ?>/superadmin/admins" 
               @click="mobileOpen = false"
               class="group flex items-center gap-3 px-3 py-3 text-slate-300 hover:text-white hover:bg-slate-700/50 rounded-xl transition-all duration-200 text-sm font-medium relative overflow-hidden <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/admins') !== false ? 'bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-white border border-blue-500/30' : '' ?>">
                <div class="w-8 h-8 rounded-lg bg-slate-700/50 group-hover:bg-purple-500/20 flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-users text-sm"></i>
                </div>
                <span>Admin Ristoranti</span>
                <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/admins') !== false ? '<div class="absolute right-2 w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>' : '' ?>
            </a>
            
            <!-- System Section -->
            <div class="text-slate-400 font-semibold text-xs uppercase tracking-wider mt-6 mb-3 px-3 flex items-center">
                <i class="fas fa-cogs mr-2"></i>
                Sistema
            </div>
            
            <a href="<?= BASE_URL ?>/superadmin/database" 
               @click="mobileOpen = false"
               class="group flex items-center gap-3 px-3 py-3 text-slate-300 hover:text-white hover:bg-slate-700/50 rounded-xl transition-all duration-200 text-sm font-medium relative overflow-hidden <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/database') !== false ? 'bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-white border border-blue-500/30' : '' ?>">
                <div class="w-8 h-8 rounded-lg bg-slate-700/50 group-hover:bg-indigo-500/20 flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-database text-sm"></i>
                </div>
                <span>Database</span>
                <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/database') !== false ? '<div class="absolute right-2 w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>' : '' ?>
            </a>
            
            <a href="<?= BASE_URL ?>/superadmin/file-manager" 
               @click="mobileOpen = false"
               class="group flex items-center gap-3 px-3 py-3 text-slate-300 hover:text-white hover:bg-slate-700/50 rounded-xl transition-all duration-200 text-sm font-medium relative overflow-hidden <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/file-manager') !== false ? 'bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-white border border-blue-500/30' : '' ?>">
                <div class="w-8 h-8 rounded-lg bg-slate-700/50 group-hover:bg-cyan-500/20 flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-folder-open text-sm"></i>
                </div>
                <span>Gestione File</span>
                <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/file-manager') !== false ? '<div class="absolute right-2 w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>' : '' ?>
            </a>
            
            <a href="<?= BASE_URL ?>/superadmin/logs" 
               @click="mobileOpen = false"
               class="group flex items-center gap-3 px-3 py-3 text-slate-300 hover:text-white hover:bg-slate-700/50 rounded-xl transition-all duration-200 text-sm font-medium relative overflow-hidden <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/logs') !== false ? 'bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-white border border-blue-500/30' : '' ?>">
                <div class="w-8 h-8 rounded-lg bg-slate-700/50 group-hover:bg-yellow-500/20 flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-file-alt text-sm"></i>
                </div>
                <span>Log Attività</span>
                <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/logs') !== false ? '<div class="absolute right-2 w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>' : '' ?>
            </a>
            
            <a href="<?= BASE_URL ?>/superadmin/settings" 
               @click="mobileOpen = false"
               class="group flex items-center gap-3 px-3 py-3 text-slate-300 hover:text-white hover:bg-slate-700/50 rounded-xl transition-all duration-200 text-sm font-medium relative overflow-hidden <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/settings') !== false ? 'bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-white border border-blue-500/30' : '' ?>">
                <div class="w-8 h-8 rounded-lg bg-slate-700/50 group-hover:bg-red-500/20 flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-cog text-sm"></i>
                </div>
                <span>Impostazioni</span>
                <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/settings') !== false ? '<div class="absolute right-2 w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>' : '' ?>
            </a>
        </nav>
        
        <!-- User Profile Section -->
        <div class="p-4 border-t border-slate-700/50 bg-gradient-to-r from-slate-800/50 to-slate-700/50 backdrop-blur-sm">
            <div class="mb-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-shield text-white text-sm"></i>
                    </div>
                    <div>
                        <div class="text-white font-semibold text-sm">Super Admin</div>
                        <div class="text-slate-400 text-xs">Amministratore di Sistema</div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="space-y-2">
                <a href="<?= BASE_URL ?>/superadmin/logout" 
                   class="group flex items-center gap-3 px-3 py-2 text-slate-400 hover:text-white hover:bg-slate-700/50 rounded-lg transition-all duration-200 text-sm">
                    <div class="w-6 h-6 rounded-md bg-slate-700/50 group-hover:bg-red-500/20 flex items-center justify-center transition-colors duration-200">
                        <i class="fas fa-sign-out-alt text-xs"></i>
                    </div>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </aside>
</div>

<style>
    [x-cloak] { display: none !important; }
    
    /* Custom scrollbar */
    .scrollbar-thin::-webkit-scrollbar {
        width: 4px;
    }
    
    .scrollbar-thumb-slate-600::-webkit-scrollbar-thumb {
        background-color: rgb(71 85 105 / 0.8);
        border-radius: 2px;
    }
    
    .scrollbar-track-transparent::-webkit-scrollbar-track {
        background: transparent;
    }
</style>

<script>
    // Alpine.js mobile detection store
    document.addEventListener('alpine:init', () => {
        Alpine.store('mobile', {
            isMobile: window.innerWidth < 1024,
            init() {
                window.addEventListener('resize', () => {
                    this.isMobile = window.innerWidth < 1024;
                });
            }
        });
        
        Alpine.store('mobile').init();
    });
</script>