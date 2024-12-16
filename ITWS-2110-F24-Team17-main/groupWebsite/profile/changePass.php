<?php

// set error reporting to display all errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// starts session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// include the file that connects to the database
require '../resources/php/connect.php';

// checks for POST from html
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // gets data from html
    $email = $_POST['email'] ?? '';
    $oldPass = $_POST['oldPass'] ?? '';
    $newPass = $_POST['newPass'] ?? '';
    $confirmNewPass = $_POST['confirmNewPass'] ?? '';

    // check if new inputted passwords match
    if ($newPass !== $confirmNewPass) {
        echo "<script>alert('New passwords do not match.'); window.location.href='changePass.html';</script>";
        exit();
    }

    // check if the email exists in the database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        die("Query preparation failed: " . $db->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('The inputted email does not have an existing account.'); window.location.href='changePass.html';</script>";
        exit();
    }

    $user = $result->fetch_assoc();

    // verify the old password
    if (!password_verify($oldPass, $user["password"])) {
        echo "<script>alert('The inputted old password is incorrect.'); window.location.href='changePass.html';</script>";
        exit();
    }

    // hash the new password
    $newPassHash = password_hash($newPass, PASSWORD_DEFAULT);

    // update password in the database
    $updateQuery = "UPDATE users SET password = ? WHERE email = ?";
    $updateStmt = $db->prepare($updateQuery);
    if (!$updateStmt) {
        die("Update query preparation failed: " . $db->error);
    }

    $updateStmt->bind_param("ss", $newPassHash, $email);
    $updateStmt->execute();

    if ($updateStmt->affected_rows > 0) {
        echo "
            <div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; text-align: center;'>
                <h2>Password updated successfully!</h2>
                <p><a href='index.php'>Click Here to Return to Profile</a></p>
            </div>";
    } 
    else {
        echo "<script>alert('Password update failed. Please try again.'); window.location.href='changePass.html';</script>";
    }

    $stmt->close();
    $updateStmt->close();
    $db->close();
}
?>