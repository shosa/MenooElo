<?php
require_once 'config/config.php';
require_once 'includes/router.php';
require_once 'includes/Database.php';

// Check HTTPS setting
try {
    $db = Database::getInstance();
    $httpsSettng = $db->selectOne("SELECT setting_value FROM system_settings WHERE setting_key = 'force_https'");
    $forceHttps = ($httpsSettng && $httpsSettng['setting_value'] === '1');
    
    if ($forceHttps && !isset($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] != 443) {
        $redirectURL = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: $redirectURL");
        exit();
    }
} catch (Exception $e) {
    // If database is not available, continue without HTTPS check
}

session_start();

$router = new Router();

// Public routes
$router->addRoute('/', 'controllers/HomeController.php', 'index');
$router->addRoute('/restaurant/{slug}', 'controllers/MenuController.php', 'show');

// Restaurant Admin routes
$router->addRoute('/admin', 'controllers/AdminController.php', 'dashboard');
$router->addRoute('/admin/login', 'controllers/AdminController.php', 'login');
$router->addRoute('/admin/logout', 'controllers/AdminController.php', 'logout');
$router->addRoute('/admin/categories', 'controllers/AdminController.php', 'categories');
$router->addRoute('/admin/categories/add', 'controllers/AdminController.php', 'categoryAdd');
$router->addRoute('/admin/categories/edit/{id}', 'controllers/AdminController.php', 'categoryEdit');
$router->addRoute('/admin/categories/delete/{id}', 'controllers/AdminController.php', 'categoryDelete');
$router->addRoute('/admin/menu', 'controllers/AdminController.php', 'menuItems');
$router->addRoute('/admin/menu/category/{id}', 'controllers/AdminController.php', 'menuItems');
$router->addRoute('/admin/menu/item/add', 'controllers/AdminController.php', 'itemAdd');
$router->addRoute('/admin/menu/item/add/{categoryId}', 'controllers/AdminController.php', 'itemAdd');
$router->addRoute('/admin/menu/item/edit/{id}', 'controllers/AdminController.php', 'itemEdit');
$router->addRoute('/admin/menu/item/delete/{id}', 'controllers/AdminController.php', 'itemDelete');
$router->addRoute('/admin/settings', 'controllers/AdminController.php', 'settings');
$router->addRoute('/admin/users', 'controllers/AdminController.php', 'users');
$router->addRoute('/admin/qrcode', 'controllers/AdminController.php', 'qrcode');
$router->addRoute('/admin/analytics', 'controllers/AdminController.php', 'analytics');

// Super Admin routes
$router->addRoute('/superadmin', 'controllers/SuperAdminController.php', 'dashboard');
$router->addRoute('/superadmin/login', 'controllers/SuperAdminController.php', 'login');
$router->addRoute('/superadmin/logout', 'controllers/SuperAdminController.php', 'logout');
$router->addRoute('/superadmin/restaurants', 'controllers/SuperAdminController.php', 'restaurants');
$router->addRoute('/superadmin/restaurant/add', 'controllers/SuperAdminController.php', 'restaurantAdd');
$router->addRoute('/superadmin/restaurant/edit/{id}', 'controllers/SuperAdminController.php', 'restaurantEdit');
$router->addRoute('/superadmin/restaurant/delete/{id}', 'controllers/SuperAdminController.php', 'restaurantDelete');
$router->addRoute('/superadmin/admins', 'controllers/SuperAdminController.php', 'restaurantAdmins');
$router->addRoute('/superadmin/admin/add', 'controllers/SuperAdminController.php', 'adminAdd');
$router->addRoute('/superadmin/admin/edit/{id}', 'controllers/SuperAdminController.php', 'adminEdit');
$router->addRoute('/superadmin/admin/delete/{id}', 'controllers/SuperAdminController.php', 'adminDelete');
$router->addRoute('/superadmin/settings', 'controllers/SuperAdminController.php', 'systemSettings');
$router->addRoute('/superadmin/database', 'controllers/SuperAdminController.php', 'database');
$router->addRoute('/superadmin/database-api', 'controllers/SuperAdminController.php', 'databaseApi');
$router->addRoute('/superadmin/file-manager', 'controllers/SuperAdminController.php', 'fileManager');
$router->addRoute('/superadmin/file-manager-api', 'controllers/SuperAdminController.php', 'fileManagerApi');
$router->addRoute('/superadmin/upload-stats', 'controllers/SuperAdminController.php', 'uploadStats');
$router->addRoute('/superadmin/test-email', 'controllers/SuperAdminController.php', 'testEmail');
$router->addRoute('/superadmin/manual-backup', 'controllers/SuperAdminController.php', 'manualBackup');
$router->addRoute('/superadmin/logs', 'controllers/SuperAdminController.php', 'activityLogs');
$router->addRoute('/superadmin/analytics', 'controllers/SuperAdminController.php', 'analytics');

// API routes
$router->addRoute('/api/admin/quick-edit', 'controllers/ApiController.php', 'quickEdit');
$router->addRoute('/api/admin/update-order', 'controllers/ApiController.php', 'updateOrder');
$router->addRoute('/api/upload', 'controllers/ApiController.php', 'upload');

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = str_replace('/menooelo', '', $requestUri);

$router->dispatch($requestUri);
?>