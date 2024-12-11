<?php
ini_set('log_errors', 1); // Enable error logging
error_reporting(E_ALL); // Report all errors

// Custom error handler
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
  $errorMessage = "Error [$errno]: $errstr in $errfile on line $errline";
  file_put_contents('php_error.log', $errorMessage . PHP_EOL, FILE_APPEND); // Log error to file
  http_response_code(500); // Set HTTP response status to 500 for error
  echo json_encode(['error' => $errorMessage]); // Return error as JSON
  exit;
});

session_start();
require("config.php");

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['ID']) || $_SESSION['stat'] === "inactive") {
    // Handle the case where user id is not set in the session
    header("Location: login.php");
    die("User not logged in");
}

$employee_id = "";
$model = "";
$date_purchased = "";
$warranty = "";
$quantity = "";
$brand = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["asset_id"])) {
        $asset_id = $_GET["asset_id"];

        // Read the asset's row
        $sql = "SELECT * FROM assets_details WHERE asset_id = $asset_id";
        $result = $connection->query($sql);
        $found = $result->fetch_assoc();

        if (!$found) {
            $errorMessage ="Asset row not found!";
            header("location: /admin-assets.php");
            exit;
        }

        $employee_id = $found["employee_id"];
        $type = $found["type"];
        $date_purchased = $found["date_purchased"];
        $warranty = $found["warranty"];
    }
} else {
    // Store input values to php variables
    $type = $_POST["type"];
    $brand = $_POST["type"];
    $model = $_POST["model"];
    $warranty = $_POST["warranty"];
    $date_purchased = $_POST["date_purchased"];

    if (empty($employee_id) || empty($type) || empty($date_purchased) || empty($warranty)) {
        $errorMessage = "All the fields are required";
    } else {
        // Update the asset details
        $asset_id = $_GET["asset_id"];
        $sql = "UPDATE assets_details SET employee_id=?, type=?, date_purchased=?, warranty=? WHERE asset_id=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("issss", $employee_id, $type, $date_purchased, $warranty, $asset_id);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert ('Asset updated successfully!'); window.location.href = '/admin/staff.php'</script>";
        } else {
            $errorMessage = "Invalid query: " . $connection->error;
        }
        // Close statement
        $stmt->close();
    }
}
