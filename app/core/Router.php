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
        // Remove the base path if any, assuming it's '/project-folder' in your case
        $uri = preg_replace('/^\/[^\/]+\//', '/', $uri);  // Strip base folder path

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $uri) {
                call_user_func($route['callback']);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

}
