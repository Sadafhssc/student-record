<?php
// Include necessary files for model, view, and controller
require_once '../models/StudentModel.php';
require_once '../views/StudentView.php';
require_once '../controllers/StudentController.php';

use App\Controllers\StudentController;

/**
 * Entry point of the application.
 * 
 * This file creates an instance of the StudentController class and calls its handleRequest method 
 * to process the incoming HTTP request and render the appropriate view.
 */
$controller = new StudentController();
$controller->handleRequest();
?>
