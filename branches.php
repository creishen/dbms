<?php 
session_start();
require('config.php');

$successMessage = "";
$errorMessage = "";

// Fetch initially all the branch
$sql = $connection->prepare("SELECT * FROM branches");
$sql->execute();
$result = $sql->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new branch
    if (isset($_POST["branch_code"]) && isset($_POST["branch_name"]) && isset($_POST["city"]) && isset($_POST["company_code"]) 
        && !empty($_POST["branch_code"]) && !empty($_POST["branch_name"]) && !empty($_POST["city"]) && !empty($_POST["company_code"])) {
        
        $branch_code = trim($_POST["branch_code"]);
        $branch_name = trim($_POST["branch_name"]);
        $city = trim($_POST["city"]);
        $company_code = trim($_POST["company_code"]);

        // Basic validation
        if (!preg_match("/^[a-zA-Z0-9]+$/",$branch_code)) {
            $errorMessage = "Branch code must be alphanumeric(letters and numbers only)!";
        }else if(empty($branch_name)){
            $errorMessage = "Branch name cannot be empty.";
        }else {
            // Check if the branch_code already exists
            $checkStmt = $connection->prepare("SELECT COUNT(*) FROM branch WHERE branch = ?");
            $checkStmt->bind_param("s", $branch_code);
            $checkStmt->execute();
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            
            if ($count > 0) {
                $errorMessage = "Branch code already exists!";
            } else {
                // Proceed to inserting the new company
                $stmt = $connection->prepare("INSERT INTO branches(branch_code, branch_name, city, company_code) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $branch_code, $branch_name, $city, $company_code);
                
                if ($stmt->execute()) {
                    $successMessage = "Branch added successfully!";
                } else {
                    $errorMessage = "Unable to add new branch! Please try again later.";
                    // Logging the actual error for debugging
                    error_log("Error executing query: " . $stmt->error);
                }
            }
        }
    }
}
?>
