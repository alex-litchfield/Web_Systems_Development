<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="icon" href="../resources/photos/favicon_io/favicon.ico" type="image/x-icon">
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <script src="../resources/jquery-3.7.1.min.js"></script>

   <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

   <script src="index.js"></script>
   <title>Professor Picker - Departments</title>
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
         <a class="active" href="./">Professor and Course Reviews</a>
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


   <div id="searchbar">
      <input type="text" id="searchbar-input" placeholder="Search for Department.." name="search" onkeyup="load_data(this.value);">
   </div>

   <!-- getting filled by the php file -->
   <div class="department-wrapper"></div>

   <div id="tester"></div>

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
   
   <script>

      $(document).ready(function(){

         // get the departments from the database displayed on the frontend
         $.ajax({
            url: "getDept.php",
            type: "GET",
            dataType: "html",
            success: function(response){
               console.log(response);
               $('.department-wrapper').html(response);

               const observer = new IntersectionObserver((entries) => {
                  entries.forEach((entry, index) => {
                     if (entry.isIntersecting) {
                           //load by 3
                           let group = Math.floor(index / 3); 
                           //delay each row of 3 by 300 ms
                           setTimeout(() => {
                              entry.target.classList.add('show');
                           }, group * 300);
                     } else {
                           // Remove "show" class when the element is not in view
                           entry.target.classList.remove('show');
                     }
                  });
               });

               //apply this to class "fade-in-section"
               const hiddenElements = document.querySelectorAll('.fade-in-section');
               hiddenElements.forEach((el, index) => observer.observe(el)); 

            }
         });

         // get input from search bar
         $('#searchbar-input').on('keyup', function() {
            const query = $(this).val();
            searchDpts(query);  
         });

         // load departments with searchDept.php
         function searchDpts(query) {
            $.ajax({
               url: "searchDept.php",
               type: "POST",
               data: {query: query},
               dataType: "json",
               success: function(response) {
                  var html = '';

                  if (response.length > 0) {
                     response.forEach(function(item) {
                        html += '<div class="department" id="' + item.courseCode + '">';
                        html += '<h2>' + item.courseCode + '</h2>';
                        html += '</div>';
                     });
                  } else {
                     html = '<p>No Department Found!</p>';
                  }

                  $('.department-wrapper').html(html);
               }
            });
         }

         // once a department is clicked, get the id and display the courses and the professors that teach that course
         $(document).on('click', '.department', function() {
            // get the department id
            var deptID = $(this).closest("div").attr("id");

            // get redirected to courses.html to view the courses in that dept
            window.location.href = "courses.php?deptID=" + deptID;
            sessionStorage.setItem("ratingContent", "-/10★");
         });
      });

   </script>
      
</body>
</html>