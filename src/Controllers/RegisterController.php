<?php

namespace App\Controllers;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class RegisterController
{
    public function showRegisterPage()
    {
        $mustache = new Mustache_Engine([
            'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../../views'),
        ]);

        // Render the registration form
        echo $mustache->render('register');
    }

    public function register()
    {
        global $conn; // Use the database connection from init.php

        // Get form inputs
        $id_number = $_POST['id_number'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Check if all fields are filled
        if ($id_number && $last_name && $email && $password) {
            try {
                // Save plain-text password
                $stmt = $conn->prepare("INSERT INTO users (id_number, last_name, email, password) VALUES (:id_number, :last_name, :email, :password)");
                $stmt->bindValue(':id_number', $id_number, \PDO::PARAM_STR);
                $stmt->bindValue(':last_name', $last_name, \PDO::PARAM_STR);
                $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
                $stmt->bindValue(':password', $password, \PDO::PARAM_STR); // Plain-text password

                // Execute the statement
                if ($stmt->execute()) {
                    // Display success message and redirect
                    echo "<!DOCTYPE html>
                    <html lang='en'>
                    <head>
                        <meta charset='UTF-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                        <title>Registration Successful</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f4f6f8;
                                margin: 0;
                                padding: 0;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                height: 100vh;
                                text-align: center;
                            }
                            .message-container {
                                background-color: #ffffff;
                                padding: 40px 60px;
                                border-radius: 12px;
                                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                                max-width: 500px;
                                width: 90%;
                            }
                            .message-container h1 {
                                color: #28a745;
                                font-size: 28px;
                                margin-bottom: 10px;
                            }
                            .message-container p {
                                color: #555;
                                font-size: 16px;
                                margin-bottom: 20px;
                            }
                            .redirect-info {
                                color: #888;
                                font-size: 14px;
                                margin-top: 10px;
                            }
                            .success-icon {
                                font-size: 50px;
                                color: #28a745;
                                margin-bottom: 20px;
                            }
                            @keyframes fadeIn {
                                from {
                                    opacity: 0;
                                }
                                to {
                                    opacity: 1;
                                }
                            }
                            .message-container {
                                animation: fadeIn 1s ease-in-out;
                            }
                        </style>
                        <script>
                            setTimeout(function() {
                                window.location.href = '/login'; // Redirect to login page after 3 seconds
                            }, 3000);
                        </script>
                    </head>
                    <body>
                        <div class='message-container'>
                            <div class='success-icon'>✔</div>
                            <h1>Registration Successful!</h1>
                            <p>Your account has been successfully created.</p>
                            <p class='redirect-info'>Redirecting to the login page...</p>
                        </div>
                    </body>
                    </html>";
                    exit;
                } else {
                    echo "Error: Unable to register user.";
                }
            } catch (\PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }
        } else {
            echo "Please fill in all fields.";
        }
    }
}
