<?php

$club_contents = file_get_contents('php://input');
$data = json_decode($club_contents, true);
require '../resources/php/connect.php';

if (isset($data['id']) && isset($data['club_name']) && isset($data['descriptions']) && isset($data['members']) && isset($data['locations'])) {

    $id = intval($data['id']);
    $club_name = strval($data['club_name']);
    $descriptions = strval($data['descriptions']);
    $members = intval($data['members']);
    $locations = strval($data['locations']);
    $dates = date($data['dates']);
    $oldStartTime = strval($data['startTime']);
    $oldEndTime = strval($data['endTime']);
    $dayOfWeek = strtolower(strval($data['dayOfWeek']));
    $datOfWeekCap = strval($data['dayOfWeek']);

   
    $dateOnly = strval($data['dateOnly']);
    
    //Conversion Math
    $oldStartTime = date('H:i:s', strtotime($oldStartTime));
    $oldEndTime = date('H:i:s', strtotime($oldEndTime));

    $startTime = $dateOnly . $oldStartTime;
    $endTime = $dateOnly . $oldEndTime; 

    $sql_insert = "INSERT INTO clubs (clubName, descriptionVal, memberCount, locationVal, roomVal, startTime, endTime, dayOfWeek) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt_insert = $db->prepare($sql_insert)) {
        $stmt_insert->bind_param("ssisssss", $club_name, $descriptions, $members, $locations, $locations, $startTime, $endTime, $datOfWeekCap);

        if ($stmt_insert->execute()) {
            $sql_delete = "DELETE FROM club_approval WHERE id = ?";

            if ($stmt_delete = $db->prepare($sql_delete)) {
                $stmt_delete->bind_param("i", $id);

                if ($stmt_delete->execute()) {
                    echo json_encode(['success' => true]);
                }
                else {
                    echo json_encode(['success' => false, 'message' => 'Error deleting row.']);
                }

                $stmt_delete->close();
            }
            else {
                echo json_encode(['success' => false, 'message' => 'Failed to prepare statement.']);
            }
        }
        else {
            echo json_encode(['success' => false, 'message' => 'Error inserting row.']);
        }

        $stmt_insert->close();
    }

}
else {
    echo json_encode(['success' => false, 'message' => 'No parameters provided.']);
}
?>