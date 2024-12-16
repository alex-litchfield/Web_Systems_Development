<?php
//set error reporting to display all errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

//create a new MySQLi object to connect to the database
// $userName = getenv('sqlUsername');
// $password = getenv('sqlPassword');
// $db = new mysqli('localhost', 'team17', 'securePassword$$%2%', 'team17');

$username = getenv('sqlUsername');
$password = getenv('sqlPassword');
$db = new mysqli('localhost', $username, $password, 'team17');

//check for connection errors
if ($db -> connect_errno) {
    //if there's an error, display the error message and exit
    echo "Failed to connect: " . $db -> connect_error;
    exit();
}
?>