<?php
class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function loginSuperAdmin($username, $password) {
        $user = $this->db->selectOne(
            "SELECT * FROM super_admins WHERE (username = ? OR email = ?) AND is_active = 1",
            [$username, $username]
        );
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['super_admin_id'] = $user['id'];
            $_SESSION['super_admin_username'] = $user['username'];
            $_SESSION['user_type'] = 'super_admin';
            $_SESSION['last_activity'] = time();
            
            $this->logActivity('super_admin', $user['id'], null, 'login', 'Login Super Admin');
            return true;
        }
        
        return false;
    }
    
    public function loginRestaurantAdmin($username, $password, $restaurantId = null) {
        $sql = "SELECT ra.*, r.name as restaurant_name, r.slug as restaurant_slug 
                FROM restaurant_admins ra 
                JOIN restaurants r ON ra.restaurant_id = r.id 
                WHERE (ra.username = ? OR ra.email = ?) 
                AND ra.is_active = 1 AND r.is_active = 1";
        
        $params = [$username, $username];
        
        if ($restaurantId) {
            $sql .= " AND ra.restaurant_id = ?";
            $params[] = $restaurantId;
        }
        
        $user = $this->db->selectOne($sql, $params);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['restaurant_admin_id'] = $user['id'];
            $_SESSION['restaurant_admin_username'] = $user['username'];
            $_SESSION['restaurant_id'] = $user['restaurant_id'];
            $_SESSION['restaurant_slug'] = $user['restaurant_slug'];
            $_SESSION['admin_role'] = $user['role'];
            $_SESSION['admin_permissions'] = json_decode($user['permissions'] ?: '[]', true) ?? [];
            $_SESSION['user_type'] = 'restaurant_admin';
            $_SESSION['last_activity'] = time();
            
            $this->logActivity('restaurant_admin', $user['id'], $user['restaurant_id'], 'login', 'Login Restaurant Admin');
            return true;
        }
        
        return false;
    }
    
    public function logout() {
        $userType = $_SESSION['user_type'] ?? null;
        $userId = $_SESSION['super_admin_id'] ?? $_SESSION['restaurant_admin_id'] ?? null;
        $restaurantId = $_SESSION['restaurant_id'] ?? null;
        
        if ($userType && $userId) {
            $this->logActivity($userType, $userId, $restaurantId, 'logout', 'Logout');
        }
        
        session_destroy();
    }
    
    public function isSuperAdmin() {
        return isset($_SESSION['user_type']) && 
               $_SESSION['user_type'] === 'super_admin' && 
               isset($_SESSION['super_admin_id']) &&
               $this->checkSessionTimeout();
    }
    
    public function isRestaurantAdmin() {
        return isset($_SESSION['user_type']) && 
               $_SESSION['user_type'] === 'restaurant_admin' && 
               isset($_SESSION['restaurant_admin_id']) &&
               $this->checkSessionTimeout();
    }
    
    public function requireSuperAdmin() {
        if (!$this->isSuperAdmin()) {
            header('Location: ' . BASE_URL . '/superadmin/login');
            exit;
        }
    }
    
    public function requireRestaurantAdmin() {
        if (!$this->isRestaurantAdmin()) {
            header('Location: ' . BASE_URL . '/admin/login');
            exit;
        }
    }
    
    public function hasPermission($permission) {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        if (!$this->isRestaurantAdmin()) {
            return false;
        }
        
        if ($_SESSION['admin_role'] === 'owner') {
            return true;
        }
        
        $permissions = $_SESSION['admin_permissions'] ?? [];
        return in_array($permission, $permissions);
    }
    
    public function getCurrentRestaurantId() {
        return $_SESSION['restaurant_id'] ?? null;
    }
    
    public function getCurrentUserId() {
        if ($this->isSuperAdmin()) {
            return $_SESSION['super_admin_id'];
        } elseif ($this->isRestaurantAdmin()) {
            return $_SESSION['restaurant_admin_id'];
        }
        return null;
    }
    
    private function checkSessionTimeout() {
        // Get session timeout from database
        $timeoutSetting = $this->db->selectOne("SELECT setting_value FROM system_settings WHERE setting_key = 'session_timeout'");
        $sessionTimeout = $timeoutSetting ? (int)$timeoutSetting['setting_value'] : 3600; // 1 hour default
        
        if (isset($_SESSION['last_activity']) && 
            (time() - $_SESSION['last_activity']) > $sessionTimeout) {
            session_destroy();
            return false;
        }
        
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    public function hashPassword($password) {
        // Get password cost from database
        $costSetting = $this->db->selectOne("SELECT setting_value FROM system_settings WHERE setting_key = 'password_cost'");
        $cost = $costSetting ? (int)$costSetting['setting_value'] : 12; // default bcrypt cost
        
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
    }
    
    public function validatePassword($password) {
        // Get password requirements from database
        $minLengthSetting = $this->db->selectOne("SELECT setting_value FROM system_settings WHERE setting_key = 'min_password_length'");
        $complexitySetting = $this->db->selectOne("SELECT setting_value FROM system_settings WHERE setting_key = 'require_password_complexity'");
        
        $minLength = $minLengthSetting ? (int)$minLengthSetting['setting_value'] : 8;
        $requireComplexity = $complexitySetting ? ($complexitySetting['setting_value'] === '1') : false;
        
        $errors = [];
        
        if (strlen($password) < $minLength) {
            $errors[] = "Password deve essere di almeno {$minLength} caratteri";
        }
        
        if ($requireComplexity) {
            if (!preg_match('/[A-Z]/', $password)) {
                $errors[] = "Password deve contenere almeno una lettera maiuscola";
            }
            if (!preg_match('/[a-z]/', $password)) {
                $errors[] = "Password deve contenere almeno una lettera minuscola";
            }
            if (!preg_match('/[0-9]/', $password)) {
                $errors[] = "Password deve contenere almeno un numero";
            }
            if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
                $errors[] = "Password deve contenere almeno un simbolo";
            }
        }
        
        return empty($errors) ? true : $errors;
    }
    
    private function logActivity($userType, $userId, $restaurantId, $action, $description) {
        $this->db->insert(
            "INSERT INTO activity_logs (user_type, user_id, restaurant_id, action, description, ip_address, user_agent) 
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $userType,
                $userId,
                $restaurantId,
                $action,
                $description,
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]
        );
    }
}
?>