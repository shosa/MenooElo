<!-- Mobile menu button -->
<button id="mobile-menu-btn" class="lg:hidden fixed top-4 left-4 z-50 bg-red-800 text-white p-3 rounded-lg shadow-lg hover:bg-red-700 transition-colors">
    <i class="fas fa-bars text-lg"></i>
</button>

<!-- Mobile sidebar overlay -->
<div id="mobile-sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" onclick="closeMobileSidebar()"></div>

<!-- Sidebar -->
<aside id="admin-sidebar" class="w-64 bg-red-800 shadow-xl flex-shrink-0 fixed left-0 top-0 h-screen transform transition-transform duration-300 z-50 lg:transform-none lg:relative lg:z-auto -translate-x-full lg:translate-x-0 flex flex-col">
    <!-- Brand -->
    <div class="p-6 border-b border-red-700">
        <div class="text-center">
            <h1 class="text-white text-xl font-bold">MenooElo</h1>
            <p class="text-red-200 text-sm mt-1">Super Admin Panel</p>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="p-4 space-y-2 flex-1">
        <!-- Dashboard Section -->
        <div class="text-red-300 font-semibold text-xs uppercase tracking-wider mt-6 mb-2 px-3">Dashboard</div>
        <a href="<?= BASE_URL ?>/superadmin" class="flex items-center gap-3 px-3 py-2 text-red-100 hover:text-white hover:bg-red-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= $_SERVER['REQUEST_URI'] == '/superadmin' ? 'bg-yellow-600 text-white' : '' ?>" data-path="/superadmin">
            <i class="fas fa-tachometer-alt w-5 h-5"></i>
            <span>Dashboard</span>
        </a>
        <a href="<?= BASE_URL ?>/superadmin/analytics" class="flex items-center gap-3 px-3 py-2 text-red-100 hover:text-white hover:bg-red-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/analytics') !== false ? 'bg-yellow-600 text-white' : '' ?>" data-path="/superadmin/analytics">
            <i class="fas fa-chart-line w-5 h-5"></i>
            <span>Analytics</span>
        </a>
        
        <!-- Restaurants Section -->
        <div class="text-red-300 font-semibold text-xs uppercase tracking-wider mt-6 mb-2 px-3">Ristoranti</div>
        <a href="<?= BASE_URL ?>/superadmin/restaurants" class="flex items-center gap-3 px-3 py-2 text-red-100 hover:text-white hover:bg-red-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/restaurants') !== false ? 'bg-yellow-600 text-white' : '' ?>" data-path="/superadmin/restaurants">
            <i class="fas fa-store w-5 h-5"></i>
            <span>Ristoranti</span>
        </a>
        <a href="<?= BASE_URL ?>/superadmin/admins" class="flex items-center gap-3 px-3 py-2 text-red-100 hover:text-white hover:bg-red-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/admins') !== false ? 'bg-yellow-600 text-white' : '' ?>" data-path="/superadmin/admins">
            <i class="fas fa-users w-5 h-5"></i>
            <span>Admin Ristoranti</span>
        </a>
        
        <!-- System Section -->
        <div class="text-red-300 font-semibold text-xs uppercase tracking-wider mt-6 mb-2 px-3">Sistema</div>
        <a href="<?= BASE_URL ?>/superadmin/database" class="flex items-center gap-3 px-3 py-2 text-red-100 hover:text-white hover:bg-red-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/database') !== false ? 'bg-yellow-600 text-white' : '' ?>" data-path="/superadmin/database">
            <i class="fas fa-database w-5 h-5"></i>
            <span>Database</span>
        </a>
        <a href="<?= BASE_URL ?>/superadmin/logs" class="flex items-center gap-3 px-3 py-2 text-red-100 hover:text-white hover:bg-red-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/logs') !== false ? 'bg-yellow-600 text-white' : '' ?>" data-path="/superadmin/logs">
            <i class="fas fa-file-alt w-5 h-5"></i>
            <span>Log Attività</span>
        </a>
        <a href="<?= BASE_URL ?>/superadmin/settings" class="flex items-center gap-3 px-3 py-2 text-red-100 hover:text-white hover:bg-red-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= strpos($_SERVER['REQUEST_URI'], '/superadmin/settings') !== false ? 'bg-yellow-600 text-white' : '' ?>" data-path="/superadmin/settings">
            <i class="fas fa-cog w-5 h-5"></i>
            <span>Impostazioni</span>
        </a>
    </nav>
    
    <!-- User Info & Logout -->
    <div class="p-4 border-t border-red-700">
        <div class="mb-4">
            <div class="text-red-300 text-xs mb-1">Connesso come:</div>
            <div class="text-white font-semibold">Super Admin</div>
            <div class="text-red-200 text-xs">Amministratore di Sistema</div>
        </div>
        
        <!-- Quick Actions -->
        <div class="space-y-2">
            <a href="<?= BASE_URL ?>/superadmin/logout" 
               class="flex items-center gap-2 text-red-300 hover:text-white text-sm py-1 transition-colors">
                <i class="fas fa-sign-out-alt w-4"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</aside>

<script>
// Mobile sidebar functionality
function openMobileSidebar() {
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('mobile-sidebar-overlay');
    
    sidebar.classList.remove('-translate-x-full');
    overlay.classList.remove('hidden');
}

function closeMobileSidebar() {
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('mobile-sidebar-overlay');
    
    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
}

// Initialize sidebar functionality
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu button
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', openMobileSidebar);
    }
    
    // Close mobile sidebar when clicking on menu items
    const menuItems = document.querySelectorAll('a[data-path]');
    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                closeMobileSidebar();
            }
        });
    });
    
    // Close mobile sidebar on window resize to desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            closeMobileSidebar();
        }
    });
});
</script>