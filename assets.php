<?php
session_start();
require("config.php");

$employee_id = null;
$errorMessage = "";
$successMessage = "";

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

// Handle form submission and fetch assets based on selected brand
$selected_brand = "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["employee_id"]) && !empty($_POST["employee_id"])) {
        $selected_brand = $_POST["employee_id"];
        
        // add new asset
        if (isset($_POST["employee_id"]) && isset($_POST["asset_id"]) && isset($_POST["serialNo"]) && isset($_POST["type"]) && isset($_POST["date_purchased"]) && isset($_POST["warranty"]) && !empty($_POST["employee_id"]) && !empty($_POST["asset_id"]) && !empty($_POST["serialNo"]) && !empty($_POST["type"]) && !empty($_POST["date_purchased"]) && !empty($_POST["warranty"])) {
        
   		    $employee_id = $_POST["employee_id"];
		    $asset_id = $_POST["asset_ID"];
		    $serialNo = $_POST["serialNo"];
		    $type = $_POST["type"];
    		$date_purchased = $_POST["date_purchased"];
            $warranty = $_POST["warranty"];

	        $stmt = $connection->prepare("INSERT INTO assets_details(employee_id, asset_id, serialNo, type, date_purchased, warranty) VALUES (?, ?, ?, ?, ?, ?)");
	        $stmt->bind_param("isssss", $employee_id, $asset_id, $serialNo, $type, $date_purchased, $warranty);
	
	        if ($stmt->execute()) {
	            $message = "Asset added successfully!";
	        } else {
	            $message = "Failed to add asset: " . $stmt->error;
	        }
        }
     

    } else {
        echo "<script>alert('Please choose a brand!'); window.location.href = window.location.href;</script>";
    }
} else {
    // Fetch all the assets initially
    $assets = $connection->query("SELECT * FROM assets_details");
}
?>
