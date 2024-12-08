<?php

session_start();
require("config.php");
  
  if (!isset($_SESSION['ID']) || $_SESSION['stat'] === "inactive") {
    // Handle the case where client_id is not set in the session
    header("Location: /admin/main.php");
    die("User not logged in");
} 
	if(isset($_GET["ID"]) ){
		$staff_id = $_GET["ID"];
		
          		
		$sql = "DELETE FROM employees WHERE ID=$ID";
		$connection->query($sql);

        $sql2 = "DELETE FROM registered_accounts WHERE ID=$ID";
        $connection->query($sql2);
		
		echo "<script>alert ('Staff Deleted!'); window.location.href = '/admin/staff.php'</script>";
	}else{
    		echo "<script> window.location.href = '/admin/staff.php'</script>";
    	}
	
?>