<?php
class HomeController extends BaseController {

    private $events;

    public function __construct($pdo) {
        $this->events = new Events($pdo);
    }

    public function index() {
        try {
            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $eventsPerPage = 6;
            $offset = ($currentPage - 1) * $eventsPerPage;
            $totalEvents = $this->events->getPublicTotalEvents();
            $event_list = $this->events->getPublicEvents($offset, $eventsPerPage);
            $totalPages = ceil($totalEvents / $eventsPerPage);
            include "views/main/home.php";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    }


}
