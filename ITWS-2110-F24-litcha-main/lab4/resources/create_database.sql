-- Create the database
CREATE DATABASE IF NOT EXISTS weather_and_pokemon_data;

-- Switch to the new database
USE weather_and_pokemon_data;

-- Create the 'pokemon_data' table
CREATE TABLE IF NOT EXISTS pokemon_data (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each entry
    pokemon_name VARCHAR(100) NOT NULL, -- Name of the Pokémon
    pokemon_type VARCHAR(50) NOT NULL,  -- Type(s) of the Pokémon
    pokedex_number INT NOT NULL,        -- Pokedex number of the Pokémon
    pokemon_height DECIMAL(5,2) NOT NULL, -- Height in cm
    pokemon_weight DECIMAL(5,2) NOT NULL, -- Weight in kg
    pokemon_abilities TEXT NOT NULL,    -- Abilities of the Pokémon
    pokemon_image_url VARCHAR(255)      -- Image URL of the Pokémon
);

-- Create the 'weather_data' table
CREATE TABLE IF NOT EXISTS weather_data (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each entry
    weather_forecast VARCHAR(100) NOT NULL, -- Forecast description (e.g., Clouds)
    city_name VARCHAR(100) NOT NULL,    -- Name of the city
    state_name VARCHAR(100) NOT NULL,   -- Name of the state/region
    country_name VARCHAR(100) NOT NULL, -- Name of the country
    timezone_offset INT NOT NULL,       -- Timezone offset (e.g., -4:00 UTC)
    humidity INT NOT NULL,              -- Humidity percentage
    wind_speed DECIMAL(5,2) NOT NULL,   -- Wind speed in mph
    air_temperature DECIMAL(5,2) NOT NULL, -- Air temperature in Fahrenheit
    min_temperature DECIMAL(5,2) NOT NULL, -- Minimum temperature in Fahrenheit
    max_temperature DECIMAL(5,2) NOT NULL, -- Maximum temperature in Fahrenheit
    feels_like_temperature DECIMAL(5,2) NOT NULL, -- Feels like temperature in Fahrenheit
    latitude DECIMAL(8,6) NOT NULL,     -- Latitude coordinate
    longitude DECIMAL(9,6) NOT NULL     -- Longitude coordinate
);

-- Confirm that the tables have been created
SHOW TABLES;
