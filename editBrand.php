<?php
session_start();
require("config.php");

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['ID']) || $_SESSION['stat'] === "inactive") {
    // Handle the case where user id is not set in the session
    header("Location: /admin/main.php");
    die("User not logged in");
}

$asset_ID = "";
$brand = "";
$model = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["asset_ID"])) {
        $asset_ID = $_GET["asset_ID"];

        // Read the asset's row
        $sql = "SELECT * FROM brands WHERE asset_ID = $asset_ID";
        $result = $connection->query($sql);
        $found = $result->fetch_assoc();

        if (!$found) {
            header("location: /admin/staff.php");
            exit;
        }

        $asset_ID = $found["asset_ID"];
        $brand = $found["brand"];
        $model = $found["model"];
    }
} else {
    // Store input values to php variables
        $asset_ID = $_POST["asset_ID"];
        $brand = $_POST["brand"];
        $model = $_POST["model"];
        
      if (empty($brand) || empty($model)) {
          $errorMessage = "All the fields are required";
      } 
       else {
	          // Update the asset details
	          $asset_ID = $_GET["company_code"];
	          $sql = "UPDATE brand SET asset_detail=?, brand=?, model=? WHERE asset_detail=?";
	          $stmt = $connection->prepare($sql);
	          $stmt->bind_param("sss", $asset_detail, $brand, $model);
	  
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