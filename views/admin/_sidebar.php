<!-- Mobile menu button -->
<button id="mobile-menu-btn" class="lg:hidden fixed top-4 left-4 z-50 bg-slate-800 text-white p-3 rounded-lg shadow-lg hover:bg-slate-700 transition-colors">
    <i class="fas fa-bars text-lg"></i>
</button>

<!-- Mobile sidebar overlay -->
<div id="mobile-sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" onclick="closeMobileSidebar()"></div>

<!-- Sidebar -->
<aside id="admin-sidebar" class="w-64 bg-slate-800 shadow-xl flex-shrink-0 fixed left-0 top-0 h-screen transform transition-transform duration-300 z-50 lg:transform-none lg:relative lg:z-auto -translate-x-full lg:translate-x-0 flex flex-col">
    <!-- Brand -->
    <div class="p-6 border-b border-slate-700">
        <div class="text-center">
            <h1 class="text-white text-xl font-bold">MenooElo</h1>
            <p class="text-slate-400 text-sm mt-1">
                <?= htmlspecialchars($_SESSION['restaurant_slug'] ?? 'Admin Panel') ?>
            </p>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="p-4 space-y-2 flex-1">
        <!-- Dashboard Section -->
        <div class="text-slate-400 font-semibold text-xs uppercase tracking-wider mt-6 mb-2 px-3">Dashboard</div>
        <a href="<?= BASE_URL ?>/admin" class="flex items-center gap-3 px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= $_SERVER['REQUEST_URI'] == '/admin' ? 'bg-blue-600 text-white' : '' ?>" data-path="/admin">
            <i class="fas fa-tachometer-alt w-5 h-5"></i>
            <span>Dashboard</span>
        </a>
        
        <!-- Menu Section -->
        <div class="text-slate-400 font-semibold text-xs uppercase tracking-wider mt-6 mb-2 px-3">Menu</div>
        <a href="<?= BASE_URL ?>/admin/categories" class="flex items-center gap-3 px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= strpos($_SERVER['REQUEST_URI'], '/admin/categories') !== false ? 'bg-blue-600 text-white' : '' ?>" data-path="/admin/categories">
            <i class="fas fa-list w-5 h-5"></i>
            <span>Categorie</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/menu" class="flex items-center gap-3 px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= strpos($_SERVER['REQUEST_URI'], '/admin/menu') !== false ? 'bg-blue-600 text-white' : '' ?>" data-path="/admin/menu">
            <i class="fas fa-utensils w-5 h-5"></i>
            <span>Piatti</span>
        </a>
        
        <!-- Tools Section -->
        <div class="text-slate-400 font-semibold text-xs uppercase tracking-wider mt-6 mb-2 px-3">Strumenti</div>
        <a href="<?= BASE_URL ?>/admin/qrcode" class="flex items-center gap-3 px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= strpos($_SERVER['REQUEST_URI'], '/admin/qrcode') !== false ? 'bg-blue-600 text-white' : '' ?>" data-path="/admin/qrcode">
            <i class="fas fa-qrcode w-5 h-5"></i>
            <span>QR Code</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/analytics" class="flex items-center gap-3 px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= strpos($_SERVER['REQUEST_URI'], '/admin/analytics') !== false ? 'bg-blue-600 text-white' : '' ?>" data-path="/admin/analytics">
            <i class="fas fa-chart-line w-5 h-5"></i>
            <span>Statistiche</span>
        </a>
        
        <!-- Settings Section -->
        <div class="text-slate-400 font-semibold text-xs uppercase tracking-wider mt-6 mb-2 px-3">Impostazioni</div>
        <a href="<?= BASE_URL ?>/admin/settings" class="flex items-center gap-3 px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= strpos($_SERVER['REQUEST_URI'], '/admin/settings') !== false ? 'bg-blue-600 text-white' : '' ?>" data-path="/admin/settings">
            <i class="fas fa-cog w-5 h-5"></i>
            <span>Ristorante</span>
        </a>
        <?php if ($_SESSION['admin_role'] === 'owner'): ?>
        <a href="<?= BASE_URL ?>/admin/users" class="flex items-center gap-3 px-3 py-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors duration-200 text-sm font-medium <?= strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'bg-blue-600 text-white' : '' ?>" data-path="/admin/users">
            <i class="fas fa-users w-5 h-5"></i>
            <span>Utenti</span>
        </a>
        <?php endif; ?>
    </nav>
    
    <!-- User Info & Logout -->
    <div class="p-4 border-t border-slate-700">
        <div class="mb-4">
            <div class="text-slate-400 text-xs mb-1">Connesso come:</div>
            <div class="text-white font-semibold">
                <?= $_SESSION['restaurant_admin_username'] ?? 'Admin' ?>
            </div>
            <div class="text-slate-400 text-xs">
                <?= ucfirst($_SESSION['admin_role'] ?? 'staff') ?>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="space-y-2">
            <a href="<?= BASE_URL ?>/restaurant/<?= $_SESSION['restaurant_slug'] ?? '' ?>" 
               class="flex items-center gap-2 text-slate-300 hover:text-white text-sm py-1 transition-colors"
               target="_blank">
                <i class="fas fa-external-link-alt w-4"></i>
                <span>Visualizza Menu</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/logout" 
               class="flex items-center gap-2 text-red-400 hover:text-red-300 text-sm py-1 transition-colors">
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