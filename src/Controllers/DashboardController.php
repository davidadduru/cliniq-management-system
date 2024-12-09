<?php

namespace App\Controllers;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class DashboardController
{
    public function index()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $mustache = new Mustache_Engine([
            'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../../views'),
        ]);

        // Render the dashboard view
        echo $mustache->render('dashboard');
    }
}
