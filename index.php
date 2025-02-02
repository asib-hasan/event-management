<?php
ob_start();
session_start();
require_once 'app/core/QueryBuilder.php';
require_once 'app/core/FrontController.php';
require_once 'config/Database.php';
require_once 'app/models/User.php';
require_once 'app/models/Events.php';
require_once 'app/controllers/BaseController.php';
require_once 'app/controllers/AuthController.php';
require_once 'app/controllers/HomeController.php';
require_once 'app/controllers/EventController.php';
require_once 'app/controllers/AttendeesController.php';


$pdo = Database::getInstance()->getConnection();
$authController = new AuthController($pdo);
$frontController = new FrontController();
$homeController = new HomeController($pdo);
$eventController = new EventController($pdo);
$router = $frontController->getRouter();

$router->add('GET', '/register', function() use ($authController) {
    $authController->register();
});
$router->add('GET', '/', function() use ($homeController) {
    $homeController->index();
});
$router->add('GET', '/my-events', function() use ($eventController) {
    $eventController->index();
});
$router->add('GET', '/create-event', function() use ($eventController) {
    $eventController->create();
});
$router->add('GET', '/edit-event', function() use ($eventController) {
    $eventController->edit($_GET['id']);
});
$router->add('POST', '/update-event', function() use ($eventController) {
    $eventController->update();
});
$router->add('GET', '/delete-event', function() use ($eventController) {
    $eventController->delete($_GET['id']);
});
$router->add('POST', '/create-event', function() use ($eventController) {
    $eventController->create();
});
$router->add('POST', '/register', function() use ($authController) {
    $authController->register();
});
$router->add('GET', '/login', function() use ($authController) {
    $authController->login();
});
$router->add('POST', '/login', function() use ($authController) {
    $authController->login();
});

$router->add('GET', '/logout', function() use ($authController) {
    $authController->logout();
});

$frontController->run();
