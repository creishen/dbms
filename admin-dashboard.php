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
                        <ul>
                            <li>
                                <a href="logout.php">
                                    <i class="fa-solid fa-right-from-bracket"  style="color: #000000;"></i>
                                    <p>Log out</p>
                                </a>
                            </li>
                        </ul>
                    </ul>
                </nav>
        <div class="top-content">
                <h2>Dashboard</h2>
                <div class="assets">
                    <a href="admin-assets.php">
                    <h4>Computer Assets</h4>
                        <table>
                            <tr><th>Branches</th><th>Devices</th></tr>
                        </table>
                    </a>
                </div>
                <div class="accounts">
                     <a href="admin-accounts.php">
                     <h4>Accounts</h4>
                        <table>
                            <tr><th>Employee IDs</th><th>Position</th></tr>
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
                            <th>By</th>
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
</script>
