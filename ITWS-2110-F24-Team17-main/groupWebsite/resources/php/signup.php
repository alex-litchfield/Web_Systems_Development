<?php

//starts session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//include the file that connects to the database
require 'connect.php';

//validates the format of an email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

//Removes anything wrapped inside <> tags
function noTags($userInput) {
    return filter_var($userInput, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

//check if the request method is POST
if($_SERVER["REQUEST_METHOD"]=="POST")
{
    //get needed items from the POST data
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    //validate that the email is in the correct format
    if (validateEmail($email)) {
        $firstName = noTags($firstName);
        $lastName = noTags($lastName);
        $email = noTags($email);
        $username = noTags($username);
        $password = noTags($password);
        $confirmPassword = noTags($confirmPassword);


        if ($password != $confirmPassword){ //passwords don't match
            echo("<script language='javascript'>alert('Your passwords do not match!');");
            echo "window.location.href='../../signup/index.html'; </script>";
            exit();
        }
        

        //checks if email already exists in database
        $checkQuery = "SELECT * FROM users WHERE email = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo("<script language='javascript'>alert('Email already registered! Please use a different email');");
            echo "window.location.href='../../signup/index.html'; </script>";
        } else {
            //extra variables needed
            $isAdmin = 0;
            $isActiveUser = 1;

            //security
            $hash = password_hash($password, PASSWORD_DEFAULT);

            //query to insert a new user
            $query = "INSERT INTO users (firstName, lastName, username, password, email, isAdmin, isActiveUser) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

            //execute the query
            $result = $db->prepare($query);
        
            //bind parameters and execute query
            $result->bind_param("sssssii", $firstName, $lastName, $username, $hash, $email, $isAdmin, $isActiveUser);
            $result->execute();

            //redirect new user into the website, and sets email in session data
            
            $_SESSION["username"] = $username;
            $_SESSION["email"] = $email;
            $_SESSION["isAdmin"] = $isAdmin;
            $_SESSION["firstName"] = $firstName;
            $_SESSION["lastName"] = $lastName;
            header('Location: ../../index.php');
            exit(); 
        }


    } else {
        echo("<script language='javascript'>alert('Invalid email! Please input a valid email');");
        echo "window.location.href='../../signup/index.html'; </script>";
    }
}
?>