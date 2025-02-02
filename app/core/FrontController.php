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
        $protectedRoutes = ['create-event', 'my-events', '/edit-event', '/delete-event'];

        if (in_array($_SERVER['REQUEST_URI'], $protectedRoutes)) {
            if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_agent']) || !isset($_SESSION['ip_address'])) {
                header('Location: /login');
                exit();
            }

            if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT'] || $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
                session_unset();
                session_destroy();
                header('Location: /login');
                exit();
            }

            if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
                session_unset();
                session_destroy();
                header('Location: /login');
                exit();
            }

            $_SESSION['last_activity'] = time();
        }
    }

}
