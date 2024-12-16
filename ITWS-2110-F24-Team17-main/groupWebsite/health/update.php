<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require '../resources/php/connect.php';

    //turns that json into a string
    //$healthJson = file_get_contents('healthResources.json');

    //turn it into a PHP array
    //$healthData = json_decode($healthJson, true);

    //connect to database
    if ($db->connect_error) {
        $connectErrors = array(
            'errors' => true,
            'errno' => mysqli_connect_errno(),
            'error' => mysqli_connect_error()
        );
        echo json_encode($connectErrors);
    }

    $contents = file_get_contents('php://input');
    $data = json_decode($contents, true);
    
    $object = htmlspecialchars(trim($data['object']));
    $likes = htmlspecialchars(trim($data['likeCount']));
    //$object should be equal to the ID of the event target 

    //update the likes for a post into the database
    $statement = $db->prepare("UPDATE healthcare SET likes=? WHERE id=?");
    $statement->bind_param('ii', $likes, $object);
    $statement->execute();
    $statement->close();

?>