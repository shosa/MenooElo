<?php
require_once 'includes/BaseController.php';

class AdminController extends BaseController {
    
    public function dashboard() {
        $this->auth->requireRestaurantAdmin();
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        $stats = [
            'total_categories' => $this->db->selectOne(
                "SELECT COUNT(*) as count FROM menu_categories WHERE restaurant_id = ?", 
                [$restaurantId]
            )['count'],
            'total_items' => $this->db->selectOne(
                "SELECT COUNT(*) as count FROM menu_items WHERE restaurant_id = ?", 
                [$restaurantId]
            )['count'],
            'active_items' => $this->db->selectOne(
                "SELECT COUNT(*) as count FROM menu_items WHERE restaurant_id = ? AND is_available = 1", 
                [$restaurantId]
            )['count'],
            'featured_items' => $this->db->selectOne(
                "SELECT COUNT(*) as count FROM menu_items WHERE restaurant_id = ? AND is_featured = 1", 
                [$restaurantId]
            )['count']
        ];
        
        $recent_items = $this->db->select(
            "SELECT mi.*, mc.name as category_name 
             FROM menu_items mi 
             JOIN menu_categories mc ON mi.category_id = mc.id 
             WHERE mi.restaurant_id = ? 
             ORDER BY mi.created_at DESC 
             LIMIT 10",
            [$restaurantId]
        );
        
        $restaurant = $this->db->selectOne(
            "SELECT * FROM restaurants WHERE id = ?", 
            [$restaurantId]
        );
        
        $this->loadView('admin/dashboard', [
            'title' => 'Dashboard Admin - ' . $restaurant['name'],
            'restaurant' => $restaurant,
            'stats' => $stats,
            'recent_items' => $recent_items
        ]);
    }
    
    public function login() {
        if ($this->auth->isRestaurantAdmin()) {
            $this->redirect('/admin');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->sanitizeInput($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } elseif (empty($username) || empty($password)) {
                $error = 'Username e password sono obbligatori';
            } elseif ($this->auth->loginRestaurantAdmin($username, $password)) {
                $this->redirect('/admin');
            } else {
                $error = 'Credenziali non valide';
            }
        }
        
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('admin/login', [
            'title' => 'Login Admin - ' . SystemSettings::getAppName(),
            'error' => $error ?? null,
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function logout() {
        $this->auth->logout();
        $this->redirect('/admin/login');
    }
    
    public function categories() {
        $this->auth->requireRestaurantAdmin();
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        $categories = $this->db->select(
            "SELECT mc.*, COUNT(mi.id) as items_count 
             FROM menu_categories mc 
             LEFT JOIN menu_items mi ON mc.id = mi.category_id 
             WHERE mc.restaurant_id = ? 
             GROUP BY mc.id 
             ORDER BY mc.sort_order ASC",
            [$restaurantId]
        );
        
        $restaurant = $this->db->selectOne("SELECT name FROM restaurants WHERE id = ?", [$restaurantId]);
        
        $this->loadView('admin/categories', [
            'title' => 'Gestione Categorie - ' . $restaurant['name'],
            'categories' => $categories
        ]);
    }
    
    public function categoryAdd() {
        $this->auth->requireRestaurantAdmin();
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } else {
                try {
                    $data = $this->sanitizeInput($_POST);
                    
                    $imageUrl = null;
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $imageUrl = $this->uploadImage($_FILES['image'], 'categories');
                    }
                    
                    $maxOrder = $this->db->selectOne(
                        "SELECT COALESCE(MAX(sort_order), 0) as max_order FROM menu_categories WHERE restaurant_id = ?",
                        [$restaurantId]
                    )['max_order'];
                    
                    $this->db->insert(
                        "INSERT INTO menu_categories (restaurant_id, name, description, image_url, sort_order, is_active) 
                         VALUES (?, ?, ?, ?, ?, ?)",
                        [
                            $restaurantId,
                            $data['name'],
                            $data['description'] ?? null,
                            $imageUrl,
                            $maxOrder + 1,
                            isset($data['is_active']) ? 1 : 0
                        ]
                    );
                    
                    $this->redirect('/admin/categories?success=created');
                    
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('admin/category-form', [
            'title' => 'Aggiungi Categoria',
            'category' => null,
            'error' => $error ?? null,
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function menuItems($categoryId = null) {
        $this->auth->requireRestaurantAdmin();
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        // Handle POST actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } else {
                try {
                    $action = $_POST['action'] ?? '';
                    
                    if ($action === 'toggle_availability' && isset($_POST['item_id'])) {
                        $itemId = $_POST['item_id'];
                        $isAvailable = $_POST['is_available'] === 'true' ? 1 : 0;
                        
                        // Check if item belongs to restaurant
                        $item = $this->db->selectOne(
                            "SELECT id FROM menu_items WHERE id = ? AND restaurant_id = ?",
                            [$itemId, $restaurantId]
                        );
                        
                        if ($item) {
                            $this->db->update(
                                "UPDATE menu_items SET is_available = ? WHERE id = ?",
                                [$isAvailable, $itemId]
                            );
                            $success = 'Stato del piatto aggiornato con successo';
                        } else {
                            $error = 'Piatto non trovato';
                        }
                    } elseif ($action === 'delete' && isset($_POST['item_id'])) {
                        $itemId = $_POST['item_id'];
                        
                        // Check if item belongs to restaurant
                        $item = $this->db->selectOne(
                            "SELECT * FROM menu_items WHERE id = ? AND restaurant_id = ?",
                            [$itemId, $restaurantId]
                        );
                        
                        if ($item) {
                            $this->db->beginTransaction();
                            
                            // Delete variants and extras first
                            $this->db->delete("DELETE FROM menu_item_variants WHERE item_id = ?", [$itemId]);
                            $this->db->delete("DELETE FROM menu_item_extras WHERE item_id = ?", [$itemId]);
                            
                            // Delete image if exists
                            if ($item['image_url'] && file_exists(UPLOADS_PATH . $item['image_url'])) {
                                unlink(UPLOADS_PATH . $item['image_url']);
                            }
                            
                            // Delete item
                            $this->db->delete("DELETE FROM menu_items WHERE id = ?", [$itemId]);
                            
                            $this->db->commit();
                            $success = 'Piatto eliminato con successo';
                        } else {
                            $error = 'Piatto non trovato';
                        }
                    }
                } catch (Exception $e) {
                    $this->db->rollback();
                    $error = 'Errore durante l\'operazione: ' . $e->getMessage();
                }
            }
        }
        
        $categories = $this->db->select(
            "SELECT * FROM menu_categories WHERE restaurant_id = ? ORDER BY sort_order ASC",
            [$restaurantId]
        );
        
        $whereClause = "mi.restaurant_id = ?";
        $params = [$restaurantId];
        
        if ($categoryId && is_numeric($categoryId)) {
            $whereClause .= " AND mi.category_id = ?";
            $params[] = $categoryId;
        }
        
        $items = $this->db->select(
            "SELECT mi.*, mc.name as category_name,
                    COUNT(miv.id) as variants_count,
                    COUNT(mie.id) as extras_count
             FROM menu_items mi 
             JOIN menu_categories mc ON mi.category_id = mc.id 
             LEFT JOIN menu_item_variants miv ON mi.id = miv.item_id
             LEFT JOIN menu_item_extras mie ON mi.id = mie.item_id
             WHERE $whereClause
             GROUP BY mi.id
             ORDER BY mc.sort_order ASC, mi.sort_order ASC",
            $params
        );
        
        $selectedCategory = null;
        if ($categoryId) {
            $selectedCategory = $this->db->selectOne(
                "SELECT * FROM menu_categories WHERE id = ? AND restaurant_id = ?",
                [$categoryId, $restaurantId]
            );
        }
        
        $restaurant = $this->db->selectOne("SELECT name FROM restaurants WHERE id = ?", [$restaurantId]);
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('admin/menu-items', [
            'title' => 'Gestione Menu - ' . $restaurant['name'],
            'items' => $items,
            'categories' => $categories,
            'selected_category' => $selectedCategory,
            'category_id' => $categoryId,
            'error' => $error ?? null,
            'success' => $success ?? null,
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function itemAdd($categoryId = null) {
        $this->auth->requireRestaurantAdmin();
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        $categories = $this->db->select(
            "SELECT * FROM menu_categories WHERE restaurant_id = ? ORDER BY sort_order ASC",
            [$restaurantId]
        );
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } else {
                try {
                    $data = $this->sanitizeInput($_POST);
                    
                    $imageUrl = null;
                    
                    // Handle regular file upload
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $imageUrl = $this->uploadImage($_FILES['image'], 'menu-items');
                    }
                    // Handle suggested image selection (external images)
                    elseif (isset($data['selected_suggestion_image']) && !empty($data['selected_suggestion_image'])) {
                        $imageData = json_decode($data['selected_suggestion_image'], true);
                        if ($imageData && isset($imageData['url'])) {
                            $imageUrl = $imageData['url']; // URL diretto (hotlinking)
                        }
                    }
                    
                    $maxOrder = $this->db->selectOne(
                        "SELECT COALESCE(MAX(sort_order), 0) as max_order 
                         FROM menu_items 
                         WHERE category_id = ?",
                        [$data['category_id']]
                    )['max_order'];
                    
                    $allergens = [];
                    if (isset($data['allergens']) && is_array($data['allergens'])) {
                        $allergens = $data['allergens'];
                    }
                    
                    $itemId = $this->db->insert(
                        "INSERT INTO menu_items 
                         (restaurant_id, category_id, name, description, price, image_url, 
                          ingredients, allergens, is_available, is_featured, sort_order) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                        [
                            $restaurantId,
                            $data['category_id'],
                            $data['name'],
                            $data['description'] ?? null,
                            $data['price'],
                            $imageUrl,
                            $data['ingredients'] ?? null,
                            json_encode($allergens),
                            isset($data['is_available']) ? 1 : 0,
                            isset($data['is_featured']) ? 1 : 0,
                            $maxOrder + 1
                        ]
                    );
                    
                    if (isset($data['variants']) && is_array($data['variants'])) {
                        foreach ($data['variants'] as $index => $variant) {
                            if ($variant['name']) {
                                $this->db->insert(
                                    "INSERT INTO menu_item_variants (item_id, name, price_modifier, is_default, sort_order) 
                                     VALUES (?, ?, ?, ?, ?)",
                                    [
                                        $itemId,
                                        $variant['name'],
                                        $variant['price_modifier'] ?? 0,
                                        isset($variant['is_default']) ? 1 : 0,
                                        $index
                                    ]
                                );
                            }
                        }
                    }
                    
                    if (isset($data['extras']) && is_array($data['extras'])) {
                        foreach ($data['extras'] as $index => $extra) {
                            if ($extra['name']) {
                                $this->db->insert(
                                    "INSERT INTO menu_item_extras (item_id, name, price, sort_order) 
                                     VALUES (?, ?, ?, ?)",
                                    [
                                        $itemId,
                                        $extra['name'],
                                        $extra['price'] ?? 0,
                                        $index
                                    ]
                                );
                            }
                        }
                    }
                    
                    $this->redirect('/admin/menu?success=created');
                    
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('admin/item-form', [
            'title' => 'Aggiungi Piatto',
            'item' => null,
            'categories' => $categories,
            'selected_category_id' => $categoryId,
            'error' => $error ?? null,
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function categoryEdit($id) {
        $this->auth->requireRestaurantAdmin();
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        $category = $this->db->selectOne(
            "SELECT * FROM menu_categories WHERE id = ? AND restaurant_id = ?",
            [$id, $restaurantId]
        );
        
        if (!$category) {
            $this->redirect('/admin/categories?error=not_found');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } else {
                try {
                    $data = $this->sanitizeInput($_POST);
                    
                    $imageUrl = $category['image_url'];
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $imageUrl = $this->uploadImage($_FILES['image'], 'categories');
                        if ($category['image_url'] && file_exists(UPLOADS_PATH . $category['image_url'])) {
                            unlink(UPLOADS_PATH . $category['image_url']);
                        }
                    }
                    
                    $this->db->update(
                        "UPDATE menu_categories SET name = ?, description = ?, image_url = ?, is_active = ? WHERE id = ?",
                        [
                            $data['name'],
                            $data['description'] ?? null,
                            $imageUrl,
                            isset($data['is_active']) ? 1 : 0,
                            $id
                        ]
                    );
                    
                    $this->redirect('/admin/categories?success=updated');
                    
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('admin/category-form', [
            'title' => 'Modifica Categoria',
            'category' => $category,
            'error' => $error ?? null,
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function categoryDelete($id) {
        $this->auth->requireRestaurantAdmin();
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        $category = $this->db->selectOne(
            "SELECT * FROM menu_categories WHERE id = ? AND restaurant_id = ?",
            [$id, $restaurantId]
        );
        
        if (!$category) {
            $this->redirect('/admin/categories?error=not_found');
        }
        
        $itemsCount = $this->db->selectOne(
            "SELECT COUNT(*) as count FROM menu_items WHERE category_id = ?",
            [$id]
        )['count'];
        
        if ($itemsCount > 0) {
            $this->redirect('/admin/categories?error=has_items');
        }
        
        try {
            if ($category['image_url'] && file_exists(UPLOADS_PATH . $category['image_url'])) {
                unlink(UPLOADS_PATH . $category['image_url']);
            }
            
            $this->db->delete("DELETE FROM menu_categories WHERE id = ?", [$id]);
            $this->redirect('/admin/categories?success=deleted');
            
        } catch (Exception $e) {
            $this->redirect('/admin/categories?error=delete_failed');
        }
    }
    
    public function itemEdit($id) {
        $this->auth->requireRestaurantAdmin();
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        $item = $this->db->selectOne(
            "SELECT * FROM menu_items WHERE id = ? AND restaurant_id = ?",
            [$id, $restaurantId]
        );
        
        if (!$item) {
            $this->redirect('/admin/menu?error=not_found');
        }
        
        $categories = $this->db->select(
            "SELECT * FROM menu_categories WHERE restaurant_id = ? ORDER BY sort_order ASC",
            [$restaurantId]
        );
        
        $variants = $this->db->select(
            "SELECT * FROM menu_item_variants WHERE item_id = ? ORDER BY sort_order ASC",
            [$id]
        );
        
        $extras = $this->db->select(
            "SELECT * FROM menu_item_extras WHERE item_id = ? ORDER BY sort_order ASC",
            [$id]
        );
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } else {
                try {
                    $data = $this->sanitizeInput($_POST);
                    
                    $imageUrl = $item['image_url'];
                    
                    // Handle regular file upload
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $imageUrl = $this->uploadImage($_FILES['image'], 'menu-items');
                        if ($item['image_url'] && file_exists(UPLOADS_PATH . $item['image_url'])) {
                            unlink(UPLOADS_PATH . $item['image_url']);
                        }
                    }
                    // Handle suggested image selection (external images)
                    elseif (isset($data['selected_suggestion_image']) && !empty($data['selected_suggestion_image'])) {
                        $imageData = json_decode($data['selected_suggestion_image'], true);
                        if ($imageData && isset($imageData['url'])) {
                            // Remove old image if it's a local file
                            if ($item['image_url'] && !filter_var($item['image_url'], FILTER_VALIDATE_URL)) {
                                if (file_exists(UPLOADS_PATH . $item['image_url'])) {
                                    unlink(UPLOADS_PATH . $item['image_url']);
                                }
                            }
                            $imageUrl = $imageData['url']; // URL diretto (hotlinking)
                        }
                    }
                    
                    $allergens = [];
                    if (isset($data['allergens']) && is_array($data['allergens'])) {
                        $allergens = $data['allergens'];
                    }
                    
                    $this->db->update(
                        "UPDATE menu_items SET category_id = ?, name = ?, description = ?, price = ?, 
                         image_url = ?, ingredients = ?, allergens = ?, is_available = ?, is_featured = ?
                         WHERE id = ?",
                        [
                            $data['category_id'],
                            $data['name'],
                            $data['description'] ?? null,
                            $data['price'],
                            $imageUrl,
                            $data['ingredients'] ?? null,
                            json_encode($allergens),
                            isset($data['is_available']) ? 1 : 0,
                            isset($data['is_featured']) ? 1 : 0,
                            $id
                        ]
                    );
                    
                    $this->db->delete("DELETE FROM menu_item_variants WHERE item_id = ?", [$id]);
                    if (isset($data['variants']) && is_array($data['variants'])) {
                        foreach ($data['variants'] as $index => $variant) {
                            if ($variant['name']) {
                                $this->db->insert(
                                    "INSERT INTO menu_item_variants (item_id, name, price_modifier, is_default, sort_order) 
                                     VALUES (?, ?, ?, ?, ?)",
                                    [
                                        $id,
                                        $variant['name'],
                                        $variant['price_modifier'] ?? 0,
                                        isset($variant['is_default']) ? 1 : 0,
                                        $index
                                    ]
                                );
                            }
                        }
                    }
                    
                    $this->db->delete("DELETE FROM menu_item_extras WHERE item_id = ?", [$id]);
                    if (isset($data['extras']) && is_array($data['extras'])) {
                        foreach ($data['extras'] as $index => $extra) {
                            if ($extra['name']) {
                                $this->db->insert(
                                    "INSERT INTO menu_item_extras (item_id, name, price, sort_order) 
                                     VALUES (?, ?, ?, ?)",
                                    [
                                        $id,
                                        $extra['name'],
                                        $extra['price'] ?? 0,
                                        $index
                                    ]
                                );
                            }
                        }
                    }
                    
                    $this->redirect('/admin/menu?success=updated');
                    
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('admin/item-form', [
            'title' => 'Modifica Piatto',
            'item' => $item,
            'variants' => $variants,
            'extras' => $extras,
            'categories' => $categories,
            'error' => $error ?? null,
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function itemDelete($id) {
        $this->auth->requireRestaurantAdmin();
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        $item = $this->db->selectOne(
            "SELECT * FROM menu_items WHERE id = ? AND restaurant_id = ?",
            [$id, $restaurantId]
        );
        
        if (!$item) {
            $this->redirect('/admin/menu?error=not_found');
        }
        
        try {
            $this->db->beginTransaction();
            
            $this->db->delete("DELETE FROM menu_item_variants WHERE item_id = ?", [$id]);
            $this->db->delete("DELETE FROM menu_item_extras WHERE item_id = ?", [$id]);
            
            if ($item['image_url'] && file_exists(UPLOADS_PATH . $item['image_url'])) {
                unlink(UPLOADS_PATH . $item['image_url']);
            }
            
            $this->db->delete("DELETE FROM menu_items WHERE id = ?", [$id]);
            
            $this->db->commit();
            $this->redirect('/admin/menu?success=deleted');
            
        } catch (Exception $e) {
            $this->db->rollback();
            $this->redirect('/admin/menu?error=delete_failed');
        }
    }
    
    public function qrcode() {
        $this->auth->requireRestaurantAdmin();
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        $restaurant = $this->db->selectOne("SELECT * FROM restaurants WHERE id = ?", [$restaurantId]);
        
        if (!$restaurant) {
            $this->redirect('/admin?error=restaurant_not_found');
        }
        
        $menuUrl = BASE_URL . '/restaurant/' . $restaurant['slug'];
        
        $this->loadView('admin/qrcode', [
            'title' => 'QR Code Menu - ' . $restaurant['name'],
            'restaurant' => $restaurant,
            'menu_url' => $menuUrl
        ]);
    }
    
    public function users() {
        $this->auth->requireRestaurantAdmin();
        
        if (!$this->auth->hasPermission('manage_users') && $_SESSION['admin_role'] !== 'owner') {
            $this->redirect('/admin?error=no_permission');
        }
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } else {
                try {
                    $action = $_POST['action'];
                    
                    if ($action === 'add_user') {
                        $data = $this->sanitizeInput($_POST);
                        
                        $existingUser = $this->db->selectOne(
                            "SELECT id FROM restaurant_admins WHERE restaurant_id = ? AND (username = ? OR email = ?)",
                            [$restaurantId, $data['username'], $data['email']]
                        );
                        
                        if ($existingUser) {
                            throw new Exception('Username o email già esistenti');
                        }
                        
                        $permissions = $data['permissions'] ?? [];
                        if (!is_array($permissions)) {
                            $permissions = [];
                        }
                        
                        // Validate password before hashing
                        $passwordValidation = $this->auth->validatePassword($data['password']);
                        if ($passwordValidation !== true) {
                            throw new Exception('Password non valida: ' . implode(', ', $passwordValidation));
                        }
                        
                        $this->db->insert(
                            "INSERT INTO restaurant_admins (restaurant_id, username, email, password_hash, full_name, role, permissions, is_active) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                            [
                                $restaurantId,
                                $data['username'],
                                $data['email'],
                                $this->auth->hashPassword($data['password']),
                                $data['full_name'],
                                $data['role'],
                                json_encode($permissions),
                                isset($data['is_active']) ? 1 : 0
                            ]
                        );
                        
                        $success = 'Utente aggiunto con successo';
                        
                    } elseif ($action === 'update_user' && isset($_POST['user_id'])) {
                        $userId = $_POST['user_id'];
                        $data = $this->sanitizeInput($_POST);
                        
                        $user = $this->db->selectOne(
                            "SELECT * FROM restaurant_admins WHERE id = ? AND restaurant_id = ?",
                            [$userId, $restaurantId]
                        );
                        
                        if (!$user) {
                            throw new Exception('Utente non trovato');
                        }
                        
                        $permissions = $data['permissions'] ?? [];
                        if (!is_array($permissions)) {
                            $permissions = [];
                        }
                        
                        $updateFields = ['full_name = ?', 'role = ?', 'permissions = ?', 'is_active = ?'];
                        $updateParams = [
                            $data['full_name'],
                            $data['role'],
                            json_encode($permissions),
                            isset($data['is_active']) ? 1 : 0
                        ];
                        
                        if (!empty($data['password'])) {
                            // Validate password before hashing
                            $passwordValidation = $this->auth->validatePassword($data['password']);
                            if ($passwordValidation !== true) {
                                throw new Exception('Password non valida: ' . implode(', ', $passwordValidation));
                            }
                            
                            $updateFields[] = 'password_hash = ?';
                            $updateParams[] = $this->auth->hashPassword($data['password']);
                        }
                        
                        $updateParams[] = $userId;
                        
                        $this->db->update(
                            "UPDATE restaurant_admins SET " . implode(', ', $updateFields) . " WHERE id = ?",
                            $updateParams
                        );
                        
                        $success = 'Utente aggiornato con successo';
                        
                    } elseif ($action === 'delete_user' && isset($_POST['user_id'])) {
                        $userId = $_POST['user_id'];
                        
                        if ($userId == $this->auth->getCurrentUserId()) {
                            throw new Exception('Non puoi eliminare il tuo account');
                        }
                        
                        $this->db->delete(
                            "DELETE FROM restaurant_admins WHERE id = ? AND restaurant_id = ?",
                            [$userId, $restaurantId]
                        );
                        
                        $success = 'Utente eliminato con successo';
                    }
                    
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        $users = $this->db->select(
            "SELECT * FROM restaurant_admins WHERE restaurant_id = ? ORDER BY created_at DESC",
            [$restaurantId]
        );
        
        $restaurant = $this->db->selectOne("SELECT name FROM restaurants WHERE id = ?", [$restaurantId]);
        $csrf_token = $this->generateCsrf();
        
        $availablePermissions = [
            'manage_categories' => 'Gestire Categorie',
            'manage_menu_items' => 'Gestire Piatti',
            'manage_settings' => 'Gestire Impostazioni',
            'manage_users' => 'Gestire Utenti',
            'view_analytics' => 'Visualizzare Analytics'
        ];
        
        $this->loadView('admin/users', [
            'title' => 'Gestione Utenti - ' . $restaurant['name'],
            'users' => $users,
            'available_permissions' => $availablePermissions,
            'current_user_id' => $this->auth->getCurrentUserId(),
            'error' => $error ?? null,
            'success' => $success ?? null,
            'csrf_token' => $csrf_token
        ]);
    }
    
    public function analytics() {
        $this->auth->requireRestaurantAdmin();
        
        if (!$this->auth->hasPermission('view_analytics')) {
            $this->redirect('/admin?error=no_permission');
        }
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        $period = $_GET['period'] ?? '30';
        $validPeriods = ['7', '30', '90', '365'];
        if (!in_array($period, $validPeriods)) {
            $period = '30';
        }
        
        $dateFrom = date('Y-m-d H:i:s', strtotime("-{$period} days"));
        
        $stats = [
            'menu_views' => 0,
            'popular_items' => [],
            'popular_categories' => [],
            'activity_summary' => []
        ];
        
        // Temporary simplified query to avoid JOIN issues
        $popularCategories = $this->db->select(
            "SELECT mc.name, 0 as views 
             FROM menu_categories mc 
             WHERE mc.restaurant_id = ?
             ORDER BY mc.name ASC 
             LIMIT 10",
            [$restaurantId]
        );
        
        $recentActivity = [];
        // Check if activity_logs table has data for this restaurant
        try {
            $recentActivity = $this->db->select(
                "SELECT action, description, created_at 
                 FROM activity_logs 
                 WHERE restaurant_id = ? AND created_at >= ?
                 ORDER BY created_at DESC 
                 LIMIT 20",
                [$restaurantId, $dateFrom]
            );
        } catch (Exception $e) {
            // If there's an error, just set empty array
            $recentActivity = [];
        }
        
        // Get menu stats with error handling
        try {
            $menuStats = [
                'total_categories' => $this->db->selectOne(
                    "SELECT COUNT(*) as count FROM menu_categories WHERE restaurant_id = ?", 
                    [$restaurantId]
                )['count'] ?? 0,
                'total_items' => $this->db->selectOne(
                    "SELECT COUNT(*) as count FROM menu_items WHERE restaurant_id = ?", 
                    [$restaurantId]
                )['count'] ?? 0,
                'active_items' => $this->db->selectOne(
                    "SELECT COUNT(*) as count FROM menu_items WHERE restaurant_id = ? AND is_available = 1", 
                    [$restaurantId]
                )['count'] ?? 0,
                'featured_items' => $this->db->selectOne(
                    "SELECT COUNT(*) as count FROM menu_items WHERE restaurant_id = ? AND is_featured = 1", 
                    [$restaurantId]
                )['count'] ?? 0
            ];
        } catch (Exception $e) {
            error_log("Error in menuStats: " . $e->getMessage());
            $menuStats = ['total_categories' => 0, 'total_items' => 0, 'active_items' => 0, 'featured_items' => 0];
        }
        
        // Get category distribution with error handling
        try {
            $categoryDistribution = $this->db->select(
                "SELECT mc.name, COUNT(mi.id) as items_count 
                 FROM menu_categories mc 
                 LEFT JOIN menu_items mi ON mc.id = mi.category_id 
                 WHERE mc.restaurant_id = ? 
                 GROUP BY mc.id, mc.name 
                 ORDER BY items_count DESC",
                [$restaurantId]
            );
        } catch (Exception $e) {
            error_log("Error in categoryDistribution: " . $e->getMessage());
            $categoryDistribution = [];
        }
        
        // Get price ranges with simplified query
        try {
            $priceRanges = [];
            $priceData = $this->db->select(
                "SELECT price FROM menu_items WHERE restaurant_id = ?",
                [$restaurantId]
            );
            
            // Process price ranges in PHP instead of SQL
            $ranges = ['< 10€' => 0, '10-20€' => 0, '20-30€' => 0, '> 30€' => 0];
            foreach ($priceData as $item) {
                $price = floatval($item['price']);
                if ($price < 10) {
                    $ranges['< 10€']++;
                } elseif ($price <= 20) {
                    $ranges['10-20€']++;
                } elseif ($price <= 30) {
                    $ranges['20-30€']++;
                } else {
                    $ranges['> 30€']++;
                }
            }
            
            foreach ($ranges as $range => $count) {
                if ($count > 0) {
                    $priceRanges[] = ['price_range' => $range, 'count' => $count];
                }
            }
        } catch (Exception $e) {
            error_log("Error in priceRanges: " . $e->getMessage());
            $priceRanges = [];
        }
        
        $restaurant = $this->db->selectOne("SELECT name FROM restaurants WHERE id = ?", [$restaurantId]);
        
        $this->loadView('admin/analytics', [
            'title' => 'Analytics - ' . $restaurant['name'],
            'period' => $period,
            'stats' => $stats,
            'menu_stats' => $menuStats,
            'popular_categories' => $popularCategories,
            'category_distribution' => $categoryDistribution,
            'price_ranges' => $priceRanges,
            'recent_activity' => $recentActivity
        ]);
    }
    
    public function settings() {
        $this->auth->requireRestaurantAdmin();
        
        if (!$this->auth->hasPermission('manage_settings')) {
            $this->redirect('/admin?error=no_permission');
        }
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF non valido';
            } else {
                $section = $_POST['section'] ?? 'basic_info';
                
                try {
                    $data = $this->sanitizeInput($_POST);
                    
                    if ($section === 'basic_info') {
                        // Handle basic restaurant info update
                        $this->db->update(
                            "UPDATE restaurants SET name = ?, description = ?, address = ?, phone = ?, email = ? WHERE id = ?",
                            [
                                $data['name'], $data['description'], $data['address'],
                                $data['phone'], $data['email'], $restaurantId
                            ]
                        );
                        $success = 'Informazioni aggiornate con successo';
                        
                    } elseif ($section === 'branding') {
                        // Handle branding updates (logo, banner, colors)
                        $restaurant = $this->db->selectOne("SELECT * FROM restaurants WHERE id = ?", [$restaurantId]);
                        
                        $logoUrl = $restaurant['logo_url'];
                        $coverUrl = $restaurant['cover_image_url'];
                        
                        // Handle logo upload
                        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                            // Delete old logo if exists
                            if ($logoUrl && file_exists(UPLOADS_PATH . $logoUrl)) {
                                unlink(UPLOADS_PATH . $logoUrl);
                            }
                            $logoUrl = $this->uploadImage($_FILES['logo'], 'logos');
                        }
                        
                        // Handle banner upload
                        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
                            // Delete old banner if exists
                            if ($coverUrl && file_exists(UPLOADS_PATH . $coverUrl)) {
                                unlink(UPLOADS_PATH . $coverUrl);
                            }
                            $coverUrl = $this->uploadImage($_FILES['banner'], 'banners');
                        }
                        
                        // Handle font upload
                        $customFontPath = null;
                        $customFontName = null;
                        if (isset($_FILES['custom_font']) && $_FILES['custom_font']['error'] === UPLOAD_ERR_OK) {
                            $fontResult = $this->uploadFont($_FILES['custom_font']);
                            if ($fontResult) {
                                $customFontPath = $fontResult['path'];
                                $customFontName = $fontResult['name'];
                            }
                        }
                        
                        // Prepare font settings
                        $primaryFont = $data['primary_font'] ?? 'Inter';
                        $finalCustomFontPath = $customFontPath ?? ($restaurant['custom_font_path'] ?? null);
                        $finalCustomFontName = $customFontName ?? ($restaurant['custom_font_name'] ?? null);
                        
                        // Prepare features settings (keep existing features)
                        $existingFeatures = json_decode($restaurant['features'] ?: '{}', true) ?? [];
                        $featuresJson = json_encode($existingFeatures);
                        
                        $this->db->update(
                            "UPDATE restaurants SET logo_url = ?, cover_image_url = ?, theme_color = ?, primary_font = ?, custom_font_path = ?, custom_font_name = ?, features = ? WHERE id = ?",
                            [
                                $logoUrl, $coverUrl, 
                                $data['primary_color'] ?? $data['primary_color_hex'] ?? '#3b82f6',
                                $primaryFont,
                                $finalCustomFontPath,
                                $finalCustomFontName,
                                $featuresJson,
                                $restaurantId
                            ]
                        );
                        $success = 'Logo e branding aggiornati con successo';
                        
                    } elseif ($section === 'opening_hours') {
                        // Handle opening hours update
                        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        $openingHours = [];
                        
                        foreach ($days as $day) {
                            $openingHours[$day] = [
                                'open' => isset($data[$day . '_open']),
                                'open_time' => $data[$day . '_open_time'] ?? '12:00',
                                'close_time' => $data[$day . '_close_time'] ?? '22:30'
                            ];
                        }
                        
                        $this->db->update(
                            "UPDATE restaurants SET opening_hours = ? WHERE id = ?",
                            [json_encode($openingHours), $restaurantId]
                        );
                        $success = 'Orari di apertura aggiornati con successo';
                        
                    } elseif ($section === 'menu_status') {
                        // Handle menu visibility settings
                        $features = [
                            'show_prices' => isset($data['show_prices']),
                            'show_descriptions' => isset($data['show_descriptions']),
                            'show_images' => isset($data['show_images']),
                            'local_cart_enabled' => isset($data['local_cart_enabled']),
                            'qrcode' => isset($data['qrcode'])
                        ];
                        
                        $this->db->update(
                            "UPDATE restaurants SET features = ? WHERE id = ?",
                            [json_encode($features), $restaurantId]
                        );
                        $success = 'Impostazioni menu aggiornate con successo';
                        
                    } elseif ($section === 'remove_logo') {
                        // Remove logo
                        $restaurant = $this->db->selectOne("SELECT logo_url FROM restaurants WHERE id = ?", [$restaurantId]);
                        if ($restaurant['logo_url']) {
                            // Delete physical file
                            if (file_exists(UPLOADS_PATH . $restaurant['logo_url'])) {
                                unlink(UPLOADS_PATH . $restaurant['logo_url']);
                            }
                            // Update database
                            $this->db->update(
                                "UPDATE restaurants SET logo_url = NULL WHERE id = ?",
                                [$restaurantId]
                            );
                        }
                        $success = 'Logo rimosso con successo';
                        
                    } elseif ($section === 'remove_banner') {
                        // Remove banner
                        $restaurant = $this->db->selectOne("SELECT cover_image_url FROM restaurants WHERE id = ?", [$restaurantId]);
                        if ($restaurant['cover_image_url']) {
                            // Delete physical file
                            if (file_exists(UPLOADS_PATH . $restaurant['cover_image_url'])) {
                                unlink(UPLOADS_PATH . $restaurant['cover_image_url']);
                            }
                            // Update database
                            $this->db->update(
                                "UPDATE restaurants SET cover_image_url = NULL WHERE id = ?",
                                [$restaurantId]
                            );
                        }
                        $success = 'Banner rimosso con successo';
                    }
                    
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        
        $restaurant = $this->db->selectOne("SELECT * FROM restaurants WHERE id = ?", [$restaurantId]);
        $csrf_token = $this->generateCsrf();
        
        $this->loadView('admin/settings', [
            'title' => 'Impostazioni - ' . $restaurant['name'],
            'restaurant' => $restaurant,
            'error' => $error ?? null,
            'success' => $success ?? null,
            'csrf_token' => $csrf_token
        ]);
    }
    
    protected function uploadImage($file, $directory = 'general') {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $maxSize = ($directory === 'banners') ? 5 * 1024 * 1024 : 2 * 1024 * 1024; // 5MB for banners, 2MB for logos
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Formato file non supportato. Usa JPG, PNG o WEBP.');
        }
        
        if ($file['size'] > $maxSize) {
            $maxSizeMB = $maxSize / (1024 * 1024);
            throw new Exception("File troppo grande. Massimo {$maxSizeMB}MB consentiti.");
        }
        
        $uploadDir = UPLOADS_PATH . '/' . $directory . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . strtolower($extension);
        $filepath = $uploadDir . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception('Errore durante il caricamento del file.');
        }
        
        return $filename;
    }
}
?>