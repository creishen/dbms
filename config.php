<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$database = "it_inventory";

$connection = mysqli_connect($servername, $username, $password, $database);

if (!$connection) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
    echo"connection fail";
}
else echo"connected";
?>
