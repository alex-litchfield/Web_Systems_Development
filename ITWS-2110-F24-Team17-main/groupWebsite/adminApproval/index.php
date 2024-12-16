<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] == 0) {
    header("Location: ../index.php");
  }
?>

<!DOCTYPE html>
<html lang = "en">
    <head>
        <link rel="icon" href="../resources/photos/favicon_io/favicon.ico" type="image/x-icon">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Administrator Approval Page</title>
        <link rel="stylesheet" href="adminApproval.css">
        <link rel = "stylesheet" href = "../resources/footer.css">
        <script src="../resources/jquery-3.7.1.min.js"></script>
        <script src="adminApproval.js"></script>
        <link rel="stylesheet" href="../resources/navbar.css">
    </head>
</html>
<body>
    <div id="navbar">
    <div id="navbarLeft">
      <img src="../resources/photos/the_nexus_logo_transparent.png" alt="Nexus Logo">
      <a href="../">The Nexus @ RPI</a>
      <a href="../eventPlanner/index.php">Event Hub</a>
      <a href="../professorPicker/index.php">Professor and Course Reviews</a>
      <a href="../discussionForum/index.php">Discussion Forum</a>
      <a href="../health/index.php">Mental Health Resources</a>
    </div>
    <div id="navbarRight">
      <?php 
        if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
          echo '<a href="../profile/index.php">Profile</a> ';
          $email = $_SESSION['email'];
        } 
        else {
            echo '<a href="../login/">Sign In</a>';
        }
        if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
            $username = $_SESSION['username'];
        }
      ?>
    </div>
  </div>

<div id = "container">
    <div id = "healthContainer">
        <h1 class = "headers" id = "resource">Resources pending approval:</h1>
        <div id = "healthApproval"></div>
    </div>
    
    <div id = "clubContainer">
        <h1 class = "headers" id = "club">Clubs pending approval:</h1>
        <div id = "clubApproval"></div>
    </div>
</div>

<div class="footer">
    <div id="footerLeft">
        <div id="leftTop">
            <h3>The Nexus @ RPI</h3>
        </div>
        <div id="leftBottom">
            <a href="https://github.com/RPI-ITWS/ITWS-2110-F24-Team17"><img src="../resources/photos/githublogo.png" alt="GitHub Logo"></a>
            <a href="https://github.com/RPI-ITWS"><img src="../resources/photos/itwslogo.png" alt="ITWS Logo"></a>
            <a href="https://www.rpi.edu/"><img src="../resources/photos/rpilogo.jpeg" alt="RPI Logo"></a>
            <a href="https://github.com/quacs/quacs-data/blob/master/semester_data/202405/courses.json"><img src="../resources/photos/quacslogo.png" alt="QUACS Logo"></a>
        </div>
    </div>
    <div id="footerMiddle">
        <a class="footerLink" href="../">Home</a>
        <p>|</p>
        <a class="footerLink" href="../eventPlanner/">Event Hub</a>
        <p>|</p>
        <a class="footerLink" href="../professorPicker/">Professor and Course Reviews</a>
        <p>|</p>
        <a class="footerLink" href="./">Discussion Forum</a>
        <p>|</p>
        <a class="footerLink" href="../health/">Mental Health Resources</a>
    </div>
    <div id="footerRight"> 
        <a href="../feedbackForm/"><button id="feedbackbtn">Have Feedback for Us?</button></a>
    </div>
  </div>
</body>