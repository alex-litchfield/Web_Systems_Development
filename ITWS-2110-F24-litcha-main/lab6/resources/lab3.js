// Retrieves the pokemon info from the pokemon data table and displays it on the webpage
function getPokeInfo() {
    // Displays the pokemon data on the webpage and taking it from the pokemon data table
    fetch('./resources/getPokemonData.php')
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error("Error:", data.error);
            return;
        }
        document.getElementById("pokemonName").innerHTML = "<strong>Name: </strong>" + data.pokemon_name;
        document.getElementById("pokemonType").innerHTML = "<strong>Type(s): </strong>" + data.pokemon_type;
        document.getElementById("pokemonDexNumber").innerHTML = "<strong>Pokedex Number: </strong>" + data.pokedex_number;
        document.getElementById("pokemonAbilities").innerHTML = "<strong>Abilities: </strong>" + data.pokemon_abilities;
        document.getElementById("pokemonHeight").innerHTML = "<strong>Height: </strong>" + data.pokemon_height + " cm";
        document.getElementById("pokemonWeight").innerHTML = "<strong>Weight: </strong>" + data.pokemon_weight + " kg";
        document.getElementById("pokemonImage").src = data.pokemon_image_url;
    })
    .catch(error => {
        console.error('Error fetching Pokémon data:', error);
    });
}

// Fetches the API call for the pokemon API data and inserts it into the pokemon data table
function fetchPokeInfo() {
    const randomDexNumber = Math.floor(Math.random() * 898) + 1; // Adjust the range based on the number of Pokémon available
    //fetch(`https://pokeapi.co/api/v2/pokemon/${randomDexNumber}/`)

    //Fetches data from pokeAPI and puts it into the pokemon data table
    fetch(`https://pokeapi.co/api/v2/pokemon/${randomDexNumber}/`)
    .then(response => response.json())
    .then(data => {
        // Converting the first letter of pokemonName to uppercase and attaching the rest of the original string
        var pokemonName = data.name.charAt(0).toUpperCase() + data.name.slice(1);
        /* Converting the first letter of each pokemon type
           .map performs the function used on pokemonName on each item of the data.types array
           .join then puts every newly altered item into a new string with ', ' between each item */
        var pokemonTypes = data.types.map(typeInfo => {
            return typeInfo.type.name.charAt(0).toUpperCase() + typeInfo.type.name.slice(1);
        }).join(', ');
        /* Converting the first letter of each pokemon ability 
        .map performs the function used on pokemonName on each item of the data.types array
           .join then puts every newly altered item into a new string with ', ' between each item */
        var pokemonAbilities = data.abilities.map(abilityInfo => {
            return abilityInfo.ability.name.charAt(0).toUpperCase() + abilityInfo.ability.name.slice(1);
        }).join(', ');

        // Send the data to PHP for insertion into MySQL
        fetch('./resources/insertPokemonData.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                pokemon_name: pokemonName,
                pokemon_type: pokemonTypes,
                pokedex_number: data.id,
                pokemon_height: data.height * 10, // Height in dm originally so converted to cm
                pokemon_weight: data.weight / 10, // Weight in hg originally so converted to kg
                pokemon_abilities: pokemonAbilities,
                pokemon_image_url: data.sprites.other["official-artwork"].front_default,
            }),
        })
        .then(response => response.text())
        .then(result => {
            console.log('Data successfully inserted:', result);
            getPokeInfo();
        })
        .catch(error => {
            console.error('Error inserting data:', error);
        });
    });
}

// Displays the weather data on the webpage and taking it from the weather data table
function getWeatherData() {
    fetch('./resources/getWeatherData.php')
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error("Error:", data.error);
            return;
        }
        // Creating locationName string
        var city = data.location_city || "";
        var country = data.location_country || "";
        var state = data.location_state || "";
        // Format location name
        var locationName = city;
        if (city && (state || country)) {
            locationName += ", ";
        }
        locationName += state;
        if (state && country) {
            locationName += ", ";
        }
        locationName += country;
        document.getElementById("cityName").textContent = locationName;
        document.getElementById("airTemp").innerHTML = "<strong>Air Temperature: </strong>" + data.air_temperature + "&degF";
        document.getElementById("weatherForecast").innerHTML = "<strong>Weather Forecast: </strong>" + data.weather_forecast;
        document.getElementById("minTemp").innerHTML = "<strong>Minimum Temperature: </strong>" + data.min_temperature + "&degF";
        document.getElementById("maxTemp").innerHTML = "<strong>Maximum Temperature: </strong>" + data.max_temperature + "&degF";
        document.getElementById("feelsLikeTemp").innerHTML = "<strong>Feels Like Temperature: </strong>" + data.feels_like_temperature + "&degF";
        document.getElementById("windSpeed").innerHTML = "<strong>Wind Speed: </strong>" + data.wind_speed + " Mph";
        document.getElementById("timezone").innerHTML = "<strong>Timezone: </strong>" + data.timezone + ":00 UTC";
        document.getElementById("humidity").innerHTML = "<strong>Humidity: </strong>" + data.humidity + "%";
        document.getElementById("cityLatitude").textContent = "Latitude: " + data.latitude;
        document.getElementById("cityLongitude").textContent = "Longitude: " + data.longitude;
    })
    .catch(error => {
        console.error('Error fetching Weather data:', error);
    });
}

// Closes popup menu and sends the information provided in the input fields to the database
function submitPopupMenuInfo() {
    const popupForm = document.getElementById('popupForm');
    popupForm.style.display = 'none';

    //Submitting weather items first
    const weatherForecastInput = document.getElementById('weatherForecastInput').value;
    const cityInput = document.getElementById('manualCityInput').value;
    const stateInput = document.getElementById('manualStateInput').value;
    const countryInput = document.getElementById('manualCountryInput').value;
    const timezoneInput = document.getElementById('timezoneInput').value;
    const humidityInput = document.getElementById('humidityInput').value;
    const windSpeedInput = document.getElementById('windSpeedInput').value;
    const airTempInput = document.getElementById('airTempInput').value;
    const minTempInput = document.getElementById('minTempInput').value;
    const maxTempInput = document.getElementById('maxTempInput').value;
    const feelsLikeTempInput = document.getElementById('feelsLikeTempInput').value;
    const latitudeInput = document.getElementById('latitudeInput').value;
    const longitudeInput = document.getElementById('longitudeInput').value;

    // Send the data to PHP for insertion into MySQL
    fetch('./resources/getWeatherData.php')
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error("Error:", data.error);
            return;
        }
        fetch('./resources/insertWeatherData.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            // If the input data is blank, the data will default back to what was already in the database for said value
            body: JSON.stringify({
                weather_forecast: weatherForecastInput || data.weather_forecast,
                location_city: cityInput || data.location_city,
                location_state: stateInput || data.location_state,
                location_country: countryInput || data.location_country,
                timezone: timezoneInput || data.timezone,
                humidity: humidityInput || data.humidity,
                wind_speed: windSpeedInput || data.wind_speed,
                air_temperature: airTempInput || data.air_temperature,
                min_temperature: minTempInput || data.min_temperature,
                max_temperature: maxTempInput || data.max_temperature,
                feels_like_temperature: feelsLikeTempInput || data.feels_like_temperature,
                latitude: latitudeInput || data.latitude,
                longitude: longitudeInput || data.longitude
            }),
        })
        .then(response => response.text())
        .then(result => {
            console.log('Data successfully inserted:', result);
            getWeatherData();
        })
        .catch(error => {
            console.error('Error inserting data:', error);
        });
        
    })
    .catch(error => {
        console.error('Error fetching Weather data:', error);
    });

    // Submitting pokemon items next
    const pokemonNameInput = document.getElementById('pokemonNameInput').value;
    const pokemonTypeInput = document.getElementById('pokemonTypeInput').value;
    const pokemonHeightInput = document.getElementById('pokemonHeightInput').value;
    const pokemonWeightInput = document.getElementById('pokemonWeightInput').value;
    const pokemonDexNumberInput = document.getElementById('pokemonDexNumberInput').value;
    const pokemonAbilitiesInput = document.getElementById('pokemonAbilitiesInput').value;
    const pokemonImageInput = document.getElementById('pokemonImageInput').value;

    // Send the data to PHP for insertion into MySQL
    fetch('./resources/getPokemonData.php')
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error("Error:", data.error);
            return;
        }
        fetch('./resources/insertPokemonData.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            // If the input data is blank, the data will default back to what was already in the database for said value
            body: JSON.stringify({
                pokemon_name: pokemonNameInput || data.pokemon_name,
                pokemon_type: pokemonTypeInput || data.pokemon_type,
                pokedex_number: pokemonDexNumberInput || data.pokedex_number,
                pokemon_height: pokemonHeightInput || data.pokemon_height,
                pokemon_weight: pokemonWeightInput || data.pokemon_weight,
                pokemon_abilities: pokemonAbilitiesInput || data.pokemon_abilities,
                pokemon_image_url: pokemonImageInput || data.pokemon_image_url
            }),
        })
        .then(response => response.text())
        .then(result => {
            console.log('Data successfully inserted:', result);
            getPokeInfo();
        })
        .catch(error => {
            console.error('Error inserting data:', error);
        });
        
    })
    .catch(error => {
        console.error('Error fetching Weather data:', error);
    });
}

// Calls insertWeatherData.php and will replace existing values in the weather table with those from an API call
function insertWeatherData(data) {
    // Send the data to PHP for insertion into MySQL
    fetch('./resources/insertWeatherData.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            weather_forecast: data.weather[0].main,
            location_city: data.name || "",
            location_state: data.sys.state || "",
            location_country: data.sys.country || "",
            timezone: data.timezone / 3600 /* Converting time to UTC hours */,
            humidity: data.main.humidity,
            wind_speed: data.wind.speed,
            air_temperature: data.main.temp,
            max_temperature: data.main.temp_max,
            min_temperature: data.main.temp_min,
            feels_like_temperature: data.main.feels_like,
            latitude: data.coord.lat,
            longitude: data.coord.lon
        }),
    })
    .then(response => response.text())
    .then(result => {
        console.log('Data successfully inserted:', result);
        getWeatherData();
    })
    .catch(error => {
        console.error('Error inserting data:', error);
    });
}

// Runs the API call for openweathermap API
function updateWeatherInfo(city = "", state = "", country = "", latitude = null, longitude = null) {
    var url;
    // If latitude and longitude are available (gathered from geolocation in browser), use them for the API call
    if (latitude && longitude) {
        url = `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${openWeatherMapAPIKey}&units=imperial`;
    } 
    // Otherwise, use city, state, and country for the API call (likely from search function)
    else if (city || state || country) {
        url = `https://api.openweathermap.org/data/2.5/weather?q=${city},${state},${country}&appid=${openWeatherMapAPIKey}&units=imperial`;
    } 
    // If no location data is provided, exit function
    else {
        alert("No valid location data provided.");
        return;
    }
    // Fetch weather data from OpenWeatherMap API
    fetch(url)
    .then(response => response.json())
    .then(data => {
        console.log(url);
        insertWeatherData(data);
    })
    .catch(error => {
        console.error("There was an error while attempting to find the location data: ", error);
    });
}

// Handles browser geolocation 
function showPosition(position) {
    const lat = position.coords.latitude
    const long = position.coords.longitude
    updateWeatherInfo("", "", "", lat, long);
}

// When the user prevents the website from accessing their browser location, replace relevant strings with "N/A"
function locationDisabled() {
    document.getElementById("cityName").textContent = "Unknown";
    document.getElementById("airTemp").innerHTML = "<strong>Air Temperature: </strong>N/A";
    document.getElementById("weatherForecast").innerHTML = "<strong>Weather Forecast: </strong>N/A";
    document.getElementById("minTemp").innerHTML = "<strong>Minimum Temperature: </strong>N/A";
    document.getElementById("maxTemp").innerHTML = "<strong>Maximum Temperature: </strong>N/A";
    document.getElementById("feelsLikeTemp").innerHTML = "<strong>Feels Like Temperature: </strong>N/A";
    document.getElementById("windSpeed").innerHTML = "<strong>Wind Speed: </strong>N/A";
    document.getElementById("timezone").innerHTML = "<strong>Timezone: </strong>N/A";
    document.getElementById("humidity").innerHTML = "<strong>Humidity: </strong>N/A";
    document.getElementById("cityLatitude").textContent = "Latitude: N/A";
    document.getElementById("cityLongitude").textContent = "Longitude: N/A";
    document.getElementById("population").innerHTML = "<strong>Population: </strong>N/A";
}

// Attempting to retrieve the location of the user by use of the browser
function getUserLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, locationDisabled);
    } 
    else {
        alert("Geolocation failed to execute in browser");
    }
}

// Grabs the input values from the location search fields
function searchLocation() {
    const city = document.getElementById("cityInput").value;
    const state = document.getElementById("stateInput").value;
    const country = document.getElementById("countryInput").value;
    updateWeatherInfo(city, state, country, null, null);
}

/* Opens the requested popup window */
function openPopup(popupID) {
    popupID.style.display = 'flex';
}

/* Closes the requested popup window */
function closePopup(popupID) {
    popupID.style.display = 'none';
}

// Global variables
const openWeatherMapAPIKey = "755b0ebbfecf791d3a8f83caad2110fc";

// Automatically get user location on page load
getUserLocation();
fetchPokeInfo();