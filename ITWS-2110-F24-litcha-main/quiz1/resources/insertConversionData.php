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

// Retrieving JSON data sent from javaScript
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Deleting existing data in the database
$deleteSql = "DELETE FROM currency_conversion_data";
if ($conn->query($deleteSql) === TRUE) {
    echo "Existing data deleted successfully.\n";
} else {
    echo "Error deleting data: " . $conn->error;
}

// Insert pokemon data into the database
$sql = "INSERT INTO currency_conversion_data (country_to_convert_from, country_to_convert_to, amount_to_convert_from, amount_to_convert_to)
        VALUES (?, ?, ?, ?)";

// Preparing data for execution
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssd",
    $data['country_to_convert_from'],
    $data['country_to_convert_to'],
    $data['amount_to_convert_from'],
    $data['amount_to_convert_to']
);
if ($stmt->execute()) {
    echo "Conversion data successfully inserted!";
} else {
    echo "Error inserting data: " . $stmt->error;
}

// Closing connection
$stmt->close();
$conn->close();
?>
