<?php
session_start();
require(__DIR__ . '/config.php');

ini_set('display_errors', 1); // Disable direct display of errors
ini_set('log_errors', 1); // Enable error logging
error_reporting(E_ALL); // Report all errors

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
  $errorMessage = "Error [$errno]: $errstr in $errfile on line $errline";
  file_put_contents('php_error.log', $errorMessage . PHP_EOL, FILE_APPEND); // Log error to file
  http_response_code(500); // Set HTTP response status to 500 for error
  echo json_encode(['error' => $errorMessage]); // Return error as JSON
});

$employee_id = null;
$errorMessage = null;
$successMessage = null;
$branch_code = null;
$lastName = null;
$firstName = null;
$middleName = null;
$position = null;
$branch = null;
$department = null;
$password = null;

if (!isset($_SESSION['employee_id'])) {
  $errorMessage = "Please log in first!";
  echo " 
      <script>
          alert('$errorMessage');
      </script>
";
  exit;
} else {
  $employee_id = $_SESSION['employee_id'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accounts</title>
  <link rel="icon" href="https://img.icons8.com/?size=100&id=85167&format=png&color=FFFFFF">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
  <link rel="preload" href="admin-assets.php">
  <link rel="preload" href="admin-dashboard.php">
  <link rel="preload" href="admin-accounts.php">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
  <!-- <div class="loading-screen" id="loading-screen">
    <i class="fa-solid fa-gear loading-icon"></i>
  </div> -->

  <div class="popup-overlay" id="popup-overlay"></div>
  <div class="popup" id="add-employee-popup">
    <div class="popup-header">Add New Employee</div>
    <form id="add-employee-form">
      <input type="text" id="employee_id" name="employee_id" placeholder="Employee ID" required>
      <input type="password" id="password" name="password" placeholder="Password" required>
      <input type="text" id="branch_code" name="branch_code" placeholder="Branch Code" required>
      <input type="text" id="position" name="position" placeholder="Position" required>
      <input type="text" id="department" name="department" placeholder="Department" required>
      <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
      <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
      <input type="text" id="midName" name="midName" placeholder="Middle Name">
      <div class="popup-actions">
        <button type="button" class="cancel-btn" id="cancel-btn">Cancel</button>
        <button type="submit" class="save-btn" id="save-btn">Save</button>
      </div>
    </form>
  </div>

  <div class="popup" id="edit-employee-popup">
    <div class="popup-header">Edit Employee</div>
    <form id="edit-employee-form">
      <?php
      $sql = "SELECT * FROM employees WHERE employee_id = $employee_id";

      $result = $connection->query($sql);

      if (!$result) {
        $errorMessage = "Database Query Failed: " . $connection->error;
        echo "<script>
          document.getElementById('error-container').innerHTML += '<div class=\"error\">' + 
          " . json_encode($errorMessage) . " + '</div>';
        </script>";
        file_put_contents('php_error.log', "[" . date('Y-m-d H:i:s') . "] " .$errorMessage . PHP_EOL, FILE_APPEND);
      }

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "
            <input type='text' id='branch_code' name='branch_code' placeholder='{$row['branch_code']}' required>
            <input type='text' id='employee_id' name='employee_id' placeholder='{$row['employee_id']}' required>
            <input type='text' id='position' name='position' placeholder='{$row['position']}' required>
            <input type='text' id='department' name='department' placeholder='{$row['department_name']}' required>
            <input type='text' id='firstName' name='firstName' placeholder='{$row['firstName']}' required>
            <input type='text' id='lastName' name='lastName' placeholder='{$row['lastName']}' required>
            <input type='text' id='middleName'name='middleName' placeholder='{$row['middleName']}'>
            <div class='popup-actions'>
            <button type='button' class='cancel-btn' id='edit-employee-cancel-btn'>Cancel</button>
            <button type='submit' class='save-btn' id='edit-employee-save-btn'>Save</button>
          </div>
          ";
        }
      } else {
        echo json_encode(['error' => $errorMessage]);
      }
      ?>
      <div class="popup-actions">
        <button type="button" class="cancel-btn" id="edit-employee-cancel-btn">Cancel</button>
        <button type="submit" class="save-btn" id="edit-employee-save-btn">Save</button>
      </div>
    </form>
  </div>

  <div class="popup" id="edit-account-popup">
    <div class="popup-header">Edit Account</div>
    <form id="edit-account-form">
      <input type="password" id="password" name="password" placeholder="Password" required>
      <div class="popup-actions">
        <button type="button" class="cancel-btn" id="edit-account-cancel-btn">Cancel</button>
        <button type="submit" class="save-btn" id="edit-account-save-btn">Save</button>
      </div>
    </form>
  </div>

  <div class="container">
    <nav role="navigation" class="primary-navigation">
      <ul>
        <li id="hamburger">
          <i class="fa-solid fa-bars" style="color: #000000;"></i>
        </li>
        <ul>
          <li>
            <a href="admin-dashboard.php">
              <i class="fa-solid fa-table-columns" style="color: #000000;"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li>
            <a href="admin-assets.php">
              <i class="fa-solid fa-computer" style="color: #000000;"></i>
              <p>Assets</p>
            </a>
          </li>
          <li>
            <a href="admin-accounts.php">
              <i class="fa-solid fa-users" style="color: #000000;"></i>
              <p>Employees</p>
            </a>
          </li>
        </ul>
        <li class="logout">
          <a href="logout.php">
            <i class="fa-solid fa-right-from-bracket" style="color: #000000;"></i>
            <p>Log out</p>
          </a>
        </li>
      </ul>
    </nav>
    <div class="content">
      <div id="error-container"></div>
      <div id="success-container"></div>
      <a href="admin-accounts.php">
        <h2>Employees</h2>
      </a>
      <div class="btns">
        <div class="asset-td">
          <i class="fa-solid fa-plus asset-icon"></i><button class="btn" type="submit" id="asset-btn" name="submit">Add Employee</button>
        </div>

        <div class="btn-group">
          <button class="btn" id="accounts-btn">Accounts</button>
          <button class="btn" id="employees-btn">Employees</button>
        </div>

        <div class="search-td">
          <input type="text" placeholder="Search" id="search" name="search"><i class="fa-solid fa-magnifying-glass icon"></i>
        </div>
      </div>
      <table id="employees-table">
        <tr>
          <th>Branch Code</th>
          <th>Employee ID</th>
          <th>Last Name</th>
          <th>First Name</th>
          <th>Middle Name</th>
          <th>Position</th>
          <th>Department</th>
          <th>Action</th>
        </tr>
        <tr>
          <?php
          $errorMessage = "No records found!";

          $sql = "SELECT e.branch_code, e.employee_id, e.lastname, e.firstname, e.middlename, p.position, e.department_code, d.department_name FROM employees e 
          JOIN positions p ON e.position_code = p.position_code
          JOIN departments d ON e.department_code = d.department_code
          ";

          $result = $connection->query($sql);

          if (!$result) {
            $errorMessage = "Database Query Failed: " . $connection->error;
            echo "<script>
            document.getElementById('error-container').innerHTML += '<div class=\"error\">' + 
            " . json_encode($errorMessage) . " + '</div>';
        </script>";
            file_put_contents('php_error.log',"[" . date('Y-m-d H:i:s') . "] " . $errorMessage . PHP_EOL, FILE_APPEND);
            exit;
          }


          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                  <td>{$row['branch_code']}</td>
                  <td>{$row['employee_id']}</td>
                  <td>{$row['lastname']}</td>
                  <td>{$row['firstname']}</td>
                  <td>{$row['middlename']}</td>
                  <td>{$row['position']}</td>
                  <td>{$row['department_name']}</td>
                  <td>
                      <button><i class='fa-solid fa-eye view'></i></button>
                      <button><i class='fa-solid fa-pen-to-square edit-employee'></i></button>
                  </td>
                </tr>";
            }
            $successMessage = "Tables loaded successfully!";
          } else {
            echo "<script>alert('$errorMessage');</script>";
          }
          ?>
        </tr>
      </table>
      <table id="accounts-table" style="display: none;">
        <tr>
          <th>Employee ID</th>
          <th>Password</th>
          <th>Actions</th>
        </tr>
        <tr>
          <?php
          $sql = "SELECT employee_id, password FROM registered_accounts";

          $result = $connection->query($sql);

          if (!$result) {
            $errorMessage = "Database Query Failed: " . $connection->error;
            echo "<script>
            document.getElementById('error-container').innerHTML += '<div class=\"error\">' + 
            " . json_encode($errorMessage) . " + '</div>';
        </script>";
            file_put_contents('php_error.log', "[" . date('Y-m-d H:i:s') . "] " .$errorMessage . PHP_EOL, FILE_APPEND);
            exit;
          }


          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                  <td>{$row['employee_id']}</td>
                  <td>{$row['password']}</td>
                  <td>
                    <button><i class='fa-solid fa-pen-to-square edit-account'></i></button>
                    <button><i class='fa-solid fa-trash remove-account'></i></button>
                  </td>
                </tr>";
            }
          } else {
            $errorMessage = "No records found!";
          }
          $connection->close();
          ?>
        </tr>
      </table>
    </div>
  </div>
</body>

</html>
<script>
  $(document).ready(function() {
    const popup = $("#add-employee-popup");
    const overlay = $("#popup-overlay");

    // Define the tables
    const tables = {
      employees: $("#employees-table"),
      accounts: $("#accounts-table")
    };

    let activeTable = "employees";

    // Function to show a specific table
    function showTable(tableKey) {
      // Validate that the tableKey exists in the tables object
      if (tables[tableKey]) {
        // Hide all tables
        Object.values(tables).forEach((table) => table.hide());
        // Show the selected table
        tables[tableKey].fadeIn(300);
        // Update activeTable
        activeTable = tableKey;
      } else {
        console.error(`Invalid table key: ${tableKey}`);
      }
    }

    // Button click handler
    $("#accounts-btn").click(function() {
      // Toggle between accounts and default table
      if (activeTable === "accounts") {
        showTable("employees");
      } else {
        showTable("accounts");
      }

    });
    $("#employees-btn").click(function() {
      // Toggle between accounts and default table
      if (activeTable === "employees") {
        showTable("accounts");

      } else {
        showTable("employees");
      }

    });


    $("#cancel-transfer").click(function() {
      popup.fadeOut(300);
      overlay.hide();
    });

    $("#confirm-transfer").click(() => {
      const selectedEmployee = $("#employee-id").val();
      alert(`Asset transferred to Employee ID: ${selectedEmployee}`);
      $(".transfer-popup").remove();
    });

    // Show default table on page load
    showTable(tables.default);

    // Show popup
    $(".asset-td").click(function() {
      overlay.show();
      popup.fadeIn(300);
    });

    // Hide popup on cancel
    $("#cancel-btn").click(function() {
      popup.fadeOut(300);
      overlay.hide();
    });

    // Save form data (can be customized)
    $("#add-employee-form").submit(function(e) {
      e.preventDefault();
      const formData = $(this).serialize(); // Collect form data

      // Example AJAX submission (update URL and logic accordingly)
      $.post("addAccount.php", formData, function(response) {
        alert("Employee added successfully!");
        popup.fadeOut(300);
        overlay.hide();
        // Optionally refresh table or UI
      }).fail(function() {
        alert("Failed to add employee!");
      });
    });

    $("#edit-employee-form").submit(function(e) {
      e.preventDefault();
      const formData = $(this).serialize(); // Collect form data

      // Example AJAX submission (update URL and logic accordingly)
      $.post("editAccount.php", formData, function(response) {
        alert("Employee added successfully!");
        popup.fadeOut(300);
        overlay.hide();
        // Optionally refresh table or UI
      }).fail(function() {
        alert("Failed to add employee!");
      });
    });


    // Hide popup when overlay is clicked
    overlay.click(function() {
      popup.fadeOut(300);
      overlay.hide();
    });

    $(".icon").click(function() {
      $(this).toggleClass("active");

      setTimeout(() => {
        $(this).removeClass("active");
      }, 100);
    });

  });

  $(document).ready(function() {
    $('a').on('click', function(event) {
      if (!$(this).attr('target')) {
        event.preventDefault();
        var linkLocation = this.href;

        $('body').fadeOut(500, function() {
          console.log('Fading out complete, navigating to: ' + linkLocation);
          window.location = linkLocation;
        });
      }
    });
  });

  window.addEventListener('load', () => {
    // Hide loading screen after 1 second
    setTimeout(() => {
      document.getElementById('loading-screen').style.display = 'none';
      document.getElementById('content').style.display = 'block';
    }, 100); // Adjust the timeout as needed
  });

  $(document).ready(function() {
    const editAccountPopup = $("#edit-account-popup");
    const editEmployeePopup = $("#edit-employee-popup");
    const overlay = $("#popup-overlay");

    overlay.click(function() {
      editAccountPopup.fadeOut(300);
      editEmployeePopup.fadeOut(300);
      overlay.hide();
    });

    // Show popup
    $(".edit-employee").click(function() {
      overlay.show();
      editEmployeePopup.fadeIn(300);
    });

    $(".edit-account").click(function() {
      overlay.show();
      editAccountPopup.fadeIn(300);
    });


    // Hide popup on cancel
    $("#edit-employee-cancel-btn").click(function() {
      editEmployeePopup.fadeOut(300);
      overlay.hide();
    });

    $("#edit-account-cancel-btn").click(function() {
      editAccountPopup.fadeOut(300);
      overlay.hide();
    });

    // Save form data (can be customized)
    $("#edit-account-form").submit(function(e) {
      e.preventDefault();
      const formData = $(this).serialize(); // Collect form data

      // Example AJAX submission (update URL and logic accordingly)
      $.post("editAccount.php", formData, function(response) {
        alert("Account details edited successfully!");
        editAccountPopup.fadeOut(300);
        overlay.hide();
        // Optionally refresh table or UI
      }).fail(function() {
        alert("Failed to edit account details!");
      });
    });

    $("#edit-employee-form").submit(function(e) {
      e.preventDefault();
      const formData = $(this).serialize(); // Collect form data

      // Example AJAX submission (update URL and logic accordingly)
      $.post("editEmployee.php", formData, function(response) {
        alert("Employee details edited successfully!");
        editEmployeePopup.fadeOut(300);
        overlay.hide();
        // Optionally refresh table or UI
      }).fail(function() {
        alert("Failed to edit employee details!");
      });
    });
  });

  const deleteButtons = document.querySelectorAll(".remove-account");

  deleteButtons.forEach(button => {
    button.addEventListener("click", function(event) {
      // Show confirmation dialog
      const confirmed = confirm("Are you sure you want to delete this item?");
      if (!confirmed) {
        // Prevent deletion if user cancels
        event.preventDefault();
      } else {
        // Proceed with deletion logic here (e.g., remove row or send a delete request)
        const row = button.closest("tr");
        row.remove(); // Example of removing the row from the table
      }
    });
  });
</script>
