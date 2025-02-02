<?php

class EventController extends BaseController {

    private $events;
    private $attendees;
    private $pdo;

    public function __construct($pdo) {
        $this->events = new Events($pdo);
        $this->attendees = new Attendee($pdo);
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
                    'user_id' => $_SESSION['user']['id'],
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
    public function event_details($encryptedId) {
        $event_id = $this->encrypt_decrypt('decrypt', $encryptedId);

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
        $attendees_list = $this->attendees->getAttendees($event_id);
        include "views/main/event_details.php";
    }

    public function download_attendees_csv() {
        $event_id = $this->encrypt_decrypt('decrypt', $_POST['event_id']);
        $event_info = $this->events->getSingle($event_id);
        if (!$event_info) {
            $_SESSION['error'] = "Event information not found.";
            header("Location: my-events");
            exit;
        }

        $attendees_list = $this->attendees->getAttendees($event_id);

        if (empty($attendees_list)) {
            $_SESSION['error'] = "No attendees found for this event.";
            header("Location: event-details?id=" . $event_id);
            exit;
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="attendees_list_' . $event_info['title'] . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Gender', 'Age']);

        foreach ($attendees_list as $attendee) {
            fputcsv($output, [
                $attendee['id'],
                $attendee['name'],
                $attendee['email'],
                $attendee['phone'],
                $attendee['gender'],
                $attendee['age']
            ]);
        }

        fclose($output);
        exit();
    }


    #attendees registration

    public function register_new_event($encryptedId) {
        $event_id = $this->encrypt_decrypt('decrypt', $encryptedId);

        if (!$event_id || !is_numeric($event_id)) {
            $_SESSION['error'] = "Invalid parameter or request";
            header("Location: /");
            exit;
        }
        $event_info = $this->events->getSingle($event_id);
        if (!$event_info) {
            $_SESSION['error'] = "Event information not found.";
            header("Location: /");
            exit;
        }
        include "views/main/register_event.php";
    }

    public function register_new_event_update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $event_id = $this->encrypt_decrypt('decrypt', $_POST['event_id']);

            if (!$event_id || !is_numeric($event_id)) {
                header("Location: /");
                exit();
            }

            $event_info = $this->events->getSingle($event_id);
            if (!$event_info) {
                header("Location: /");
                exit();
            }

            if ($event_info['total_slot'] <= 0) {
                echo json_encode(['status' => 'error', 'message' => 'No slot available.']);
                exit;
            }

            $requiredFields = [
                'name' => 'Your name is required.',
                'email' => 'Email is required.',
                'phone' => 'Phone number is required.',
                'gender' => 'Gender is required.',
                'age' => 'Age must be a positive number.'
            ];

            foreach ($requiredFields as $field => $message) {
                if (empty($_POST[$field])) {
                    $errors[] = $message;
                } elseif ($field === 'age' && (!is_numeric($_POST[$field]) || $_POST[$field] <= 0)) {
                    $errors[] = 'Age must be a positive number.';
                }
            }

            if (!empty($errors)) {
                echo json_encode(['status' => 'error', 'message' => implode("\n", $errors)]);
                exit;
            }

            try {
                $this->pdo->beginTransaction();
                $this->attendees->add([
                    'event_id' => $event_id,
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'phone' => $_POST['phone'],
                    'gender' => $_POST['gender'],
                    'age' => $_POST['age'],
                ]);
                # decrement slot
                $this->events->update($event_id, [
                    'total_slot' => $event_info['total_slot'] - 1,
                ]);
                $this->pdo->commit();
                echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
                exit;
            } catch (Exception $e) {
                $this->pdo->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
                exit;
            }
        }
        include "views/main/register_event.php";
    }


    # ajax call
    public function search_event($searchTerm) {
        if (empty($searchTerm)) {
            echo json_encode(['status' => 'error', 'message' => 'Search term cannot be empty.']);
            exit();
        }
        $searchTerm = "%" . $searchTerm . "%";
        try {
            $sql = "SELECT * FROM events WHERE title LIKE :searchTerm";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['searchTerm' => $searchTerm]);
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($events) {
                echo json_encode(['status' => 'success', 'events' => $events]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No events found.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit();
    }

}
?>