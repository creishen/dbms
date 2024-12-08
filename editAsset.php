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
$type = "";
$date_of_purchase = "";
$warranty= "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["asset_ID"])) {
        $asset_ID = $_GET["asset_ID"];

        // Read the asset's row
        $sql = "SELECT * FROM assets_details WHERE asset_ID = $asset_ID";
        $result = $connection->query($sql);
        $found = $result->fetch_assoc();

        if (!$found) {
            header("location: /admin/staff.php");
            exit;
        }

        $employee_ID = $found["employee_ID"];
        $type = $found["type"];
        $date_of_purchase = $found["date_of_purchase"];
        $warranty = $found["warranty"];
    }
} else {
    // Store input values to php variables
        $employee_ID = $_POST["employee_ID"];
        $type = $_POST["type"];
        $date_of_purchase = $_POST["date_of_purchase"];
        $warranty = $_POST["warranty"];
        
      if (empty($employee_ID) || empty($type) || empty($date_of_purchase) || empty($warranty)) {
          $errorMessage = "All the fields are required";
      } 
       else {
	          // Update the asset details
	          $asset_ID = $_GET["asset_ID"];
	          $sql = "UPDATE assets_details SET employee_ID=?, type=?, date_of_purchase=?, warranty=? WHERE asset_ID=?";
	          $stmt = $connection->prepare($sql);
	          $stmt->bind_param("issss", $employee_ID, $type, $date_of_purchase, $warranty, $asset_ID);
	  
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
?>