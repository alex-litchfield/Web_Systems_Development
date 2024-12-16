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
    $conn->query("DELETE FROM pokemon_data");

    // Prepare SQL to insert new Pokémon data
    $sql = "INSERT INTO pokemon_data (
                pokemon_name, pokemon_type, pokedex_number, pokemon_height, 
                pokemon_weight, pokemon_abilities, pokemon_image_url
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters with correct types
        $stmt->bind_param(
            "ssiddss", 
            $data['name'], 
            $data['type'], 
            $data['pokedexNumber'], 
            $data['height'], 
            $data['weight'], 
            $data['abilities'], 
            $data['imageUrl']
        );

        // Execute insertion
        if ($stmt->execute()) {
            echo "Pokémon data inserted successfully.";
        } else {
            echo "Error inserting Pokémon data: " . $stmt->error;
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
