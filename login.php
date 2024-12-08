<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" >
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>Log In</title>
    </head>
    <body>
    <div class="loading-screen" id="loading-screen">
    <img class="loading-icon" src="https://img.icons8.com/?size=100&id=18806&format=png&color=000000" alt=""> <!-- Replace with your desired icon -->
  </div>
        <div class="container">
        <h2>Asset Inventory System</h2>
            <div class="login-details">
                <div class="info">
                    <h3>Log In</h3>
                    <label for="eID">Email</label>
                    <input type="text" id="eID" name="eID">
                    <label for="pWord">Password</label>
                    <input type="password" id="pWord" name="pWord">    
                </div>
                <div class="btns">
                    <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                    </label><p>Remember Me</p>
                    <button class="loginBtn"type="submit" name="submit">Login Now</button>
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