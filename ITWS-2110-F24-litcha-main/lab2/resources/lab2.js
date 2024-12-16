function getPokeInfo() {
    const randomDexNumber = Math.floor(Math.random() * 898) + 1; // Adjust the range based on the number of Pokémon available
    fetch(`https://pokeapi.co/api/v2/pokemon/${randomDexNumber}/`)
    .then(response => response.json())
    .then(data => {
        // Converting the first letter of pokemonName to uppercase and attaching the rest of the original string
        var pokemonName = data.name;
        pokemonName = pokemonName.charAt(0).toUpperCase() + pokemonName.slice(1);
        /* Converting the first letter of each pokemon type
           .map performs the function used on pokemonName on each item of the data.types array
           .join then puts every newly altered item into a new string with ', ' between each item */
        var pokemonTypes = data.types.map(typeInfo => {
            currentType = typeInfo.type.name;
            return currentType.charAt(0).toUpperCase() + currentType.slice(1);
        }).join(', ');
        /* Converting the first letter of each pokemon ability 
        .map performs the function used on pokemonName on each item of the data.types array
           .join then puts every newly altered item into a new string with ', ' between each item */
        var pokemonAbilities = data.abilities.map(abilityInfo => {
            currentAbility = abilityInfo.ability.name;
            return currentAbility.charAt(0).toUpperCase() + currentAbility.slice(1);
        }).join(', ');
        document.getElementById("pokemonName").innerHTML = "<strong>Name: </strong>" + pokemonName;
        document.getElementById("pokemonType").innerHTML = "<strong>Type(s): </strong>" + pokemonTypes;
        document.getElementById("pokemonDexNumber").innerHTML = "<strong>Pokedex Number: </strong>" + data.id;
        document.getElementById("pokemonAbilities").innerHTML = "<strong>Abilities: </strong>" + pokemonAbilities;
        document.getElementById("pokemonHeight").innerHTML = "<strong>Height: </strong>" + data.height * 10 + " cm"; // Height in dm originally so converted to cm
        document.getElementById("pokemonWeight").innerHTML = "<strong>Weight: </strong>" + data.weight / 10 + " kg"; // Weight in hg originally so converted to kg
        document.getElementById("pokemonImage").src = data.sprites.other["official-artwork"].front_default;
    });
}

// Replaces strings in the HTML are replaced by revelant information from the openweathermap API
function getWeatherData(data) {
    document.getElementById("airTemp").innerHTML = "<strong>Air Temperature: </strong>" + data.main.temp + "&degF";
    document.getElementById("weatherForcast").innerHTML = "<strong>Weather Forecast: </strong>" + data.weather[0].main;
    document.getElementById("minTemp").innerHTML = "<strong>Minimum Temperature: </strong>" + data.main.temp_min + "&degF";
    document.getElementById("maxTemp").innerHTML = "<strong>Maximum Temperature: </strong>" + data.main.temp_max + "&degF";
    document.getElementById("feelsLikeTemp").innerHTML = "<strong>Feels Like Temperature: </strong>" + data.main.feels_like + "&degF";
    document.getElementById("windSpeed").innerHTML = "<strong>Wind Speed: </strong>" + data.wind.speed + " Mph";
    document.getElementById("timezone").innerHTML = "<strong>Timezone: </strong>" + data.timezone / 3600 + ":00 UTC";
    document.getElementById("humidity").innerHTML = "<strong>Humidity: </strong>" + data.main.humidity + "%";
    document.getElementById("cityLatitude").textContent = "Latitude: " + data.coord.lat;
    document.getElementById("cityLongitude").textContent = "Longitude: " + data.coord.lon;
}

// Runs the API call for openweathermap API
function updateWeatherInfo(city = "", state = "", country = "", latitude = null, longitude = null) {
    var url;
    // If latitude and longitude are available (gathered from geolocation in browser), use them for the API call
    if (latitude && longitude) {
        url = `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${openWeatherMapAPIKey}&units=imperial`;
        console.log(url);
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
        // Creating locationName string
        city = data.name;
        country = data.sys.country;
        state = data.sys.state || "";
        // Format location name
        let locationName = city;
        if (city && (state || country)) {
            locationName += ", ";
        }
        locationName += state;
        if (state && country) {
            locationName += ", ";
        }
        locationName += country;
        document.getElementById("cityName").textContent = locationName;
        getWeatherData(data);
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
    document.getElementById("weatherForcast").innerHTML = "<strong>Weather Forecast: </strong>N/A";
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
    } else {
        alert("Geolocation failed to execute in browser");
    }
}

// Global variables
const openWeatherMapAPIKey = "755b0ebbfecf791d3a8f83caad2110fc";
// Adding event listener for the search form
document.getElementById("searchBar").addEventListener("submit", function(event) {
    event.preventDefault();
    const city = document.getElementById("cityInput").value;
    const state = document.getElementById("stateInput").value;
    const country = document.getElementById("countryInput").value;
    updateWeatherInfo(city, state, country);
});
// Automatically get user location on page load
getUserLocation();
getPokeInfo();