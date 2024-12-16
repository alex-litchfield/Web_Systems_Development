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

    //default to empty string if not set
    $option = isset($_GET['option']) ? $_GET['option'] : '';  
    $action = isset($_GET['action']) ? $_GET['action'] : '';  
    $target = isset($_GET['target']) ? $_GET['target'] : ''; 

    //delete post from db
    if(isset($_GET['deletePost'])){
        $resource = $_GET['deletePost'];
        $stmt = $db->prepare("DELETE FROM healthcare WHERE id=?");
        $stmt->bind_param('s', $resource);
        $stmt->execute();
    }


    //check which one to sort by
    if($option == "likes"){
        $stmt = $db->query("SELECT * FROM healthcare ORDER BY likes DESC");
    }
    else if($option == "date"){
        $stmt = $db->query("SELECT * FROM healthcare ORDER BY datePosted DESC");
    }
    else if($option == ""){
        $stmt = $db->query("SELECT * FROM healthcare");
    }

    //display that info from the database the way I want
    if ($stmt->num_rows > 0) {
        while($row = $stmt->fetch_assoc()) {
            $id = $row['id'];
            $resourceName = htmlspecialchars($row['resourceName']);
            $descriptionValue = htmlspecialchars($row['descriptionValue']);
            $datePosted = htmlspecialchars($row['datePosted']);
            $likeCount = $row["likes"];
            if (($action == "like" && $target == $id)){
                //recently liked post
                echo '<div id="' . $id . '" class="post">';
                echo "<h2>{$resourceName}</h2>";
                echo "<p>{$descriptionValue}</p><br>";
                echo "<p>Date Posted: {$datePosted}</p>";
                //$likeCount++;
                echo "<span class='material-symbols-outlined fillLeaf' id='{$id}' >thumb_up {$likeCount}</span>";
                echo "<span class='true-count'>{$likeCount}</span>";

                //only display the trash if admin
                if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']==1) {
                    echo '<button class="material-symbols-outlined trash">delete</button>';
                    
                }
                echo '</div>';
            }
            else if ($action == "unlike" && $target == $id){
                echo '<div id="' . $id . '" class="post">';
                echo "<h2>{$resourceName}</h2>";
                echo "<p>{$descriptionValue}</p><br>";
                echo "<p>Date Posted: {$datePosted}</p>";
                //$likeCount--;
                echo "<span class='material-symbols-outlined' id='{$id}'>thumb_up {$likeCount}</span>";
                echo "<span class='true-count'>{$likeCount}</span>";

                //only display the trash if admin
                if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']==1) {
                    echo '<button class="material-symbols-outlined trash">delete</button>';
                    
                }
                echo '</div>';
            }
            else{
                echo '<div id="' . $id . '" class="post">';
                echo "<h2>{$resourceName}</h2>";
                echo "<p>{$descriptionValue}</p><br>";
                echo "<p>Date Posted: {$datePosted}</p>";
                echo "<span class='material-symbols-outlined' id='{$id}'>thumb_up {$likeCount}</span>";
                echo "<span class='true-count'>{$likeCount}</span>";

                //only display the trash if admin
                if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']==1) {
                    echo '<button class="material-symbols-outlined trash">delete</button>';
                    
                }
    
                echo '</div>';
            }
    
        }
    }
    else {
        echo json_encode(["error" => "No resources available. Please refresh the page."]);
    }

    //Closing the statement
    $stmt->close();


?>