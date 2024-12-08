<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accounts</title>
  <link rel="icon" href="https://img.icons8.com/?size=100&id=85167&format=png&color=FFFFFF">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" >
  <link rel="preload" href="admin-assets.php">
  <link rel="preload" href="admin-dashboard.php">
  <link rel="preload" href="admin-accounts.php">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
</head>
<body>
<div class="loading-screen" id="loading-screen">
<i class="fa-solid fa-gear loading-icon"></i>
  </div>
  <div class="popup-overlay" id="popup-overlay"></div>
<div class="popup" id="add-asset-popup">
  <div class="popup-header">Add New Account</div>
  <form id="add-account-form">
    <input type="text" id="bCode" name="bCode" placeholder="Branch Code" required>
    <input type="text" id="eID" name="eID" placeholder="Employee ID" required>
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
<div class="container">
  <nav role="navigation" class="primary-navigation">
                    <ul>
                        <li id="hamburger">
                          <i class="fa-solid fa-bars" style="color: #000000;"></i>
                        </li>
                        <ul>
                            <li>
                                <a href="admin-dashboard.php">
                                <i class="fa-solid fa-table-columns"  style="color: #000000;"></i>
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
                                    <p>Accounts</p>
                                </a>
                            </li>
                        </ul>
                            <li class="logout">
                                <a href="logout.php">
                                 <i class="fa-solid fa-right-from-bracket"  style="color: #000000;"></i>
                                <p>Log out</p>
                            </a>
                        </li>
                    </ul>
                </nav>
        <div class="content">
        <a href="admin-accounts.php"><h2>Accounts</h2> </a>
            <div class="btns">
                    <div class="asset-td">
                            <i class="fa-solid fa-plus asset-icon"></i><button class="btn"type="submit" id="asset-btn" name="submit">Add Account</button>
                    </div>
                    <div class="search-td">
                        <input type="text" placeholder="Search" id="search" name="search"><i class="fa-solid fa-magnifying-glass"></i>
                     </div>
                </div>
                 <div class="accounts">
                 <table>
                    <tr>
                        <th>Branch Code</th>
                        <th>ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>001</td>
                        <td>101</td>
                        <td>Smith</td>
                        <td>John</td>
                        <td>Albert</td>
                        <td>Manager</td>
                        <td>Operations</td>
                        <td>Active</td>
                        <td><button><i class="fa-solid fa-eye"></i></button></td>
                    </tr>
                    <tr>
                        <td>002</td>
                        <td>102</td>
                        <td>Doe</td>
                        <td>Jane</td>
                        <td>Marie</td>
                        <td>Supervisor</td>
                        <td>Logistics</td>
                        <td>Inactive</td>
                        <td><button><i class="fa-solid fa-eye"></i></button></td>
                    </tr>
                    <tr>
                        <td>003</td>
                        <td>103</td>
                        <td>Johnson</td>
                        <td>Michael</td>
                        <td>Lee</td>
                        <td>Technician</td>
                        <td>Maintenance</td>
                        <td>Active</td>
                        <td><button><i class="fa-solid fa-pen-to-square"></i></button></td>
                    </tr>
                </table>
                   </div>
            </div>  
        </div>
</body>
</html> 
<script>
    $(document).ready(function () {
    const popup = $("#add-asset-popup");
    const overlay = $("#popup-overlay");

    // Show popup
    $(".asset-td").click(function () {
      overlay.show();
      popup.fadeIn(300);
    });

    // Hide popup on cancel
    $("#cancel-btn").click(function () {
      popup.fadeOut(300);
      overlay.hide();
    });

    // Save form data (can be customized)
    $("#add-asset-form").submit(function (e) {
      e.preventDefault();
      const formData = $(this).serialize(); // Collect form data

      // Example AJAX submission (update URL and logic accordingly)
      $.post("add_asset.php", formData, function (response) {
        alert("Asset saved successfully!");
        popup.fadeOut(300);
        overlay.hide();
        // Optionally refresh table or UI
      }).fail(function () {
        alert("Failed to save asset!");
      });
    });

    // Hide popup when overlay is clicked
    overlay.click(function () {
      popup.fadeOut(300);
      overlay.hide();
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
</script>
