<?php

namespace App\Controllers;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class AppointmentsController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index()
    {
        $mustache = new Mustache_Engine([
            'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../../views'),
        ]);

        $stmt = $this->conn->query("SELECT * FROM appointments");
        $appointments = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        echo $mustache->render('appointments', ['appointments' => $appointments]);
    }

    public function create()
    {
        $student_id = $_POST['student_id'] ?? null;
        $appointment_date = $_POST['appointment_date'] ?? null;
        $type_of_checkup = $_POST['type_of_checkup'] ?? null;

        if ($student_id && $appointment_date && $type_of_checkup) {
            try {
                $stmt = $this->conn->prepare("INSERT INTO appointments (student_id, appointment_date, type_of_checkup) VALUES (:student_id, :appointment_date, :type_of_checkup)");
                $stmt->bindValue(':student_id', $student_id);
                $stmt->bindValue(':appointment_date', $appointment_date);
                $stmt->bindValue(':type_of_checkup', $type_of_checkup);
                $stmt->execute();

                header("Location: /appointments");
                exit;
            } catch (\PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }
        } else {
            echo "Please fill in all fields.";
        }
    }

    public function remove()
    {
        $id = $_POST['id'] ?? null;

        if ($id) {
            try {
                $stmt = $this->conn->prepare("DELETE FROM appointments WHERE id = :id");
                $stmt->bindValue(':id', $id);
                $stmt->execute();

                header("Location: /appointments");
                exit;
            } catch (\PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }
        } else {
            echo "Invalid appointment ID.";
        }
    }
}
