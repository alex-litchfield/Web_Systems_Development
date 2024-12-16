<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../resources/photos/favicon_io/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../resources/jquery-3.7.1.min.js"></script>
    <script src="./health.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="./health.css">
    <link rel="stylesheet" href="../resources/navbar.css">
    <link rel="stylesheet" href="../resources/footer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <title>Health Resources</title>
</head>
<body>
    <!-- Navigation Bar -->
    <div id="navbar">
        <div id="navbarLeft">
            <img src="../resources/photos/the_nexus_logo_transparent.png" alt="Nexus Logo">
            <a href="../">The Nexus @ RPI</a>
            <a href="../eventPlanner/">Event Hub</a>
            <a href="../professorPicker/">Professor and Course Reviews</a>
            <a href="../discussionForum/">Discussion Forum</a>
            <a class="active" href="./">Mental Health Resources</a>
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

    <div class="resources">
        <div class="card">
            <img src="../resources/photos/activeMinds.png" alt="Active Minds logo">
            <div class="description">
                <h2>Active Minds</h2>
                <p>Active Minds seeks to start open conversations about mental health and mental illness with a focus on student issues. We aim to educate students about mental health and available resources and to bring attention to the issues prevalent in our community.</p>
                <br/>
            </div>
        </div>

        <div class="card">
            <img src="../resources/photos/soleSurvivors.png" alt="Sole Survivors logo">
            <div class="description">
                <h2>Solve Survivors</h2>
                <p>Sole Survivors is a student-run organization geared towards demonstrating community support for sexual assault survivors. We host interactive art events on campus throughout the year to de-stigmatize the topic and raise awareness about sexual assault.</p>
            </div>
        </div>

        <div class="card">
            <img src="../resources/photos/counseling.jpg" alt="Counseling image">
            <div class="description">
                <h2>Walk-in Counseling</h2>
                <p>New this semester, RPI is offering walk-in counseling sessions! It will be on a first come first serve basis Monday, Wednesday, Friday 10am to 12pm OR Tuesday, Thursday 2pm to 4pm. No appointments needed. Located in Academy Hall 3000 Level, Suite 3200.</p>
            </div>
        </div>

    </div>

    <div id="dialogTwo" title="Confirm Delete">
        <p>Are you sure you want to delete?</p>
    </div>

    <!-- will be writing into moreResources -->
    <div id="bar">
        <h1 id = "addResourceHeader">Additional Resources</h1>
        <div id="sorter">
            <div class="dropdown">
                <button id="dropButton" class="dropdownButton">Sort By</button>
                <div id="options" class="content">
                    <button id="likes">Likes</button>
                    <button id="date">Date Posted</button>
                </div>
            </div>
        </div>
        <button id = "formButton">Add a Resource</button>
    </div>
    
        
    <!-- getting updated with php calls -->

    <div id = "resourcesContainer">
        <div id = "approvalFormContainer" class = "popupContainer">
            <form id = "approvalForm" action = "resourceToApproval.php" method = "POST">
                <label>Resource Name: </label>
                <input type = "text" id = "resourceInput" class = "formInput" name = "resourceInput" placeholder = "Enter the name of your resource" required>
                <br>
                <label>Resource Description: </label>
                <textarea type = "text" id = "descriptionInput" class = "formInput" name = "descriptionInput" placeholder = "Enter a description" required></textarea>
                <br>
                <div id = "buttonDiv">
                    <button type = "submit" id = "submitButton">Submit</button>
                    <button type = "button" id = "closeButton">Close</button>
                </div>
            </form>
        </div>
        <div id="moreResources"></div>
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
            <a class="footerLink" href="../discussionForum/">Discussion Forum</a>
            <p>|</p>
            <a class="footerLink" href="./">Mental Health Resources</a>
        </div>
        <div id="footerRight"> 
            <a href="../feedbackForm/"><button id="feedbackbtn">Have Feedback for Us?</button></a>
        </div>
  </div>
    
</body>
</html>
