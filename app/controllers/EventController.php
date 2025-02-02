<?php

class EventController extends BaseController {

    private $events;
    private $pdo;

    public function __construct($pdo) {
        $this->events = new Events($pdo);
        $this->pdo = $pdo;
    }
    public function index()
    {
        try {
            $perPage = 3;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $start = ($page - 1) * $perPage;
            $totalEvents = $this->events->getTotalEvents();
            $totalPages = ceil($totalEvents / $perPage);
            $managedEvents = $this->events->getEvents($start, $perPage);
            include "views/main/my_events.php";
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $requiredFields = [
                'title' => 'Event title is required.',
                'event_date' => 'Event date is required.',
                'event_time' => 'Event time is required.',
                'location' => 'Event location is required.',
                'total_slot' => 'Total slot must be a positive number.',
                'reg_last_date' => 'Deadline date is required.'
            ];

            foreach ($requiredFields as $field => $message) {
                if (empty($_POST[$field])) {
                    $errors[] = $message;
                } elseif ($field === 'total_slot' && (!is_numeric($_POST[$field]) || $_POST[$field] <= 0)) {
                    $errors[] = 'Total slot must be a positive number.';
                }
            }

            # If there are errors, store them in session and redirect
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            try {
                $this->pdo->beginTransaction();

                $this->events->add([
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'event_date' => $_POST['event_date'],
                    'event_time' => $_POST['event_time'],
                    'location' => $_POST['location'],
                    'total_slot' => $_POST['total_slot'],
                    'reg_last_date' => $_POST['reg_last_date']
                ]);

                $this->pdo->commit();
                $_SESSION['success'] = "Event created successfully!";

                header("Location: my-events");
                exit;

            } catch (Exception $e) {
                $this->pdo->rollBack();
                $_SESSION['error'] = "Database error: " . $e->getMessage();
                header("Location: create-event");
                exit;
            }
        }
        include "views/main/create_event.php";
    }
    public function edit($encryptedId) {
        $event_id = $this->encrypt_decrypt('decrypt', urldecode($encryptedId));
        if (!$event_id || !is_numeric($event_id)) {
            $_SESSION['error'] = "Invalid parameter or request";
            header("Location: my-events");
            exit;
        }

        $event_info = $this->events->getSingle($event_id);

        if (!$event_info) {
            $_SESSION['error'] = "Event information not found.";
            header("Location: my-events");
            exit;
        }

        include "views/main/edit_event.php";
    }
    public function update()
    {
        $event_id = $this->encrypt_decrypt('decrypt', $_POST['id']);

        if (!$event_id || !is_numeric($event_id)) {
            $_SESSION['error'] = "Invalid parameter or request.";
            header("Location: my-events");
            exit;
        }

        $errors = [];
        $requiredFields = [
            'title' => 'Event title is required.',
            'event_date' => 'Event date is required.',
            'event_time' => 'Event time is required.',
            'location' => 'Event location is required.',
            'total_slot' => 'Total slot must be a positive number.',
            'reg_last_date' => 'Deadline date is required.'
        ];

        foreach ($requiredFields as $field => $message) {
            if (empty($_POST[$field])) {
                $errors[] = $message;
            } elseif ($field === 'total_slot' && (!is_numeric($_POST[$field]) || $_POST[$field] <= 0)) {
                $errors[] = 'Total slot must be a positive number.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: edit-event?id=" . urlencode($_POST['id']));
            exit;
        }

        try {
            $this->pdo->beginTransaction();

            $this->events->update($event_id, [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'event_date' => $_POST['event_date'],
                'event_time' => $_POST['event_time'],
                'location' => $_POST['location'],
                'total_slot' => $_POST['total_slot'],
                'reg_last_date' => $_POST['reg_last_date']
            ]);

            $this->pdo->commit();
            $_SESSION['success'] = "Event updated successfully!";
            header("Location: my-events");
            exit;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $_SESSION['error'] = "Database error: " . $e->getMessage();
            header("Location: edit-event?id=" . urlencode($_POST['id']));
            exit;
        }
    }

    public function delete($encryptedId)
    {
        $event_id = $this->encrypt_decrypt('decrypt',$encryptedId);

        if (!$event_id || !is_numeric($event_id)) {
            $_SESSION['error'] = "Invalid parameter or request.";
            header("Location: my-events");
            exit;
        }

        try {
            $this->pdo->beginTransaction();

            $this->events->delete($event_id);
            $this->pdo->commit();
            $_SESSION['success'] = "Event deleted successfully!";
            header("Location: my-events");
            exit;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $_SESSION['error'] = "Database error: " . $e->getMessage();
            header("Location: /my-events");
            exit;
        }
    }
}
?>