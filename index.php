<?php

require "vendor/autoload.php";
require "init.php";

use Bramus\Router\Router;
use App\Controllers\PrescriptionsController;
use App\Controllers\AppointmentsController;
use App\Controllers\MedicalHistoryController;
use App\Controllers\NotificationsController;
use App\Controllers\RegisterController;
use App\Controllers\PatientQueueController;
use App\Controllers\UserProfileController;
use App\Controllers\DashboardController;

// Create a Router instance
$router = new Router();

// Database connection (ensure it's accessible globally)
global $conn;

// Route: Landing Page
$router->get('/', '\App\Controllers\LandingController@index');

// Routes: Login
$router->get('/login', '\App\Controllers\LoginController@showLoginPage');
$router->post('/login', '\App\Controllers\LoginController@authenticate');

// Routes: Registration
$router->get('/register', function () {
    $controller = new RegisterController();
    $controller->showRegisterPage();
});
$router->post('/register', function () use ($conn) {
    $controller = new RegisterController($conn);
    $controller->register();
});

// Route: Dashboard
$router->get('/dashboard', function () use ($conn) {
    $controller = new DashboardController($conn);  // Pass $conn to DashboardController
    $controller->index();
});

// Routes: Appointments Management
$router->get('/appointments', function () use ($conn) {
    $controller = new AppointmentsController($conn);
    $controller->index();
});
$router->post('/appointments/create', function () use ($conn) {
    $controller = new AppointmentsController($conn);
    $controller->create();
});
$router->post('/appointments/remove', function () use ($conn) {
    $controller = new AppointmentsController($conn);
    $controller->remove();
});

// Routes: Medical History Management
$router->get('/medical-history', function () {
    $controller = new MedicalHistoryController();
    $controller->index();
});
$router->post('/medical-history/add', function () use ($conn) {
    $controller = new MedicalHistoryController($conn);
    $controller->add();
});

// Routes: Prescriptions Management
$router->get('/prescriptions', function () use ($conn) {
    $controller = new PrescriptionsController($conn);
    $controller->index();
});
$router->post('/prescriptions/create', function () use ($conn) {
    $controller = new PrescriptionsController($conn);
    $controller->create();
});
$router->post('/prescriptions/remove', function () use ($conn) {
    $controller = new PrescriptionsController($conn);
    $controller->remove();
});

// Routes: Notifications Management
$router->get('/notifications', function () use ($conn) {
    $controller = new NotificationsController($conn);
    $controller->index();
});
$router->post('/notifications/mark-as-read/{id}', function ($id) use ($conn) {
    $controller = new NotificationsController($conn);
    $controller->markAsRead($id);
});

// Routes: Patient Queue Management
$router->get('/queue', function () use ($conn) {
    $controller = new PatientQueueController($conn);
    $controller->index();
});
$router->post('/queue/add', function () use ($conn) {
    $controller = new PatientQueueController($conn);
    $controller->add();
});
$router->post('/queue/remove', function () use ($conn) {
    $controller = new PatientQueueController($conn);
    $controller->remove();
});

// Route: Logout
$router->post('/logout', function () {
    session_start();
    session_destroy(); // Destroy session
    header("Location: /login"); // Redirect to login page
    exit;
});

// Routes: User Profile Management
$router->get('/profile', function () use ($conn) {
    $controller = new UserProfileController($conn);
    $controller->index();
});
$router->post('/profile/update', function () use ($conn) {
    $controller = new UserProfileController($conn);
    $controller->update();
});

// Custom 404 Error Handling
$router->set404(function () {
    http_response_code(404);
    echo "Error 404: Page not found.";
});

// Run the router
$router->run();
