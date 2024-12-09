<?php

namespace App\Controllers;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class LoginController
{
    public function showLoginPage()
    {
        $mustache = new Mustache_Engine([
            'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../../views'),
        ]);

        // Check if there's an error message in the session
        session_start();
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']); // Clear error message after displaying

        // Render the login page with the error (if any)
        echo $mustache->render('login', ['error' => $error]);
    }

    public function authenticate()
    {
        global $conn; // Use the database connection from init.php

        $id_number = $_POST['id_number'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validate inputs
        if (!$id_number || !$password) {
            $_SESSION['login_error'] = 'Please provide both ID number and password.';
            header('Location: /login');
            exit;
        }

        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE id_number = :id_number");
            $stmt->bindValue(':id_number', $id_number, \PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch();

            if ($user && $user['password'] === $password) {
                // Login successful
                session_start();
                $_SESSION['user_id'] = $user['id'];
                header('Location: /dashboard');
                exit;
            } else {
                // Login failed
                session_start();
                $_SESSION['login_error'] = 'Invalid ID number or password.';
                header('Location: /login');
                exit;
            }
        } catch (\PDOException $e) {
            die('Database error: ' . $e->getMessage());
        }
    }
}
