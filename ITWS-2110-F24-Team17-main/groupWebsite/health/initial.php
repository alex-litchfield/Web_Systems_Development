<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require '../resources/php/connect.php';

    //turns that json into a string
    $healthJson = file_get_contents('healthResources.json');

    //turn it into a PHP array
    $healthData = json_decode($healthJson, true);

    //connect to database
    if ($db->connect_error) {
        $connectErrors = array(
            'errors' => true,
            'errno' => mysqli_connect_errno(),
            'error' => mysqli_connect_error()
        );
        echo json_encode($connectErrors);
    }


    //variable initializers
    $stmtInitial = $db->prepare("TRUNCATE TABLE healthcare");
    $stmtInitial->execute();


    //insert the information json into the database
    $stmt = $db->prepare("INSERT INTO healthcare (resourceName, descriptionValue, likes, datePosted) VALUES (?,?,?,?)");

    //parse through the json
    //iterate through the json to get each post data
    foreach($healthData['posts'] as $currentPost){
        $resourceName = $currentPost['resource'];
        $descriptionVal = $currentPost['description'];
        $likes = $currentPost['likes'];
        $datePosted = $currentPost['date-posted'];
        $stmt->bind_param('ssis', $resourceName, $descriptionVal, $likes, $datePosted);
        $stmt->execute();
    }
    $stmt->close();
    
?>