<?php
/**
 * Sistema di gestione impostazioni globali
 * Carica e rende disponibili le impostazioni del sistema in tutte le view
 */
class SystemSettings {
    private static $instance = null;
    private static $settings = null;
    private $db;
    
    private function __construct() {
        $this->db = Database::getInstance();
        $this->loadSettings();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function loadSettings() {
        if (self::$settings === null) {
            try {
                $dbSettings = $this->db->select("SELECT setting_key, setting_value FROM system_settings");
                
                // Default values
                self::$settings = [
                    'app_name' => 'MenooElo',
                    'app_url' => BASE_URL,
                    'system_email' => 'admin@menooelo.com',
                    'timezone' => 'Europe/Rome',
                    'default_language' => 'it',
                    'default_currency' => 'EUR',
                    'currency_symbol' => '€',
                    'maintenance_mode' => false,
                    'debug_mode' => false
                ];
                
                // Override with database values
                foreach ($dbSettings as $setting) {
                    $value = $setting['setting_value'];
                    
                    // Convert boolean strings
                    if (in_array($value, ['0', '1'])) {
                        $value = ($value === '1');
                    }
                    
                    self::$settings[$setting['setting_key']] = $value;
                }
                
                // Add currency symbol based on currency
                self::$settings['currency_symbol'] = $this->mapCurrencyToSymbol(self::$settings['default_currency']);
                
            } catch (Exception $e) {
                error_log('SystemSettings: Error loading settings - ' . $e->getMessage());
            }
        }
    }
    
    private function mapCurrencyToSymbol($currency) {
        $symbols = [
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            'CHF' => 'CHF',
            'JPY' => '¥'
        ];
        
        return $symbols[$currency] ?? '€';
    }
    
    public static function get($key, $default = null) {
        $instance = self::getInstance();
        return self::$settings[$key] ?? $default;
    }
    
    public static function getAll() {
        $instance = self::getInstance();
        return self::$settings;
    }
    
    public static function formatPrice($price) {
        $symbol = self::get('currency_symbol', '€');
        return $symbol . number_format($price, 2);
    }
    
    public static function getCurrencySymbol() {
        return self::get('currency_symbol', '€');
    }
    
    public static function isMaintenanceMode() {
        return self::get('maintenance_mode', false);
    }
    
    public static function isDebugMode() {
        return self::get('debug_mode', false);
    }
    
    public static function getAppName() {
        return self::get('app_name', 'MenooElo');
    }
    
    public static function getSystemEmail() {
        return self::get('system_email', 'admin@menooelo.com');
    }
}
?>