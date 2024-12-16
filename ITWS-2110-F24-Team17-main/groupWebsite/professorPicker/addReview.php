<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

?>

<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require '../resources/php/connect.php';

    //connect to database
    if ($db->connect_error) {
        $connectErrors = array(
            'errors' => true,
            'errno' => mysqli_connect_errno(),
            'error' => mysqli_connect_error()
        );
        echo json_encode($connectErrors);
    }

    //initialize variables
    $anonymous = htmlspecialchars($_POST['anonymousVal']);
    $reviewerName = htmlspecialchars($_POST['reviewerName']);
    $descriptionVal = htmlspecialchars($_POST['descriptionVal']);
    $rating = htmlspecialchars($_POST['ratingVal']);
    $courseCode = htmlspecialchars($_POST['courseCodeVal']);
    $courseTitle = htmlspecialchars($_POST['courseTitleVal']);
    $professor = htmlspecialchars($_POST['professorVal']);

    //set reviewer name to Anonymous if it is not empty
    if($anonymous=='Anonymous'){
        $reviewerName = 'Anonymous';
    }
    else{
        //if anonymous is "", then take the first and last name
        if($anonymous==''){
            $reviewerName = $_SESSION['firstName'] . ' ' .  $_SESSION['lastName'];
        }

    }

    
    $email = $_SESSION['email'];
    date_default_timezone_set("America/New_York");
    $date = time();
    $datePosted = date("Y-m-d H:i:s",$date);


    $stmt = $db->prepare("INSERT INTO reviews (professorName, courseTitle, reviewerName, rating, courseCode, descriptionVal, email, datePosted) VALUES(?,?,?,?,?,?,?,?)");

    $stmt->bind_param('sssissss', $professor, $courseTitle, $reviewerName, $rating, $courseCode, $descriptionVal, $email, $datePosted);
    $stmt->execute();
    $stmt->close();

    //recalculate the average
    //get the reviews where it matches the professor and course code
    $stmtTwo = $db->prepare("SELECT reviewerName, rating, descriptionVal, courseCode FROM reviews WHERE professorName=? AND courseCode=?");

    $stmtTwo->bind_param("ss", $professor, $courseCode);

    $stmtTwo->execute();
    $result = $stmtTwo->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC); 

    //get the number of rows in the result query
    $rowCount=mysqli_num_rows($result);

    $total = 0.0;
    foreach($rows as $review){
        $total= $total + $review['rating'];
    }

    $average = $total/$rowCount;

    $stmtTwo->close();

    //update the average the database
    $stmtThree = $db->prepare("UPDATE professors SET rating=? WHERE professorName=? AND courseCode=?");
    $stmtThree->bind_param("dss", $average, $professor, $courseCode);
    $stmtThree->execute();
    $stmtThree->close();

    //redirect to the admin page
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'review.php';
    header("Location: http://$host$uri/$extra");
    exit;

    
?>

