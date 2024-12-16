<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  if (!empty($_SESSION['isAdmin'])) {
    echo json_encode(['showButton' => true]);
  } else {
    echo json_encode(['showButton' => false]);
  }

?>