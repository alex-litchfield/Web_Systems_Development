<?php
session_start();
//gets rid of session data
session_unset();  
//destroys session   
session_destroy();   
//redirects user back to homepage 
header('Location: ../../index.php');
exit();
?>