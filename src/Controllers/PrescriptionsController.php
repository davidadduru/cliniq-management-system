<?php

namespace App\Controllers;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class PrescriptionsController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index()
    {
        session_start();

        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $student_id = $_SESSION['user_id'];

        try {
            // Fetch prescriptions for the logged-in user
            $stmt = $this->conn->prepare("SELECT * FROM prescriptions WHERE student_id = :student_id");
            $stmt->bindParam(':student_id', $student_id, \PDO::PARAM_INT);
            $stmt->execute();
            $prescriptions = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Render the view with fetched prescriptions
            $mustache = new Mustache_Engine([ 
                'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../../views'),
            ]);
            echo $mustache->render('prescriptions', ['prescriptions' => $prescriptions]);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function create()
    {
        session_start();

        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $student_id = $_SESSION['user_id'];
        $prescription_details = $_POST['prescription_details'] ?? '';
        $doctor_name = $_POST['doctor_name'] ?? '';
        $issued_date = $_POST['issued_date'] ?? '';

        if ($prescription_details && $doctor_name && $issued_date) {
            try {
                // Insert a new prescription into the database
                $stmt = $this->conn->prepare("
                    INSERT INTO prescriptions (student_id, prescription_details, doctor_name, issued_date) 
                    VALUES (:student_id, :prescription_details, :doctor_name, :issued_date)
                ");
                $stmt->bindParam(':student_id', $student_id, \PDO::PARAM_INT);
                $stmt->bindParam(':prescription_details', $prescription_details, \PDO::PARAM_STR);
                $stmt->bindParam(':doctor_name', $doctor_name, \PDO::PARAM_STR);
                $stmt->bindParam(':issued_date', $issued_date, \PDO::PARAM_STR);

                if ($stmt->execute()) {
                    header('Location: /prescriptions');
                    exit;
                } else {
                    echo "Error: Unable to add prescription.";
                }
            } catch (\PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }
        } else {
            echo "Error: All fields are required.";
        }
    }

    // Remove prescription
    public function remove()
    {
        session_start();

        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Get the prescription ID from the POST request
        $prescription_id = $_POST['id'] ?? null;

        if ($prescription_id) {
            try {
                // Delete the prescription
                $stmt = $this->conn->prepare("DELETE FROM prescriptions WHERE id = :id");
                $stmt->bindParam(':id', $prescription_id, \PDO::PARAM_INT);

                if ($stmt->execute()) {
                    header('Location: /prescriptions');
                    exit;
                } else {
                    echo "Error: Unable to remove prescription.";
                }
            } catch (\PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }
        } else {
            echo "Error: Invalid prescription ID.";
        }
    }
}
