<?php

namespace App\Models;

class AppointmentModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAppointments()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM appointments");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }
}
