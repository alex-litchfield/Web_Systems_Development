<?php

$health_contents = file_get_contents('php://input');
$data = json_decode($health_contents, true);
require '../resources/php/connect.php';

if (isset($data['id'])) {
    
    $id = intval($data['id']);

    $sql = "DELETE FROM health_approval WHERE id = ?";

    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        }
        else {
            echo json_encode(['success' => false, 'message' => 'Error deleting row.']);
        }

        $stmt->close();
    }
    else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement.']);
    }
    $db->close();
}
else {
    echo json_encode(['success' => false, 'message' => 'No id provided.']);
}
?>