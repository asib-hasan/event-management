<?php

require_once 'Router.php';

class FrontController {
    private $router;

    public function __construct() {
        $this->router = new Router();
    }

    public function run() {
        #$this->checkAuthentication();

        $this->router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }

    public function getRouter() {
        return $this->router;
    }

    private function checkAuthentication() {
        session_start();

        $protectedRoutes = ['/add-event', '/dashboard','/my-events','/edit-event','/delete-event'];

        if (in_array($_SERVER['REQUEST_URI'], $protectedRoutes) && !isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
    }
}
