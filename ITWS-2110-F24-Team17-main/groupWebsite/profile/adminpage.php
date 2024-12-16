<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['email']) && !empty($_SESSION['email']);

// check if the user is a normal user
if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 0) {
   header('Location: index.php'); 
   exit();
}

require '../resources/php/connect.php';

// fetch user data from session variables
$firstName = $_SESSION['firstName'] ?? 'N/A';
$lastName = $_SESSION['lastName'] ?? 'N/A';
$username = $_SESSION['username'] ?? 'N/A';
$email = $_SESSION['email'] ?? 'N/A';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" href="resources/photos/favicon_io/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="../resources/navbar.css">
    <link rel="stylesheet" href="../resources/footer.css">
    <script src="resources/main.js" defer></script>
    <title>Profile</title>
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
            <a href="../health/">Mental Health Resources</a>
        </div>
        <div id="navbarRight">
            <?php
            if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
                echo '<a class="active" href="./">Profile</a> ';
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

    <div class="profile-ctn">
        <h1>Admin Profile</h1>
        <div class="user-info">
            <div class="user-line">
                <p><strong>First Name:</strong> <?= htmlspecialchars($firstName); ?></p>
            </div>
            <div class="user-line">
                <p><strong>Last Name:</strong> <?= htmlspecialchars($lastName); ?></p>
            </div>
            <div class="user-line">
                <p><strong>Username:</strong> <?= htmlspecialchars($username); ?></p>
            </div>
            <div class="user-line">
                <p><strong>Email:</strong> <?= htmlspecialchars($email); ?></p>
            </div>
            <a href="changePass.html" id="resetpass">Reset User Password</a> 
        </div>
        <div class="buttons">
            <a href="../adminApproval/index.php" id="resetpass"><button>Access Admin Page</button></a> 
            <form action="../resources/php/logout.php" method="post" style="display:inline;">
                <button type="submit">Log Out</button>
            </form>
        </div>
    </div>
</body>
</html>