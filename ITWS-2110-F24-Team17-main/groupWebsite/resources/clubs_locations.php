<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require 'php/connect.php';

$data = json_decode(file_get_contents('clubLocations.json'), true);

if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1){
  if ($data) {
    // Delete existing data from the table
    $db->query("DELETE FROM locationLatLong");

    // Insert new data into the table
    foreach ($data[0]['locations'] as $location) {
      $name = $location['name'];
      $longitude = $location['longitude'];
      $latitude = $location['latitude'];

      $sql = "INSERT INTO locationLatLong (name, longitude, latitude) VALUES ('$name', '$longitude', '$latitude')";
      $db->query($sql);
    }
    echo '<script>
      alert("Reset Course Data...redirecting");
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
