<?php

namespace App\Controllers;

use Mustache_Engine;

class MedicalHistoryController
{
    protected $conn;

    public function __construct($conn = null)
    {
        $this->conn = $conn; // Store DB connection
    }

    // Display Medical History page
    public function index()
    {
        // Example medical history data (can be replaced with DB data if needed)
        $history = [
            ['id' => 1, 'date' => '2024-01-15', 'condition' => 'Flu', 'treatment' => 'Rest and hydration'],
            ['id' => 2, 'date' => '2024-03-22', 'condition' => 'Headache', 'treatment' => 'Pain relievers'],
            ['id' => 3, 'date' => '2024-05-10', 'condition' => 'Back pain', 'treatment' => 'Physical therapy'],
        ];

        // Render the Mustache template
        $mustache = new Mustache_Engine;
        $template = file_get_contents('src/Views/medical_history.mustache');
        echo $mustache->render($template, ['history' => $history]);
    }
}
