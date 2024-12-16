<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$isLoggedIn = isset($_SESSION['email']) && !empty($_SESSION['email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="resources/photos/favicon_io/favicon.ico" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="resources/main.css">  
  <link rel="stylesheet" href="resources/navbar.css">
  <link rel="stylesheet" href="resources/footer.css">
  <script src="resources/main.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <title>The Nexus @ RPI</title>
</head>
<body>
    <!-- Navigation Bar -->
    <div id="navbar">
        <div id="navbarLeft">
            <img src="resources/photos/the_nexus_logo_transparent.png" alt="Nexus Logo">
            <a class="active" href="./">The Nexus @ RPI</a>
            <a href="eventPlanner/">Event Hub</a>
            <a href="professorPicker/">Professor and Course Reviews</a>
            <a href="discussionForum/">Discussion Forum</a>
            <a href="health/">Mental Health Resources</a>
        </div>
        <div id="navbarRight">
            <?php
            if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
                echo '<a href="./profile/index.php">Profile</a> ';
                $email = $_SESSION['email'];
            } 
            else {
                echo '<a href="./login/">Sign In</a>';
            }
            if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                $username = $_SESSION['username'];
            }
            ?>
        </div>
    </div>
    <div class="section no-animate" id="landing" logged-in-status="<?php echo $isLoggedIn ? 'true' : 'false'; ?>">
        <img id="landingImage" src="resources/photos/the_nexus_logo_transparent_with_borders.png" alt="Nexus Logo">
        <div id="buttonsContainer">
            <button class="linkbutton" style="<?php echo $isLoggedIn ? 'display: none;' : ''; ?>" id="homeSignupButton">Sign Up Today!</button>
        </div>
        <div class="popup" id="popup"></div>
        <div class="contentBox">
            <div class="innerContentBox" id="topContent">
                <div class="iconTitleBox">
                    <h2>Event Hub</h2>
                    <a href="eventPlanner/"><i class="fa-solid fa-calendar-days fa-5x"></i></a>
                </div>
                <div class="iconTitleBox">
                    <h2>Professor/Course Reviews</h2>
                    <a href="professorPicker/"><i class="fa-solid fa-chalkboard-user fa-5x"></i></a>
                </div>
            </div>
            <div class="innerContentBox" id="bottomContent">
                <div class="iconTitleBox">
                    <h2>Discussion Forum</h2>
                    <a href="discussionForum/"><i class="fa-solid fa-comments fa-5x"></i></a>
                </div>
                <div class="iconTitleBox">
                    <h2>Mental Health Resources</h2>
                    <a href="health/"><i class="fa-solid fa-brain fa-5x"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div id="footerLeft">
            <div id="leftTop">
                <h3>The Nexus @ RPI</h3>
            </div>
            <div id="leftBottom">
                <a href="https://github.com/RPI-ITWS/ITWS-2110-F24-Team17"><img src="resources/photos/githublogo.png" alt="GitHub Logo"></a>
                <a href="https://github.com/RPI-ITWS"><img src="resources/photos/itwslogo.png" alt="ITWS Logo"></a>
                <a href="https://www.rpi.edu/"><img src="resources/photos/rpilogo.jpeg" alt="RPI Logo"></a>
                <a href="https://github.com/quacs/quacs-data/blob/master/semester_data/202405/courses.json"><img src="resources/photos/quacslogo.png" alt="QUACS Logo"></a>
            </div>
        </div>
        <div id="footerMiddle">
            <a class="footerLink" href="./">Home</a>
            <p>|</p>
            <a class="footerLink" href="eventPlanner/">Event Hub</a>
            <p>|</p>
            <a class="footerLink" href="professorPicker/">Professor and Course Reviews</a>
            <p>|</p>
            <a class="footerLink" href="discussionForum/">Discussion Forum</a>
            <p>|</p>
            <a class="footerLink" href="health/">Mental Health Resources</a>
        </div>
        <div id="footerRight"> 
            <a href="feedbackForm/"><button id="feedbackbtn">Have Feedback for Us?</button></a>
        </div>
    </div>
</body>
</html>