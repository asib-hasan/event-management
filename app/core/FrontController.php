<?php

require_once 'Router.php';

class FrontController {
    private $router;

    public function __construct() {
        $this->router = new Router();
    }

    public function run() {
        $prevent_request = $this->checkAuthentication();
        if($prevent_request) {
            session_destroy();
            header('Location: login');
            exit();
        } else {
            $this->router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
        }
    }

    public function getRouter() {
        return $this->router;
    }

    private function checkAuthentication() {
        $protectedRoutes = ['/create-event', '/my-events', '/edit-event', '/delete-event', '/event-details', '/download-attendees'];
        $requestUri = str_replace('/event-management', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        if (in_array($requestUri, $protectedRoutes)) {
            if (!isset($_SESSION['user'])) {
                return true;
            }
        } else {
            return false;
        }
    }

}
