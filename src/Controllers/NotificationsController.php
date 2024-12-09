<?php

namespace App\Controllers;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class NotificationsController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        // Example notifications
        $notifications = [
            [
                'id' => 1,
                'message' => 'You can schedule a Dental Checkup now!',
                'read_status' => false,
                'created_at' => '2024-11-26',
            ],
            [
                'id' => 2,
                'message' => 'The clinic will be closed for the holidays.',
                'read_status' => false,
                'created_at' => '2024-12-20',
            ],
        ];

        $mustache = new Mustache_Engine([
            'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../../views'),
        ]);

        // Render the notifications page
        echo $mustache->render('notifications', ['notifications' => $notifications]);
    }

    public function markAsRead($id)
    {
        // Example: Update the notification's read status (simulate with output)
        echo "Notification {$id} marked as read.";
    }
}
