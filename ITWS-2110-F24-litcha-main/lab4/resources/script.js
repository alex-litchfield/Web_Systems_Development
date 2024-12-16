const editDataBtn = document.getElementById('edit-data-btn');
const popup = document.getElementById('popup');
const submitBtn = document.querySelector('.submit-btn');
const cancelBtn = document.querySelector('.cancel-btn');
const openWeatherMapAPIKey = '755b0ebbfecf791d3a8f83caad2110fc';

let currentPokemonData = {};
let currentWeatherData = {};

// Open popup
editDataBtn.addEventListener('click', () => {
  popup.classList.remove('hidden');
});

// Close popup
submitBtn.addEventListener('click', async () => {
  await updateData();
  popup.classList.add('hidden');
});

cancelBtn.addEventListener('click', () => {
  popup.classList.add('hidden');
});

document.addEventListener("DOMContentLoaded", async () => {
  // Fetch weather data using geolocation
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(position => {
      const latitude = position.coords.latitude;
      const longitude = position.coords.longitude;
      fetchWeatherData(latitude, longitude);
    }, error => console.error("Geolocation error:", error));
  } else {
    console.error("Geolocation is not supported by this browser.");
  }

  // Fetch initial Pokémon data
  await fetchPokemonData();
});

// Function to fetch Pokémon data and update the UI
async function fetchPokemonData() {
  const randomDexNumber = Math.floor(Math.random() * 898) + 1; // Random Pokémon between 1 and 898

  try {
    const response = await fetch(`https://pokeapi.co/api/v2/pokemon/${randomDexNumber}/`);
    const data = await response.json();

    const pokemonName = data.name;
    const pokemonType = data.types.map(type => type.type.name).join(", ");
    const pokedexNumber = data.id;
    const pokemonAbilities = data.abilities.map(ability => ability.ability.name).join(", ");
    const pokemonHeight = data.height / 10; // cm
    const pokemonWeight = data.weight / 10; // kg
    const pokemonImageUrl = data.sprites.other['official-artwork'].front_default;

    currentPokemonData = {
      name: pokemonName,
      type: pokemonType,
      pokedexNumber: pokedexNumber,
      height: pokemonHeight,
      weight: pokemonWeight,
      abilities: pokemonAbilities,
      imageUrl: pokemonImageUrl
    };

    // Insert Pokémon data into the database using PHP
    await fetch('resources/insert_pokemon.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(currentPokemonData)
    });

    // Retrieve Pokémon data from the database
    const dbResponse = await fetch('resources/get_pokemon.php');
    const pokemonFromDB = await dbResponse.json();

    // Update the UI with Pokémon data from the database
    document.querySelector('.pokemon-name').textContent = `Name: ${pokemonFromDB.pokemon_name}`;
    document.querySelector('.pokemon-type').textContent = `Type(s): ${pokemonFromDB.pokemon_type}`;
    document.querySelector('.pokedex-number').textContent = `Pokedex Number: ${pokemonFromDB.pokedex_number}`;
    document.querySelector('.pokemon-abilities').textContent = `Abilities: ${pokemonFromDB.pokemon_abilities}`;
    document.querySelector('.pokemon-height').textContent = `Height: ${pokemonFromDB.pokemon_height} cm`;
    document.querySelector('.pokemon-weight').textContent = `Weight: ${pokemonFromDB.pokemon_weight} kg`;
    document.querySelector('.pokemon-image').src = pokemonFromDB.pokemon_image_url;

  } catch (error) {
    console.error("Error fetching or displaying Pokémon data:", error);
  }
}

// Function to fetch weather data and update the UI
async function fetchWeatherData(latitude, longitude) {
  try {
    const weatherResponse = await fetch(
      `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${openWeatherMapAPIKey}&units=imperial`
    );
    const weatherData = await weatherResponse.json();

    const weatherForecast = weatherData.weather[0].description;
    const cityName = weatherData.name;
    const countryName = weatherData.sys.country;
    const timezoneOffset = weatherData.timezone / 3600;
    const humidity = weatherData.main.humidity;
    const windSpeed = weatherData.wind.speed;
    const airTemperature = weatherData.main.temp;
    const minTemperature = weatherData.main.temp_min;
    const maxTemperature = weatherData.main.temp_max;
    const feelsLikeTemperature = weatherData.main.feels_like;

    currentWeatherData = {
      forecast: weatherForecast,
      city: cityName,
      state: "",
      country: countryName,
      timezone: timezoneOffset,
      humidity: humidity,
      windSpeed: windSpeed,
      airTemperature: airTemperature,
      minTemperature: minTemperature,
      maxTemperature: maxTemperature,
      feelsLikeTemperature: feelsLikeTemperature,
      latitude: latitude,
      longitude: longitude
    };

    // Insert weather data into the database using PHP
    await fetch('resources/insert_weather.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(currentWeatherData)
    });

    // Retrieve weather data from the database
    const dbResponse = await fetch('resources/get_weather.php');
    const weatherFromDB = await dbResponse.json();

    // Update the UI with weather data
    document.querySelector('.left-column').innerHTML = `
      <div>Weather Forecast: ${weatherFromDB.weather_forecast}</div>
      <div>Air Temperature: ${weatherFromDB.air_temperature} F</div>
      <div>Minimum Temperature: ${weatherFromDB.min_temperature} F</div>
      <div>Maximum Temperature: ${weatherFromDB.max_temperature} F</div>
    `;
    document.querySelector('.right-column').innerHTML = `
      <div>Feels Like Temperature: ${weatherFromDB.feels_like_temperature} F</div>
      <div>Timezone: ${weatherFromDB.timezone_offset}:00 UTC</div>
      <div>Wind Speed: ${weatherFromDB.wind_speed} Mph</div>
      <div>Humidity: ${weatherFromDB.humidity}%</div>
    `;
    document.querySelector('.coordinates').innerHTML = `
      <p>Longitude: ${weatherFromDB.longitude}</p>
      <p>Latitude: ${weatherFromDB.latitude}</p>
    `;
    document.querySelector('.location-name').textContent = `${weatherFromDB.city_name}, ${weatherFromDB.country_name}`;

  } catch (error) {
    console.error("Error fetching or displaying weather data:", error);
  }
}

// Function to update data in the database
async function updateData() {
  const pokemonForm = document.querySelector('.pokemon-form');
  const weatherForm = document.querySelector('.weather-form');

  // Gather Pokémon data
  const updatedPokemonData = {
    name: pokemonForm[0].value || currentPokemonData.name,
    type: pokemonForm[1].value || currentPokemonData.type,
    pokedexNumber: pokemonForm[4].value || currentPokemonData.pokedexNumber,
    height: pokemonForm[2].value ? parseFloat(pokemonForm[2].value) : currentPokemonData.height,
    weight: pokemonForm[3].value ? parseFloat(pokemonForm[3].value) : currentPokemonData.weight,
    abilities: pokemonForm[5].value || currentPokemonData.abilities,
    imageUrl: pokemonForm[6].value || currentPokemonData.imageUrl
  };

  // Gather Weather data
  const updatedWeatherData = {
    forecast: weatherForm[0].value || currentWeatherData.forecast,
    city: weatherForm[10].value || currentWeatherData.city,   // Changed index from 9 to 10
    state: weatherForm[11].value || currentWeatherData.state,  // Changed index from 8 to 11
    country: weatherForm[12].value || currentWeatherData.country, // Changed index from 10 to 12
    timezone: weatherForm[5].value ? parseInt(weatherForm[5].value) : currentWeatherData.timezone,
    humidity: weatherForm[7].value ? parseInt(weatherForm[7].value) : currentWeatherData.humidity,
    windSpeed: weatherForm[6].value ? parseFloat(weatherForm[6].value) : currentWeatherData.windSpeed,
    airTemperature: weatherForm[1].value ? parseFloat(weatherForm[1].value) : currentWeatherData.airTemperature,
    minTemperature: weatherForm[2].value ? parseFloat(weatherForm[2].value) : currentWeatherData.minTemperature,
    maxTemperature: weatherForm[3].value ? parseFloat(weatherForm[3].value) : currentWeatherData.maxTemperature,
    feelsLikeTemperature: weatherForm[4].value ? parseFloat(weatherForm[4].value) : currentWeatherData.feelsLikeTemperature,
    latitude: weatherForm[8].value ? parseFloat(weatherForm[8].value) : currentWeatherData.latitude,
    longitude: weatherForm[9].value ? parseFloat(weatherForm[9].value) : currentWeatherData.longitude
  };

  // Send updated Pokémon data to the database
  await fetch('resources/insert_pokemon.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(updatedPokemonData)
  });

  // Send updated weather data to the database
  await fetch('resources/insert_weather.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(updatedWeatherData)
  });

  // Refresh the displayed data after update
  await fetch('resources/get_pokemon.php')
    .then(response => response.json())
    .then(pokemonFromDB => {
      document.querySelector('.pokemon-name').textContent = `Name: ${pokemonFromDB.pokemon_name}`;
      document.querySelector('.pokemon-type').textContent = `Type(s): ${pokemonFromDB.pokemon_type}`;
      document.querySelector('.pokedex-number').textContent = `Pokedex Number: ${pokemonFromDB.pokedex_number}`;
      document.querySelector('.pokemon-abilities').textContent = `Abilities: ${pokemonFromDB.pokemon_abilities}`;
      document.querySelector('.pokemon-height').textContent = `Height: ${pokemonFromDB.pokemon_height} cm`;
      document.querySelector('.pokemon-weight').textContent = `Weight: ${pokemonFromDB.pokemon_weight} kg`;
      document.querySelector('.pokemon-image').src = pokemonFromDB.pokemon_image_url;
    });

  await fetch('resources/get_weather.php')
    .then(response => response.json())
    .then(weatherFromDB => {
      document.querySelector('.left-column').innerHTML = `
        <div>Weather Forecast: ${weatherFromDB.weather_forecast}</div>
        <div>Air Temperature: ${weatherFromDB.air_temperature} F</div>
        <div>Minimum Temperature: ${weatherFromDB.min_temperature} F</div>
        <div>Maximum Temperature: ${weatherFromDB.max_temperature} F</div>
      `;
      document.querySelector('.right-column').innerHTML = `
        <div>Feels Like Temperature: ${weatherFromDB.feels_like_temperature} F</div>
        <div>Timezone: ${weatherFromDB.timezone_offset}:00 UTC</div>
        <div>Wind Speed: ${weatherFromDB.wind_speed} Mph</div>
        <div>Humidity: ${weatherFromDB.humidity}%</div>
      `;
      document.querySelector('.coordinates').innerHTML = `
        <p>Longitude: ${weatherFromDB.longitude}</p>
        <p>Latitude: ${weatherFromDB.latitude}</p>
      `;
      document.querySelector('.location-name').textContent = `${weatherFromDB.city_name}, ${weatherFromDB.country_name}`;
    });
}

document.getElementById('search-btn').addEventListener('click', searchWeatherByLocation);

async function searchWeatherByLocation() {
  const city = document.getElementById('city-input').value.trim();
  const state = document.getElementById('state-input').value.trim();
  const country = document.getElementById('country-input').value.trim();

  if (!city) {
    alert('Please enter a city name.');
    return;
  }

  let locationQuery = city;
  if (state) locationQuery += `,${state}`;
  if (country) locationQuery += `,${country}`;

  try {
    // Fetch weather data from OpenWeatherMap API
    const response = await fetch(
      `https://api.openweathermap.org/data/2.5/weather?q=${locationQuery}&appid=${openWeatherMapAPIKey}&units=imperial`
    );
    if (!response.ok) {
      throw new Error('Weather data not found. Please check your inputs.');
    }

    const weatherData = await response.json();

    // Prepare data for insertion into the database
    const weatherToInsert = {
      forecast: weatherData.weather[0].description,
      city: weatherData.name,
      state: state || "",
      country: weatherData.sys.country,
      timezone: weatherData.timezone / 3600,
      humidity: weatherData.main.humidity,
      windSpeed: weatherData.wind.speed,
      airTemperature: weatherData.main.temp,
      minTemperature: weatherData.main.temp_min,
      maxTemperature: weatherData.main.temp_max,
      feelsLikeTemperature: weatherData.main.feels_like,
      latitude: weatherData.coord.lat,
      longitude: weatherData.coord.lon
    };

    // Send the weather data to the database
    await fetch('resources/insert_weather.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(weatherToInsert)
    });

    // Retrieve the latest weather data from the database
    const dbResponse = await fetch('resources/get_weather.php');
    const weatherFromDB = await dbResponse.json();

    // Display the fetched weather data
    displayWeatherData(weatherFromDB);

  } catch (error) {
    console.error('Error fetching or storing weather data:', error);
  }
}

function displayWeatherData(weatherFromDB) {
  document.querySelector('.location-name').textContent = 
    `${weatherFromDB.city_name}, ${weatherFromDB.country_name}`;
  
  document.querySelector('.left-column').innerHTML = `
    <div>Weather Forecast: ${weatherFromDB.weather_forecast}</div>
    <div>Air Temperature: ${weatherFromDB.air_temperature} F</div>
    <div>Min Temperature: ${weatherFromDB.min_temperature} F</div>
    <div>Max Temperature: ${weatherFromDB.max_temperature} F</div>
  `;

  document.querySelector('.right-column').innerHTML = `
    <div>Feels Like Temperature: ${weatherFromDB.feels_like_temperature} F</div>
    <div>Timezone: ${weatherFromDB.timezone_offset}:00 UTC</div>
    <div>Wind Speed: ${weatherFromDB.wind_speed} Mph</div>
    <div>Humidity: ${weatherFromDB.humidity}%</div>
  `;

  document.querySelector('.coordinates').innerHTML = `
    <p>Latitude: ${weatherFromDB.latitude}</p>
    <p>Longitude: ${weatherFromDB.longitude}</p>
  `;
}
