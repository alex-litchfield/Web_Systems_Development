<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require '../resources/php/connect.php';

    // connect to database
    if ($db->connect_error) {
        echo json_encode(["error" => "Database connection failed: " . $db->connect_error]);
        exit();
    }

    // sanitized to prevent SQL injection
    $deptID = htmlspecialchars($_GET['deptID'] ?? '', ENT_QUOTES, 'UTF-8');
    $sortOrder = strtolower($_GET['sortOrder'] ?? 'asc');

    // validate sorting order
    if (!in_array($sortOrder, ['asc', 'desc'])) {
        $sortOrder = 'asc';
    }
    
    // order by clause sorts query by asc or desc
    $stmt = $db->prepare("
        SELECT courseCode, courseTitle, GROUP_CONCAT(professorName) AS professor
        FROM professors
        WHERE courseCode LIKE ?
        GROUP BY courseCode, courseTitle
        ORDER BY courseCode $sortOrder
    ");

    $likeCode = $deptID . '%';
    $stmt->bind_param('s', $likeCode);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($rows as $course) {
        echo '<div id="' . $course['courseCode'] . '" class="dropdown">';
        echo "<h2>{$course['courseCode']}</h2>";
        echo "<h3>{$course['courseTitle']}</h3>";
        $professorsArray = explode(',', $course['professor']);
        foreach ($professorsArray as $prof) {
            echo '<div class="dropdown-content ' . trim($prof) . '">';
            echo "<p>$prof</p>";
            echo '</div>';
        }
        echo '</div>';
    }

    // close the connection
    $stmt->close();
    mysqli_close($db);
?>