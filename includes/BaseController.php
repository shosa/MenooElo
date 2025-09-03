<?php
require_once 'includes/Database.php';
require_once 'includes/Auth.php';
require_once 'includes/SystemSettings.php';

class BaseController {
    protected $db;
    protected $auth;
    protected $settings;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->auth = new Auth();
        $this->settings = SystemSettings::getInstance();
    }
    
    protected function loadView($view, $data = []) {
        // Add global system settings to all views
        $data['app_settings'] = SystemSettings::getAll();
        
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
        
        // Get settings from database
        $maxSizeSetting = $this->db->selectOne("SELECT setting_value FROM system_settings WHERE setting_key = 'max_image_size'");
        $allowedFormatsSetting = $this->db->selectOne("SELECT setting_value FROM system_settings WHERE setting_key = 'allowed_image_formats'");
        
        // Use database settings or fallback to defaults
        $maxSize = $maxSizeSetting ? (int)$maxSizeSetting['setting_value'] : 5242880; // 5MB default
        $allowedFormats = $allowedFormatsSetting ? explode(',', $allowedFormatsSetting['setting_value']) : ['jpg', 'jpeg', 'png', 'webp'];
        
        // Map file extensions to MIME types
        $mimeTypeMap = [
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'], 
            'png' => ['image/png'],
            'webp' => ['image/webp'],
            'gif' => ['image/gif'],
            'svg' => ['image/svg+xml'],
            'bmp' => ['image/bmp']
        ];
        
        $allowedTypes = [];
        foreach ($allowedFormats as $format) {
            if (isset($mimeTypeMap[strtolower(trim($format))])) {
                $allowedTypes = array_merge($allowedTypes, $mimeTypeMap[strtolower(trim($format))]);
            }
        }
        
        if (!in_array($file['type'], $allowedTypes)) {
            $formatsStr = implode(', ', array_map('strtoupper', $allowedFormats));
            throw new Exception("Formato file non supportato. Formati consentiti: {$formatsStr}");
        }
        
        if ($file['size'] > $maxSize) {
            $maxSizeMB = round($maxSize / 1048576, 1);
            throw new Exception("File troppo grande (max {$maxSizeMB}MB)");
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $directory . '/' . $filename;
        }
        
        throw new Exception('Errore durante upload file');
    }
    
    protected function uploadFont($file) {
        $uploadDir = UPLOADS_PATH . 'fonts/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Get settings from database
        $maxFontSizeSetting = $this->db->selectOne("SELECT setting_value FROM system_settings WHERE setting_key = 'max_font_size'");
        $allowedFontFormatsSetting = $this->db->selectOne("SELECT setting_value FROM system_settings WHERE setting_key = 'allowed_font_formats'");
        
        // Use database settings or fallback to defaults
        $maxSize = $maxFontSizeSetting ? (int)$maxFontSizeSetting['setting_value'] : 2097152; // 2MB default
        $allowedExtensions = $allowedFontFormatsSetting ? explode(',', $allowedFontFormatsSetting['setting_value']) : ['woff', 'woff2', 'ttf', 'otf'];
        
        // Normalize extensions
        $allowedExtensions = array_map(function($ext) { return strtolower(trim($ext)); }, $allowedExtensions);
        
        // Get file extension for validation since MIME types can be inconsistent for fonts
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $allowedExtensions)) {
            $formatsStr = implode(', ', array_map('strtoupper', $allowedExtensions));
            throw new Exception("Formato font non supportato. Formati consentiti: {$formatsStr}");
        }
        
        if ($file['size'] > $maxSize) {
            $maxSizeMB = round($maxSize / 1048576, 1);
            throw new Exception("Font troppo grande (max {$maxSizeMB}MB)");
        }
        
        $cleanName = preg_replace('/[^a-zA-Z0-9._-]/', '', pathinfo($file['name'], PATHINFO_FILENAME));
        $filename = $cleanName . '_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return [
                'path' => 'fonts/' . $filename,
                'name' => $cleanName,
                'extension' => $extension,
                'size' => $file['size']
            ];
        }
        
        throw new Exception('Errore durante upload font');
    }
}
?>