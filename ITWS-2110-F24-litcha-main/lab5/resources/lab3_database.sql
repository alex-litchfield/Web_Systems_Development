-- Create the database
CREATE DATABASE lab3_database;

-- Use the newly created database
USE lab3_database;

-- Create the pokemon_data table
CREATE TABLE pokemon_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pokemon_name VARCHAR(100),
    pokemon_type VARCHAR(255),
    pokedex_number INT,
    pokemon_height FLOAT,
    pokemon_weight FLOAT,
    pokemon_abilities VARCHAR(255),
    pokemon_image_url VARCHAR(255)
);

-- Create the weather_data table
CREATE TABLE weather_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    weather_forecast VARCHAR(100),
    location_city VARCHAR(100),
    location_state VARCHAR(100),
    location_country VARCHAR(100),
    timezone INT,
    humidity INT,
    wind_speed FLOAT,
    air_temperature FLOAT,
    min_temperature FLOAT,
    max_temperature FLOAT,
    feels_like_temperature FLOAT,
    latitude FLOAT,
    longitude FLOAT
);