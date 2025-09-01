<?php
require_once 'includes/BaseController.php';

class HomeController extends BaseController {
    
    public function index() {
        $featuredRestaurants = $this->db->select(
            "SELECT r.*, COUNT(mi.id) as menu_items_count 
             FROM restaurants r 
             LEFT JOIN menu_items mi ON r.id = mi.restaurant_id AND mi.is_available = 1
             WHERE r.is_active = 1 
             GROUP BY r.id 
             ORDER BY r.created_at DESC 
             LIMIT 8"
        );
        
        $stats = [
            'total_restaurants' => $this->db->selectOne("SELECT COUNT(*) as count FROM restaurants WHERE is_active = 1")['count'],
            'total_menus' => $this->db->selectOne("SELECT COUNT(*) as count FROM menu_items WHERE is_available = 1")['count']
        ];
        
        $this->loadView('public/home', [
            'title' => 'MenooElo - Sistema di Menu Digitali per Ristoranti',
            'description' => 'Crea il tuo menu digitale professionale. Sistema completo per ristoranti, bar, pizzerie e locali.',
            'featured_restaurants' => $featuredRestaurants,
            'stats' => $stats
        ]);
    }
}
?>