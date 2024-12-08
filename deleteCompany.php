<?php

session_start();
require("config.php");

if (!isset($_SESSION['ID']) || $_SESSION['stat'] === "inactive") {
    // Handle the case where client_id is not set in the session
    header("Location: /admin/main.php"); //change latuur
    die("User not logged in");
}

$ID = $_SESSION['ID'];

if (isset($_GET["company_code"])) {
    $company_code = $_GET["company_code"];

    //Check if there are any related branches in the branches table
    $checkSql = "SELECT COUNT(*) FROM branches WHERE company_code = ?";
    $stmt = $connection->prepare($checkSql);
    $stmt->bind_param("s", $company_code);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();

    //If no related branches, proceed with the deletion
    if ($count == 0) {
        // No related branches, safe to delete the company
        $deleteSql = "DELETE FROM company WHERE company_code = ?";
        $deleteStmt = $connection->prepare($deleteSql);
        $deleteStmt->bind_param("s", $company_code);
        if ($deleteStmt->execute()) {
            echo "<script>alert ('Company Deleted!'); window.location.href = '/admin/serviceFunc.php'</script>";//change loc latur
        } else {
            echo "<script>alert ('Failed to delete Company. Please try again later.'); window.location.href = '/admin/serviceFunc.php'</script>";
        }
    } else {
        // There are branches linked to this company, do not delete
        echo "<script>alert ('Cannot delete this Company. It has associated branches.'); 
              window.location.href = '/admin/serviceFunc.php'</script>";
    }

    $stmt->close();
    $deleteStmt->close();
}

header("location: /admin/serviceFunc.php");//change loc
exit;

?>
