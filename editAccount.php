<?php
session_start();
require("config.php");

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['ID']) || $_SESSION['stat'] === "inactive") {
    // Handle the case where user id is not set in the session
    header("Location: /admin/main.php");
    die("User not logged in");
}

$employee_ID = "";
$branch_code = "";
$lastname = "";
$firstname = "";
$middle_IN = "";
$position_code = "";
$department_code = "";
$status = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["ID"])) {
        $employee_ID = $_GET["ID"];

        // Read the employee's row
        $sql = "SELECT * FROM employees WHERE ID = $employee_ID";
        $result = $connection->query($sql);
        $found = $result->fetch_assoc();

        if (!$found) {
            header("location: /admin/staff.php");
            exit;
        }

        $branch_code = $found["branch_code"];
        $lastname = $found["lastname"];
        $firstname = $found["firstname"];
        $middle_IN = $found["middle_IN"];
        $position_code = $found["position_code"];
        $department_code = $found["department_code"];
        $status = $found["status"];
    }
} else {
    // Store input values to php variables
    $branch_code = $_POST["branch_code"];
    $lastname = $_POST["lastname"];
    $firstname = $_POST["firstname"];
    $middle_IN = $_POST["middle_IN"];
    $position_code = $_POST["position_code"];
    $department_code = $_POST["department_code"];
    $status = $_POST["status"];

    if (empty($branch_code) || empty($lastname) || empty($firstname) || empty($middle_IN) || empty($position_code) || empty($department_code) || empty($status)) {
        $errorMessage = "All the fields are required";
    } else {
        // Update the employee details
        $employee_ID = $_GET["ID"];
        $sql = "UPDATE employees SET ID=?, type=?, date_of_purchase=?, warranty=? WHERE asset_ID=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("issss", $employee_ID, $type, $date_of_purchase, $warranty, $asset_ID);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert ('Employee updated successfully!'); window.location.href = '/admin/staff.php'</script>";
        } else {
            $errorMessage = "Invalid query: " . $connection->error;
        }
        // Close statement
        $stmt->close();
    }
}
