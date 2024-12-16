<?php
// Database connection
$host = 'localhost';
$db = 'weather_and_pokemon_data';
$user = 'phpmyadmin'; // Updated username
$pass = 'kev73bto&'; // Updated password

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the latest Pokémon data
$sql = "SELECT * FROM pokemon_data ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $pokemon = $result->fetch_assoc();
    echo json_encode($pokemon);
} else {
    echo json_encode([]);
}

$conn->close();
?>
