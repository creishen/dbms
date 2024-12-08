<?php

session_start();
require("config.php");

if (!isset($_SESSION['ID']) || $_SESSION['stat'] === "inactive") {
    // Handle the case where client_id is not set in the session
    header("Location: /admin/main.php"); //change latuur
    die("User not logged in");
}

$ID = $_SESSION['ID'];

if (isset($_GET["branch_code"])) {
    $branch_code = $_GET["branch_code"];

    //Check if there are any related employees under this branch
    $checkSql = "SELECT COUNT(*) FROM employees WHERE branch_code = ?";
    $stmt = $connection->prepare($checkSql);
    $stmt->bind_param("s", $branch_code);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();

    //If no related empployees, proceed with the deletion
    if ($count == 0) {
        // No related employees, safe to delete the branch
        $deleteSql = "DELETE FROM branches WHERE branch_code = ?";
        $deleteStmt = $connection->prepare($deleteSql);
        $deleteStmt->bind_param("s", $branch_code);
        if ($deleteStmt->execute()) {
            echo "<script>alert ('Brand Deleted!'); window.location.href = '/admin/serviceFunc.php'</script>";//change loc latur
        } else {
            echo "<script>alert ('Failed to delete brand. Please try again later.'); window.location.href = '/admin/serviceFunc.php'</script>";
        }
    } else {
        // There are assets or hardware linked to this brand, do not delete
        echo "<script>alert ('Cannot delete this brand. It has associated assets or hardware.'); 
              window.location.href = '/admin/serviceFunc.php'</script>";
    }

    $stmt->close();
    $deleteStmt->close();
}

header("location: /admin/serviceFunc.php");//change loc
exit;

?>
