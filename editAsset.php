<?php
ini_set('display_errors', 0); // Disable direct display of errors
session_start();
require("config.php");

$employee_id = null;
$errorMessage = "";
$successMessage = "";
$asset_id = "";
$type = "";
$brand = "";
$model = "";

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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["asset_id"])) {
        $asset_id = $_GET["asset_id"];

        // Read the asset's row
        $sql = "SELECT * FROM assets_details WHERE asset_id = $asset_id";
        $result = $connection->query($sql);
        $found = $result->fetch_assoc();

        if (!$found) {
            header("location:admin-assets.php");
            exit;
        }

        $asset_id = $found["asset_id"];
        $brand = $found["brand"];
        $model = $found["model"];
        $type = $_POST["type"];
    }
} else {
    // Store input values to php variables
    $asset_id = $_POST["asset_id"];
    $type = $_POST["type"];
    $brand = $_POST["brand"];
    $model = $_POST["model"];

    if (empty($brand) || empty($model)) {
        $message = "All the fields are required";
    } else {
        // Update the asset details
        $asset_id = $_GET["company_code"];
        $sql = "UPDATE assets_details SET asset_id=?, type=?, brand=?, model=? WHERE asset_id=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssss", $asset_id, $type, $brand, $model, $asset_id);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert ('Asset updated successfully!'); window.location.href = '/admin/staff.php'</script>";
        } else {
            $message = "Invalid query: " . $connection->error;
        }
        // Close statement
        $stmt->close();
    }
}
