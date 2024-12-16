<?php
// Added headers for lab5 security
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self;");
header("Access-Control-Allow-Origin: https://litcha.eastus.cloudapp.azure.com");
header("Access-Control-Allow-Methods: POST");

// Database information variables
$servername = "localhost";
$dbname = "lab3_database";
$username = "phpmyadmin";
$password = "kev73bto&";
/*$username = "root";
$password = "";*/

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
$deleteSql = "DELETE FROM pokemon_data";
if ($conn->query($deleteSql) === TRUE) {
    echo "Existing data deleted successfully.\n";
} else {
    echo "Error deleting data: " . $conn->error;
}

// Insert pokemon data into the database
$sql = "INSERT INTO pokemon_data (pokemon_name, pokemon_type, pokedex_number, pokemon_height, pokemon_weight, pokemon_abilities, pokemon_image_url)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

// Preparing data for execution
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssiddss",
    $data['pokemon_name'],
    $data['pokemon_type'],
    $data['pokedex_number'],
    $data['pokemon_height'],
    $data['pokemon_weight'],
    $data['pokemon_abilities'],
    $data['pokemon_image_url']
);
if ($stmt->execute()) {
    echo "Pokemon data successfully inserted!";
} else {
    echo "Error inserting data: " . $stmt->error;
}

// Closing connection
$stmt->close();
$conn->close();
?>
