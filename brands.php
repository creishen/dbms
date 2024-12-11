<?php 
session_start();
require('config.php');

$message = "";

// Fetch initially all the brands
$sql = $connection->prepare("SELECT * FROM brands");
$sql->execute();
$result = $sql->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new brand
    if (isset($_POST["asset_ID"]) && isset($_POST["brand"]) && isset($_POST["model"])&& isset($_POST["quantity"]) && !empty($_POST["asset_ID"]) && !empty($_POST["brand"]) && !empty($_POST["model"])&& !empty($_POST["quantity"])) {
        
        $asset_ID = trim($_POST["asset_ID"]);
        $brand = trim($_POST["brand"]);
        $model = trim($_POST["model"]);
        $quantity = trim($_POST["quantity"]);

        // Basic validation
        if (!preg_match("/^[a-zA-Z0-9]+$/",$asset_ID)) {
            $errorMessage = "Asset ID must be alphanumeric(letters and numbers only)!";
        }else if(empty($brand) || empty ($model)){
            $message = "Brand and model cannot be empty.";
        }else {
            // Check if the asset_ID already exists
            $checkStmt = $connection->prepare("SELECT COUNT(*) FROM brands WHERE asset_ID = ?");
            $checkStmt->bind_param("s", $asset_ID);
            $checkStmt->execute();
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            
            if ($count > 0) {
                $message = "Asset ID already exists!";
            } else {
                // Proceed to insert the new brand
                $stmt = $connection->prepare("INSERT INTO brands(asset_ID, brand, model) VALUES (?, ?, ?)");
                $stmt->bind_param("sssi", $asset_ID, $brand, $model, $quantity);
                
                if ($stmt->execute()) {
                    $message = "Brand added successfully!";
                } else {
                    $message = "Unable to add new asset! Please try again later.";
                    // Logging the actual error for debugging
                    error_log("Error executing query: " . $stmt->error);
                }
            }
        }
    }
}
?>
