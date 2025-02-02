<?php

class AuthController extends BaseController
{
    private $user;
    private $pdo;

    public function __construct($pdo)
    {
        $this->user = new User($pdo);
        $this->pdo = $pdo;
    }

    public function register()
    {
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
                $this->pdo->rollBack();
                echo "Error: " . $e->getMessage();
            }
        }

        include "views/auth/register.php";
    }


    public function login()
    {
        if(!isset($_SESSION['user'])) {
            include "views/auth/login.php";
        } else {
            header('Location: /event-management');
        }
    }
    public function login_attempt()
    {
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $user = $this->user->findByEmail($email);

        if ($user && password_verify($password, $user[0]['password'])) {
            $_SESSION['user'] = $user[0];
            header('Location: /event-management');
        } else {
            $_SESSION['error'] = "Incorrect email or password!";
            header('Location: login');
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /event-management');
    }
}
