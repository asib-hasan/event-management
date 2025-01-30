<?php
ob_start();
session_start();
require_once 'app/core/QueryBuilder.php';
require_once 'app/core/FrontController.php';
require_once 'config/Database.php';
require_once 'app/models/User.php';
require_once 'app/controllers/AuthController.php';

$pdo = Database::getInstance()->getConnection();
$authController = new AuthController($pdo);

$frontController = new FrontController();
$router = $frontController->getRouter();

$router->add('GET', '/register', function() use ($authController) {
    $authController->register();
});

$router->add('POST', '/register', function() use ($authController) {
    $authController->register();
});

$router->add('GET', '/login', function() use ($authController) {
    $authController->login();
});
$router->add('GET', '/', function() use ($authController) {
    $authController->login();
});

$router->add('POST', '/login', function() use ($authController) {
    $authController->login();
});

$router->add('GET', '/logout', function() use ($authController) {
    $authController->logout();
});

// Start application
$frontController->run();
