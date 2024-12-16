<?php
// Database information variables
require '../../resources/php/connect.php';

// Get professor data from database
$sql = "SELECT id, clubName, memberCount, descriptionVal, locationVal, roomVal, dayOfWeek, startTime, endTime FROM clubs";
$result = $db->query($sql);

// Checking to see if any data was returned
if ($result->num_rows > 0) {
    $clubsData = []; 
    // Fetch rows one by one and store in the array
    while ($row = $result->fetch_assoc()) {
        $clubsData[] = $row;
    }
    echo json_encode($clubsData);
} else {
    // If no rows were returned, output an error as JSON
    echo json_encode(["error" => "No Clubs data found."]);
}
