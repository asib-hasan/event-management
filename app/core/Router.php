<?php

class Router {
    private $routes = [];

    public function add($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
        ];
    }

    public function dispatch($method, $uri) {
        $requestUri = str_replace('/event-management', '', parse_url($uri, PHP_URL_PATH));

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $requestUri) {
                call_user_func($route['callback']);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

}
