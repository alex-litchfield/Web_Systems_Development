<?php
   
  if (isset($_GET['clubID'])) {
    require '../../resources/php/connect.php';
    $clubID = $_GET['clubID'];
    $sql = "DELETE FROM clubs WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $clubID);
    $stmt->execute(); 
    if ($stmt->execute()) {
      echo json_encode(['success' => 'Club successfully deleted.']);
    } else {
      echo json_encode(['error' => 'Failed to delete club.']);
    }
  } else {
    echo json_encode(['error' => 'Failed to delete club. No clubID.']);
  }

?>