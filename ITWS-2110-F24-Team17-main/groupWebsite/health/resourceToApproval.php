<?php
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    }
?>

<?php
require '../resources/php/connect.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    

    $resources = $db->real_escape_string($_POST['resourceInput']);
    $descriptions = $db->real_escape_string($_POST['descriptionInput']);


    //change sql statement if admin
    if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']==1) {
        //initialize variables
        $resourceName = htmlspecialchars($_POST['resourceInput']);
        $descriptionVal = htmlspecialchars($_POST['descriptionInput']);
        $likes = 0;
        date_default_timezone_set("America/New_York");
        $date = time();
        $datePosted = date("Y-m-d H:i:s",$date);

        $stmt = $db->prepare("INSERT INTO healthcare (resourceName, descriptionValue, likes, datePosted) VALUES (?,?,?,?)");

        $stmt->bind_param('ssis', $resourceName, $descriptionVal, $likes, $datePosted);
        $stmt->execute();
        header("Location: index.php");
        exit();

    }
    else{
        $sql = "INSERT INTO health_approval (resources, descriptions) VALUES (?, ?)";

        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("ss", $resources, $descriptions);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
                header("Location: index.php");
                exit();
            }
            else {
                echo json_encode(['success' => false, 'message' => 'Error inserting club for approval.']);
            }
        }

    }

    $stmt->close();
}
?>

<!--echo "<script>alert('Your resource has been submitted and is currently waiting for approval.');</script>";-->
