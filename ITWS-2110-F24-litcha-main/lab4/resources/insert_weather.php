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

// Get the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    // Delete all previous entries
    $conn->query("DELETE FROM weather_data");

    // Prepare SQL to insert new weather data
    $sql = "INSERT INTO weather_data (
                weather_forecast, city_name, state_name, country_name, 
                timezone_offset, humidity, wind_speed, air_temperature, 
                min_temperature, max_temperature, feels_like_temperature, 
                latitude, longitude
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters with correct types
        $stmt->bind_param(
            "ssssiiddddddd",
            $data['forecast'],             // VARCHAR -> s
            $data['city'],                 // VARCHAR -> s
            $data['state'],                // VARCHAR -> s
            $data['country'],              // VARCHAR -> s
            $data['timezone'],             // INT -> i
            $data['humidity'],             // INT -> i
            $data['windSpeed'],            // DECIMAL -> d
            $data['airTemperature'],       // DECIMAL -> d
            $data['minTemperature'],       // DECIMAL -> d
            $data['maxTemperature'],       // DECIMAL -> d
            $data['feelsLikeTemperature'], // DECIMAL -> d
            $data['latitude'],             // DECIMAL -> d
            $data['longitude']             // DECIMAL -> d
        );

        // Execute insertion
        if ($stmt->execute()) {
            echo "Weather data inserted successfully.";
        } else {
            echo "Error inserting weather data: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Invalid JSON data.";
}

$conn->close();
?>
