<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  require_once '../../../vendor/autoload.php';
  // above for vm
  // below for local
  // require_once '../../vendor/autoload.php';

  use Google\Client;

  # Determines where the API server redirects the user after the user completes the authorization flow
  # This value must exactly match one of the authorized redirect URIs for the OAuth 2.0 client, which you configured in your client’s API Console Credentials page.
  // for vm:
  $redirectUrl= 'https://team17.eastus.cloudapp.azure.com/groupWebsite/eventPlanner/index.php';
  // for local:
  // $redirectUrl= 'http://localhost/groupWebsite/eventPlanner/index.php';

  # Create an configure client
  $client = new Client();
  $client->setAuthConfig('../../resources/client_secret_427489259850-eq4kfm1ib9m3to3u5ob7hvmb47ius2pb.apps.googleusercontent.com.json');
  $client->setRedirectUri($redirectUrl);
  $client->addScope('https://www.googleapis.com/auth/calendar');
  $client->addScope('https://www.googleapis.com/auth/userinfo.email');
  $client->addScope('https://www.googleapis.com/auth/calendar.events');

  if (!empty($_SESSION['google_oauth_token'])) {
    
    $client->setAccessToken($_SESSION['google_oauth_token']);

    if (isset($_GET['clubID'])) {
      $clubID = $_GET['clubID'];
      // Prepare the event data

      require '../../resources/php/connect.php';
      
      $sql = "SELECT * FROM clubs WHERE id = ?";
      $stmt = $db->prepare($sql);
      $stmt->bind_param("i", $clubID);
      $stmt->execute(); 
      
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        $clubData = $result->fetch_assoc();
    
        $eventData = [
          'summary' => $clubData['clubName'],      // Club name as the event summary
          'location' => $clubData['locationVal'],  // Club meeting location
          'start' => ['dateTime' => $clubData['startTime'], 'timeZone' => 'America/New_York'],
          'end' => ['dateTime' => $clubData['endTime'], 'timeZone' => 'America/New_York'],
          'recurrence' => [
            'RRULE:FREQ=WEEKLY;UNTIL=20251231T235959Z' // Recurring until end of 2025
          ] 
        ];
      
        // Insert the event into the user's primary calendar
        $calendarService = new \Google\Service\Calendar($client);
        try {
          $event = $calendarService->events->insert('primary', new \Google\Service\Calendar\Event($eventData));
          $event->setRecurrence(array('RRULE:FREQ=WEEKLY;UNTIL=20110701T170000Z'));
          echo json_encode(['success' => 'Added event to Calendar']);
        } catch (Exception $e) {
          echo json_encode(['error' => 'Could not create a new event in Calendar']);
        }
      } else {
        echo json_encode(['error' => 'No Club found']);
      }
    }
  } else {
    echo json_encode(['error' => 'Not logged in']);
  }
?>