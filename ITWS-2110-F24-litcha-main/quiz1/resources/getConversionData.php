<?php
// Database information variables
$servername = "localhost";
$username = "phpmyadmin";
$password = "kev73bto&"; 
$dbname = "quiz1_database";

// Creating connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Checking connection is valid
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get currency data from database
$sql = "SELECT country_to_convert_from, country_to_convert_to, amount_to_convert_from, amount_to_convert_to FROM currency_conversion_data ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

// Checking to see if any data was returned
if ($result->num_rows > 0) {
    $conversion = $result->fetch_assoc();
    // Output the information as a JSON
    echo json_encode($conversion);
} else {
    echo json_encode(["error" => "No Conversion data found."]);
}

// Closing connection
$conn->close();