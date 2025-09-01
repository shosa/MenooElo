<?php
require_once 'includes/BaseController.php';

class ApiController extends BaseController {
    
    public function __construct() {
        parent::__construct();
        
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }
    }
    
    public function quickEdit() {
        if (!$this->auth->isRestaurantAdmin()) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $field = $input['field'] ?? '';
        $id = (int)($input['id'] ?? 0);
        $value = $input['value'] ?? '';
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        try {
            switch ($field) {
                case 'category_name':
                    $this->db->update(
                        "UPDATE menu_categories SET name = ? WHERE id = ? AND restaurant_id = ?",
                        [$value, $id, $restaurantId]
                    );
                    break;
                    
                case 'item_name':
                    $this->db->update(
                        "UPDATE menu_items SET name = ? WHERE id = ? AND restaurant_id = ?",
                        [$value, $id, $restaurantId]
                    );
                    break;
                    
                case 'item_price':
                    if (!is_numeric($value) || $value < 0) {
                        throw new Exception('Prezzo non valido');
                    }
                    $this->db->update(
                        "UPDATE menu_items SET price = ? WHERE id = ? AND restaurant_id = ?",
                        [$value, $id, $restaurantId]
                    );
                    break;
                    
                default:
                    throw new Exception('Campo non supportato');
            }
            
            $this->jsonResponse(['success' => true]);
            
        } catch (Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }
    
    public function updateOrder() {
        if (!$this->auth->isRestaurantAdmin()) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $order = $input['order'] ?? [];
        
        $restaurantId = $this->auth->getCurrentRestaurantId();
        
        try {
            foreach ($order as $item) {
                $id = (int)$item['id'];
                $sortOrder = (int)$item['order'];
                
                $this->db->update(
                    "UPDATE menu_categories SET sort_order = ? WHERE id = ? AND restaurant_id = ?",
                    [$sortOrder, $id, $restaurantId]
                );
                
                $this->db->update(
                    "UPDATE menu_items SET sort_order = ? WHERE id = ? AND restaurant_id = ?",
                    [$sortOrder, $id, $restaurantId]
                );
            }
            
            $this->jsonResponse(['success' => true]);
            
        } catch (Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }
    
    public function upload() {
        if (!$this->auth->isRestaurantAdmin()) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }
        
        if (!isset($_FILES['file'])) {
            $this->jsonResponse(['error' => 'No file uploaded'], 400);
        }
        
        try {
            $directory = $_POST['directory'] ?? 'general';
            $filePath = $this->uploadImage($_FILES['file'], $directory);
            
            $this->jsonResponse([
                'success' => true,
                'file_path' => $filePath,
                'url' => BASE_URL . '/uploads/' . $filePath
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
?>