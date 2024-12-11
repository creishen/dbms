<?php 

session_start();
require("config.php");

$employee_id = null;
$errorMessage = "";
$successMessage = "";
$branch_code = "";
$lastName = "";
$firstName = "";
$middleName = "";
$position = "";
$branch = "";
$department = "";
$password = "";

ini_set('display_errors', 0); // Disable direct display of errors
ini_set('log_errors', 1); // Enable error logging
error_reporting(E_ALL); // Report all errors

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $errorMessage = "Error [$errno]: $errstr in $errfile on line $errline";
    file_put_contents('php_error.log', $errorMessage . PHP_EOL, FILE_APPEND); // Log error to file
    http_response_code(500); // Set HTTP response status to 500 for error
    echo json_encode(['error' => $errorMessage]); // Return error as JSON
    exit;
});

// Success Logger
function log_success($message)
{
    $timestamp = date('Y-m-d H:i:s');
    $successMessage = "[$timestamp] SUCCESS: $message";
    file_put_contents('php_success.log', $successMessage . PHP_EOL, FILE_APPEND);
}


if (!isset($_SESSION['employee_id']) || !isset($_SESSION['stat']) === "inactive") {
    $errorMessage = "Please log in first!";
    echo " 
        <script>
        alert('$errorMessage');
            setTimeout(() => {
              window.location.href = 'login.php';
              }, 100); // Redirect after # seconds 
        </script>
  ";
    exit;
  }

if($_SERVER['REQUEST_METHOD'] == 'POST') { // Add account
    // Collect POST data
    $lastName = $_POST["lastName"];
    $firstName = $_POST["firstName"]; 
    $MI = $_POST["MI"];
    $position = $_POST["position"];
    $branch = $_POST["branch"];
    $department = $_POST["department"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check for empty fields
    if(empty($lastName) || empty($firstName) || empty($position) || empty($branch) || empty($department) || empty($username) || empty($password)) {
        $errorMessage = "Fill in required fields!";
    } else {
        // Check for duplicate username
        $checkDuplicateQuery = "SELECT * FROM registered_accounts WHERE username = ?";
        $prepareStmnt = $connection->prepare($checkDuplicateQuery);
        $prepareStmnt->bind_param("s", $username);
        $prepareStmnt->execute();
        $result = $prepareStmnt->get_result();

        if($result->num_rows > 0) {
            $errorMessage = "Username already exists. Try again!";
            $username = ""; // Clear username field
        } else {
            // Insert data into `employees` table
            $sql = "INSERT INTO employees (branch_code, lastname, firstname, middle_initial, position_code, department_code)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("isssii", $branch, $lastName, $firstName, $MI, $position, $department);

            // Insert data into `registered_accounts` table
            $sql2 = "INSERT INTO registered_accounts (username, password) VALUES (?, ?)";
            $stmt2 = $connection->prepare($sql2);
            $stmt2->bind_param("ss", $username, $password);

            // Execute the statements
            if($stmt->execute() && $stmt2->execute()) {
                $successMessage = "Account added successfully!";
            } else {
                $errorMessage = "Failed to add account: " . $stmt->error;
            }

            // Example query for selecting data (not necessary here but included for reference)
            $stmt = $connection->prepare("SELECT branch_code, e.ID, lastname, firstname, middle_initial, position_code, department_code, username, password
                                           FROM employees e
                                           LEFT JOIN registered_accounts r ON e.ID = r.ID");
            $stmt->execute();
            $staff_result = $stmt->get_result();

            // Close statements
            $stmt->close();
            $stmt2->close();
        }

        // Close connection
        $connection->close();
    }
} 

?>
