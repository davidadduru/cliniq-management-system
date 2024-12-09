<?php

namespace App\Controllers;

class UserProfileController
{
    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Fetch user data
        $stmt = $this->conn->prepare("SELECT id_number, last_name, email FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Render user profile page using Mustache
        $mustache = new \Mustache_Engine([
            'loader' => new \Mustache_Loader_FilesystemLoader(__DIR__ . '/../../views'),
        ]);
        echo $mustache->render('user_profile', ['user' => $user]);
    }

    public function update()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $id_number = $_POST['id_number'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];

        // Update user data
        $stmt = $this->conn->prepare("UPDATE users SET id_number = :id_number, last_name = :last_name, email = :email WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->bindParam(':id_number', $id_number);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            header("Location: /profile");
        } else {
            echo "Error updating profile.";
        }
    }
}
