<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require '../resources/php/connect.php';

    $club_name = trim($_POST['clubNameInput'] ?? '');
    $club_name = filter_var($club_name, FILTER_SANITIZE_STRING);

    $members = (int)trim($_POST['member_num'] ?? 0);

    $descriptions = trim($_POST['descriptionInput'] ?? '');
    $descriptions = filter_var($descriptions, FILTER_SANITIZE_STRING);

    $locations = trim($_POST['locationInput'] ?? '');
    $locations = filter_var($locations, FILTER_SANITIZE_STRING);

    $dayOfWeek = trim($_POST['dayOfWeekInput'] ?? '');
    $dayOfWeek = filter_var($dayOfWeek, FILTER_SANITIZE_STRING);

    $startTime = trim($_POST['startTimeInput'] ?? '');
    $startTime = filter_var($startTime, FILTER_SANITIZE_STRING);

    $endTime = trim($_POST['endTimeInput'] ?? '');
    $endTime = filter_var($endTime, FILTER_SANITIZE_STRING);

    $sql = "INSERT INTO club_approval (club_name, members, descriptions, locations, startTime, endTime, dayOfWeek) VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param("sisssss", $club_name, $members, $descriptions, $locations, $startTime, $endTime, $dayOfWeek);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
            header("Location: index.php");
            exit();
        }
        else {
            echo json_encode(['success' => false, 'message' => 'Error inserting club for approval.']);
        }
    }
    $stmt->close();
}
?>

