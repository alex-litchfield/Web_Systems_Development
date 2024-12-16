<?php
// Added headers for lab5 security
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self;");
header("Access-Control-Allow-Origin: https://litcha.eastus.cloudapp.azure.com");
header("Access-Control-Allow-Methods: GET");

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

// Get pokemon data from database
$sql = "SELECT pokemon_name, pokemon_type, pokedex_number, pokemon_height, pokemon_weight, pokemon_abilities, pokemon_image_url FROM pokemon_data ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

// Checking to see if any data was returned
if ($result->num_rows > 0) {
    $pokemon = $result->fetch_assoc();
    // Output the information as a JSON
    echo json_encode($pokemon);
} else {
    echo json_encode(["error" => "No Pokémon data found."]);
}

// Closing connection
$conn->close();