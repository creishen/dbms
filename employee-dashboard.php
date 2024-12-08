<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" >
</head>
<body>
  <div class="container">
    <nav role="navigation" class="primary-navigation">
                    <ul>
                        <li id="hamburger">
                            <a href="#">
                                <i class="bi bi-list"></i>
                            </a>
                        </li>
                        <li>
                            <a href="admin-dashboard.php">
                                <i class="bi bi-bar-chart-line-fill"></i>
                            </a>
                            <p>Dashboard</p>
                        </li>
                        <li>
                            <a href="employee-assets.php">
                                <i class="bi bi-pc-display"></i>
                            </a>
                            <p>Assets</p>
                        </li>
                        <ul>
                            <li>
                                <a href="#">
                                    <i class="bi bi-box-arrow-right"></i>
                                </a>
                                <p>Log out</p>
                            </li>
                        </ul>
                    </ul>
                </nav>
            <div class="top-content">  
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
</script>
