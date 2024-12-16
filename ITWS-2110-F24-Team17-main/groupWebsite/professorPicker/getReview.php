<!-- start session to check if user is admin -->
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
    //use PHP to fetch that information out of the database and display it on your frontend

    //connect to database
    if ($db->connect_error) {
        $connectErrors = array(
            'errors' => true,
            'errno' => mysqli_connect_errno(),
            'error' => mysqli_connect_error()
        );
        echo json_encode($connectErrors);
    }

    //get the data passed from ajax call
    $professor = $_GET['professor'];
    $courseCode = $_GET['courseCode'];
    $courseTitle = $_GET['courseTitle'];

    //delete the review that is assocciated with the reviewID
    if(isset($_GET['option']) && $_GET['option']=="delete"){
        $reviewID = $_GET['reviewID'];

        $stmtZero = $db->prepare("DELETE FROM reviews WHERE primaryKey=?");
        $stmtZero->bind_param("i", $reviewID);
        $stmtZero->execute();
        $stmtZero->close();

    }

    //save the average if the rating is 0
    //in the professors table, get the rating
    $stmtTwo = $db->prepare("SELECT rating FROM professors WHERE professorName=? AND courseCode=?");
    $stmtTwo->bind_param("ss", $professor, $courseCode);
    $stmtTwo->execute();
    $currentRatingRow = $stmtTwo->get_result();
    $currentRating = $currentRatingRow->fetch_all(MYSQLI_ASSOC);
    $ratingCurr = $currentRating[0]['rating'];


    $stmtTwo->close();

    //decide how reviews are sorted
    if (isset($_GET['sortOrder'])){
        $sortOrder = $_GET['sortOrder'];
        echo '<script>console.log("' . $sortOrder . ' value");</script>';

        if($sortOrder == "neg"){
            // $stmt = $db->query("SELECT * FROM healthcare ORDER BY likes DESC");
            $stmt = $db->prepare("SELECT primaryKey, reviewerName, rating, descriptionVal, courseCode, datePosted FROM reviews WHERE professorName=? AND courseCode=? ORDER BY rating ASC");
        }
        else if($sortOrder == "pos"){
            // $stmt = $db->query("SELECT * FROM healthcare ORDER BY datePosted DESC");
            $stmt = $db->prepare("SELECT primaryKey, reviewerName, rating, descriptionVal, courseCode, datePosted FROM reviews WHERE professorName=? AND courseCode=? ORDER BY rating DESC");
        }
        else if($sortOrder == "recent"){
            $stmt = $db->prepare("SELECT primaryKey, reviewerName, rating, descriptionVal, courseCode, datePosted FROM reviews WHERE professorName=? AND courseCode=? ORDER BY datePosted DESC");
            echo '<script>console.log("recentEqual");</script>';
        }
    }
    else{
        $stmt = $db->prepare("SELECT primaryKey, reviewerName, rating, descriptionVal, courseCode, datePosted FROM reviews WHERE professorName=? AND courseCode=?");

    }

    

    //get the reviews where it matches the professor and course code
    
    $stmt->bind_param("ss", $professor, $courseCode);

    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC); 

    //get the number of rows in the result query
    $rowCount=mysqli_num_rows($result);

    // echo '<h2>Ratings</h2>';
    // echo '<h3>'.$professor.'</h3>';
    

    //only calculate average ratings if rowCount is not 0
    //go through to calculate the ratings and display it on the page
    //should only calculate if the saved rating is 0
    if($rowCount != 0){

        //calculate the average ratings only if the rating is not 0
        // echo '<h1>Current Rating: '.round($ratingCurr,2). '/10★</h1>';
        if($ratingCurr==0 || isset($_GET['option'])){
            $total = 0.0;
            foreach($rows as $review){
                $total= $total + $review['rating'];
            }

            $average = round($total/$rowCount, 2);
            $value = $average . '/10★';
            // echo '<h1>'.$average. '/10★</h1>';
            ?>
            <script>
                sessionStorage.setItem("ratingContent", "<?php echo addslashes($value); ?>");
            </script>

        <?php
        }
        else{
            //if there were no previous ratings, then just display the current rating
            $value = round($ratingCurr, 2) . '/10★';
            // echo '<h1>'.round($ratingCurr,2). '/10★</h1>';
            ?>
            
            <!-- place into sessionStorage to access in review.php -->
            <script>
                sessionStorage.setItem("ratingContent", "<?php echo addslashes($value); ?>");
            </script>
        <?php
        }

        echo '
        <div class="storeSelecWrap j-store-select selWrap">

            <div class="s-dropdown--styled">

                <span class="store-default" style="color: rgb(255, 255, 255);">Filter Ratings By</span>

                <ul class="s-dropdown u-hide">
                    <li class="j-store" data-value="1">Most Recent</li>
                    <li class="j-store" data-value="2">Positive First</li>
                    <li class="j-store" data-value="2">Negative First</li>
                </ul>

            </div>

        </div>
        <div id="buttonArea">        
            <button class="addReview">
                Add Your Own Review +
            </button>
        </div>';

        
        //display the review
        foreach($rows as $review){
            echo '<div class="rating fade-in-section">';

            //if user is admin, then they can view the trashcan icon to delete the review
            if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']==1) {
                echo '<button id="'.$review['primaryKey']. '" class="material-symbols-outlined trash">close</button>';
                // echo '<br>';
            }

            echo '<h4>'.$review['reviewerName']. '-' .$review['rating']. '/10★</h4>';
            echo $review['descriptionVal'];
            echo '<p class="reviewDate">'. $review['datePosted']. '</p>';


            echo '</div>';
        }

        $stmt->close();


        //update the professor table with the average rating
        if($ratingCurr == 0 || isset($_GET['option'])){
            $stmtThree = $db->prepare("UPDATE professors SET rating=? WHERE professorName=? AND courseCode=?");
            $stmtThree->bind_param("dss", $average, $professor, $courseCode);
            $stmtThree->execute();
            $stmtThree->close();
        }

        

    }
    else{
        $average = 0;
        //update the professor table with the average rating
        $stmtThree = $db->prepare("UPDATE professors SET rating=? WHERE professorName=? AND courseCode=?");
        $stmtThree->bind_param("dss", $average, $professor, $courseCode);
        $stmtThree->execute();
        $stmtThree->close();

        $value = '-/10★';
        // echo '<h1>'.round($ratingCurr,2). '/10★</h1>';
        ?>
        
        <!-- place into sessionStorage to access in review.php -->
        <script>
            sessionStorage.setItem("ratingContent", "<?php echo addslashes($value); ?>");
        </script>
        
        <?php
        //if there are no reviews, then say there are none at the moment
        echo '<h4>There are no reviews posted yet.</h4>';
        echo '<button class="addReview">
                Add Your Own Review +
            </button>';
    }



    //Closing the statement
    // $stmt->close();

    //Closing the connection
    $db->close();




?>