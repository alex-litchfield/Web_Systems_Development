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
$deleteSql = "DELETE FROM weather_data";
if ($conn->query($deleteSql) === TRUE) {
    echo "Existing data deleted successfully.\n";
} else {
    echo "Error deleting data: " . $conn->error;
}

// Insert weather data into the database
$sql = "INSERT INTO weather_data (weather_forecast, location_city, location_state, location_country, timezone, humidity, wind_speed, air_temperature, max_temperature, min_temperature, feels_like_temperature, latitude, longitude)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Preparing data for execution
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssiiddddddd",
    $data['weather_forecast'],
    $data['location_city'],
    $data['location_state'],
    $data['location_country'],
    $data['timezone'],
    $data['humidity'],
    $data['wind_speed'],
    $data['air_temperature'],
    $data['max_temperature'],
    $data['min_temperature'],
    $data['feels_like_temperature'],
    $data['latitude'],
    $data['longitude']
);
if ($stmt->execute()) {
    echo "Weather data successfully inserted!";
} else {
    echo "Error inserting data: " . $stmt->error;
}

// Closing connection
$stmt->close();
$conn->close();
?>
