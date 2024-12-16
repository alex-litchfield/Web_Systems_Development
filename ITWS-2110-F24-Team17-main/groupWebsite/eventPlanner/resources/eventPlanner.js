let tokenClient;
const API_KEY = 'AIzaSyDgUj71xACmy2fD45eAbBQwkoZiOt9l720'; 
const CLIENT_ID = '427489259850-eq4kfm1ib9m3to3u5ob7hvmb47ius2pb.apps.googleusercontent.com'; 
const SCOPES = 'https://www.googleapis.com/auth/calendar.readonly';

var expandMap = false;
var currentMarker = null;
var map = null;

var locationDic = [];

function addMarkerToMap(name) {
  if (currentMarker != null){
    map.removeObject(currentMarker);
  }
  var html = document.createElement('div');
  var containerDiv = document.createElement('div');
  containerDiv.style.border = '2px solid red';
  containerDiv.style.backgroundColor = 'rgba(255, 0, 0, 0.5)';
  containerDiv.style.padding = '5px';
  containerDiv.style.borderRadius = '5px';
  containerDiv.style.display = 'inline-block';
  
  var divText = document.createElement('div');
  divText.innerHTML = name;
  divText.style.color = 'black';
  
  containerDiv.appendChild(divText);
  html.appendChild(containerDiv);
  
  // console.log(locationDic)
  var currLat = 0;
  var currLong = 0;
  for(let i = 0; i < locationDic.length; i++){
    if(locationDic[i].locationName == name){
      currLat = locationDic[i].lat;
      currLong = locationDic[i].long;
      // console.log(name)
      // console.log("LAT:", currLat, "LONG", currLong);
      break;
    }
  }

  var domIcon = new H.map.DomIcon(html);

  var marker = new H.map.DomMarker({lat: currLat, lng: currLong,},
                                    {icon: domIcon});
  currentMarker = marker;
  map.addObject(marker);
  map.setCenter({lat: currLat, lng: currLong});
  map.setZoom(15);
  
}

function getLongAndLat(){
  navigator.geolocation.getCurrentPosition(displayCurrLocation,locationNotAllowed,
    { 
      maximumAge:10000, timeout:5000, enableHighAccuracy: true
    }
  );
}

//checks if user does not allow location to be shown
function locationNotAllowed(error){
  var html = document.createElement('div');
  divText = document.createElement('div');
  divText.innerHTML = "RPI";
  html.appendChild(divText);
  const lat = 42.72988098601549;
  const lng = -73.67887767436711;

  var domIcon = new H.map.DomIcon(html);

  var marker = new H.map.DomMarker({lat: lat, lng: lng,},
                                    {icon: domIcon});

  map.addObject(marker);
}

function displayCurrLocation(position){
  var html = document.createElement('div');
  
  // Create a container div for styling
  var containerDiv = document.createElement('div');
  containerDiv.style.border = '2px solid red';
  containerDiv.style.backgroundColor = 'rgba(255, 0, 0, 0.5)';
  containerDiv.style.padding = '5px';
  containerDiv.style.borderRadius = '5px';
  containerDiv.style.display = 'inline-block';
  
  var divText = document.createElement('div');
  divText.innerHTML = "YOU";
  divText.style.color = 'black';
  
  containerDiv.appendChild(divText);
  html.appendChild(containerDiv);

  const lat = position.coords.latitude;
  const lng = position.coords.longitude;

  var domIcon = new H.map.DomIcon(html);

  var marker = new H.map.DomMarker({lat: lat, lng: lng},
                                    {icon: domIcon});

  map.addObject(marker);
}

function initializeMap() {
  const platform = new H.service.Platform({
    'apikey': 's3sbx6xX-v9ej-jC6K-Rxh8TbzuPNYipZUfW4zR52jU'
  });

  const mapElement = document.getElementById("map");
  if (!mapElement) {
    console.error("Map element not found in initializeMap");
    return;
  }

  const defaultLayers = platform.createDefaultLayers();

  map = new H.Map(
    mapElement,
    defaultLayers.vector.normal.map, {
        zoom: 14,
        center: { lat: 42.7298, lng:  -73.676728 }
    });

  const behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

  getLongAndLat();

}

function addToCalendar(clubID) {
  // console.log("Club ID:", clubID);

  // Append the clubID as a query parameter in the fetch URL
  fetch(`./resources/addEventToCalendar.php?clubID=${clubID}`)
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        if (data.error === 'Not logged in') {
          alert("You are not logged in. Please log in to add events to your calendar.");
        } else {
          console.error("Error:", data.error);
        }
        return;
      }
      // console.log("THE DATA:", data);
      console.log("Successfully added to Calendar!");
      alert("Added club to calendar!");
      location.reload();
    })
    .catch(error => {
      console.error('Error fetching Clubs locations:', error);
    });
}

function deleteClub(clubID){
  fetch(`resources/deleteClub.php?clubID=${clubID}`)
  .then(response => response.json())
  .then(data => {
    console.log("DELETE");  
    location.reload();  
  })
  .catch(error => {
    console.error('Error loading session status:', error);
  });
}

// Displays all the information on a club that has been selected
function displayIndividualClub(clubData, clubButton) {
  const courseDescription = document.getElementById("courseDescription");
  const nameOfClub = clubData.clubName;
  const clubDescription = clubData.descriptionVal;
  courseDescription.innerHTML = `<h2>Name: ${nameOfClub}</h2>
                                 <h3 class="clubContentStyle"><b>Description:</b> ${clubDescription}</h3>`;
  if (courseDescription.textContent != "") {
    hideClubsform();
  }
  // console.log(clubData.clubName);
  const bottomDescDiv = document.getElementById("bottomDesc");
  const numMems = clubData.memberCount;
  const location = clubData.locationVal;
  const roomVal = clubData.roomVal;
  const clubID = clubData.id;
  const dayOfWeek = clubData.dayOfWeek;
  

  var startTime = clubData.startTime; 
  var endTime = clubData.endTime;    

  const firstTime = startTime.split('T')[1]; 
  // console.log(firstTime);

  const secondTime = endTime.split('T')[1]; 
  // console.log(secondTime);

  var firstSuffix = "am";
  var secondSuffix = "am";

  var firstHour = parseInt(firstTime.split(':')[0], 10);
  var firstMinute = firstTime.split(':')[1];

  var secondHour = parseInt(secondTime.split(':')[0], 10);
  var secondMinute = secondTime.split(':')[1];

  if (firstHour >= 12) {
    firstSuffix = "pm";
    if (firstHour > 12) {
      firstHour = firstHour - 12;
    }
  } else if (firstHour === 0) {
    firstHour = 12;
  }

  if (secondHour >= 12) {
    secondSuffix = "pm";
    if (secondHour > 12) {
      secondHour = secondHour - 12;
    }
  } else if (secondHour === 0) {
    secondHour = 12;
  }

  startTime = firstHour + ":" + firstMinute + " " + firstSuffix;
  endTime = secondHour + ":" + secondMinute + " " + secondSuffix;

  addMarkerToMap(location);
  
  fetch('resources/checkIfAdmin.php')
  .then(response => response.json())
  .then(data => {
    if (data.showButton) {
      // console.log("WORKED");
      bottomDescDiv.innerHTML = `
                                  <div>
                                    <h3 id="memberCount" class="clubContentStyle"><b>Member Count:</b> ${numMems}</h3>
                                    <h3 class="clubContentStyle"><b>Location:</b> ${roomVal}</h3>
                                    <h3 class="clubContentStyle"><b>Meeting Day:</b> ${dayOfWeek}</h3>
                                    <h3 class="clubContentStyle"><b>Start Time:</b> ${startTime}</h3>
                                    <h3 class="clubContentStyle"><b>End Time:</b> ${endTime}</h3>
                                  </div>
                                  <button class="addToCalendarBttn" onclick="deleteClub('${clubID}')">Delete club</button>
                                `;
    } else {
      // console.log('Not admin');
      bottomDescDiv.innerHTML = ` 
                                  <div>
                                    <h3 id="memberCount" class="clubContentStyle"><b>Member Count:</b> ${numMems}</h3>
                                    <h3 class="clubContentStyle"><b>Location:</b> ${roomVal}</h3>
                                    <h3 class="clubContentStyle"><b>Meeting Day:</b> ${dayOfWeek}</h3>
                                    <h3 class="clubContentStyle"><b>Start Time:</b> ${startTime}</h3>
                                    <h3 class="clubContentStyle"><b>End Time:</b> ${endTime}</h3>
                                  </div>
                                  <button class="addToCalendarBttn" onclick="addToCalendar('${clubID}')">Add to calendar</button>
                                `;
      }
  })
  .catch(error => {
    console.error('Error loading session status:', error);
  });

  // Removes active class from previously clicked buttons
  const clubContainer = document.getElementById("clubContainer");
  const clubContainerbuttons = clubContainer.getElementsByTagName("button");
  for (let button of clubContainerbuttons) {
    if (button.classList.contains("clubButtonActive")) {
      button.classList.remove("clubButtonActive");
    }
  }
  // Adds active class to current button
  clubButton.classList.add("clubButton");
}

// Display club data on the left most tab in a descending list
function populateClubs(searchString) {
  console.log("Started populate function with search of: ", searchString)
  const clubContainer = document.getElementById("clubContainer");
  clubContainer.innerHTML = '';
  const lowerCaseSearchString = searchString.toLowerCase();
  clubsData.forEach(clubData => {
    const lowerCaseClubName = clubData.clubName.toLowerCase();
    if (searchString == "" || lowerCaseClubName.includes(lowerCaseSearchString)) {
      const clubButton = document.createElement("button");
      clubButton.classList.add("clubButton");
      clubButton.textContent = clubData.clubName;
      clubButton.onclick = function() {
        displayIndividualClub(clubData, clubButton)
      };
      clubContainer.appendChild(clubButton);
    }
  });
}

function showClubsForm() {
  const formContainer = document.getElementById("clubFormContainer");
  formContainer.style.display = "block";
  const courseDescription = document.getElementById("courseDescription");
  courseDescription.textContent = "";
  const bottomDescDiv = document.getElementById("bottomDesc");
  bottomDescDiv.innerHTML = "";
  fetch('resources/getLocations.php')
    .then(response => response.json())
    .then(data => {
      const locationsInput = document.getElementById("locationInput");
      var locationsDropdown = "";
      for (i = 0; i < data.length; i++) {
        locationsDropdown += '<option value = "' + data[i].name + '">' + data[i].name + '</option>';
      }
      locationsInput.innerHTML = locationsDropdown;
  })
  .catch(error => {
    console.error("Error displaying locations dropdown: ", error);
  })
}

function hideClubsform() {
  const formId = document.getElementById("clubFormContainer");
  formId.style.display = "none";
}

function fetchClubLocations(clubsData){
  fetch('./resources/getLocations.php')
  .then(response => response.json())
  .then(data => {
    if (data.error) {
      console.error("Error:", data.error);
      return;
    }
    data.forEach(club => {
      const clubData = {
        locationName: club.name,
        long: club.longitude,
        lat: club.latitude,
      };
      clubsData.push(clubData);
    });
    console.log("Successfully retrieved Clubs locations!");
  })
  .catch(error => {
    console.error('Error fetching Clubs locations:', error);
  });
}

// Fetches all the data on clubs from the database
function fetchClubData(clubsData) {
  //var clubsData = [];
  fetch('./resources/getClubsData.php')
  .then(response => response.json())
  .then(data => {
    if (data.error) {
      console.error("Error:", data.error);
      return;
    }
    data.forEach(club => {
      const clubData = {
        id: club.id,
        clubName: club.clubName,
        memberCount: club.memberCount,
        descriptionVal: club.descriptionVal,
        locationVal: club.locationVal,
        roomVal: club.roomVal,
        startTime: club.startTime,
        endTime: club.endTime,
        dayOfWeek: club.dayOfWeek
      };
      clubsData.push(clubData);
    });
    console.log("Successfully retrieved Clubs data!");
    populateClubs("");
  })
  .catch(error => {
    console.error('Error fetching Clubs data:', error);
  });
}

var clubsData = [];
window.onload=function() { 
  const mapElement = document.getElementById("map");
  if (mapElement) {
    // console.log("Found");
    initializeMap();
  } else {
    console.error("Map container not found");
  }
  // console.log(mapElement);
  fetchClubLocations(locationDic);
  fetchClubData(clubsData);

  // Allows search bar text to be searched by the click of the enter key on the keyboard
  const clubSearchBar = document.getElementById("clubSearchBar");
  clubSearchBar.addEventListener("keypress", function(event) {
    if (event.key == "Enter") {
      event.preventDefault();
      document.getElementById("searchButton").click();
    }
  });
};
