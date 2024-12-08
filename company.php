<?php 
session_start();
require('config.php');

$successMessage = "";
$errorMessage = "";

// Fetch initially all the company
$sql = $connection->prepare("SELECT * FROM company");
$sql->execute();
$result = $sql->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new company
    if (isset($_POST["company_code"]) && isset($_POST["company_name"]) && !empty($_POST["company_code"]) && !empty($_POST["company_name"])) {
        
        $company_code = trim($_POST["company_code"]);
        $company_name = trim($_POST["company_name"]);

        // Basic validation
        if (!preg_match("/^[a-zA-Z0-9]+$/",$company_code)) {
            $errorMessage = "Company code must be alphanumeric(letters and numbers only)!";
        }else if(empty($company_name)){
            $errorMessage = "Company name cannot be empty.";
        }else {
            // Check if the company_code already exists
            $checkStmt = $connection->prepare("SELECT COUNT(*) FROM company WHERE company_code = ?");
            $checkStmt->bind_param("s", $company_code);
            $checkStmt->execute();
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            
            if ($count > 0) {
                $errorMessage = "Company code already exists!";
            } else {
                // Proceed to inserting the new company
                $stmt = $connection->prepare("INSERT INTO company(company_code, company_name) VALUES (?, ?)");
                $stmt->bind_param("ss", $company_code, $company_name);
                
                if ($stmt->execute()) {
                    $successMessage = "Company added successfully!";
                } else {
                    $errorMessage = "Unable to add new company! Please try again later.";
                    // Logging the actual error for debugging
                    error_log("Error executing query: " . $stmt->error);
                }
            }
        }
    }
}
?>
