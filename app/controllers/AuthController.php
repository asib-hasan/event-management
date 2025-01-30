<?php

class AuthController {
    private $user;
    private $pdo;

    public function __construct($pdo) {
        $this->user = new User($pdo);
        $this->pdo = $pdo;
    }

    public function register() {
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $password = $_POST['password'];
            $password_confirmation = $_POST['password_confirmation'];

            $errors = [];

            if (empty($name)) {
                $errors[] = "Name is required!";
            }

            if (!preg_match("/^[a-zA-Z\s-]+$/", $name)) {
                $errors[] = "Invalid name format!";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Please enter a valid email!";
            }

            if (strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters!";
            }

            if ($password !== $password_confirmation) {
                $errors[] = "Passwords do not match!";
            }

            if ($this->user->findByEmail($email)) {
                $errors[] = "Email already exists!";
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header('Location: /event-management/register');
                exit;
            }

            // Start the transaction
            $this->pdo->beginTransaction();

            try {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $this->user->register([
                    'name' => $name,
                    'email' => $email,
                    'password' => $hashedPassword
                ]);

                $this->pdo->commit();

                header('Location: /event-management/login');
                exit;
            } catch (Exception $e) {
                // Rollback the transaction if something goes wrong
                $this->pdo->rollBack();
                echo "Error: " . $e->getMessage(); // Output the error message
            }
        }

        include "views/auth/register.php";
    }



    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars($_POST['email']);
            $password = $_POST['password'];

            $user = $this->user->findByEmail($email);
            if ($user && password_verify($password, $user[0]['password'])) {
                session_start();
                $_SESSION['user'] = $user[0];
                header('Location: /dashboard');
            } else {
                echo "Invalid credentials!";
            }
        }

        include "views/auth/login.php";
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login');
    }
}
