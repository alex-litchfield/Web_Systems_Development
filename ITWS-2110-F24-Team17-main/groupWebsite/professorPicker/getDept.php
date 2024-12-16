<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require '../resources/php/connect.php';
    // use PHP to fetch that information out of the database and display it on your frontend

    // connect to database
    if ($db->connect_error) {
        $connectErrors = array(
            'errors' => true,
            'errno' => mysqli_connect_errno(),
            'error' => mysqli_connect_error()
        );
        echo json_encode($connectErrors);
    }

    $dept = array();

    $stmt = $db->query("SELECT courseCode FROM professors");

    $courseCodes = mysqli_fetch_all($stmt, MYSQLI_ASSOC);
    

    foreach($courseCodes as $department){
        // shorten it to the 4 letters
        $shorten = substr($department['courseCode'], 0, 4);
        array_push($dept, $shorten);
    }

    // remove the duplicates
    $final = array_unique($dept);

    // display on frontend
    foreach($final as $row) {
        echo '<div id="' . $row . '" class="department fade-in-section">';
        echo "<h2>$row</h2>";
        echo "</div>";
    }

    mysqli_free_result($stmt);
    mysqli_close($db);
?>