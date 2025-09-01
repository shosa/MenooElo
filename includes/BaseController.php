<?php
require_once 'includes/Database.php';
require_once 'includes/Auth.php';

class BaseController {
    protected $db;
    protected $auth;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->auth = new Auth();
    }
    
    protected function loadView($view, $data = []) {
        extract($data);
        include "views/{$view}.php";
    }
    
    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit;
    }
    
    protected function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function validateCsrf($token) {
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }
    
    protected function generateCsrf() {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }
    
    protected function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    protected function uploadImage($file, $directory = 'general') {
        $uploadDir = UPLOADS_PATH . $directory . '/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 5 * 1024 * 1024;
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Formato file non supportato');
        }
        
        if ($file['size'] > $maxSize) {
            throw new Exception('File troppo grande (max 5MB)');
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $directory . '/' . $filename;
        }
        
        throw new Exception('Errore durante upload file');
    }
}
?>