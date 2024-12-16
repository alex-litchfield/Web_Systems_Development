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
$password = "";*/;

// Creating connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Checking connection is valid
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get weather data from database
$sql = "SELECT weather_forecast, location_city, location_state, location_country, timezone, humidity, wind_speed, air_temperature, max_temperature, min_temperature, feels_like_temperature, latitude, longitude FROM weather_data ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

// Checking to see if any data was returned
if ($result->num_rows > 0) {
    $weather_item = $result->fetch_assoc();
    // Output the information as JSON
    echo json_encode($weather_item);
} else {
    echo json_encode(["error" => "No Weather data found."]);
}

// Closing connection
$conn->close();
