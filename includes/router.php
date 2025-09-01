<?php
class Router {
    private $routes = [];
    
    public function addRoute($pattern, $controller, $method) {
        $this->routes[] = [
            'pattern' => $pattern,
            'controller' => $controller,
            'method' => $method
        ];
    }
    
    public function dispatch($uri) {
        foreach ($this->routes as $route) {
            $pattern = str_replace('/', '\/', $route['pattern']);
            $pattern = preg_replace('/\{(\w+)\}/', '([^\/]+)', $pattern);
            $pattern = '/^' . $pattern . '$/';
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                
                if (file_exists($route['controller'])) {
                    require_once $route['controller'];
                    
                    $controllerName = $this->getControllerName($route['controller']);
                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();
                        if (method_exists($controller, $route['method'])) {
                            call_user_func_array([$controller, $route['method']], $matches);
                            return;
                        }
                    }
                }
            }
        }
        
        $this->show404();
    }
    
    private function getControllerName($path) {
        $filename = basename($path, '.php');
        return $filename;
    }
    
    private function show404() {
        http_response_code(404);
        include 'views/404.php';
    }
}
?>