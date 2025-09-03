<?php
require_once 'includes/BaseController.php';

class SuperAdminController extends BaseController {
    
    public function dashboard() {
        $this->auth->requireSuperAdmin();
        
        $stats = [
            'total_restaurants' => $this->db->selectOne("SELECT COUNT(*) as count FROM restaurants")['count'],
            'active_restaurants' => $this->db->selectOne("SELECT COUNT(*) as count FROM restaurants WHERE is_active = 1")['count'],
            'total_admins' => $this->db->selectOne("SELECT COUNT(*) as count FROM restaurant_admins")['count'],
            'total_menu_items' => $this->db->selectOne("SELECT COUNT(*) as count FROM menu_items")['count']
        ];
        
        $recent_restaurants = $this->db->select(
            "SELECT r.*, COUNT(ra.id) as admin_count 
             FROM restaurants r 
             LEFT JOIN restaurant_admins ra ON r.id = ra.restaurant_id AND ra.is_active = 1 
             GROUP BY r.id 
             ORDER BY r.created_at DESC 
             LIMIT 10"
        );
        
        $recent_activity = $this->db->select(
            "SELECT al.*, r.name as restaurant_name 
             FROM activity_logs al 
             LEFT JOIN restaurants r ON al.restaurant_id = r.id 
             ORDER BY al.created_at DESC 
             LIMIT 20"
        );
        
        $this->loadView('superadmin/dashboard', [
            'title' => 'Super Admin Dashboard - ' . SystemSettings::getAppName(),
            'stats' => $stats,
            'recent_restaurants' => $recent_restaurants,
            'recent_activity' => $recent_activity
        ]);
    }
    
    public function login() {
        if ($this->auth->isSuperAdmin()) {
            $this->redirect('/superadmin');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->sanitizeInput($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } elseif (empty($username) || empty($password)) {
                $error = 'Username e password sono obbligatori';
            } elseif ($this->auth->loginSuperAdmin($username, $password)) {
                $this->redirect('/superadmin');
            } else {
                $error = 'Credenziali non valide';
            }
        }
        
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('superadmin/login', [
            'title' => 'Super Admin Login - ' . SystemSettings::getAppName(),
            'error' => $error ?? null,
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function logout() {
        $this->auth->logout();
        $this->redirect('/superadmin/login');
    }
    
    public function restaurants() {
        $this->auth->requireSuperAdmin();
        
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $search = $this->sanitizeInput($_GET['search'] ?? '');
        $status = $_GET['status'] ?? '';
        
        $where = ['1=1'];
        $params = [];
        
        if ($search) {
            $where[] = "(name LIKE ? OR slug LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($status !== '') {
            $where[] = "is_active = ?";
            $params[] = (int)$status;
        }
        
        $whereClause = implode(' AND ', $where);
        
        $restaurants = $this->db->select(
            "SELECT r.*, 
                    COUNT(ra.id) as admin_count,
                    COUNT(mi.id) as menu_items_count
             FROM restaurants r 
             LEFT JOIN restaurant_admins ra ON r.id = ra.restaurant_id AND ra.is_active = 1 
             LEFT JOIN menu_items mi ON r.id = mi.restaurant_id 
             WHERE $whereClause
             GROUP BY r.id 
             ORDER BY r.created_at DESC 
             LIMIT $limit OFFSET $offset",
            $params
        );
        
        $total = $this->db->selectOne(
            "SELECT COUNT(*) as count FROM restaurants WHERE $whereClause",
            $params
        )['count'];
        
        $totalPages = ceil($total / $limit);
        
        $this->loadView('superadmin/restaurants', [
            'title' => 'Gestione Ristoranti - ' . SystemSettings::getAppName(),
            'restaurants' => $restaurants,
            'page' => $page,
            'total_pages' => $totalPages,
            'search' => $search,
            'status' => $status,
            'total' => $total
        ]);
    }
    
    public function restaurantAdd() {
        $this->auth->requireSuperAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } else {
                try {
                    $data = $this->sanitizeInput($_POST);
                    
                    $slug = $this->generateSlug($data['name']);
                    
                    if ($this->db->selectOne("SELECT id FROM restaurants WHERE slug = ?", [$slug])) {
                        throw new Exception('Uno slug simile esiste già');
                    }
                    
                    $features = [
                        'menu' => isset($data['features']['menu']),
                        'orders' => isset($data['features']['orders']),
                        'qrcode' => isset($data['features']['qrcode'])
                    ];
                    
                    $restaurantId = $this->db->insert(
                        "INSERT INTO restaurants 
                         (slug, name, description, address, phone, email, website, 
                          social_facebook, social_instagram, theme_color, currency, 
                          is_active, features) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                        [
                            $slug,
                            $data['name'],
                            $data['description'] ?? null,
                            $data['address'] ?? null,
                            $data['phone'] ?? null,
                            $data['email'] ?? null,
                            $data['website'] ?? null,
                            $data['social_facebook'] ?? null,
                            $data['social_instagram'] ?? null,
                            $data['theme_color'] ?? '#3273dc',
                            $data['currency'] ?? 'EUR',
                            isset($data['is_active']) ? 1 : 0,
                            json_encode($features)
                        ]
                    );
                    
                    if (isset($data['admin_username']) && $data['admin_username']) {
                        // Validate password before hashing
                        $passwordValidation = $this->auth->validatePassword($data['admin_password']);
                        if ($passwordValidation !== true) {
                            throw new Exception('Password non valida: ' . implode(', ', $passwordValidation));
                        }
                        
                        $this->db->insert(
                            "INSERT INTO restaurant_admins 
                             (restaurant_id, username, email, password_hash, full_name, role) 
                             VALUES (?, ?, ?, ?, ?, 'owner')",
                            [
                                $restaurantId,
                                $data['admin_username'],
                                $data['admin_email'],
                                $this->auth->hashPassword($data['admin_password']),
                                $data['admin_full_name']
                            ]
                        );
                    }
                    
                    $this->redirect('/superadmin/restaurants?success=created');
                    
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('superadmin/restaurant-form', [
            'title' => 'Aggiungi Ristorante - ' . SystemSettings::getAppName(),
            'error' => $error ?? null,
            'csrf_token' => $csrf_token,
            'restaurant' => null
        ]);
    }
    
    public function restaurantEdit($id) {
        $this->auth->requireSuperAdmin();
        
        $restaurant = $this->db->selectOne("SELECT * FROM restaurants WHERE id = ?", [$id]);
        
        if (!$restaurant) {
            $this->redirect('/superadmin/restaurants?error=not_found');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } else {
                try {
                    $data = $this->sanitizeInput($_POST);
                    
                    $features = [
                        'menu' => isset($data['features']['menu']),
                        'orders' => isset($data['features']['orders']),
                        'qrcode' => isset($data['features']['qrcode'])
                    ];
                    
                    $this->db->update(
                        "UPDATE restaurants SET 
                         name = ?, description = ?, address = ?, phone = ?, email = ?, 
                         website = ?, social_facebook = ?, social_instagram = ?, 
                         theme_color = ?, currency = ?, is_active = ?, features = ?
                         WHERE id = ?",
                        [
                            $data['name'],
                            $data['description'] ?? null,
                            $data['address'] ?? null,
                            $data['phone'] ?? null,
                            $data['email'] ?? null,
                            $data['website'] ?? null,
                            $data['social_facebook'] ?? null,
                            $data['social_instagram'] ?? null,
                            $data['theme_color'] ?? '#3273dc',
                            $data['currency'] ?? 'EUR',
                            isset($data['is_active']) ? 1 : 0,
                            json_encode($features),
                            $id
                        ]
                    );
                    
                    $this->redirect('/superadmin/restaurants?success=updated');
                    
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        $restaurant['features'] = json_decode($restaurant['features'] ?: '{}', true) ?? [];
        
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('superadmin/restaurant-form', [
            'title' => 'Modifica Ristorante - ' . SystemSettings::getAppName(),
            'error' => $error ?? null,
            'csrf_token' => $csrf_token,
            'restaurant' => $restaurant
        ]);
    }
    
    public function restaurantDelete($id) {
        $this->auth->requireSuperAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->validateCsrf($_POST['csrf_token'] ?? '')) {
            try {
                $this->db->delete("DELETE FROM restaurants WHERE id = ?", [$id]);
                $this->redirect('/superadmin/restaurants?success=deleted');
            } catch (Exception $e) {
                $this->redirect('/superadmin/restaurants?error=delete_failed');
            }
        }
        
        $this->redirect('/superadmin/restaurants');
    }
    
    public function systemSettings() {
        $this->auth->requireSuperAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } else {
                try {
                    $settings = $this->sanitizeInput($_POST['settings'] ?? []);
                    
                    // Handle special conversions and validations
                    if (isset($_POST['settings']['session_timeout_minutes'])) {
                        $minutes = (int)$_POST['settings']['session_timeout_minutes'];
                        $settings['session_timeout'] = $minutes * 60; // Convert to seconds
                        unset($settings['session_timeout_minutes']);
                    }
                    
                    if (isset($_POST['settings']['max_image_size_mb'])) {
                        $mb = (float)$_POST['settings']['max_image_size_mb'];
                        $settings['max_image_size'] = (int)($mb * 1048576); // Convert to bytes
                        unset($settings['max_image_size_mb']);
                    }
                    
                    if (isset($_POST['settings']['max_font_size_mb'])) {
                        $mb = (float)$_POST['settings']['max_font_size_mb'];
                        $settings['max_font_size'] = (int)($mb * 1048576); // Convert to bytes
                        unset($settings['max_font_size_mb']);
                    }
                    
                    // Handle array fields
                    if (isset($_POST['settings']['allowed_image_formats']) && is_array($_POST['settings']['allowed_image_formats'])) {
                        $settings['allowed_image_formats'] = implode(',', $_POST['settings']['allowed_image_formats']);
                    }
                    
                    if (isset($_POST['settings']['allowed_font_formats']) && is_array($_POST['settings']['allowed_font_formats'])) {
                        $settings['allowed_font_formats'] = implode(',', $_POST['settings']['allowed_font_formats']);
                    }
                    
                    // Handle boolean fields
                    $booleanFields = [
                        'maintenance_mode', 'registration_enabled', 'two_factor_enabled', 'force_https',
                        'log_failed_logins', 'cookie_secure', 'auto_image_optimization',
                        'generate_thumbnails', 'gzip_compression', 'lazy_loading', 'debug_mode'
                    ];
                    
                    foreach ($booleanFields as $field) {
                        if (!isset($settings[$field])) {
                            $settings[$field] = false;
                        } else {
                            $settings[$field] = (bool)$settings[$field];
                        }
                    }
                    
                    
                    if (isset($settings['app_url']) && !filter_var($settings['app_url'], FILTER_VALIDATE_URL)) {
                        throw new Exception('URL del sistema non valido');
                    }
                    
                    // Update or insert settings
                    foreach ($settings as $key => $value) {
                        if (is_bool($value)) {
                            $value = $value ? '1' : '0';
                        }
                        
                        $this->db->query(
                            "INSERT INTO system_settings (setting_key, setting_value) 
                             VALUES (?, ?) 
                             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)",
                            [$key, $value]
                        );
                    }
                    
                    $success = 'Impostazioni aggiornate con successo';
                    
                    // Log the settings change
                    $this->logActivity('system_settings_updated', 'super_admin', null, [
                        'updated_settings' => array_keys($settings)
                    ]);
                    
                } catch (Exception $e) {
                    error_log('Settings update error: ' . $e->getMessage());
                    $error = $e->getMessage();
                }
            }
        }
        
        // Load current settings with defaults
        $settings = $this->db->select("SELECT * FROM system_settings ORDER BY setting_key");
        
        // Set default values
        $defaultSettings = [
            'app_name' => 'MenooElo',
            'app_url' => BASE_URL,
            'timezone' => 'Europe/Rome',
            'default_language' => 'it',
            'default_currency' => 'EUR',
            'max_image_size' => 5242880,
            'max_font_size' => 2097152,
            'session_timeout' => 3600,
            'max_login_attempts' => 5,
            'allowed_image_formats' => 'jpg,jpeg,png,webp',
            'allowed_font_formats' => 'ttf,otf,woff,woff2'
        ];
        
        $settingsArray = $defaultSettings;
        
        // Ensure minimal required settings exist in database
        $this->ensureRequiredSettings($defaultSettings);
        
        foreach ($settings as $setting) {
            $key = $setting['setting_key'];
            $value = $setting['setting_value'];
            
            // Convert boolean strings back to actual booleans for display
            if (in_array($value, ['0', '1'])) {
                $value = $value === '1';
            }
            
            // Ensure numeric fields are never zero or empty
            if (in_array($key, ['max_image_size', 'max_font_size']) && (!$value || $value === '0' || (int)$value <= 0)) {
                $value = $defaultSettings[$key] ?? ($key === 'max_image_size' ? 5242880 : 2097152);
            }
            
            // Ensure string fields are never empty
            if (in_array($key, ['app_name', 'app_url']) && (!$value || trim($value) === '')) {
                $value = $defaultSettings[$key] ?? '';
            }
            
            $settingsArray[$key] = $value;
        }
        
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('superadmin/settings', [
            'title' => 'Impostazioni Sistema - ' . SystemSettings::getAppName(),
            'settings' => $settingsArray,
            'error' => $error ?? null,
            'success' => $success ?? null,
            'csrf_token' => $csrf_token
        ]);
    }
    
    private function ensureRequiredSettings($defaultSettings) {
        $requiredKeys = ['app_name', 'app_url', 'max_image_size'];
        
        foreach ($requiredKeys as $key) {
            $existing = $this->db->selectOne(
                "SELECT id FROM system_settings WHERE setting_key = ?", 
                [$key]
            );
            
            if (!$existing) {
                try {
                    $this->db->insert(
                        "INSERT INTO system_settings (setting_key, setting_value, description) VALUES (?, ?, ?)",
                        [
                            $key,
                            $defaultSettings[$key] ?? '',
                            'Auto-generated default setting'
                        ]
                    );
                } catch (Exception $e) {
                    error_log("Failed to insert default setting {$key}: " . $e->getMessage());
                }
            }
        }
    }
    
    private function generateSlug($name) {
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->db->selectOne("SELECT id FROM restaurants WHERE slug = ?", [$slug])) {
            $slug = $originalSlug . '-' . $counter++;
        }
        
        return $slug;
    }
    
    public function restaurantAdmins() {
        $this->auth->requireSuperAdmin();
        
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $search = $this->sanitizeInput($_GET['search'] ?? '');
        $restaurantId = (int)($_GET['restaurant'] ?? 0);
        
        $where = ['1=1'];
        $params = [];
        
        if ($search) {
            $where[] = "(ra.username LIKE ? OR ra.email LIKE ? OR ra.full_name LIKE ? OR r.name LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($restaurantId) {
            $where[] = "ra.restaurant_id = ?";
            $params[] = $restaurantId;
        }
        
        $whereClause = implode(' AND ', $where);
        
        $admins = $this->db->select(
            "SELECT ra.*, r.name as restaurant_name, r.slug as restaurant_slug
             FROM restaurant_admins ra 
             JOIN restaurants r ON ra.restaurant_id = r.id 
             WHERE $whereClause
             ORDER BY ra.created_at DESC 
             LIMIT $limit OFFSET $offset",
            $params
        );
        
        $total = $this->db->selectOne(
            "SELECT COUNT(*) as count 
             FROM restaurant_admins ra 
             JOIN restaurants r ON ra.restaurant_id = r.id 
             WHERE $whereClause",
            $params
        )['count'];
        
        $restaurants = $this->db->select("SELECT id, name FROM restaurants ORDER BY name ASC");
        
        $totalPages = ceil($total / $limit);
        
        $this->loadView('superadmin/admins', [
            'title' => 'Admin Ristoranti - MenooElo',
            'admins' => $admins,
            'restaurants' => $restaurants,
            'page' => $page,
            'total_pages' => $totalPages,
            'search' => $search,
            'selected_restaurant' => $restaurantId,
            'total' => $total
        ]);
    }
    
    public function database() {
        $this->auth->requireSuperAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } else {
                try {
                    $action = $_POST['action'] ?? '';
                    
                    switch ($action) {
                        case 'optimize':
                            $tables = $this->db->select("SHOW TABLES");
                            foreach ($tables as $table) {
                                $tableName = array_values($table)[0];
                                $this->db->query("OPTIMIZE TABLE `$tableName`");
                            }
                            $success = 'Database ottimizzato con successo';
                            break;
                            
                        case 'backup':
                            $success = 'Funzionalità di backup non ancora implementata';
                            break;
                            
                        default:
                            $error = 'Azione non valida';
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        // Database stats
        $stats = [];
        try {
            $tables = [
                'restaurants' => 'Ristoranti',
                'restaurant_admins' => 'Admin Ristoranti',
                'menu_categories' => 'Categorie Menu',
                'menu_items' => 'Piatti',
                'menu_item_variants' => 'Varianti',
                'menu_item_extras' => 'Extra',
                'activity_logs' => 'Log Attività'
            ];
            
            foreach ($tables as $table => $label) {
                $result = $this->db->selectOne("SELECT COUNT(*) as count FROM `$table`");
                $stats[$label] = $result['count'];
            }
        } catch (Exception $e) {
            $stats['Error'] = $e->getMessage();
        }
        
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('superadmin/database', [
            'title' => 'Gestione Database - MenooElo',
            'stats' => $stats,
            'error' => $error ?? null,
            'success' => $success ?? null,
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function activityLogs() {
        $this->auth->requireSuperAdmin();
        
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        $userType = $_GET['user_type'] ?? '';
        $restaurantId = (int)($_GET['restaurant'] ?? 0);
        $action = $this->sanitizeInput($_GET['action'] ?? '');
        
        $where = ['1=1'];
        $params = [];
        
        if ($userType) {
            $where[] = "al.user_type = ?";
            $params[] = $userType;
        }
        
        if ($restaurantId) {
            $where[] = "al.restaurant_id = ?";
            $params[] = $restaurantId;
        }
        
        if ($action) {
            $where[] = "al.action LIKE ?";
            $params[] = "%$action%";
        }
        
        $whereClause = implode(' AND ', $where);
        
        $logs = $this->db->select(
            "SELECT al.*, r.name as restaurant_name 
             FROM activity_logs al 
             LEFT JOIN restaurants r ON al.restaurant_id = r.id 
             WHERE $whereClause
             ORDER BY al.created_at DESC 
             LIMIT $limit OFFSET $offset",
            $params
        );
        
        $total = $this->db->selectOne(
            "SELECT COUNT(*) as count FROM activity_logs al WHERE $whereClause",
            $params
        )['count'];
        
        $restaurants = $this->db->select("SELECT id, name FROM restaurants ORDER BY name ASC");
        
        $totalPages = ceil($total / $limit);
        
        $this->loadView('superadmin/logs', [
            'title' => 'Log Attività - MenooElo',
            'logs' => $logs,
            'restaurants' => $restaurants,
            'page' => $page,
            'total_pages' => $totalPages,
            'user_type' => $userType,
            'selected_restaurant' => $restaurantId,
            'action' => $action,
            'total' => $total
        ]);
    }
    
    public function analytics() {
        $this->auth->requireSuperAdmin();
        
        // Stats generali
        $stats = [
            'total_restaurants' => $this->db->selectOne("SELECT COUNT(*) as count FROM restaurants")['count'],
            'active_restaurants' => $this->db->selectOne("SELECT COUNT(*) as count FROM restaurants WHERE is_active = 1")['count'],
            'total_admins' => $this->db->selectOne("SELECT COUNT(*) as count FROM restaurant_admins")['count'],
            'total_categories' => $this->db->selectOne("SELECT COUNT(*) as count FROM menu_categories")['count'],
            'total_items' => $this->db->selectOne("SELECT COUNT(*) as count FROM menu_items")['count']
        ];
        
        // Ristoranti più attivi (con più piatti)
        $topRestaurants = $this->db->select(
            "SELECT r.name, r.slug, COUNT(mi.id) as items_count 
             FROM restaurants r 
             LEFT JOIN menu_items mi ON r.id = mi.restaurant_id 
             WHERE r.is_active = 1 
             GROUP BY r.id 
             ORDER BY items_count DESC 
             LIMIT 10"
        );
        
        // Crescita mensile
        $monthlyGrowth = $this->db->select(
            "SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as count
             FROM restaurants 
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY DATE_FORMAT(created_at, '%Y-%m')
             ORDER BY month ASC"
        );
        
        $this->loadView('superadmin/analytics', [
            'title' => 'Analytics - MenooElo',
            'stats' => $stats,
            'top_restaurants' => $topRestaurants,
            'monthly_growth' => $monthlyGrowth
        ]);
    }
    
    // Admin Restaurant Management Methods
    
    public function adminAdd() {
        $this->auth->requireSuperAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } else {
                try {
                    $data = $this->sanitizeInput($_POST);
                    
                    // Check if username or email already exists
                    $existing = $this->db->selectOne(
                        "SELECT id FROM restaurant_admins WHERE username = ? OR email = ?",
                        [$data['username'], $data['email']]
                    );
                    
                    if ($existing) {
                        throw new Exception('Username o email già esistente');
                    }
                    
                    // Verify restaurant exists
                    $restaurant = $this->db->selectOne(
                        "SELECT id, name FROM restaurants WHERE id = ?",
                        [$data['restaurant_id']]
                    );
                    
                    if (!$restaurant) {
                        throw new Exception('Ristorante non trovato');
                    }
                    
                    // Validate password before hashing
                    $passwordValidation = $this->auth->validatePassword($data['password']);
                    if ($passwordValidation !== true) {
                        throw new Exception('Password non valida: ' . implode(', ', $passwordValidation));
                    }
                    
                    $this->db->insert(
                        "INSERT INTO restaurant_admins 
                         (restaurant_id, username, email, password_hash, full_name, role) 
                         VALUES (?, ?, ?, ?, ?, ?)",
                        [
                            $data['restaurant_id'],
                            $data['username'],
                            $data['email'],
                            $this->auth->hashPassword($data['password']),
                            $data['full_name'],
                            $data['role'] ?? 'admin'
                        ]
                    );
                    
                    $this->redirect('/superadmin/admins?success=created');
                    
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        $restaurants = $this->db->select("SELECT id, name FROM restaurants WHERE is_active = 1 ORDER BY name ASC");
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('superadmin/admin-form', [
            'title' => 'Aggiungi Admin - MenooElo',
            'restaurants' => $restaurants,
            'error' => $error ?? null,
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function adminEdit($id) {
        $this->auth->requireSuperAdmin();
        
        $admin = $this->db->selectOne(
            "SELECT ra.*, r.name as restaurant_name 
             FROM restaurant_admins ra 
             JOIN restaurants r ON ra.restaurant_id = r.id 
             WHERE ra.id = ?",
            [$id]
        );
        
        if (!$admin) {
            $this->redirect('/superadmin/admins?error=not_found');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } else {
                try {
                    $data = $this->sanitizeInput($_POST);
                    
                    // Check if username or email already exists (excluding current admin)
                    $existing = $this->db->selectOne(
                        "SELECT id FROM restaurant_admins WHERE (username = ? OR email = ?) AND id != ?",
                        [$data['username'], $data['email'], $id]
                    );
                    
                    if ($existing) {
                        throw new Exception('Username o email già esistente');
                    }
                    
                    // Verify restaurant exists
                    $restaurant = $this->db->selectOne(
                        "SELECT id FROM restaurants WHERE id = ?",
                        [$data['restaurant_id']]
                    );
                    
                    if (!$restaurant) {
                        throw new Exception('Ristorante non trovato');
                    }
                    
                    $updateFields = [
                        $data['restaurant_id'],
                        $data['username'],
                        $data['email'],
                        $data['full_name'],
                        $data['role'] ?? 'admin',
                        $id
                    ];
                    
                    $sql = "UPDATE restaurant_admins 
                            SET restaurant_id = ?, username = ?, email = ?, full_name = ?, role = ?";
                    
                    // Update password only if provided
                    if (!empty($data['password'])) {
                        // Validate password before hashing
                        $passwordValidation = $this->auth->validatePassword($data['password']);
                        if ($passwordValidation !== true) {
                            throw new Exception('Password non valida: ' . implode(', ', $passwordValidation));
                        }
                        
                        $sql .= ", password_hash = ?";
                        $updateFields = array_slice($updateFields, 0, -1);
                        $updateFields[] = $this->auth->hashPassword($data['password']);
                        $updateFields[] = $id;
                    }
                    
                    $sql .= " WHERE id = ?";
                    
                    $this->db->update($sql, $updateFields);
                    
                    $this->redirect('/superadmin/admins?success=updated');
                    
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        $restaurants = $this->db->select("SELECT id, name FROM restaurants WHERE is_active = 1 ORDER BY name ASC");
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('superadmin/admin-form', [
            'title' => 'Modifica Admin - MenooElo',
            'admin' => $admin,
            'restaurants' => $restaurants,
            'error' => $error ?? null,
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function adminDelete($id) {
        $this->auth->requireSuperAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/superadmin/admins');
        }
        
        if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
            $this->redirect('/superadmin/admins?error=csrf');
        }
        
        try {
            $admin = $this->db->selectOne("SELECT id, full_name FROM restaurant_admins WHERE id = ?", [$id]);
            
            if (!$admin) {
                throw new Exception('Admin non trovato');
            }
            
            $this->db->delete("DELETE FROM restaurant_admins WHERE id = ?", [$id]);
            
            $this->redirect('/superadmin/admins?success=deleted');
            
        } catch (Exception $e) {
            $this->redirect('/superadmin/admins?error=delete_failed');
        }
    }
    
    public function databaseApi() {
        $this->auth->requireSuperAdmin();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
            return;
        }
        
        try {
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['success' => false, 'error' => 'JSON non valido: ' . json_last_error_msg()]);
                return;
            }
            
            $action = $input['action'] ?? '';
            $csrfToken = $input['csrf_token'] ?? '';
            
            if (!$this->validateCsrf($csrfToken)) {
                echo json_encode(['success' => false, 'error' => 'Token CSRF non valido']);
                return;
            }
        
            switch ($action) {
                case 'get_stats':
                    error_log("Getting database stats...");
                    $stats = $this->getDatabaseStats();
                    error_log("Stats retrieved: " . print_r($stats, true));
                    echo json_encode([
                        'success' => true,
                        'stats' => $stats
                    ]);
                    break;
                    
                case 'get_tables':
                    error_log("Getting tables list...");
                    $tables = $this->getTablesList();
                    error_log("Tables: " . print_r($tables, true));
                    echo json_encode([
                        'success' => true,
                        'tables' => $tables
                    ]);
                    break;
                    
                case 'get_table_details':
                    $tableName = $input['table'] ?? '';
                    error_log("Request for table details: '$tableName'");
                    if (empty($tableName)) {
                        throw new Exception('Nome tabella mancante');
                    }
                    $details = $this->getTableDetails($tableName);
                    error_log("Table details retrieved successfully");
                    echo json_encode([
                        'success' => true,
                        'details' => $details
                    ]);
                    break;
                    
                case 'execute_query':
                    $query = trim($input['query'] ?? '');
                    if (empty($query)) {
                        throw new Exception('Query mancante');
                    }
                    echo json_encode($this->executeQuery($query));
                    break;
                    
                case 'optimize_table':
                    $tableName = $input['table'] ?? '';
                    if (empty($tableName)) {
                        throw new Exception('Nome tabella mancante');
                    }
                    $this->db->query("OPTIMIZE TABLE `$tableName`");
                    echo json_encode(['success' => true, 'message' => "Tabella $tableName ottimizzata"]);
                    break;
                    
                case 'repair_table':
                    $tableName = $input['table'] ?? '';
                    if (empty($tableName)) {
                        throw new Exception('Nome tabella mancante');
                    }
                    $this->db->query("REPAIR TABLE `$tableName`");
                    echo json_encode(['success' => true, 'message' => "Tabella $tableName riparata"]);
                    break;
                    
                case 'truncate_table':
                    $tableName = $input['table'] ?? '';
                    if (empty($tableName) || !$this->isValidTableName($tableName)) {
                        throw new Exception('Nome tabella non valido');
                    }
                    $this->db->query("TRUNCATE TABLE `$tableName`");
                    echo json_encode(['success' => true, 'message' => "Tabella $tableName svuotata"]);
                    break;
                    
                case 'drop_table':
                    $tableName = $input['table'] ?? '';
                    if (empty($tableName) || !$this->isValidTableName($tableName)) {
                        throw new Exception('Nome tabella non valido');
                    }
                    // Protezione per tabelle critiche
                    $criticalTables = ['restaurants', 'restaurant_admins', 'super_admins', 'menu_categories', 'menu_items'];
                    if (in_array($tableName, $criticalTables)) {
                        throw new Exception('Non è possibile eliminare tabelle critiche del sistema');
                    }
                    $this->db->query("DROP TABLE `$tableName`");
                    echo json_encode(['success' => true, 'message' => "Tabella $tableName eliminata"]);
                    break;
                    
                case 'export_database':
                    $exportType = $input['export_type'] ?? 'full';
                    $format = $input['format'] ?? 'sql';
                    $tables = $input['tables'] ?? [];
                    echo json_encode($this->exportDatabase($exportType, $format, $tables));
                    break;
                    
                case 'backup_database':
                    $backupType = $input['backup_type'] ?? 'full';
                    echo json_encode($this->backupDatabase($backupType));
                    break;
                    
                default:
                    throw new Exception('Azione non riconosciuta');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function getDatabaseStats() {
        error_log("Starting getDatabaseStats()...");
        
        try {
            // Test basic connection first
            $testResult = $this->db->selectOne("SELECT 1 as test");
            error_log("Basic connection test: " . print_r($testResult, true));
            
            // Get current database name
            $dbResult = $this->db->selectOne("SELECT DATABASE() as name");
            $dbName = $dbResult ? $dbResult['name'] : null;
            error_log("Database name: " . ($dbName ?? 'NULL'));
            
            if (!$dbName) {
                throw new Exception("No database selected");
            }
            
            // Get MySQL version
            $versionResult = $this->db->selectOne("SELECT VERSION() as version");
            $mysqlVersion = $versionResult ? $versionResult['version'] : 'N/A';
            error_log("MySQL version: " . $mysqlVersion);
            
            // Count tables using SHOW TABLES
            $allTables = $this->db->select("SHOW TABLES");
            $tableCount = count($allTables);
            error_log("Found $tableCount tables: " . print_r($allTables, true));
            
            // Simple table info - get only first few tables for overview
            $formattedTables = [];
            $tablesProcessed = 0;
            
            foreach ($allTables as $tableRow) {
                if ($tablesProcessed >= 5) break; // Limit to 5 for debugging
                
                $tableName = array_values($tableRow)[0];
                error_log("Processing table: $tableName");
                
                try {
                    // Get row count
                    $rowCountResult = $this->db->selectOne("SELECT COUNT(*) as count FROM `$tableName`");
                    $rowCount = $rowCountResult ? (int)$rowCountResult['count'] : 0;
                    error_log("Table $tableName has $rowCount rows");
                    
                    $formattedTables[] = [
                        'name' => $tableName,
                        'rows' => $rowCount,
                        'size' => '0.001 MB',
                        'engine' => 'InnoDB'
                    ];
                    $tablesProcessed++;
                } catch (Exception $e) {
                    error_log("Error processing table $tableName: " . $e->getMessage());
                }
            }
            
            $result = [
                'table_count' => $tableCount,
                'db_size' => '1.0 MB', // Simplified for now
                'mysql_version' => $mysqlVersion,
                'collation' => 'utf8mb4_unicode_ci',
                'charset' => 'utf8mb4',
                'tables' => $formattedTables
            ];
            
            error_log("Final result: " . print_r($result, true));
            return $result;
            
        } catch (Exception $e) {
            error_log("Database stats error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            return [
                'table_count' => 0,
                'db_size' => '0 MB',
                'mysql_version' => 'N/A',
                'collation' => 'N/A',
                'charset' => 'N/A',
                'tables' => [],
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function getTablesList() {
        error_log("Starting getTablesList()...");
        
        try {
            // Get all tables using SHOW TABLES (simpler and more reliable)
            $allTables = $this->db->select("SHOW TABLES");
            error_log("Found tables: " . print_r($allTables, true));
            
            $formattedTables = [];
            foreach ($allTables as $tableRow) {
                $tableName = array_values($tableRow)[0];
                error_log("Processing table: $tableName");
                
                // Get actual row count with error handling
                try {
                    $rowCountResult = $this->db->selectOne("SELECT COUNT(*) as count FROM `$tableName`");
                    $rowCount = $rowCountResult ? (int)$rowCountResult['count'] : 0;
                    error_log("Table $tableName has $rowCount rows");
                } catch (Exception $e) {
                    error_log("Error counting rows for table $tableName: " . $e->getMessage());
                    $rowCount = 0;
                }
                
                $formattedTables[] = [
                    'name' => $tableName,
                    'rows' => $rowCount,
                    'size' => '0.001 MB',
                    'engine' => 'InnoDB',
                    'collation' => 'utf8mb4_unicode_ci',
                    'comment' => ''
                ];
            }
            
            // Sort by name
            usort($formattedTables, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            
            error_log("Final tables list: " . print_r($formattedTables, true));
            return $formattedTables;
        } catch (Exception $e) {
            error_log("Tables list error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return [];
        }
    }
    
    private function getTableDetails($tableName) {
        error_log("Getting details for table: $tableName");
        
        if (!$this->isValidTableName($tableName)) {
            throw new Exception('Nome tabella non valido');
        }
        
        try {
            // Get actual row count
            $rowCountResult = $this->db->selectOne("SELECT COUNT(*) as count FROM `$tableName`");
            $rowCount = $rowCountResult ? (int)$rowCountResult['count'] : 0;
            error_log("Table $tableName has $rowCount rows");
            
            // Get column details using DESCRIBE (more reliable than information_schema)
            $columns = $this->db->select("DESCRIBE `$tableName`");
            error_log("Table $tableName has " . count($columns) . " columns");
            
            // Try to get additional info from information_schema (optional)
            $engine = 'InnoDB';
            $collation = 'utf8mb4_unicode_ci';
            $size = '0.001';
            
            try {
                $tableInfo = $this->db->selectOne(
                    "SELECT 
                        engine,
                        table_collation as collation,
                        ROUND((data_length + index_length) / 1024 / 1024, 3) as size_mb
                     FROM information_schema.tables 
                     WHERE table_schema = DATABASE() AND table_name = ?",
                    [$tableName]
                );
                
                if ($tableInfo) {
                    $engine = $tableInfo['engine'] ?? 'InnoDB';
                    $collation = $tableInfo['collation'] ?? 'utf8mb4_unicode_ci';
                    $size = $tableInfo['size_mb'] ?? '0.001';
                    error_log("Got additional info: engine=$engine, collation=$collation, size={$size}MB");
                }
            } catch (Exception $e) {
                error_log("Could not get additional table info (using defaults): " . $e->getMessage());
            }
            
            $result = [
                'name' => $tableName,
                'rows' => $rowCount,
                'size' => $size . ' MB',
                'engine' => $engine,
                'collation' => $collation,
                'columns' => $columns
            ];
            
            error_log("Table details result: " . print_r($result, true));
            return $result;
            
        } catch (Exception $e) {
            error_log("Error getting table details for $tableName: " . $e->getMessage());
            throw new Exception("Errore nel recuperare i dettagli della tabella: " . $e->getMessage());
        }
    }
    
    private function executeQuery($query) {
        // Basic security checks
        $query = trim($query);
        $queryType = strtoupper(substr($query, 0, 6));
        
        // Check for dangerous operations
        $dangerousPatterns = [
            '/DROP\s+DATABASE/i',
            '/CREATE\s+DATABASE/i',
            '/GRANT\s+/i',
            '/REVOKE\s+/i',
            '/LOAD_FILE\s*\(/i',
            '/INTO\s+OUTFILE/i',
            '/INTO\s+DUMPFILE/i'
        ];
        
        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $query)) {
                throw new Exception('Query non consentita per motivi di sicurezza');
            }
        }
        
        try {
            $startTime = microtime(true);
            
            if (in_array($queryType, ['SELECT', 'SHOW', 'DESCRI', 'EXPLAI'])) {
                // SELECT queries
                $results = $this->db->select($query);
                $executionTime = round((microtime(true) - $startTime) * 1000, 2);
                
                return [
                    'success' => true,
                    'type' => 'select',
                    'results' => $results,
                    'count' => count($results),
                    'execution_time' => $executionTime
                ];
            } else {
                // INSERT, UPDATE, DELETE queries
                $stmt = $this->db->query($query);
                $affectedRows = $stmt->rowCount();
                $executionTime = round((microtime(true) - $startTime) * 1000, 2);
                
                return [
                    'success' => true,
                    'type' => 'modify',
                    'affected_rows' => $affectedRows,
                    'execution_time' => $executionTime
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function isValidTableName($tableName) {
        // Check if table name contains only allowed characters
        return preg_match('/^[a-zA-Z0-9_]+$/', $tableName) && strlen($tableName) <= 64;
    }
    
    private function exportDatabase($exportType, $format, $tables = []) {
        try {
            error_log("Exporting database: type=$exportType, format=$format");
            
            $dbName = $this->db->selectOne("SELECT DATABASE() as name")['name'] ?? 'menooelo';
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "menooelo_export_{$timestamp}." . ($format === 'sql' ? 'sql' : $format);
            
            // Get tables to export
            $allTables = $this->db->select("SHOW TABLES");
            $tablesToExport = [];
            
            if ($exportType === 'custom' && !empty($tables)) {
                $tablesToExport = $tables;
            } else {
                foreach ($allTables as $tableRow) {
                    $tablesToExport[] = array_values($tableRow)[0];
                }
            }
            
            if ($format === 'sql') {
                return $this->exportToSQL($tablesToExport, $exportType, $filename);
            } elseif ($format === 'json') {
                return $this->exportToJSON($tablesToExport, $filename);
            } else {
                throw new Exception('Formato export non supportato');
            }
            
        } catch (Exception $e) {
            error_log("Export error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function exportToSQL($tables, $exportType, $filename) {
        $sql = "-- MenooElo Database Export\n";
        $sql .= "-- Generated on " . date('Y-m-d H:i:s') . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
        
        foreach ($tables as $tableName) {
            error_log("Exporting table: $tableName");
            
            try {
                // Add DROP TABLE if requested
                if ($exportType === 'full' || $exportType === 'structure') {
                    $sql .= "DROP TABLE IF EXISTS `$tableName`;\n";
                }
                
                // Add CREATE TABLE statement
                if ($exportType === 'full' || $exportType === 'structure') {
                    $createResult = $this->db->selectOne("SHOW CREATE TABLE `$tableName`");
                    if ($createResult) {
                        $createStatement = array_values($createResult)[1];
                        $sql .= $createStatement . ";\n\n";
                    }
                }
                
                // Add data if requested
                if ($exportType === 'full' || $exportType === 'data') {
                    $rows = $this->db->select("SELECT * FROM `$tableName`");
                    
                    if (!empty($rows)) {
                        $columns = array_keys($rows[0]);
                        $columnsList = '`' . implode('`, `', $columns) . '`';
                        
                        foreach ($rows as $row) {
                            $values = [];
                            foreach ($row as $value) {
                                if ($value === null) {
                                    $values[] = 'NULL';
                                } else {
                                    $values[] = "'" . addslashes($value) . "'";
                                }
                            }
                            $sql .= "INSERT INTO `$tableName` ($columnsList) VALUES (" . implode(', ', $values) . ");\n";
                        }
                        $sql .= "\n";
                    }
                }
                
            } catch (Exception $e) {
                error_log("Error exporting table $tableName: " . $e->getMessage());
                $sql .= "-- Error exporting table $tableName: " . $e->getMessage() . "\n\n";
            }
        }
        
        $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";
        
        // Create download
        return [
            'success' => true,
            'filename' => $filename,
            'content' => $sql,
            'size' => strlen($sql),
            'download_url' => $this->createDownloadFile($filename, $sql)
        ];
    }
    
    private function exportToJSON($tables, $filename) {
        $export = [
            'database' => 'menooelo',
            'exported_at' => date('c'),
            'tables' => []
        ];
        
        foreach ($tables as $tableName) {
            try {
                $rows = $this->db->select("SELECT * FROM `$tableName`");
                $export['tables'][$tableName] = $rows;
            } catch (Exception $e) {
                error_log("Error exporting table $tableName to JSON: " . $e->getMessage());
                $export['tables'][$tableName] = ['error' => $e->getMessage()];
            }
        }
        
        $jsonContent = json_encode($export, JSON_PRETTY_PRINT);
        
        return [
            'success' => true,
            'filename' => $filename,
            'content' => $jsonContent,
            'size' => strlen($jsonContent),
            'download_url' => $this->createDownloadFile($filename, $jsonContent)
        ];
    }
    
    private function backupDatabase($backupType) {
        try {
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "menooelo_backup_{$backupType}_{$timestamp}.sql";
            
            return $this->exportDatabase($backupType, 'sql');
            
        } catch (Exception $e) {
            error_log("Backup error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function createDownloadFile($filename, $content) {
        // Create temporary download file
        $downloadDir = UPLOADS_PATH . 'downloads/';
        if (!is_dir($downloadDir)) {
            mkdir($downloadDir, 0755, true);
        }
        
        $filepath = $downloadDir . $filename;
        file_put_contents($filepath, $content);
        
        // Return URL for download
        return BASE_URL . '/uploads/downloads/' . $filename;
    }
    
    // File Management Methods
    
    public function fileManager() {
        $this->auth->requireSuperAdmin();
        
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('superadmin/file-manager', [
            'title' => 'Gestione File - ' . SystemSettings::getAppName(),
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function fileManagerApi() {
        $this->auth->requireSuperAdmin();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
            return;
        }
        
        try {
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['success' => false, 'error' => 'JSON non valido']);
                return;
            }
            
            $action = $input['action'] ?? '';
            $csrfToken = $input['csrf_token'] ?? '';
            
            if (!$this->validateCsrf($csrfToken)) {
                echo json_encode(['success' => false, 'error' => 'Token CSRF non valido']);
                return;
            }
        
            switch ($action) {
                case 'get_stats':
                    echo json_encode([
                        'success' => true,
                        'stats' => $this->getFileStats()
                    ]);
                    break;
                    
                case 'get_directory_usage':
                    echo json_encode([
                        'success' => true,
                        'directories' => $this->getDirectoryUsage()
                    ]);
                    break;
                    
                case 'get_file_types':
                    echo json_encode([
                        'success' => true,
                        'types' => $this->getFileTypes()
                    ]);
                    break;
                    
                case 'get_recent_files':
                    echo json_encode([
                        'success' => true,
                        'files' => $this->getRecentFiles()
                    ]);
                    break;
                    
                case 'scan_orphaned':
                    echo json_encode([
                        'success' => true,
                        'orphaned_files' => $this->scanOrphanedFiles()
                    ]);
                    break;
                    
                case 'cleanup_orphaned':
                    $result = $this->cleanupOrphanedFiles();
                    echo json_encode($result);
                    break;
                    
                case 'scan_duplicates':
                    echo json_encode([
                        'success' => true,
                        'duplicates' => $this->scanDuplicateFiles()
                    ]);
                    break;
                    
                case 'bulk_delete':
                    $filepaths = $input['filepaths'] ?? [];
                    $result = $this->bulkDeleteFiles($filepaths);
                    echo json_encode($result);
                    break;
                    
                case 'browse_files':
                    $directory = $input['directory'] ?? '';
                    echo json_encode([
                        'success' => true,
                        'files' => $this->browseFiles($directory)
                    ]);
                    break;
                    
                case 'delete_file':
                    $filepath = $input['filepath'] ?? '';
                    $result = $this->deleteFile($filepath);
                    echo json_encode($result);
                    break;
                    
                case 'preview_file':
                    $filepath = $input['filepath'] ?? '';
                    $result = $this->previewFile($filepath);
                    echo json_encode($result);
                    break;
                    
                default:
                    throw new Exception('Azione non riconosciuta');
            }
            
        } catch (Exception $e) {
            error_log('File manager API error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function uploadStats() {
        $this->auth->requireSuperAdmin();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
            return;
        }
        
        try {
            echo json_encode([
                'success' => true,
                'stats' => $this->getFileStats()
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function testEmail() {
        $this->auth->requireSuperAdmin();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
            return;
        }
        
        try {
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            
            if (!$this->validateCsrf($input['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'error' => 'Token CSRF non valido']);
                return;
            }
            
            // Test email connection (placeholder - would implement actual SMTP test)
            $testResult = $this->testSmtpConnection($input);
            
            echo json_encode([
                'success' => $testResult['success'],
                'message' => $testResult['message'] ?? ''
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function manualBackup() {
        $this->auth->requireSuperAdmin();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
            return;
        }
        
        try {
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            
            if (!$this->validateCsrf($input['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'error' => 'Token CSRF non valido']);
                return;
            }
            
            $result = $this->backupDatabase('full');
            echo json_encode($result);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    // Private helper methods for file management
    
    private function getFileStats() {
        $stats = [
            'total_files' => 0,
            'total_size' => 0,
            'orphaned_files' => 0,
            'duplicate_files' => 0
        ];
        
        try {
            // Count files in upload directories
            $uploadDirs = ['logos', 'banners', 'categories', 'menu-items', 'fonts'];
            
            foreach ($uploadDirs as $dir) {
                $dirPath = UPLOADS_PATH . $dir . '/';
                if (is_dir($dirPath)) {
                    $files = glob($dirPath . '*');
                    $stats['total_files'] += count(array_filter($files, 'is_file'));
                    
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            $stats['total_size'] += filesize($file);
                        }
                    }
                }
            }
            
            // Quick orphaned files scan (simplified)
            $stats['orphaned_files'] = $this->countOrphanedFiles();
            
        } catch (Exception $e) {
            error_log('Error getting file stats: ' . $e->getMessage());
        }
        
        return $stats;
    }
    
    private function getDirectoryUsage() {
        $directories = [];
        $uploadDirs = [
            'logos' => 'Loghi Ristoranti',
            'banners' => 'Banner',
            'categories' => 'Categorie Menu',
            'menu-items' => 'Piatti',
            'fonts' => 'Font Personalizzati'
        ];
        
        foreach ($uploadDirs as $dir => $name) {
            $dirPath = UPLOADS_PATH . $dir . '/';
            if (is_dir($dirPath)) {
                $files = glob($dirPath . '*');
                $fileCount = count(array_filter($files, 'is_file'));
                $totalSize = 0;
                
                foreach ($files as $file) {
                    if (is_file($file)) {
                        $totalSize += filesize($file);
                    }
                }
                
                $directories[] = [
                    'name' => $name,
                    'directory' => $dir,
                    'file_count' => $fileCount,
                    'total_size' => $totalSize
                ];
            }
        }
        
        return $directories;
    }
    
    private function getFileTypes() {
        $types = [];
        $extensions = [];
        
        $uploadDirs = ['logos', 'banners', 'categories', 'menu-items', 'fonts'];
        
        foreach ($uploadDirs as $dir) {
            $dirPath = UPLOADS_PATH . $dir . '/';
            if (is_dir($dirPath)) {
                $files = glob($dirPath . '*');
                
                foreach ($files as $file) {
                    if (is_file($file)) {
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        if (!isset($extensions[$ext])) {
                            $extensions[$ext] = [
                                'file_count' => 0,
                                'total_size' => 0
                            ];
                        }
                        
                        $extensions[$ext]['file_count']++;
                        $extensions[$ext]['total_size'] += filesize($file);
                    }
                }
            }
        }
        
        foreach ($extensions as $ext => $data) {
            $types[] = [
                'extension' => $ext,
                'file_count' => $data['file_count'],
                'total_size' => $data['total_size']
            ];
        }
        
        // Sort by total size descending
        usort($types, function($a, $b) {
            return $b['total_size'] - $a['total_size'];
        });
        
        return array_slice($types, 0, 10); // Top 10
    }
    
    private function getRecentFiles($limit = 20) {
        $files = [];
        $uploadDirs = ['logos', 'banners', 'categories', 'menu-items', 'fonts'];
        
        foreach ($uploadDirs as $dir) {
            $dirPath = UPLOADS_PATH . $dir . '/';
            if (is_dir($dirPath)) {
                $dirFiles = glob($dirPath . '*');
                
                foreach ($dirFiles as $file) {
                    if (is_file($file)) {
                        $files[] = [
                            'filepath' => $file,
                            'filename' => basename($file),
                            'directory' => $dir,
                            'size' => filesize($file),
                            'extension' => strtolower(pathinfo($file, PATHINFO_EXTENSION)),
                            'created_at' => date('Y-m-d H:i:s', filemtime($file))
                        ];
                    }
                }
            }
        }
        
        // Sort by modification time descending
        usort($files, function($a, $b) {
            return filemtime($b['filepath']) - filemtime($a['filepath']);
        });
        
        return array_slice($files, 0, $limit);
    }
    
    private function countOrphanedFiles() {
        $orphanedCount = 0;
        $uploadDirs = ['logos', 'banners', 'categories', 'menu-items', 'fonts'];
        
        foreach ($uploadDirs as $dir) {
            $dirPath = UPLOADS_PATH . $dir . '/';
            if (is_dir($dirPath)) {
                $files = glob($dirPath . '*');
                
                foreach ($files as $file) {
                    if (is_file($file)) {
                        $filename = basename($file);
                        if (!$this->isFileReferencedInDatabase($filename, $dir)) {
                            $orphanedCount++;
                        }
                    }
                }
            }
        }
        
        return $orphanedCount;
    }
    
    private function scanOrphanedFiles() {
        $orphanedFiles = [];
        $uploadDirs = ['logos', 'banners', 'categories', 'menu-items', 'fonts', 'general'];
        
        foreach ($uploadDirs as $dir) {
            $dirPath = UPLOADS_PATH . $dir . '/';
            if (is_dir($dirPath)) {
                $this->scanDirectoryForOrphans($dirPath, $dir, $orphanedFiles);
            }
        }
        
        // Also scan root uploads directory
        $this->scanDirectoryForOrphans(UPLOADS_PATH, '', $orphanedFiles);
        
        // Sort by size descending
        usort($orphanedFiles, function($a, $b) {
            return $b['size'] - $a['size'];
        });
        
        return $orphanedFiles;
    }
    
    private function scanDirectoryForOrphans($dirPath, $dir, &$orphanedFiles) {
        if (!is_dir($dirPath)) return;
        
        $files = scandir($dirPath);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $filePath = $dirPath . $file;
            if (is_file($filePath)) {
                $relativePath = str_replace(UPLOADS_PATH, '', $filePath);
                if (!$this->isFileReferencedInDatabase($file, $dir, $relativePath)) {
                    $orphanedFiles[] = [
                        'filepath' => $filePath,
                        'filename' => $file,
                        'directory' => $dir ?: 'root',
                        'relative_path' => $relativePath,
                        'size' => filesize($filePath),
                        'modified' => date('Y-m-d H:i:s', filemtime($filePath)),
                        'extension' => strtolower(pathinfo($file, PATHINFO_EXTENSION))
                    ];
                }
            }
        }
    }
    
    private function cleanupOrphanedFiles() {
        $orphanedFiles = $this->scanOrphanedFiles();
        $deletedCount = 0;
        $deletedSize = 0;
        $errors = [];
        
        foreach ($orphanedFiles as $file) {
            try {
                if (file_exists($file['filepath']) && unlink($file['filepath'])) {
                    $deletedCount++;
                    $deletedSize += $file['size'];
                    
                    // Log the deletion
                    $this->logActivity('file_deleted', 'super_admin', null, [
                        'filename' => $file['filename'],
                        'directory' => $file['directory'],
                        'size' => $file['size'],
                        'reason' => 'orphaned_cleanup'
                    ]);
                } else {
                    $errors[] = 'Impossibile eliminare: ' . $file['filename'];
                }
            } catch (Exception $e) {
                $errors[] = 'Errore eliminando ' . $file['filename'] . ': ' . $e->getMessage();
            }
        }
        
        return [
            'success' => true,
            'deleted_count' => $deletedCount,
            'deleted_size' => $deletedSize,
            'errors' => $errors
        ];
    }
    
    private function scanDuplicateFiles() {
        $duplicates = [];
        $fileHashes = [];
        $uploadDirs = ['logos', 'banners', 'categories', 'menu-items', 'fonts'];
        
        // First pass: collect all files and their hashes
        foreach ($uploadDirs as $dir) {
            $dirPath = UPLOADS_PATH . $dir . '/';
            if (is_dir($dirPath)) {
                $files = glob($dirPath . '*');
                
                foreach ($files as $file) {
                    if (is_file($file)) {
                        $hash = md5_file($file);
                        $filename = basename($file);
                        
                        if (!isset($fileHashes[$hash])) {
                            $fileHashes[$hash] = [];
                        }
                        
                        $fileHashes[$hash][] = [
                            'filepath' => $file,
                            'filename' => $filename,
                            'directory' => $dir,
                            'size' => filesize($file),
                            'modified' => date('Y-m-d H:i:s', filemtime($file))
                        ];
                    }
                }
            }
        }
        
        // Second pass: find duplicates (groups with more than one file)
        foreach ($fileHashes as $hash => $files) {
            if (count($files) > 1) {
                $duplicates[] = [
                    'hash' => $hash,
                    'files' => $files,
                    'total_size' => array_sum(array_column($files, 'size')),
                    'wasted_space' => (count($files) - 1) * $files[0]['size']
                ];
            }
        }
        
        // Sort by wasted space descending
        usort($duplicates, function($a, $b) {
            return $b['wasted_space'] - $a['wasted_space'];
        });
        
        return $duplicates;
    }
    
    private function browseFiles($directory = '') {
        try {
            $files = [];
            $basePath = UPLOADS_PATH;
        
        if ($directory) {
            $dirPath = $basePath . $directory . '/';
        } else {
            $dirPath = $basePath;
        }
        
        if (!is_dir($dirPath)) {
            return [];
        }
        
        // Security check
        $realDirPath = realpath($dirPath);
        $realBasePath = realpath($basePath);
        if (!$realDirPath || !$realBasePath || strpos($realDirPath, $realBasePath) !== 0) {
            return [];
        }
        
        $items = scandir($dirPath);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            
            $itemPath = $dirPath . $item;
            $relativePath = ($directory ? $directory . '/' : '') . $item;
            
            if (is_dir($itemPath)) {
                $files[] = [
                    'name' => $item,
                    'type' => 'directory',
                    'path' => $relativePath,
                    'size' => $this->getDirectorySize($itemPath),
                    'modified' => date('Y-m-d H:i:s', filemtime($itemPath)),
                    'file_count' => count(glob($itemPath . '/*'))
                ];
            } else {
                $mimeType = 'unknown';
                try {
                    $mimeType = mime_content_type($itemPath) ?: 'unknown';
                } catch (Exception $e) {
                    // Fallback se mime_content_type fallisce
                    $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                    $mimeMap = [
                        'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
                        'gif' => 'image/gif', 'webp' => 'image/webp', 'svg' => 'image/svg+xml',
                        'txt' => 'text/plain', 'json' => 'application/json', 'css' => 'text/css',
                        'js' => 'application/javascript', 'pdf' => 'application/pdf'
                    ];
                    $mimeType = $mimeMap[$ext] ?? 'application/octet-stream';
                }
                
                $files[] = [
                    'name' => $item,
                    'type' => 'file',
                    'path' => $relativePath,
                    'size' => filesize($itemPath),
                    'modified' => date('Y-m-d H:i:s', filemtime($itemPath)),
                    'extension' => strtolower(pathinfo($item, PATHINFO_EXTENSION)),
                    'mime_type' => $mimeType
                ];
            }
        }
        
        // Sort: directories first, then by name
        usort($files, function($a, $b) {
            if ($a['type'] === 'directory' && $b['type'] === 'file') return -1;
            if ($a['type'] === 'file' && $b['type'] === 'directory') return 1;
            return strcasecmp($a['name'], $b['name']);
        });
        
        return $files;
        
        } catch (Exception $e) {
            error_log('Browse files error: ' . $e->getMessage());
            return [];
        }
    }
    
    private function deleteFile($filepath) {
        try {
            $basePath = UPLOADS_PATH;
            $fullPath = $basePath . $filepath;
            
            // Security check
            if (strpos(realpath(dirname($fullPath)), realpath($basePath)) !== 0) {
                throw new Exception('Percorso non valido');
            }
            
            if (!file_exists($fullPath)) {
                throw new Exception('File non trovato');
            }
            
            if (!is_file($fullPath)) {
                throw new Exception('Solo i file possono essere eliminati');
            }
            
            $size = filesize($fullPath);
            
            if (unlink($fullPath)) {
                return [
                    'success' => true,
                    'message' => 'File eliminato con successo',
                    'size_freed' => $size
                ];
            } else {
                throw new Exception('Impossibile eliminare il file');
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function previewFile($filepath) {
        try {
            $basePath = UPLOADS_PATH;
            $fullPath = $basePath . $filepath;
            
            // Security check
            if (strpos(realpath(dirname($fullPath)), realpath($basePath)) !== 0) {
                throw new Exception('Percorso non valido');
            }
            
            if (!file_exists($fullPath)) {
                throw new Exception('File non trovato');
            }
            
            $info = [
                'name' => basename($filepath),
                'path' => $filepath,
                'size' => filesize($fullPath),
                'modified' => date('Y-m-d H:i:s', filemtime($fullPath)),
                'extension' => strtolower(pathinfo($filepath, PATHINFO_EXTENSION)),
                'mime_type' => mime_content_type($fullPath)
            ];
            
            // Add preview content for supported types
            $extension = $info['extension'];
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $info['preview_type'] = 'image';
                $info['preview_url'] = UPLOADS_URL . $filepath;
            } elseif (in_array($extension, ['txt', 'md', 'json', 'css', 'js'])) {
                $info['preview_type'] = 'text';
                $info['content'] = file_get_contents($fullPath, false, null, 0, 5000); // First 5KB
            } else {
                $info['preview_type'] = 'none';
            }
            
            return [
                'success' => true,
                'file_info' => $info
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function getDirectorySize($dir) {
        $size = 0;
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                $size += filesize($file);
            } elseif (is_dir($file)) {
                $size += $this->getDirectorySize($file);
            }
        }
        return $size;
    }

    private function testSmtpConnection($config) {
        // Placeholder implementation - would test SMTP connection
        return ['success' => true, 'message' => 'Test connessione simulato'];
    }
    
    // Enhanced method to check if a file is referenced in database
    private function isFileReferencedInDatabase($filename, $directory, $relativePath = null) {
        try {
            // Build the expected path patterns based on how files are stored
            $possiblePaths = [];
            
            if ($directory) {
                // Standard directory/filename pattern
                $possiblePaths[] = $directory . '/' . $filename;
            }
            
            if ($relativePath) {
                // Use the full relative path
                $possiblePaths[] = ltrim($relativePath, '/');
            }
            
            // Also try just the filename (for older entries)
            $possiblePaths[] = $filename;
            
            // Search in all database columns with exact matching
            foreach ($possiblePaths as $searchPath) {
                
                // Restaurant logos and cover images
                $result = $this->db->selectOne(
                    "SELECT id FROM restaurants WHERE logo_url = ? OR cover_image_url = ? OR logo_url = ? OR cover_image_url = ?",
                    [$searchPath, $searchPath, '/' . $searchPath, '/' . $searchPath]
                );
                if ($result) return true;
                
                // Menu categories
                $result = $this->db->selectOne(
                    "SELECT id FROM menu_categories WHERE image_url = ? OR image_url = ?",
                    [$searchPath, '/' . $searchPath]
                );
                if ($result) return true;
                
                // Menu items  
                $result = $this->db->selectOne(
                    "SELECT id FROM menu_items WHERE image_url = ? OR image_url = ?",
                    [$searchPath, '/' . $searchPath]
                );
                if ($result) return true;
                
                // System settings
                $result = $this->db->selectOne(
                    "SELECT id FROM system_settings WHERE setting_value = ? OR setting_value = ?",
                    [$searchPath, '/' . $searchPath]
                );
                if ($result) return true;
                
                // Fonts in theme settings (JSON)
                $result = $this->db->selectOne(
                    "SELECT id FROM restaurants WHERE JSON_EXTRACT(theme_settings, '$.custom_font_url') = ? OR JSON_EXTRACT(theme_settings, '$.custom_font_url') = ?",
                    [$searchPath, '/' . $searchPath]
                );
                if ($result) return true;
                
                // Also check with LIKE for partial matches (ending with the path)
                $result = $this->db->selectOne(
                    "SELECT id FROM restaurants WHERE logo_url LIKE ? OR cover_image_url LIKE ?",
                    ['%' . $searchPath, '%' . $searchPath]
                );
                if ($result) return true;
                
                $result = $this->db->selectOne(
                    "SELECT id FROM menu_categories WHERE image_url LIKE ?",
                    ['%' . $searchPath]
                );
                if ($result) return true;
                
                $result = $this->db->selectOne(
                    "SELECT id FROM menu_items WHERE image_url LIKE ?",
                    ['%' . $searchPath]
                );
                if ($result) return true;
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log('Error checking file reference for ' . $filename . ': ' . $e->getMessage());
            // If we can't check, assume it's NOT referenced to find more orphans
            return false;
        }
    }
    
    private function countTotalFiles() {
        $count = 0;
        $uploadDirs = ['logos', 'banners', 'categories', 'menu-items', 'fonts', 'general'];
        
        // Count files in subdirectories
        foreach ($uploadDirs as $dir) {
            $dirPath = UPLOADS_PATH . $dir . '/';
            if (is_dir($dirPath)) {
                $files = glob($dirPath . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        $count++;
                    }
                }
            }
        }
        
        // Count files in root uploads directory
        $rootFiles = glob(UPLOADS_PATH . '*');
        foreach ($rootFiles as $file) {
            if (is_file($file)) {
                $count++;
            }
        }
        
        return $count;
    }
    
    private function getTotalFilesSize() {
        return $this->getDirectorySize(UPLOADS_PATH);
    }
    
    private function countDirectories() {
        $count = 0;
        $uploadDirs = ['logos', 'banners', 'categories', 'menu-items', 'fonts', 'general'];
        
        foreach ($uploadDirs as $dir) {
            if (is_dir(UPLOADS_PATH . $dir)) {
                $count++;
            }
        }
        
        return $count;
    }
    
    private function bulkDeleteFiles($filepaths) {
        $deletedCount = 0;
        $errors = [];
        $totalSize = 0;
        
        foreach ($filepaths as $filepath) {
            try {
                $fullPath = UPLOADS_PATH . $filepath;
                
                // Security check
                if (strpos(realpath(dirname($fullPath)), realpath(UPLOADS_PATH)) !== 0) {
                    $errors[] = 'Percorso non valido: ' . $filepath;
                    continue;
                }
                
                if (file_exists($fullPath) && is_file($fullPath)) {
                    $size = filesize($fullPath);
                    if (unlink($fullPath)) {
                        $deletedCount++;
                        $totalSize += $size;
                        
                        // Log the deletion
                        $this->logActivity('file_deleted', 'super_admin', null, [
                            'filepath' => $filepath,
                            'size' => $size,
                            'reason' => 'bulk_delete'
                        ]);
                    } else {
                        $errors[] = 'Impossibile eliminare: ' . $filepath;
                    }
                } else {
                    $errors[] = 'File non trovato: ' . $filepath;
                }
                
            } catch (Exception $e) {
                $errors[] = 'Errore eliminando ' . $filepath . ': ' . $e->getMessage();
            }
        }
        
        return [
            'success' => true,
            'deleted_count' => $deletedCount,
            'total_size' => $totalSize,
            'errors' => $errors
        ];
    }

    /**
     * Handle database query execution requests
     * This method is called by the frontend JavaScript to execute SQL queries safely
     */
    public function databaseQuery() {
        $this->auth->requireSuperAdmin();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
            return;
        }
        
        try {
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['success' => false, 'error' => 'JSON non valido: ' . json_last_error_msg()]);
                return;
            }
            
            $query = trim($input['query'] ?? '');
            $csrfToken = $input['csrf_token'] ?? '';
            
            // Validate CSRF token
            if (!$this->validateCsrf($csrfToken)) {
                echo json_encode(['success' => false, 'error' => 'Token CSRF non valido']);
                return;
            }
            
            if (empty($query)) {
                echo json_encode(['success' => false, 'error' => 'Query mancante']);
                return;
            }
            
            // Execute the query using the existing method
            $result = $this->executeQuery($query);
            echo json_encode($result);
            
            // Log the query execution
            $this->logActivity('database_query_executed', 'super_admin', null, [
                'query' => substr($query, 0, 200), // Log first 200 chars for security
                'success' => $result['success'] ?? false
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Handle database maintenance operations
     * This method is called by the frontend JavaScript to perform maintenance tasks
     */
    public function databaseMaintenance() {
        $this->auth->requireSuperAdmin();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Metodo non consentito']);
            return;
        }
        
        try {
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['success' => false, 'error' => 'JSON non valido: ' . json_last_error_msg()]);
                return;
            }
            
            $operation = $input['operation'] ?? '';
            $csrfToken = $input['csrf_token'] ?? '';
            
            // Validate CSRF token
            if (!$this->validateCsrf($csrfToken)) {
                echo json_encode(['success' => false, 'error' => 'Token CSRF non valido']);
                return;
            }
            
            if (empty($operation)) {
                echo json_encode(['success' => false, 'error' => 'Operazione mancante']);
                return;
            }
            
            // Perform the requested maintenance operation
            $result = $this->performMaintenanceOperation($operation);
            echo json_encode($result);
            
            // Log the maintenance operation
            $this->logActivity('database_maintenance', 'super_admin', null, [
                'operation' => $operation,
                'success' => $result['success'] ?? false
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Perform database maintenance operations safely
     */
    private function performMaintenanceOperation($operation) {
        try {
            switch ($operation) {
                case 'optimize':
                    return $this->optimizeAllTables();
                    
                case 'cleanup':
                    return $this->cleanupDatabase();
                    
                case 'repair':
                    return $this->repairAllTables();
                    
                default:
                    throw new Exception('Operazione di manutenzione non riconosciuta: ' . $operation);
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Optimize all database tables
     */
    private function optimizeAllTables() {
        try {
            $tables = $this->db->select("SHOW TABLES");
            $optimizedTables = [];
            $errors = [];
            
            foreach ($tables as $table) {
                $tableName = array_values($table)[0];
                
                try {
                    $this->db->query("OPTIMIZE TABLE `$tableName`");
                    $optimizedTables[] = $tableName;
                } catch (Exception $e) {
                    $errors[] = "Errore ottimizzando $tableName: " . $e->getMessage();
                }
            }
            
            return [
                'success' => true,
                'message' => 'Ottimizzazione completata',
                'optimized_tables' => count($optimizedTables),
                'total_tables' => count($tables),
                'errors' => $errors
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Errore durante l\'ottimizzazione: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Clean up database by removing old logs and temporary data
     */
    private function cleanupDatabase() {
        try {
            $cleanupActions = [];
            $errors = [];
            
            // Clean old activity logs (older than 90 days)
            try {
                $stmt = $this->db->query("DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)");
                $deletedLogs = $stmt->rowCount();
                $cleanupActions[] = "Eliminati $deletedLogs log di attività vecchi";
            } catch (Exception $e) {
                $errors[] = "Errore pulizia log: " . $e->getMessage();
            }
            
            // Clean up any session data that might be stored (if table exists)
            try {
                $this->db->query("DELETE FROM sessions WHERE last_activity < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR))");
                $cleanupActions[] = "Sessioni scadute rimosse";
            } catch (Exception $e) {
                // Table might not exist, ignore this error
            }
            
            // Optimize tables after cleanup
            try {
                $tables = $this->db->select("SHOW TABLES");
                foreach ($tables as $table) {
                    $tableName = array_values($table)[0];
                    $this->db->query("OPTIMIZE TABLE `$tableName`");
                }
                $cleanupActions[] = "Tabelle ottimizzate dopo la pulizia";
            } catch (Exception $e) {
                $errors[] = "Errore ottimizzazione post-pulizia: " . $e->getMessage();
            }
            
            return [
                'success' => true,
                'message' => 'Pulizia database completata',
                'actions_performed' => $cleanupActions,
                'errors' => $errors
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Errore durante la pulizia: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Repair all database tables
     */
    private function repairAllTables() {
        try {
            $tables = $this->db->select("SHOW TABLES");
            $repairedTables = [];
            $errors = [];
            
            foreach ($tables as $table) {
                $tableName = array_values($table)[0];
                
                try {
                    // Check table first
                    $checkResult = $this->db->select("CHECK TABLE `$tableName`");
                    $needsRepair = false;
                    
                    foreach ($checkResult as $result) {
                        if (isset($result['Msg_text']) && 
                            (strpos($result['Msg_text'], 'corrupt') !== false || 
                             strpos($result['Msg_text'], 'error') !== false)) {
                            $needsRepair = true;
                            break;
                        }
                    }
                    
                    if ($needsRepair) {
                        $this->db->query("REPAIR TABLE `$tableName`");
                        $repairedTables[] = $tableName;
                    }
                } catch (Exception $e) {
                    $errors[] = "Errore riparando $tableName: " . $e->getMessage();
                }
            }
            
            return [
                'success' => true,
                'message' => 'Controllo e riparazione completati',
                'repaired_tables' => count($repairedTables),
                'tables_needing_repair' => $repairedTables,
                'total_tables_checked' => count($tables),
                'errors' => $errors
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Errore durante la riparazione: ' . $e->getMessage()
            ];
        }
    }

    // Helper method for logging activities
    private function logActivity($action, $userType, $restaurantId = null, $details = []) {
        try {
            $this->db->insert(
                "INSERT INTO activity_logs (user_type, restaurant_id, action, details, ip_address, user_agent, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, NOW())",
                [
                    $userType,
                    $restaurantId,
                    $action,
                    json_encode($details),
                    $_SERVER['REMOTE_ADDR'] ?? '',
                    $_SERVER['HTTP_USER_AGENT'] ?? ''
                ]
            );
        } catch (Exception $e) {
            error_log('Failed to log activity: ' . $e->getMessage());
        }
    }
}
?>