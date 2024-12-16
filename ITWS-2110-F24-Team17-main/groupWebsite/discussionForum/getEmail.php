<?php
header('Content-Type: application/json');

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Check if 'email' and 'username' exists in the session
if (isset($_SESSION['email']) && !empty($_SESSION['email']) && isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    // Return the email and username of the logged-in user
    $siteAdminStatus = null;
    if (!empty($_SESSION['isAdmin'])) {
        $siteAdminStatus = true;
    } 
    else {
        $siteAdminStatus = false;
    }

    echo json_encode([
        'email' => $_SESSION['email'],
        'username' => $_SESSION['username'],
        'siteAdminStatus' => $siteAdminStatus
    ]);
} 
else {
    // Return null if no user is logged in
    $siteAdminStatus = false;
    echo json_encode([
        'email' => null,
        'username' => null,
        'siteAdminStatus' => $siteAdminStatus
    ]);
}

exit;
?>
