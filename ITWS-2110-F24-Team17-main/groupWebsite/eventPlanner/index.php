<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="../resources/photos/favicon_io/favicon.ico" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="resources/style.css">  
  <link rel="stylesheet" href="../resources/navbar.css">
  <link rel="stylesheet" href="../resources/footer.css">
  <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
  <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
  <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
  <script src="https://apis.google.com/js/api.js"></script>
  <script src="https://accounts.google.com/gsi/client"></script>
  <script src="resources/eventPlanner.js" defer></script>
  <title>Events Planner</title>
</head>

<body>
  <div id="navbar">
    <div id="navbarLeft">
      <img src="../resources/photos/the_nexus_logo_transparent.png" alt="Nexus Logo">
      <a href="../">The Nexus @ RPI</a>
      <a class="active" href="./">Event Hub</a>
      <a href="../professorPicker/">Professor and Course Reviews</a>
      <a href="../discussionForum/">Discussion Forum</a>
      <a href="../health/">Mental Health Resources</a>
    </div>
    <div id="navbarRight">
      <?php 
        if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
          echo '<a href="../profile/index.php">Profile</a> ';
          $email = $_SESSION['email'];
        } 
        else {
            echo '<a href="../login/">Sign In</a>';
        }
        if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
            $username = $_SESSION['username'];
        }
      ?>
    </div>
  </div>

  <div class="container">
    <div class="leftcolumn">
      <input type="text" id="clubSearchBar" placeholder="Search..">
      <button id="searchButton" onclick = "populateClubs(document.getElementById('clubSearchBar').value)"></button>
      <div id="buttonContainer">
        <!--Club Addition Button-->
        <button class = "clubButton" id = "formButton" onclick="showClubsForm()">Add Club</button>
        <div id="clubContainer">
          <!--<button class="clubButton" onclick="d()">Rifle Club</button>-->
        </div>
      </div> 
    </div>

    <div class="middlecolumn">
      <div class="desc-container">
        <div id = "clubFormContainer">
          <form id = "addClubsForm" action = "clubToApproval.php" method = "POST">
            <label>Club Name: </label>
            <input type = "text" id = "clubNameInput" class = "formInput" name = "clubNameInput" placeholder = "Enter the club name" required>
            <br>
            <label>Number of Members: </label>
            <input type = "text" id = "member_num" class = "formInput" name = "member_num" placeholder = "Enter the number of members" required>
            <br>
            <label>Description: </label>
            <textarea type = "text" id = "descriptionInput" class = "formInput" name = "descriptionInput" placeholder = "Enter a description" required></textarea>
            <br>
            <label>Location: </label>
            <select type = "text" id = "locationInput" class = "formInput" name = "locationInput" placeholder = "Select a club location" required></select>
            <br>
            <label for = "dayOfWeekInput">Day of Week: </label>
            <select id = "dayOfWeekInput" class = "formInput" name = "dayOfWeekInput">
              <option value = "Monday">Monday</option>
              <option value = "Tuesday">Tuesday</option>
              <option value = "Wednesday">Wednesday</option>
              <option value = "Thursday">Thursday</option>
              <option value = "Friday">Friday</option>
              <option value = "Saturday">Saturday</option>
              <option value = "Sunday">Sunday</option>
            </select>
            <br>
            <label for = "startTimeInput">Start Time: </label>
            <input type = "time" id = "startTimeInput" class = "formInput" name = "startTimeInput" placeholder = "Select a club start time" required>
            <br>
            <label for = "endTimeInput">End Time: </label>
            <input type = "time" id = "endTimeInput" class = "formInput" name = "endTimeInput" placeholder = "Select a club end time" required>
            <br>
            <button type = "submit" id = "submitButton">Submit</button>
          </form>
        </div>  
        <p id="courseDescription"></p>
        <div id="bottomDesc">
        </div>
      </div>

      <div class="mapcal-container">
        <div class="calendarContainer">
          <div class="containerTitle">
            <h2>Google Calendar</h2>
          </div>
          <div class="calendar" id="calendarLocation">
            <?php
              require_once '../../vendor/autoload.php';
              // above for vm
              // below for local
              // require_once '../vendor/autoload.php';

              use Google\Client;

              # Determines where the API server redirects the user after the user completes the authorization flow
              # This value must exactly match one of the authorized redirect URIs for the OAuth 2.0 client, which you configured in your client’s API Console Credentials page.
              // for vm:
              $redirectUrl= 'https://team17.eastus.cloudapp.azure.com/groupWebsite/eventPlanner/index.php';
              
              // for local:
              // $redirectUrl= 'http://localhost/groupWebsite/eventPlanner/index.php';

              # Create an configure client
              $client = new Client();
              $client->setAuthConfig('../resources/client_secret_427489259850-eq4kfm1ib9m3to3u5ob7hvmb47ius2pb.apps.googleusercontent.com.json');
              $client->setRedirectUri($redirectUrl);
              $client->addScope('https://www.googleapis.com/auth/calendar');
              $client->addScope('https://www.googleapis.com/auth/userinfo.email');
              $client->addScope('https://www.googleapis.com/auth/calendar.events');


              # === SCENARIO 1: PREPARE FOR AUTHORIZATION ===
              if(!isset($_GET['code']) && empty($_SESSION['google_oauth_token'])) {
                  $_SESSION['code_verifier'] = $client->getOAuth2Service()->generateCodeVerifier();

                  # Get the URL to Google’s OAuth server to initiate the authentication and authorization process
                  $authUrl = $client->createAuthUrl();

                  $connected = false;
              }


              # === SCENARIO 2: COMPLETE AUTHORIZATION ===
              # If we have an authorization code, handle callback from Google to get and store access token
              if (isset($_GET['code'])) {
                  # Exchange the authorization code for an access token
                  $token = $client->fetchAccessTokenWithAuthCode($_GET['code'], $_SESSION['code_verifier']);
                  $client->setAccessToken($token);

                  $oauth2 = new \Google\Service\Oauth2($client);
                  $userInfo = $oauth2->userinfo->get();
                  $_SESSION['emailG'] = $userInfo->email;

                  $_SESSION['google_oauth_token'] = $token;
                  header('Location: ' . $redirectUrl);
                  exit;
              }


              # === SCENARIO 3: ALREADY AUTHORIZED ===
              # If we’ve previously been authorized, we’ll have an access token in the session
              if (!empty($_SESSION['google_oauth_token'])) {
                $client->setAccessToken($_SESSION['google_oauth_token']);
                if ($client->isAccessTokenExpired()) {
                  $_SESSION['google_oauth_token'] = null;
                  $connected = false;
                } else {
                  $connected = true;
                }
              }

              # === SCENARIO 4: TERMINATE AUTHORIZATION ===
              if(isset($_GET['disconnect'])) {
                $_SESSION['google_oauth_token'] = null;
                $_SESSION['code_verifier'] = null;
                $_SESSION['emailG'] = null;
                header('Location: ' . $redirectUrl);
              }
            ?>

                
            <?php if($connected): ?>
              <a id='disconnectButton' href='?disconnect'>Disconnect</a>
              <?php
                $email = $_SESSION['emailG'];
                echo '<iframe class="calendarDisplay" src="https://calendar.google.com/calendar/embed?src=' . $email . '&ctz=America%2FNew_York"></iframe>';
              ?>
            
            <?php else: ?>
              <a id='apiConnectButton' href='<?php echo $authUrl; ?>'>Connect To Google Calendar</a>
            <?php endif; ?>
              
          </div>
        </div>

        <div class="mapContainer">
          <div class="containerTitle">
            <h2>Club Location</h2>
          </div>
          <div class="mapStyle" id="map"></div>
        </div>
        
      </div>
    </div>
  </div>

  <div class="footer">
    <div id="footerLeft">
        <div id="leftTop">
            <h3>The Nexus @ RPI</h3>
        </div>
        <div id="leftBottom">
            <a href="https://github.com/RPI-ITWS/ITWS-2110-F24-Team17"><img src="../resources/photos/githublogo.png" alt="GitHub Logo"></a>
            <a href="https://github.com/RPI-ITWS"><img src="../resources/photos/itwslogo.png" alt="ITWS Logo"></a>
            <a href="https://www.rpi.edu/"><img src="../resources/photos/rpilogo.jpeg" alt="RPI Logo"></a>
            <a href="https://github.com/quacs/quacs-data/blob/master/semester_data/202405/courses.json"><img src="../resources/photos/quacslogo.png" alt="QUACS Logo"></a>
        </div>
    </div>
    <div id="footerMiddle">
        <a class="footerLink" href="../">Home</a>
        <p>|</p>
        <a class="footerLink" href="./">Event Hub</a>
        <p>|</p>
        <a class="footerLink" href="../professorPicker/">Professor and Course Reviews</a>
        <p>|</p>
        <a class="footerLink" href="../discussionForum/">Discussion Forum</a>
        <p>|</p>
        <a class="footerLink" href="../health/">Mental Health Resources</a>
    </div>
    <div id="footerRight"> 
        <a href="../feedbackForm/"><button id="feedbackbtn">Have Feedback for Us?</button></a>
    </div>
  </div>
  <?php
    ob_end_flush();
  ?>
</body>

</html>