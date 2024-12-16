<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require 'php/connect.php';

$data = json_decode(file_get_contents('clubData.json'), true);
if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1){
  if ($data) {
    // Delete existing data from the table
    $db->query("DELETE FROM clubs");
  
    // Insert new data into the table
    foreach ($data[0]['clubs'] as $club) {
      $clubName = $club['clubName'];
      $memberCount = $club['memberCount'];
      $descriptionVal = $club['descriptionVal'];
  
      $locationVal = $club['locationVal'];
      $roomVal = $club['roomVal'];
      $startTime = $club['startTime'];
      $endTime = $club['endTime'];
      $dayOfWeek = $club['dayOfWeek'];
  
      $sql = "INSERT INTO clubs (clubName, memberCount, dayOfWeek, descriptionVal, locationVal, roomVal, startTime, endTime
      ) VALUES ('$clubName', '$memberCount', '$dayOfWeek', '$descriptionVal', '$locationVal', '$roomVal', '$startTime',  '$endTime')";
      $db->query($sql);
    }

    echo '<script>
          alert("Reset Club Data...redirecting");
          window.location.href = "../index.php";
        </script>';
    exit;

  }
} else {
  echo '<script>
          alert("YOU ARE NOT AN ADMIN...redirecting");
          window.location.href = "../index.php";
        </script>';
  exit;
}

?>
