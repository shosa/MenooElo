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
             LEFT JOIN restaurant_admins ra ON r.id = ra.restaurant_id 
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
            'title' => 'Super Admin Dashboard - MenooElo',
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
            'title' => 'Super Admin Login - MenooElo',
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
             LEFT JOIN restaurant_admins ra ON r.id = ra.restaurant_id 
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
            'title' => 'Gestione Ristoranti - MenooElo',
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
            'title' => 'Aggiungi Ristorante - MenooElo',
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
            'title' => 'Modifica Ristorante - MenooElo',
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
                    
                    // Handle special conversions
                    if (isset($_POST['settings']['session_timeout_seconds'])) {
                        $settings['session_timeout'] = (int)$_POST['settings']['session_timeout_seconds'];
                    }
                    
                    if (isset($_POST['settings']['max_image_size'])) {
                        $settings['max_image_size'] = (int)$_POST['settings']['max_image_size'];
                    }
                    
                    if (isset($_POST['settings']['allowed_image_formats'])) {
                        $settings['allowed_image_formats'] = $_POST['settings']['allowed_image_formats'];
                    }
                    
                    // Remove temporary fields
                    unset($settings['max_image_size_mb']);
                    unset($settings['session_timeout_seconds']);
                    unset($settings['allowed_formats']);
                    
                    foreach ($settings as $key => $value) {
                        $this->db->query(
                            "INSERT INTO system_settings (setting_key, setting_value) 
                             VALUES (?, ?) 
                             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)",
                            [$key, $value]
                        );
                    }
                    
                    $success = 'Impostazioni aggiornate con successo';
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        $settings = $this->db->select("SELECT * FROM system_settings ORDER BY setting_key");
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting['setting_key']] = $setting['setting_value'];
        }
        
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('superadmin/settings', [
            'title' => 'Impostazioni Sistema - MenooElo',
            'settings' => $settingsArray,
            'error' => $error ?? null,
            'success' => $success ?? null,
            'csrf_token' => $csrf_token
        ]);
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
}
?>