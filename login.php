<?php
session_start();
require(__DIR__ . '/config.php');

$_SESSION['stat'] = 'inactive';
$employee_id = null;
$errorMessage = "";
$successMessage = "";

ini_set('display_errors', 1); // Disable direct display of errors
ini_set('log_errors', 1); // Enable error logging
error_reporting(E_ALL); // Report all errors

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $errorMessage = "Error [$errno]: $errstr in $errfile on line $errline";
    file_put_contents('php_error.log', $errorMessage . PHP_EOL, FILE_APPEND); // Log error to file
    http_response_code(500); // Set HTTP response status to 500 for error
    echo json_encode(['error' => $errorMessage]); // Return error as JSON
});

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    try {
        $employee_id = trim($_POST["employee_id"] ?? '');
        $password = trim($_POST["password"] ?? '');

        if (empty($employee_id) || empty($password)) {
            throw new Exception("All fields are required!");
        }

        $checkExistQuery = "SELECT employee_id, password 
                            FROM registered_accounts
                            WHERE employee_id = ? AND password = ?";

        $prepareStmt = $connection->prepare($checkExistQuery);
        $prepareStmt->bind_param("ss", $employee_id, $password);
        $prepareStmt->execute();
        $result = $prepareStmt->get_result();

        if (!$result) {
            file_put_contents('php_debug.log', "[" . date('Y-m-d H:i:s') . "] " . "Database connection failed: " . $connection->connect_error . PHP_EOL, FILE_APPEND);
        }
        if (!$prepareStmt) {
            file_put_contents('php_debug.log', "[" . date('Y-m-d H:i:s') . "] " ."Prepare failed: " . $connection->error . PHP_EOL, FILE_APPEND);
        }

        if ($result->num_rows > 0) {
            $username_data = $result->fetch_assoc();
            $_SESSION['employee_id'] = $username_data['employee_id'];
            $employee_id = $_SESSION['employee_id'];
            $_SESSION['stat'] = 'active';
            echo json_encode(['success' => true, 'message' => 'Login successful.']);
        } else {
            throw new Exception("Username or password doesn't match any existing accounts!");
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    } finally {
        $connection->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Log In</title>
</head>

<body>
    <div class="loading-screen" id="loading-screen">
        <i class="fa-solid fa-gear loading-icon"></i>
    </div>
    <div class="content">
        <div id="error-container"></div>
        <div id="success-container"></div>
        <h2>Asset Inventory System</h2>
        <div class="login-details">
            <div class="info">
                <h3>Log In</h3>
                <label for="employee_id">User</label>
                <input class="logInInput" type="text" id="employee_id" name="employee_id">
                <label for="password">Password</label>
                <input class="logInInput" type="password" id="password" name="password">
            </div>
            <div class="btns">
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                </label>
                <p>Remember Me</p>
                <button class="loginBtn" id="login-button" type="button">Login Now</button>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    $(document).ready(function() {
        $('#login-button').on('click', function() {
            const employee_id = $('#employee_id').val();
            const password = $('#password').val();

            $.ajax({
                url: 'login.php', // Your PHP script URL
                type: 'POST',
                dataType: 'json',
                data: {
                    employee_id: employee_id,
                    password: password
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'admin-dashboard.php';
                        $('#success-container').text(response.message).slideDown();
                        $('#error-container').slideUp();
                    } else {
                        $('#error-container').text(response.error).slideDown();
                        $('#success-container').slideUp();
                    }
                },
                error: function(xhr) {
                    console.error('Status:', xhr.status); // Log status code
                    console.error('Response Text:', xhr.responseText); // Log response text
                    console.log('Response Headers:', xhr.getAllResponseHeaders());
                    const error = xhr.responseJSON?.error || 'An unexpected error occurred.';
                    $('#error-container').text(error).slideDown();
                    $('#error-container').text(error).slideDown();
                    $('#success-container').slideUp();
                },

            });
        });

        // Show content after loading screen
        setTimeout(() => {
            $('#loading-screen').fadeOut();
            $('#content').fadeIn();
        }, 100);
    });
</script>
