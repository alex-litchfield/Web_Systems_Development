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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="index.js"></script>
    <title>Course List</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../resources/navbar.css">
    <link rel="stylesheet" href="../resources/footer.css"/>
    <link rel="stylesheet" href="index.css"/>
</head>
<body>
    <!-- navigation bar -->
    <div id="navbar">
        <div id="navbarLeft">
            <img src="../resources/photos/the_nexus_logo_transparent.png" alt="Nexus Logo">
            <a href="../">The Nexus @ RPI</a>
            <a href="../eventPlanner">Event Hub</a>
            <a class="active" href="index.php">Professor and Course Reviews</a>
            <a href="../discussionForum">Discussion Forum</a>
            <a href="../health">Mental Health Resources</a>
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

    <!-- header for filter option -->
    
    <!-- <div class="filter-dptcrn">
        
        <button onclick="filter_ratings()" id="filterButton">Filter Courses By 
            <span class="material-symbols-outlined arrow">keyboard_arrow_down</span>
        </button>
        
        <div id="myDropdown" class="dptcrn-dropdown-content">
            <a href="#">Lowest Number First</a>
            <a href="#">Largest Number First</a>
        </div>
    </div> -->

    <!-- <div id="filter-dptcrn">
        <div class="storeSelecWrap j-store-select selWrap">

            <div class="s-dropdown--styled">

                <span class="store-default" style="color: rgb(255, 255, 255);">Filter Courses By 
                <span class="material-symbols-outlined arrow">keyboard_arrow_down</span></span>

                <ul class="s-dropdown u-hide">
                    <li class="j-store" data-value="1">Lowest Number First</li>
                    <li class="j-store" data-value="2">Largest Number First</li>
                </ul>

            </div>

        </div>
    <div> -->

    <div class="storeSelecWrap j-store-select selWrap">

        <div class="s-dropdown--styled">

            <span class="store-default" style="color: rgb(255, 255, 255);">Filter Courses By</span>

            <ul class="s-dropdown u-hide">
                <li class="j-store" data-value="1">Lowest Number First</li>
                <li class="j-store" data-value="2">Largest Number First</li>
            </ul>

        </div>

    </div>
    
    
    <!-- all course containers -->
    <!-- this is written into by getCoursesProfessor.php -->
    <div class="course-wrapper">
    </div>

    <script>
        $(document).ready(function () {
            // get the CRN from the URL
            function getQueryParameter(name) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(name);
            }

            var deptID = getQueryParameter('deptID');

            // fetch and display courses based on sorting
            function fetchCourses(sortOrder) {
                $.ajax({
                    url: "getCoursesProfessors.php",
                    type: "GET",
                    data: { deptID: deptID, sortOrder: sortOrder },
                    dataType: "html",
                    success: function (response) {
                        $('.course-wrapper').html(response);
                    }
                });
            }

            $(document).on('click', '.j-store-select', function() {
                    $(this).find('ul').slideToggle();
            });

            $(document).on('click', '.j-store', function () {
                var selectedText = $(this).html();
                $(this).toggleClass('selected').siblings().removeClass('selected');  // Add class to selected item and remove from others
                $('.store-default').html(selectedText);  // Update the filter button text
                
                // Determine sort order (ascending for "Lowest Number First", descending for "Largest Number First")
                var sortOrder = $(this).text().includes('Lowest') ? 'asc' : 'desc';
                fetchCourses(sortOrder);  // Fetch courses based on the selected sorting order
            });


            $(document).on('click', '.dropdown-content', function() {
                //get the professor, which is in dropdown-content
                var professor = $(this).text();

                //get the courseCode, which is in h2
                // var courseCode = $(this).closest("h2").text();

                var courseCode = $(this).closest('.dropdown').find('h2').text();

                //get the courseTitle, which is in h3
                var courseTitle = $(this).closest('.dropdown').find('h3').text();

                sessionStorage.setItem("professor", professor);
                sessionStorage.setItem("courseCode", courseCode);
                sessionStorage.setItem("courseTitle", courseTitle);

                //get redirected to reviews.html to view the courses in that dept
                window.location.href = "review.php";
                sessionStorage.setItem("ratingContent", "-/10★");

            });

            // dropdown button
            $('#myDropdown a').click(function () {
                // sends asc or desc to getCoursesProfessors.php
                var sortOrder = $(this).text().includes('Lowest') ? 'asc' : 'desc';
                fetchCourses(sortOrder);

                document.getElementById("myDropdown").classList.remove("show");
            });
            
            // default sort
            fetchCourses('asc');
        });

        function filter_ratings() {
            const dropdown = document.getElementById("myDropdown");
            dropdown.classList.toggle("show");
        }

        // closes dropdown button if user clicks anywhere on page
        window.onclick = function(event) {
            if (!event.target.matches('.filter-dptcrn-btn')) {
                const dropdowns = document.getElementsByClassName("dptcrn-dropdown-content");
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        };
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