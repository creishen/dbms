<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "IT_INVENTORY";

try {
    $connection = mysqli_connect('127.0.0.1', $username, $password, $database);

    if ($connection->connect_error) {
        throw new Exception("Connection failed: " . $connection->connect_error);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}
?>
