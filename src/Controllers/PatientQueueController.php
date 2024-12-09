<?php

namespace App\Controllers;

use PDO;

class PatientQueueController
{
    private $conn;
    private $mustache;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->mustache = new \Mustache_Engine([
            'loader' => new \Mustache_Loader_FilesystemLoader(__DIR__ . '/../../views'),
        ]);
    }

    public function index()
    {
        $stmt = $this->conn->prepare("SELECT * FROM patient_queue ORDER BY created_at DESC");
        $stmt->execute();
        $queue = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Count the patients by checkup type
        $general_count = 0;
        $dental_count = 0;
        $eye_count = 0;

        foreach ($queue as $patient) {
            if ($patient['checkup_type'] === 'General Checkup') {
                $general_count++;
            } elseif ($patient['checkup_type'] === 'Dental Checkup') {
                $dental_count++;
            } elseif ($patient['checkup_type'] === 'Eye Checkup') {
                $eye_count++;
            }
        }

        // Pass the count values to the template
        echo $this->mustache->render('patient_queue', [
            'queue' => $queue,
            'general_count' => $general_count,
            'dental_count' => $dental_count,
            'eye_count' => $eye_count,
        ]);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $queue_number = $_POST['queue_number'];
            $checkup_type = $_POST['checkup_type'];

            $stmt = $this->conn->prepare("INSERT INTO patient_queue (queue_number, checkup_type, status, created_at) VALUES (:queue_number, :checkup_type, 'Waiting', NOW())");
            $stmt->bindParam(':queue_number', $queue_number);
            $stmt->bindParam(':checkup_type', $checkup_type);
            $stmt->execute();
        }

        header("Location: /queue");
        exit;
    }

    public function remove()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $stmt = $this->conn->prepare("DELETE FROM patient_queue WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }

        header("Location: /queue");
        exit;
    }
}
