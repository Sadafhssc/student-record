<?php
namespace App\Models;
use mysqli;

/**
 * Class StudentModel
 * Handles CRUD operations for the student records.
 */
class StudentModel
{
    private $connection;
    public $name;
    public $email;
    public $id;
    public $age;
    public $gender;
    public $image;

    /**
     * StudentModel constructor.
     * Initializes the database connection.
     *
     * @param string $servername The database server name.
     * @param string $username The database username.
     * @param string $password The database password.
     * @param string $database The name of the database.
     */
    public function __construct($servername, $username, $password, $database)
    {
        $this->connection = new mysqli($servername, $username, $password, $database);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    /**
     * Inserts a new student record into the database.
     *
     * @return bool True if the record was inserted successfully, false otherwise.
     * @throws \Exception if there are input validation errors.
     */
    public function insertStudent()
    {
        $this->validateInputs('insert');

        // Directory for storing images
        $folder = 'public/images/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        // Handle image upload
        $imagePath = '';
        if (!empty($_FILES['image']['name'])) {
            $targetFile = $folder . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = basename($_FILES['image']['name']);
            } else {
                error_log("File upload failed: " . $_FILES['image']['name']);
            }
        }

        // Insert the student record into the database
        $stmt = $this->connection->prepare(
            "INSERT INTO `studentrecord` (`name`, `email`, `id`, `gender`, `age`, `image`) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssis", $this->name, $this->email, $this->id, $this->gender, $this->age, $imagePath);

        if (!$stmt->execute()) {
            error_log("SQL Insert Error: " . $stmt->error);
            return false;
        }

        return true;
    }

    /**
     * Updates an existing student record in the database.
     *
     * @param int $sno The unique student record number (primary key).
     * @return bool True if the record was updated successfully, false otherwise.
     * @throws \Exception if there are input validation errors.
     */
    public function updateStudent($sno)
    {
        $this->validateInputs('update');

        // SQL query for updating the student record
        $sql = "UPDATE `studentrecord` SET `name`=?, `email`=?, `id`=?, `age`=?, `gender`=?";
        $params = [$this->name, $this->email, $this->id, $this->age, $this->gender];
        $types = "sssis";

        // Handle image upload if provided
        if (!empty($_FILES['imageEdit']['name'])) {
            $folder = 'public/images/';
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
            $targetFile = $folder . basename($_FILES['imageEdit']['name']);
            if (move_uploaded_file($_FILES['imageEdit']['tmp_name'], $targetFile)) {
                $sql .= ", `image`=?";
                $params[] = basename($_FILES['imageEdit']['name']);
                $types .= "s";
            } else {
                error_log("Failed to upload image: " . $_FILES['imageEdit']['name']);
            }
        }

        // Add condition to update the specific record by student number
        $sql .= " WHERE `srno`=?";
        $params[] = $sno;
        $types .= "i";

        // Prepare and execute the update query
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            error_log("SQL Update Error: " . $stmt->error);
            return false;
        }

        return true;
    }

    /**
     * Deletes a student record from the database.
     *
     * @param int $sno The unique student record number (primary key).
     * @return bool True if the record was deleted successfully, false otherwise.
     */
    public function deleteStudent($sno)
    {
        // Prepare and execute delete query
        $stmt = $this->connection->prepare("DELETE FROM `studentrecord` WHERE `srno` = ?");
        $stmt->bind_param("i", $sno);

        if (!$stmt->execute()) {
            error_log("SQL Delete Error: " . $stmt->error);
            return false;
        }
        return true;
    }

    /**
     * Fetches all student records from the database.
     *
     * @return array An associative array of student records.
     */
    public function getAllStudents()
    {
        // Fetch all student records
        $result = $this->connection->query("SELECT * FROM `studentrecord`");
        if (!$result) {
            error_log("SQL Fetch Error: " . $this->connection->error);
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Closes the database connection when the object is destroyed.
     */
    public function __destruct()
    {
        $this->connection->close();
    }

    /**
     * Validates the input fields before inserting or updating a student record.
     *
     * @param string $operation The operation type ('insert' or 'update').
     * @throws \Exception if any validation fails.
     */
    private function validateInputs($operation)
    {
        if ($operation === 'insert') {
            $this->name = $_POST['name'] ?? '';
            $this->email = $_POST['email'] ?? '';
            $this->id = $_POST['idNumber'] ?? '';
            $this->age = $_POST['age'] ?? null;
            $this->gender = $_POST['gender'] ?? '';
            $this->image = $_FILES['image']['name'] ?? '';
        } elseif ($operation === 'update') {
            $this->name = $_POST['nameEdit'] ?? '';
            $this->email = $_POST['emailEdit'] ?? '';
            $this->id = $_POST['idEdit'] ?? '';
            $this->age = $_POST['ageEdit'] ?? null;
            $this->gender = $_POST['genderEdit'] ?? '';
            $this->image = $_FILES['imageEdit']['name'] ?? '';
        }

        // Validate email format
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format.");
        }

        // Validate age
        if (!is_numeric($this->age) || $this->age <= 0 || $this->age > 120) {
            throw new \Exception("Invalid age.");
        }

        // Validate name is not empty
        if (empty($this->name)) {
            throw new \Exception("Name is required.");
        }
    }
}
?>