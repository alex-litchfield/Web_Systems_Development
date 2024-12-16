<?php
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);

   if (isset($_POST["query"])) {
      require '../resources/php/connect.php';
      $dept = array();  

      // connect to database
      if ($db->connect_error) {
         echo json_encode(["error" => "Database connection failed: " . $db->connect_error]);
         exit();
      }

      $query = "SELECT courseCode FROM professors WHERE courseCode LIKE ?";
      $condition = '%' . $_POST["query"] . '%';

      $stmt = $db->prepare($query);
      if (!$stmt) {
         echo json_encode(["error" => "Statement preparation failed: " . $db->error]);
         exit();
      }

      $stmt->bind_param("s", $condition);
      $stmt->execute();
      $result = $stmt->get_result();

      while ($row = $result->fetch_assoc()) {
         $shorten = substr($row['courseCode'], 0, 4);
         array_push($dept, $shorten); 
      }

      $final = array_unique($dept);

      $data = array();
      foreach ($final as $code) {
         $data[] = array('courseCode' => $code);
      }

      echo json_encode($data);

      $stmt->close();
   } else {
      echo json_encode(["error" => "Query not set"]);
   }
?>