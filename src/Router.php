<?php
namespace App;

class Router {
    private $routes = [];

    //CRUD Methods
    public function get($uri, $action){
         $this->routes['GET'][$uri] = $action; 
    }
    
    public function post($uri, $action){ 
        $this->routes['POST'][$uri] = $action; 
    }

    public function put($uri, $action){
         $this->routes['PUT'][$uri] = $action; 
    }

    public function delete($uri, $action){
         $this->routes['DELETE'][$uri] = $action; 
    }

    

    public function dispatch($uri, $method, $dbConnection) {

        // Overrides the POST method (to PUT/PATCH/DELETE) allowing record updates via FormData
        if ($method === 'POST' && isset($_POST['_method'])) {
        $method = strtoupper($_POST['_method']);
        
        }

        // 1. Clean the URI: remove query strings and trim slashes
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = ($uri !== '/') ? rtrim($uri, '/') : $uri;

        if (!isset($this->routes[$method])) {
            http_response_code(405);
            echo json_encode(["error" => "Method not allowed"]);
            return;
        }

        // 2. Direct match (Normalized)
        foreach ($this->routes[$method] as $route => $action) {
            // Normalize route for comparison
            $normalizedRoute = ($route !== '/') ? rtrim($route, '/') : $route;
            
            if ($normalizedRoute === $uri) {
                $controller = new $action[0]($dbConnection);
                return $controller->{$action[1]}(); 
            }
        }

        // 3. Dynamic match ({id})
        foreach($this->routes[$method] as $route => $action) {
            $pattern = preg_replace('/\{id\}/', '(\d+)', $route);
            // Add delimiters and anchors
            if(preg_match("#^" . $pattern . "$#", $uri, $matches)){
                $id = $matches[1];
                $controller = new $action[0]($dbConnection);
                return $controller->{$action[1]}($id);
            }
        }

        // 4. Detailed Error for Debugging
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Application not found - Router mismatch 🕵️‍♂️",
            "debug" => [
                "received_method" => $method,
                "received_uri" => $uri,
                "registered_routes" => array_keys($this->routes[$method])
            ]
        ]);
    }
}