<?php 
session_start();
require('config.php');

$successMessage = "";
$errorMessage = "";

// Fetch initially all the brands
$sql = $connection->prepare("SELECT * FROM brands");
$sql->execute();
$result = $sql->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new brand
    if (isset($_POST["asset_ID"]) && isset($_POST["brand"]) && isset($_POST["model"]) && !empty($_POST["asset_ID"]) && !empty($_POST["brand"]) && !empty($_POST["model"])) {
        
        $asset_ID = trim($_POST["asset_ID"]);
        $brand = trim($_POST["brand"]);
        $model = trim($_POST["model"]);

        // Basic validation
        if (!preg_match("/^[a-zA-Z0-9]+$/",$asset_ID)) {
            $errorMessage = "Asset ID must be alphanumeric(letters and numbers only)!";
        }else if(empty($brand) || empty ($model)){
            $errorMessage = "Brand and model cannot be empty.";
        }else {
            // Check if the asset_ID already exists
            $checkStmt = $connection->prepare("SELECT COUNT(*) FROM brands WHERE asset_ID = ?");
            $checkStmt->bind_param("s", $asset_ID);
            $checkStmt->execute();
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            
            if ($count > 0) {
                $errorMessage = "Asset ID already exists!";
            } else {
                // Proceed to insert the new brand
                $stmt = $connection->prepare("INSERT INTO brands(asset_ID, brand, model) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $asset_ID, $brand, $model);
                
                if ($stmt->execute()) {
                    $successMessage = "Brand added successfully!";
                } else {
                    $errorMessage = "Unable to add new brand! Please try again later.";
                    // Logging the actual error for debugging
                    error_log("Error executing query: " . $stmt->error);
                }
            }
        }
    }
}
?>
