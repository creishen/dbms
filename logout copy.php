<?php
	session_start();
	
	$_SESSION['stat'] = "inactive";
	unset($_SESSION['ID']);
	echo "<script> window.location.href = '/admin/main.php'</script>";
?>