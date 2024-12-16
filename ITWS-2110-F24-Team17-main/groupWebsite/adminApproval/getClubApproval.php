<?php

require '../resources/php/connect.php';

$sql = "SELECT * FROM club_approval ORDER BY dates ASC";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    $clubApprovalData = [];
    while ($row = $result->fetch_assoc()) {
        $clubApprovalData[] = $row;
    }
    echo json_encode($clubApprovalData);
}
else {
    echo json_encode(["error" => "No clubs need approval."]);
}

?>