<?php
namespace App\Controllers;

use App\Models\StudentModel;
use App\Views\StudentView;

/**
 * Class StudentController
 * Handles the operations related to student records, including insert, update, delete, and fetching student data.
 */
class StudentController
{
    private $model;
    private $view;

    /**
     * StudentController constructor.
     * Initializes the model and view for handling student data.
     */
    public function __construct()
    {
        $this->model = new StudentModel("localhost", "root", "", "student");
        $this->view = new StudentView();
    }

    /**
     * Handles incoming requests, including insert, update, delete, and fetching student data.
     * Depending on the request method and parameters, it delegates to appropriate functions.
     */
    public function handleRequest()
    {
        // Handle Insertions
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['snoEdit'])) {
            $this->model->name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $this->model->email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $this->model->id = isset($_POST['idNumber']) ? trim($_POST['idNumber']) : '';
            $this->model->age = isset($_POST['age']) ? intval($_POST['age']) : null;
            $this->model->gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
            $this->model->image = isset($_FILES['image']['name']) ? $this->handleFileUpload($_FILES['image']) : '';
            $this->handleInsert();
        }

        // Handle Updates
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['snoEdit'])) {
            $this->model->name = isset($_POST['nameEdit']) ? trim($_POST['nameEdit']) : '';
            $this->model->email = isset($_POST['emailEdit']) ? trim($_POST['emailEdit']) : '';
            $this->model->id = isset($_POST['idEdit']) ? trim($_POST['idEdit']) : '';
            $this->model->age = isset($_POST['ageEdit']) ? intval($_POST['ageEdit']) : null;
            $this->model->gender = isset($_POST['genderEdit']) ? trim($_POST['genderEdit']) : '';
            $this->model->image = isset($_FILES['imageEdit']['name']) ? $this->handleFileUpload($_FILES['imageEdit']) : '';
            $this->handleUpdate($_POST['snoEdit']);
        }

        // Handle Deletion
        if (isset($_GET['delete'])) {
            $this->handleDelete($_GET['delete']);
        }

        // Fetch and pass students to the view
        $students = $this->model->getAllStudents();
        $this->view->renderTable($students);
    }

    /**
     * Handles the insertion of a new student record.
     * If the insertion is successful, an alert message is rendered. 
     * If an exception occurs, the error message is displayed.
     */
    private function handleInsert()
    {
        try {
            if ($this->model->insertStudent()) {
                $this->view->renderAlerts("Your record has been inserted successfully!", "success");
            }
        } catch (\Exception $e) {
            $this->view->renderAlerts($e->getMessage(), "danger"); // Display exception message
        }
    }

    /**
     * Handles the updating of an existing student record.
     * If the update is successful, an alert message is rendered.
     * If an exception occurs, the error message is displayed.
     *
     * @param int $snoEdit The student record number to be updated.
     */
    private function handleUpdate($snoEdit)
    {
        try {
            if ($this->model->updateStudent($snoEdit)) {
                $this->view->renderAlerts("Your record has been updated successfully!", "primary");
            }
        } catch (\Exception $e) {
            $this->view->renderAlerts($e->getMessage(), "danger"); // Display exception message
        }
    }

    /**
     * Handles the deletion of a student record.
     * If the deletion is successful, an alert message is rendered.
     * If an exception occurs, the error message is displayed.
     *
     * @param int $deleteId The student record number to be deleted.
     */
    private function handleDelete($deleteId)
    {
        try {
            if ($this->model->deleteStudent($deleteId)) {
                $this->view->renderAlerts("Your record has been deleted successfully!", "danger");
            }
        } catch (\Exception $e) {
            $this->view->renderAlerts($e->getMessage(), "danger"); // Display exception message
        }
    }

    /**
     * Handles the file upload for student images.
     * Moves the uploaded file to the designated directory and returns the file name.
     *
     * @param array $file The file data from the form input.
     * @return string The file name if upload is successful, empty string otherwise.
     */
    private function handleFileUpload($file)
    {
        $uploadDir = 'public/images/';
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $targetFile = $uploadDir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return basename($file['name']); // Store only the file name
        }
        return ''; // Return empty string on failure
    }
}
?>