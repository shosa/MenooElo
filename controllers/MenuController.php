<?php
require_once 'includes/BaseController.php';

class MenuController extends BaseController {
    
    public function show($slug) {
        $restaurant = $this->db->selectOne(
            "SELECT * FROM restaurants WHERE slug = ? AND is_active = 1", 
            [$slug]
        );
        
        if (!$restaurant) {
            $this->show404();
            return;
        }
        
        $categories = $this->db->select(
            "SELECT mc.* FROM menu_categories mc 
             WHERE mc.restaurant_id = ? AND mc.is_active = 1 
             ORDER BY mc.sort_order ASC",
            [$restaurant['id']]
        );
        
        $menuItems = [];
        foreach ($categories as $category) {
            $items = $this->db->select(
                "SELECT mi.* FROM menu_items mi 
                 WHERE mi.category_id = ? AND mi.is_available = 1 
                 ORDER BY mi.sort_order ASC",
                [$category['id']]
            );
            
            foreach ($items as &$item) {
                $item['variants'] = $this->db->select(
                    "SELECT * FROM menu_item_variants WHERE item_id = ? ORDER BY sort_order ASC",
                    [$item['id']]
                );
                
                $item['extras'] = $this->db->select(
                    "SELECT * FROM menu_item_extras WHERE item_id = ? AND is_available = 1 ORDER BY sort_order ASC",
                    [$item['id']]
                );
                
                $item['allergens'] = json_decode($item['allergens'] ?: '[]', true) ?? [];
            }
            
            $menuItems[$category['id']] = $items;
        }
        
        $restaurant['features'] = json_decode($restaurant['features'] ?: '{}', true) ?? [];
        $restaurant['opening_hours'] = json_decode($restaurant['opening_hours'] ?: '{}', true) ?? [];
        
        $this->loadView('public/menu', [
            'title' => $restaurant['name'] . ' - Menu Digitale',
            'description' => $restaurant['description'] ?? 'Menu digitale di ' . $restaurant['name'],
            'restaurant' => $restaurant,
            'categories' => $categories,
            'menuItems' => $menuItems,
            'customTheme' => $restaurant['theme_color']
        ]);
    }
    
    private function show404() {
        http_response_code(404);
        $this->loadView('public/404', [
            'title' => 'Ristorante non trovato - MenooElo'
        ]);
    }
}
?>