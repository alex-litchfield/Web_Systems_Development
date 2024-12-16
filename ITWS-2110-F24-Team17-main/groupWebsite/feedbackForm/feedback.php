<?php
   require '../resources/php/connect.php';

   // gets data from html
   $fullName = $_POST['fullName'];
   $email = $_POST['email'];
   $firstVisit = $_POST['firstVisit'];
   $whyVisit = $_POST['whyVisit'];
   $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
   $comments = $_POST['comments'];

   // Ensure required fields are not empty
   if (empty($fullName) || empty($email) || empty($firstVisit) || empty($rating)) {
      die('<div style="text-align: center; margin-top: 50px; font-family: Arial, sans-serif;">
               <h1>Error</h1>
               <p>All fields marked as required (*) must be filled out. Please try again.</p>
               <a href="index.html" style="text-decoration: none; color: blue;">Click Here to Go Back to Feedback Form</a>
            </div>');
   }

   // inserts data into database
   $stmt = $db->prepare("INSERT INTO feedback (fullName, email, firstVisit, whyVisit, rating, comments) VALUES (?, ?, ?, ?, ?, ?)");
   $stmt->bind_param("ssssis", $fullName, $email, $firstVisit, $whyVisit, $rating, $comments);

   if ($stmt->execute()) {
      echo '<div style="text-align: center; margin-top: 50px; font-family: Arial, sans-serif;">
               <h1>Thank You!</h1>
               <p>Your feedback has been submitted successfully.</p>
               <a href="../index.php" style="text-decoration: none; color: blue;">Click Here to Go Back to Home</a>
            </div>';
   } 
   else {
      echo '<div style="text-align: center; margin-top: 50px; font-family: Arial, sans-serif;">
               <h1>Error</h1>
               <p>There was an issue submitting your feedback. Please try again.</p>
               <a href="../index.php" style="text-decoration: none; color: blue;">Click Here to Go Back to Home</a>
            </div>';
   }

   $stmt->close();
?>