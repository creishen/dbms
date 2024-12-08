<?php
session_start();
require("config.php");

// Check if the user is logged in
if (!isset($_SESSION['ID']) || $_SESSION['stat'] === "inactive") {
    // Handle the case where user id is not set in the session
    header("Location: /admin/main.php");
    die("User not logged in");
}

// Fetch all the brands for choices when adding assets
$brand = $connection->prepare("SELECT * FROM brands");
$brand->execute();
$brand_result = $brand->get_result();

// Handle form submission and fetch assets based on selected brand
$selected_brand = "";
$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["asset_ID"]) && !empty($_POST["asset_ID"])) {
        $selected_brand = $_POST["asset_ID"];
        
        // add new asset
        if (isset($_POST["employee_ID"]) && isset($_POST["asset_ID"]) && isset($_POST["serialNo"]) && isset($_POST["type"]) && isset($_POST["date_of_purchase"]) && isset($_POST["warranty"]) && !empty($_POST["employee_ID"]) && !empty($_POST["asset_ID"]) && !empty($_POST["serialNo"]) && !empty($_POST["type"]) && !empty($_POST["date_of_purchase"]) && !empty($_POST["warranty"])) {
        
   		    $employee_ID = $_POST["employee_ID"];
		    $asset_ID = $_POST["asset_ID"];
		    $serialNo = $_POST["serialNo"];
		    $type = $_POST["type"];
    		$date_of_purchase = $_POST["date_of_purchase"];
            $warranty = $_POST["warranty"];

	        $stmt = $connection->prepare("INSERT INTO assets_details(employee_ID, asset_ID, serialNo, type, date_of_purchase, warranty) VALUES (?, ?, ?, ?, ?, ?)");
	        $stmt->bind_param("isssss", $employee_ID, $asset_ID, $serialNo, $type, $date_of_purchase, $warranty);
	
	        if ($stmt->execute()) {
	            $successMessage = "Asset added successfully!";
	        } else {
	            $errorMessage = "Failed to add asset: " . $stmt->error;
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
