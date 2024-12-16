<?php

require '../resources/php/connect.php';

$sql = "SELECT id, resources, descriptions, posted FROM health_approval ORDER BY posted ASC";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    $healthApprovalData = [];
    while ($row = $result->fetch_assoc()) {
        $healthApprovalData[] = $row;
    }
    echo json_encode($healthApprovalData);
}
else {
    echo json_encode(["error" => "No clubs need approval."]);
}

?>