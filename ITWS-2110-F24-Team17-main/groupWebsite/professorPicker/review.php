<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../resources/jquery-3.7.1.min.js"></script>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <title>Course List</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../resources/navbar.css">
    <link rel="stylesheet" href="index.css"/>
    <link rel="stylesheet" href="../resources/footer.css"/>
</head>
<body>
   <!-- navigation bar -->
   <div id="navbar">
      <div id="navbarLeft">
         <img src="../resources/photos/the_nexus_logo_transparent.png" alt="Nexus Logo">
         <a href="../">The Nexus @ RPI</a>
         <a href="../eventPlanner/">Event Hub</a>
         <a class="active" href="index.php">Professor and Course Reviews</a>
         <a href="../discussionForum/">Discussion Forum</a>
         <a href="../health/">Mental Health Resources</a>
      </div>
      <div id="navbarRight">
            <?php
            if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
                echo '<a href="../profile/index.php">Profile</a> ';
                $email = $_SESSION['email'];
            } 
            else {
                echo '<a href="../login/">Sign In</a>';
            }
            if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                $username = $_SESSION['username'];
            }
            ?>
        </div>
   </div>

   <!-- all course containers -->
   <div class="professor-wrapper">

      <div id="left">

        <div id="top">
            <h3>Course ID</h3>
            <h2>Course Title</h2>
        </div>

        <div id="ratingArea">
            <p id="ratingTop" class="ratingName">Professor Name</p>
            <p id="ratingBottom" class="ratingName">/10★</p>

        </div>

      </div>

      

      <!-- will be populated from getReview.php -->
      <div id="right">
        <div id="reviews">

        </div>

        <div id="dialogThree" title="Add Review">
            <form id="formReview" action="addReview.php" method="POST">
                    <!-- if anonymous is not checked, then take the firstName and lastName from the users table -->
                <label for="anonymous">Anonymous</label>
                <input type="checkbox" id="anonymous" name="anonymousUser">
                <!-- only display the option to place a name if checkbox is not checked-->
                <!-- <div id="reviewerInfo">
                    <label for="raterName">Name</label>
                    <input type="text" id="raterName" name="reviewerName">
                </div> -->
                <!-- display the stars -->
                <br><br>
                <span>Rating: </span>
                <span id="1" class="star material-symbols-outlined">star</span>
                <span id="2" class="star material-symbols-outlined">star</span>
                <span id="3" class="star material-symbols-outlined">star</span>
                <span id="4" class="star material-symbols-outlined">star</span>
                <span id="5" class="star material-symbols-outlined">star</span>
                <span id="6" class="star material-symbols-outlined">star</span>
                <span id="7" class="star material-symbols-outlined">star</span>
                <span id="8" class="star material-symbols-outlined">star</span>
                <span id="9" class="star material-symbols-outlined">star</span>
                <span id="10" class="star material-symbols-outlined">star</span>
                <p id="amountClicked"></p>

                <input type="hidden" id="ratingValue" name="ratingVal">
                <input type="hidden" id="anonymousValue" name="anonymousVal">

                <input type="hidden" id="courseCodeValue" name="courseCodeVal">
                <input type="hidden" id="courseTitleValue" name="courseTitleVal">
                <input type="hidden" id="professorValue" name="professorVal">
 
                <br>
                <label for="descriptionValue">Review:</label>
                <br>
                <textarea id="descriptionValue" name="descriptionVal" rows="4" placeholder="Insert review" required></textarea>
        
                <br><br>
                <input type="submit" value="Submit" id="formSubmit" name="submitted">

            </form> 
            
        </div>

        <div id="dialog">
            <p id="message"></p>
        </div>



   </div>
   <script>
    $(document).ready(function(){
        var loggedIn = false;
        //get the professor, courseCode, and courseTitle chosen which is saved from session storage
        var professor = sessionStorage.getItem("professor");
        var courseCode = sessionStorage.getItem("courseCode");
        var courseTitle = sessionStorage.getItem("courseTitle");
        var starNumber = 0;
        var errorMsg = '';
        var ratingContent = 0;

        $('#ratingTop').html(professor);
        <?php
            if (isset($_SESSION['email'])) {
                echo 'loggedIn = true;';
            }
        ?>
        
        if (loggedIn){
            $('#navbarRight').html('<a href="../resources/php/logout.php">Logout</a>');
        }
        else{
            $('#navbarRight').html('<a href="../login/index.html">Sign In</a>');
        }


        //update display based on professor and course chosen
        $('#top h2').html(courseTitle);
        $('#top h3').html(courseCode);
        // echo '<h2>Ratings</h2>';
    // echo '<h3>'.$professor.'</h3>';

        function displayFade(){
            //animation for displayign reviews
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        // When the element comes into view, add the "show" class after a delay
                        setTimeout(() => {
                            entry.target.classList.add('show');
                        }, index * 200); // Delay increases by 200ms for each element
                    } else {
                        // When the element is out of view, remove the "show" class
                        entry.target.classList.remove('show');
                    }
                });
            });

            //apply this to "fade-in-section"
            const hiddenElements = document.querySelectorAll('.fade-in-section');
            hiddenElements.forEach((el) => observer.observe(el));
        }
        

        //get the reviews from the database and display that
        $.ajax({
            url: "getReview.php",
            type: "GET",
            data: {professor: professor, courseCode: courseCode, courseTitle: courseTitle},
            dataType: "html",
            success: function(response){
                $('#reviews').html(response);
                ratingContent = sessionStorage.getItem("ratingContent");
                $('#ratingBottom').html(ratingContent);

                displayFade();
            }

        });

        //if anonymous if checked, then do not display the reviewer name option and clear name if anonymous is clicked
        $('#anonymous').click(function() {

            if(this.checked){
                // $('#raterName').val('');
                $('#anonymousValue').val('Anonymous');
            }else{
                $('#anonymousValue').val('');
            }
        });


        //when star is clicked display
        $(document).on('click', '.star', function(){
            //get the id of the star clicked and fill it along with the previous ones
            var starClicked = $(this).closest("span").attr("id");
            starNumber = Number(starClicked);

            //save the number to the form
            $('#ratingValue').val(starNumber);

            $(this).toggleClass("fillStar");

            //go through the stars to fill in the previous stars

            starNumber = Number($(this).attr("id"));
            $('#amountClicked').html(starNumber+"/10");
        
            // Fill in the stars
            $(this).parent().find('.star').each(function() {
                var curStar = parseInt($(this).attr("id"));
                if (curStar <= starNumber) {
                    $(this).addClass("fillStar"); // Add the filled class
                } else {
                    $(this).removeClass("fillStar"); // Remove the filled class
                }
            });

        });

        //delete review from database when trashcan is clicked
        $(document).on('click', '.trash', function(){
            //get the id of the trash icon clicked because that is the primary key to delete
            var id = $(this).attr('id');

            //open a modal to confirm with the user they want to delete
            $("#message").text("Are you sure you want to delete this review?");
            $( "#dialog" ).dialog({
                modal: true,
                title: "Confirm Delete",
                buttons: {
                    "Yes": function() {
                        $.ajax({
                            url: "getReview.php",
                            type: "GET",
                            data: {professor: professor, courseCode: courseCode, courseTitle: courseTitle, option: "delete", reviewID: id},
                            dataType: "html",
                            success: function(response){
                                $('#reviews').html(response);

                                ratingContent = sessionStorage.getItem("ratingContent");
                                $('#ratingBottom').html(ratingContent);

                                displayFade();
                            }

                        });
                        $( this ).dialog( "close" );
                    },
                    "No": function() {
                        $( this ).dialog( "close" );
                    }

                }
            });

           

        });


        //add review into the database when it is clicked
        $(document).on('click', '.addReview', function(){
            //check if the user is signed in
            if (loggedIn) {
                //check if user already made a review
                $.ajax({
                    url: "checker.php",
                    type: "GET",
                    data: {professor: professor, courseCode: courseCode},
                    dataType: "html",
                    success: function(response){
                  
                        if (response.trim() == 'duplicate'){
                            $("#message").text("You have already made a review! Therefore, you can't make another here.");
                            $( "#dialog" ).dialog({
                                modal: true,
                                title: "Duplicate Review",
                                buttons: {
                                    "Ok": function() {
                                        $( this ).dialog( "close" );
                                    }
                                }
                            });
                        }
                        else{
                            //create an area where people can write their review
                            $( "#dialogThree" ).dialog({minWidth: 500});
                        }
                        // $('#reviews').html(response);
                    }

                });

                

            } 
            else{
                
                //could add a pop up to say must be signed in to add review
                $("#message").text("Please sign in to add a review.");
                $( "#dialog" ).dialog({
                    modal: true,
                    title: "Sign In Required",
                    buttons: {
                        "Sign In": function() {
                            //redirect to sign in page
                            window.location.href = "../login/index.html";
                            $( this ).dialog( "close" );
                        },
                        "Cancel": function() {
                            $( this ).dialog( "close" );
                        }

                    }
                });
                
            }  

        });

        $("#formSubmit").on('click', function(e){
            e.preventDefault();
            errorMsg = '';
            

            $('#courseCodeValue').val(courseCode);
            $('#courseTitleValue').val(courseTitle);
            $('#professorValue').val(professor);

            //must submit a review, can't be 0
            if (starNumber == 0){
                errorMsg += 'Please enter a rating.<br>';
            }
                        
            //do some checking, can not submit an empty review
            if ($('#descriptionValue').val().trim() === '') {
                errorMsg += 'Please enter a review.';
            }

            if (errorMsg !== ''){
                $('#dialog').html(errorMsg);
                $('#dialog').dialog({
                    modal: true,
                    title: "Error",
                    buttons: {
                        "Ok": function() {
                            $( this ).dialog( "close" );
                        }
                    }
                })
                return;
            }

            
            $("#formReview").submit();
        });


        //before going to addReview.php get how many stars were clicked and if anonymous was checked
        // $("#formSubmit").on('click', function(e){
        //     e.preventDefault();

        //     //post the value of the stars clicked and if anonymous is clicked

            
        //     $('input[value="option"]').val(1);
        //     $("#formSubmit").submit();
        // });

        var selectedText = '';

        // fetch and display courses based on sorting
        function fetchReviews(sortOrder) {
            $.ajax({
                url: "getReview.php",
                type: "GET",
                data: { professor: professor, courseCode: courseCode, courseTitle: courseTitle, sortOrder: sortOrder },
                dataType: "html",
                success: function (response) {
                    $('#reviews').html(response);

                    ratingContent = sessionStorage.getItem("ratingContent");
                    $('#ratingBottom').html(ratingContent);

                    $('.store-default').html(selectedText);
                    displayFade();
                }
            });
        }

        $(document).on('click', '.j-store-select', function() {
                $(this).find('ul').slideToggle();
        });


        $(document).on('click', '.j-store', function () {
            selectedText = $(this).html();
            console.log(selectedText);
            $(this).toggleClass('selected').siblings().removeClass('selected');  // Add class to selected item and remove from others
            $('.store-default').html(selectedText);  // Update the filter button text
            
            var sortOrder = '';
            //determine how to sort the reviews
            if (selectedText.includes('Negative')){
                sortOrder = 'neg';
                console.log(sortOrder);
            }
            else if (selectedText.includes('Positive')){
                sortOrder = 'pos';
                console.log(sortOrder);
            }
            else if (selectedText.includes('Recent')){
                sortOrder = 'recent';
                console.log(sortOrder);
            }
            
            fetchReviews(sortOrder);  // Fetch courses based on the selected sorting order
        });

        // default sort
        // fetchReviews('asc');

    });

        
   </script>

<div class="footer">
    <div id="footerLeft">
        <div id="leftTop">
            <h3>The Nexus @ RPI</h3>
        </div>
        <div id="leftBottom">
            <a href="https://github.com/RPI-ITWS/ITWS-2110-F24-Team17"><img src="../resources/photos/githublogo.png" alt="GitHub Logo"></a>
            <a href="https://github.com/RPI-ITWS"><img src="../resources/photos/itwslogo.png" alt="ITWS Logo"></a>
            <a href="https://www.rpi.edu/"><img src="../resources/photos/rpilogo.jpeg" alt="RPI Logo"></a>
            <a href="https://github.com/quacs/quacs-data/blob/master/semester_data/202405/courses.json"><img src="../resources/photos/quacslogo.png" alt="QUACS Logo"></a>
        </div>
    </div>
    <div id="footerMiddle">
        <a class="footerLink" href="../">Home</a>
        <p>|</p>
        <a class="footerLink" href="../eventPlanner/">Event Hub</a>
        <p>|</p>
        <a class="footerLink" lass="active" href="./">Professor and Course Reviews</a>
        <p>|</p>
        <a class="footerLink" href="../discussionForum/">Discussion Forum</a>
        <p>|</p>
        <a class="footerLink" href="../health/">Mental Health Resources</a>
    </div>
    <div id="footerRight"> 
        <a href="../feedbackForm/"><button id="feedbackbtn">Have Feedback for Us?</button></a>
    </div>
</div>
    
</body>
</html>