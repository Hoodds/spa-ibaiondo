<?php
class Router {
    private $routes = [];
    
    public function add($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function dispatch() {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Eliminar la base de la URL si existe
        $baseFolder = '/spa-ibaiondo';
        if (strpos($uri, $baseFolder) === 0) {
            $uri = substr($uri, strlen($baseFolder));
        }
        
        // Eliminar parámetros de consulta
        $uri = explode('?', $uri)[0];
        
        // Si la URI está vacía, establecerla como '/'
        if (empty($uri)) {
            $uri = '/';
        }
        
        foreach ($this->routes as $route) {
            // Convertir ruta con parámetros a expresión regular
            $pattern = $this->convertRouteToRegex($route['path']);
            
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Eliminar la coincidencia completa
                
                $controller = $route['controller'];
                $action = $route['action'];
                
                // Cargar el controlador
                require_once BASE_PATH . '/app/controllers/' . $controller . '.php';
                
                // Instanciar el controlador y llamar a la acción con los parámetros
                $controllerInstance = new $controller();
                call_user_func_array([$controllerInstance, $action], $matches);
                return;
            }
        }
        
        // Si no se encuentra ninguna ruta
        header("HTTP/1.0 404 Not Found");
        include BASE_PATH . '/app/views/errors/404.php';
    }
    
    private function convertRouteToRegex($route) {
        // Convertir parámetros como {id} a expresiones regulares
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
        return '#^' . $pattern . '$#';
    }
}