<?php

class AuthController {
    private $user;

    public function __construct($pdo) {
        $this->user = new User($pdo);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            if ($this->user->findByEmail($email)) {
                echo "Email already exists!";
                return;
            }

            $this->user->register([
                'name' => $name,
                'email' => $email,
                'password' => $password
            ]);

            header('Location: /login');
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
