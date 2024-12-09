<?php

namespace App\Controllers;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class LandingController
{
    public function index()
    {
        try {
            $mustache = new Mustache_Engine([
                'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../../views'),
            ]);

            echo $mustache->render('landing');
        } catch (\Exception $e) {
            echo "Error rendering view: " . $e->getMessage();
        }
    }
}
