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
        $uri = parse_url($uri, PHP_URL_PATH);

        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo json_encode(["error" => "Method not allowed"]);
            return;
        }

        // 
        if(isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            $controller = new $action[0]($dbConnection);

            //
            return $controller->{$action[1]}(); 
        }

        foreach($this->routes[$method] as $route => $action) {
            $pattern = preg_replace('/\{id\}/', '(\d+)', $route);
            
            if(preg_match("#^$pattern$#", $uri, $matches)){
                $id = $matches[1];
                $controller = new $action[0]($dbConnection);
                
                // 
                return $controller->{$action[1]}($id);
            }
        }

        http_response_code(404);
        echo json_encode(["Bienvenido, todo funcionando por aqui ✨🚀"]); 
    }
}