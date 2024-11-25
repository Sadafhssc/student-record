<?php
namespace App\Views;

/**
 * Class StudentView
 * Handles rendering of student-related views such as alerts, forms, and tables.
 */
class StudentView
{
    /**
     * Renders an alert message on the page.
     * 
     * This method outputs an alert box with a message and a close button. The style of the alert is 
     * determined by the `$type` parameter (e.g., 'success', 'danger').
     *
     * @param string $message The message to display inside the alert.
     * @param string $type The type of the alert, which determines its style (e.g., 'success', 'danger', 'warning').
     */
    public function renderAlerts($message, $type)
    {
        echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }

    /**
     * Renders the student form for adding or editing student records.
     * 
     * This method includes the form HTML (usually located in `form.php`) for displaying the form
     * where users can input or edit student data.
     */
    public function renderForm()
    {
        include_once __DIR__ . '/../public/form.php';
    }

    /**
     * Renders the table displaying all student records.
     * 
     * This method includes the table HTML (usually located in `table.php`) to display the list of 
     * students. It passes the `$students` array to the table view for rendering.
     *
     * @param array $students The array of student records to display in the table.
     */
    public function renderTable($students)
    {
        include_once __DIR__ . '/../public/table.php'; // Pass students to table.php
    }
}
?>