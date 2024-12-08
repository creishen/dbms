<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="icon" sizes="16x16" href="https://img.icons8.com/?size=100&id=98956&format=png&color=FFFFFF">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" >
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="preload" href="admin-assets.php">
  <link rel="preload" href="admin-dashboard.php">
  <link rel="preload" href="admin-accounts.php">
</head>
<body>
<div class="loading-screen" id="loading-screen">
<i class="fa-solid fa-gear loading-icon"></i>
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
        <div class="top-content">
                <h2>Dashboard</h2>
                <div class="assets">
                    <a href="admin-assets.php">
                    <h4>Company Assets</h4></a>
                        <table>
                            <tr><th>Branch</th></tr>
                            <tr class="dropdown">
                            <td>Branch 001 <button class="dropdown-button" onclick="toggleDropdown('branch1')"><i class="fa-solid fa-caret-down"></i></button></td>
                            <tr>
                                <td>
                                    <div class="dropdown-container">
                                        <ul id="branch1" class="dropdown-list">
                                            <li>Samsung</li>
                                            <li>Apple</li>
                                            <li>Sony</li>
                                            <li>LG</li>
                                            <li>Huawei</li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tr>
                        <tr>
                            <td>Branch 002 <button class="dropdown-button" onclick="toggleDropdown('branch2')"><i class="fa-solid fa-caret-down"></i></button></td>
                            <tr>
                                <td>
                                    <div class="dropdown-container">
                                        <ul id="branch2" class="dropdown-list">
                                            <li>Dell</li>
                                            <li>HP</li>
                                            <li>Lenovo</li>
                                            <li>Acer</li>
                                            <li>Asus</li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tr>
                        </table>
                </div>
                <div class="accounts">
                     <a href="admin-assets.php">
                     <h4>Employees</h4>
                        <table>
                            <tr><th>Employee ID</th><th>No. of Assets</th></tr>
                            <tr><td>101</td><td>1</td></tr>
                            <tr><td>102</td><td>1</td></tr>
                            <tr><td>103</td><td>1</td></tr>
                        </table>
                    </a>
                </div>
                
                <div class="history">
                <h4>History</h4>
                <table>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Activity</th>
                            <th>Emloyee ID</th>
                        </tr>
                        <tr>
                            <td>2023-10-01</td>
                            <td>09:00 AM</td>
                            <td>Asset A001 Acquired</td>
                            <td>101</td>
                        </tr>
                        <tr>
                            <td>2023-06-05</td>
                            <td>10:30 AM</td>
                            <td>Asset A002 Acquired</td>
                            <td>102</td>
                        </tr>
                        <tr>
                            <td>2023-12-01</td>
                            <td>02:45 PM</td>
                            <td>Asset A002 Returned</td>
                            <td>102</td>
                        </tr>
                        <tr>
                            <td>2023-08-10</td>
                            <td>01:15 PM</td>
                            <td>Asset A003 Acquired</td>
                            <td>103</td>
                        </tr>
                </table>
            </div>  
        </div>
        </div>
  </div>
</body>
</html> 
<script>
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

    function toggleDropdown(dropdownId) {
    // Hide all dropdowns first
    const dropdownLists = document.querySelectorAll('.dropdown-list');
    dropdownLists.forEach(list => {
        if (list.id !== dropdownId) {
            list.style.display = 'none';
        }
    });

    // Toggle the selected dropdown
    const dropdownList = document.getElementById(dropdownId);
    if (dropdownList.style.display === 'block') {
        dropdownList.style.display = 'none';
    } else {
        dropdownList.style.display = 'block';
    }
}

</script>
