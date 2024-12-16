<?php

$health_contents = file_get_contents('php://input');
$data = json_decode($health_contents, true);
require '../resources/php/connect.php';

if (isset($data['id']) && isset($data['resources']) && isset($data['descriptions'])) {
    
    $id = intval($data['id']);
    $resources = strval($data['resources']);
    $descriptions = strval($data['descriptions']);
    $likes = 0;

    $sql_insert = "INSERT INTO healthcare (resourceName, descriptionValue, likes) VALUES (?, ?, ?)";

    if ($stmt_insert = $db->prepare($sql_insert)) {
        $stmt_insert->bind_param("ssi", $resources, $descriptions, $likes);

        if ($stmt_insert->execute()) {
            $sql_delete = "DELETE FROM health_approval WHERE id = ?";

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