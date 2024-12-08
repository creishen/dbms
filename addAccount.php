<?php 

session_start();
require("config.php");

// Check if the user is logged in
if (!isset($_SESSION['ID']) || $_SESSION['stat'] === "inactive") {
    // Handle the case where user id is not set in the session
    header("Location: /admin/main.php");
    die("User not logged in");
}

$lastName = "";
$firstName = ""; 
$MI = "";
$position = "";
$branch = "";
$department = "";
$username = "";
$password = "";
$errorMessage = "";
$successMessage = "";

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
