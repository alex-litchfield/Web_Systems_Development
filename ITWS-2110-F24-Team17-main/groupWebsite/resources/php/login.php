<?php

//starts session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//include the file that connects to the database
require 'connect.php';

//removes anything wrapped inside <> tags
function noTags($userInput) {
    return filter_var($userInput, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

//check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //get email and password from the POST data
    $preEmail = $_POST["email"];
    $prePassword = $_POST["password"];
    
    $email = noTags($preEmail);
    $password = noTags($prePassword);

    //prepare the query to select the user by email
    $query = "SELECT * FROM users WHERE email = ?";
    
    //execute the query
    $statement = $db->prepare($query);
    $statement->bind_param("s", $email);
    $statement->execute();
    $result = $statement->get_result();

    //check if a user with the provided email exists
    if ($result->num_rows > 0) {
        $userInfo = $result->fetch_assoc();
        $storedPasswordHash = $userInfo['password'];
        $isAdmin = $userInfo['isAdmin'];
        
        //verify the password against the stored hash
        if (password_verify($password, $storedPasswordHash)) {
            //Password is correct, redirect to the correct page
            $isAdmin = $userInfo['isAdmin'];
            $firstName = $userInfo['firstName'];
            $lastName = $userInfo['lastName'];
            $username = $userInfo['username'];
            $_SESSION["username"] = $username;
            $_SESSION["email"] = $email;
            $_SESSION["isAdmin"] = $isAdmin;
            $_SESSION["firstName"] = $firstName;
            $_SESSION["lastName"] = $lastName;

            header('Location: ../../index.php');
            exit(); 
        } else {
            //invalid password
            echo "<script>
                    alert('Invalid username or password!');
                    window.location.href = '../../login/index.html';
                  </script>";
            exit();
        }
    } else {
        //user not found
        echo "<script>
                alert('Invalid username or password!');
                window.location.href = '../../login/index.html';
              </script>";
        exit();
    
    }
}
?>