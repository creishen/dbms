<?php
session_start();
require("config.php");

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['ID']) || $_SESSION['stat'] === "inactive") {
    // Handle the case where user id is not set in the session
    header("Location: login.php");
    die("User not logged in");
}

$company_code = "";
$company_name = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET["company_code"])) {
        $company_code = $_GET["company_code"];

        // Read the asset's row
        $sql = "SELECT * FROM company WHERE company_code = $company_code";
        $result = $connection->query($sql);
        $found = $result->fetch_assoc();

        if (!$found) {
            header("location: /admin-dashboard.php");
            exit;
        }

        $company_code = $found["company_code"];
        $company_name = $found["company_name"];
    }
} else {
    // Store input values to php variables
        $company_code = $_POST["company_code"];
        $company_name = $_POST["company_name"];
        
      if (empty($company_code) || empty($company_name)) {
          $errorMessage = "All the fields are required";
      } 
       else {
	          // Update the asset details
	          $company_code = $_GET["company_code"];
	          $sql = "UPDATE company SET company_code=?, company_name=? WHERE company_code=?";
	          $stmt = $connection->prepare($sql);
	          $stmt->bind_param("ss", $company_code, $company_name);
	  
	         // Execute the statement
	         if ($stmt->execute()) {
	             echo "<script>alert ('Company updated successfully!'); window.location.href = '/admin-assset.php'</script>";
	        } else {
	               $errorMessage = "Invalid query: " . $connection->error;
	           }
	            // Close statement
            $stmt->close();
        
    }
}
?>
