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
    $courseCode = htmlspecialchars($_GET['courseCode']);
    $professor = htmlspecialchars($_GET['professor']);

    $email = $_SESSION['email'];

    //check that the user is not making multiple reviews for one professor and course
    $check = $db->prepare("SELECT COUNT(*) FROM reviews WHERE professorName=? AND courseCode=? AND email=?");
    $check->bind_param('sss', $professor, $courseCode, $email);
    $check->execute();
    $checker = $check->get_result();
    $emailCount = $checker->fetch_row()[0];

    //means that user already made a review here
    if($emailCount != 0){
        echo 'duplicate';
    }

    
?>

