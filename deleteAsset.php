<?php

session_start();
require("config.php");
  
  if (!isset($_SESSION['ID']) || $_SESSION['stat'] === "inactive") {
      // Handle the case where user id is not set in the session
      header("Location: /admin/main.php"); //change latuur
      die("User not logged in");
  }

$ID = $_SESSION['ID'];

	if(isset($_GET["serialNo"]) ){
		$serialNo = $_GET["serialNo"];
		
		$sql = "DELETE FROM assets_details WHERE serialNo = $serialNo";
		$connection->query($sql);
		
		echo "<script>alert ('Brand Deleted!'); window.location.href = '/admin/serviceFunc.php'</script>";//change laturr
	}
	
	header("location: /admin/serviceFunc.php");//change loc latur
	exit;
?>