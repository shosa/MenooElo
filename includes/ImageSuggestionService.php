<?php

class ImageSuggestionService {
    private $unsplashAccessKey = null; // Configurare in config/config.php
    
    public function __construct() {
        // Leggi la chiave dalla configurazione
        $this->unsplashAccessKey = defined('UNSPLASH_ACCESS_KEY') ? UNSPLASH_ACCESS_KEY : null;
    }
    
    public function searchImages($query, $count = 4) {
        // Sanitize query for food context
        $foodQuery = $this->prepareFoodQuery($query);
        
        // Try Unsplash first
        $images = $this->searchUnsplash($foodQuery, $count);
        
        // If Unsplash fails, use fallback
        if (empty($images)) {
            $images = $this->getFallbackImages($foodQuery, $count);
        }
        
        return $images;
    }
    
    private function prepareFoodQuery($query) {
        // Add food-related terms to improve results
        $foodTerms = [' food', ' dish', ' recipe', ' cuisine', ' cooking'];
        $query = trim(strtolower($query));
        
        // Remove common words that might confuse the search
        $stopWords = ['il', 'la', 'di', 'del', 'della', 'con', 'alle', 'alla', 'in'];
        $words = explode(' ', $query);
        $filteredWords = array_filter($words, function($word) use ($stopWords) {
            return !in_array(strtolower($word), $stopWords) && strlen($word) > 2;
        });
        
        $cleanQuery = implode(' ', $filteredWords);
        return $cleanQuery . ' food dish';
    }
    
    private function searchUnsplash($query, $count) {
        // Se non abbiamo API key, usa immagini mock
        if (!$this->unsplashAccessKey) {
            error_log("Unsplash API key not configured, using mock images");
            return $this->getMockImages($query, $count);
        }
        
        try {
            $url = "https://api.unsplash.com/search/photos";
            $params = [
                'query' => $query,
                'per_page' => $count,
                'orientation' => 'landscape',
                'content_filter' => 'high'
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Client-ID ' . $this->unsplashAccessKey,
                'Accept-Version: v1',
                'User-Agent: MenooElo/1.0'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                error_log("Unsplash API curl error: " . $error);
                return $this->getMockImages($query, $count);
            }
            
            if ($httpCode === 200) {
                $data = json_decode($response, true);
                
                if (isset($data['results']) && !empty($data['results'])) {
                    $images = [];
                    
                    foreach ($data['results'] as $result) {
                        $images[] = [
                            'id' => $result['id'],
                            'url' => $result['urls']['regular'],
                            'thumb' => $result['urls']['thumb'],
                            'description' => $result['description'] ?? $result['alt_description'] ?? 'Food image',
                            'source' => 'unsplash',
                            'download_url' => $result['urls']['regular'],
                            // Dati per compliance Unsplash
                            'photographer_name' => $result['user']['name'],
                            'photographer_username' => $result['user']['username'],
                            'photographer_profile' => $result['user']['links']['html'],
                            'unsplash_photo_url' => $result['links']['html'],
                            'download_endpoint' => $result['links']['download_location']
                        ];
                    }
                    
                    return $images;
                }
            } else {
                error_log("Unsplash API error: HTTP $httpCode - " . $response);
            }
            
        } catch (Exception $e) {
            error_log("Unsplash API exception: " . $e->getMessage());
        }
        
        // Fallback a immagini mock se l'API fallisce
        return $this->getMockImages($query, $count);
    }
    
    private function getFallbackImages($query, $count) {
        // Fallback to mock images for demo
        return $this->getMockImages($query, $count);
    }
    
    private function getMockImages($query, $count) {
        // Pool di immagini mock organizzate per categoria di cibo
        $foodImagePools = [
            'pasta' => [
                ['id' => 'pasta_1', 'url' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400', 'description' => 'Spaghetti'],
                ['id' => 'pasta_2', 'url' => 'https://images.unsplash.com/photo-1621996346565-e3dbc353d2e5?w=400', 'description' => 'Carbonara'],
                ['id' => 'pasta_3', 'url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400', 'description' => 'Penne'],
                ['id' => 'pasta_4', 'url' => 'https://images.unsplash.com/photo-1563379091339-03246963d263?w=400', 'description' => 'Tagliatelle']
            ],
            'pizza' => [
                ['id' => 'pizza_1', 'url' => 'https://images.unsplash.com/photo-1565299507177-b0ac66763828?w=400', 'description' => 'Pizza Margherita'],
                ['id' => 'pizza_2', 'url' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=400', 'description' => 'Pizza Napoletana'],
                ['id' => 'pizza_3', 'url' => 'https://images.unsplash.com/photo-1520201163981-8cc95007dd2a?w=400', 'description' => 'Pizza al taglio'],
                ['id' => 'pizza_4', 'url' => 'https://images.unsplash.com/photo-1571407970349-bc81e7e96d47?w=400', 'description' => 'Pizza gourmet']
            ],
            'carne' => [
                ['id' => 'meat_1', 'url' => 'https://images.unsplash.com/photo-1558030006-450675393462?w=400', 'description' => 'Bistecca'],
                ['id' => 'meat_2', 'url' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=400', 'description' => 'Arrosto'],
                ['id' => 'meat_3', 'url' => 'https://images.unsplash.com/photo-1529692236671-f1f6cf9683ba?w=400', 'description' => 'Tagliata'],
                ['id' => 'meat_4', 'url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=400', 'description' => 'Spiedini']
            ],
            'pesce' => [
                ['id' => 'fish_1', 'url' => 'https://images.unsplash.com/photo-1580476262798-bddd9f4b7369?w=400', 'description' => 'Salmone'],
                ['id' => 'fish_2', 'url' => 'https://images.unsplash.com/photo-1559847844-d721426d6eaf?w=400', 'description' => 'Branzino'],
                ['id' => 'fish_3', 'url' => 'https://images.unsplash.com/photo-1549887534-1541e9326642?w=400', 'description' => 'Frutti di mare'],
                ['id' => 'fish_4', 'url' => 'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?w=400', 'description' => 'Pesce alla griglia']
            ],
            'dolce' => [
                ['id' => 'dessert_1', 'url' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=400', 'description' => 'Tiramisu'],
                ['id' => 'dessert_2', 'url' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400', 'description' => 'Gelato'],
                ['id' => 'dessert_3', 'url' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=400', 'description' => 'Torta'],
                ['id' => 'dessert_4', 'url' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=400', 'description' => 'Cannoli']
            ]
        ];
        
        // Default generic food images
        $defaultImages = [
            ['id' => 'generic_1', 'url' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=400', 'description' => 'Piatto gourmet'],
            ['id' => 'generic_2', 'url' => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400', 'description' => 'Cucina tradizionale'],
            ['id' => 'generic_3', 'url' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=400', 'description' => 'Ingredienti freschi'],
            ['id' => 'generic_4', 'url' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=400', 'description' => 'Specialità culinaria']
        ];
        
        // Aggiungi più categorie con sinonimi
        $categoryMatches = [
            'pasta' => ['pasta', 'spaghetti', 'penne', 'tagliatelle', 'carbonara', 'amatriciana', 'cacio', 'pepe'],
            'pizza' => ['pizza', 'margherita', 'napoletana', 'diavola', 'quattro stagioni', 'capricciosa'],
            'carne' => ['carne', 'bistecca', 'arrosto', 'tagliata', 'pollo', 'maiale', 'manzo', 'vitello', 'agnello'],
            'pesce' => ['pesce', 'salmone', 'branzino', 'orata', 'tonno', 'baccalà', 'frutti mare', 'gamberi'],
            'dolce' => ['dolce', 'tiramisu', 'gelato', 'torta', 'cannoli', 'panna cotta', 'semifreddo', 'dessert']
        ];
        
        // Determina quale pool usare basandosi sulla query
        $queryLower = strtolower($query);
        $selectedPool = $defaultImages;
        
        foreach ($categoryMatches as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($queryLower, $keyword) !== false) {
                    $selectedPool = $foodImagePools[$category];
                    break 2;
                }
            }
        }
        
        // Converti al formato richiesto
        $mockImages = [];
        foreach (array_slice($selectedPool, 0, $count) as $image) {
            $mockImages[] = [
                'id' => $image['id'],
                'url' => $image['url'],
                'thumb' => str_replace('?w=400', '?w=150', $image['url']),
                'description' => $image['description'] . ' - ' . ucfirst($query),
                'source' => 'demo',
                'download_url' => $image['url']
            ];
        }
        
        return $mockImages;
    }
    
    public function downloadAndSaveImage($imageUrl, $directory = 'menu-items') {
        try {
            // Download image
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $imageUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_USERAGENT, 'MenooElo/1.0');
            
            $imageData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            curl_close($ch);
            
            if ($httpCode !== 200 || empty($imageData)) {
                throw new Exception('Failed to download image');
            }
            
            // Determine file extension
            $extension = 'jpg';
            if (strpos($contentType, 'png') !== false) $extension = 'png';
            elseif (strpos($contentType, 'webp') !== false) $extension = 'webp';
            
            // Create filename
            $filename = uniqid() . '_suggested.' . $extension;
            
            // Save to uploads directory  
            $uploadDir = UPLOADS_PATH . '/' . $directory . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $filepath = $uploadDir . $filename;
            if (file_put_contents($filepath, $imageData) === false) {
                throw new Exception('Failed to save image');
            }
            
            return $filename;
            
        } catch (Exception $e) {
            error_log('Image download error: ' . $e->getMessage());
            throw $e;
        }
    }
}
?>