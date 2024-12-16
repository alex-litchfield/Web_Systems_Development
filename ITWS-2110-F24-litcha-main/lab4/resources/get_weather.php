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

// Retrieve the latest weather data
$sql = "SELECT * FROM weather_data ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $weather = $result->fetch_assoc();
    echo json_encode($weather);
} else {
    echo json_encode([]);
}

$conn->close();
?>
