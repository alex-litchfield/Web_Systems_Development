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
  <title>Discussion Forum</title>
  <link rel="stylesheet" href="../resources/navbar.css">
  <link rel="stylesheet" href="../resources/footer.css">
  <link rel="stylesheet" href="discussionForum.css">
  <script src="discussionForum.js" defer></script>
</head>

<body>
  <!-- Navigation Bar -->
  <div id="navbar">
    <div id="navbarLeft">
      <img src="../resources/photos/the_nexus_logo_transparent.png" alt="Nexus Logo">
      <a href="../">The Nexus @ RPI</a>
      <a href="../eventPlanner/">Event Hub</a>
      <a href="../professorPicker/">Professor and Course Reviews</a>
      <a class="active" href="./">Discussion Forum</a>
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
  <!--Seach Bar and course/prof filter-->
  <div id="searchBar">
    <input type="text" id="filterName" placeholder="Search Course Name, Course Code, or Professor Name">
    <div class="dropdown" id="flipDiscussionDropdown">
      <button class="generalUsageButton" id="searchButton" onclick="performSearch(document.getElementById('filterName').value)">Search</button>
    </div>
  </div>
  <!--Page Content-->
  <div id="pageContent">
    <div id="discussionBox">
      <div id="discussionInfo">
        <div class="discussionInfoHeaderBox">
          <button class="generalUsageButton" id="addPublicPostBtn">Make A Post</button>
          <h3 id="discussionInfoHeader">Use the filter or search option to begin!</h3>
          <button class="generalUsageButton" id="groupInfoBtn" onclick=openGroupInfoMenu()>More Information</button>
        </div>
        <div id="contentContainers"></div>
      </div>
    </div>
    <div id="studyGroupInfo">
      <h3 id="studyGroupHeader">My Study Groups:</h3>
      <input type="text" id="studyGroupFilter" placeholder="Search Access Code/Name">
      <div id="studyGroupBox"></div>
      <div class="studyGroupFooterBox">
        <button class="generalUsageButton" id="createStudyGroupBtn">Create New Study Group</button>
      </div>
    </div>
  </div>
  <!-- Footer -->
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
        <a class="footerLink" href="../professorPicker/">Professor and Course Reviews</a>
        <p>|</p>
        <a class="footerLink" href="./">Discussion Forum</a>
        <p>|</p>
        <a class="footerLink" href="../health/">Mental Health Resources</a>
    </div>
    <div id="footerRight"> 
        <a href="../feedbackForm/"><button id="feedbackbtn">Have Feedback for Us?</button></a>
    </div>
  </div>
  <!-- Hidden Course Information Popup Window -->
  <div id="groupInfoPopup" class="popup hidden">
    <div class="popup-content">
      <button class="popup-button popup-button-right" onclick="closeGroupInfoMenu()">Close</button>
      <div class="popup-field">
        <label id="accessCodeLabel1"></label>
        <label id="groupVisibilityLabel1"></label>
      </div>
      <div class="popup-field">
        <label id="groupNameLabel1"></label>
        <input type="text" id="groupNameInput1" placeholder="Enter Group Name">
      </div>
      <div class="popup-field">
        <label id="professorNameLabel1"></label>
        <input type="text" id="professorNameInput1" placeholder="Enter Professor Name">
      </div>
      <div class="popup-field">
        <label id="courseCodeLabel1"></label>
        <input type="text" id="courseCodeInput1" placeholder="Enter Course Code">
      </div>
      <div class="popup-field">
        <label id="courseNameLabel1"></label>
        <input type="text" id="courseNameInput1" placeholder="Enter Course Name">
      </div>
      <button class="popup-button popup-button-left" id="editGroupMemberStatusButton"></button>
      <button class="popup-button popup-button-right" id="submitGroupChangesButton1" onclick="submitGroupChanges('groupNameInput1', 'professorNameInput1', 'courseNameInput1', 'courseCodeInput1', 'accessCodeLabel1', 'groupVisibilityLabel1')">Submit Changes</button>
    </div>
  </div>
  <!-- Hidden Study Group Creation Popup Window -->
  <div id="newStudyGroupPopup" class="popup hidden">
    <div class="popup-content">
      <button class="popup-button popup-button-right" onclick="closeNewStudyGroupMenu()">Close</button>
      <div class="popup-field">
        <label id="accessCodeLabel2"></label>
        <label id="groupVisibilityLabel2"></label>
      </div>
      <div class="popup-field">
        <label id="groupNameLabel2"></label>
        <input type="text" id="groupNameInput2" placeholder="Enter Group Name">
      </div>
      <div class="popup-field">
        <label id="professorNameLabel2"></label>
        <input type="text" id="professorNameInput2" placeholder="Enter Professor Name">
      </div>
      <div class="popup-field">
        <label id="courseCodeLabel2"></label>
        <input type="text" id="courseCodeInput2" placeholder="Enter Course Code">
      </div>
      <div class="popup-field">
        <label id="courseNameLabel2"></label>
        <input type="text" id="courseNameInput2" placeholder="Enter Course Name">
      </div>
      <button class="popup-button popup-button-left" id="submitGroupChangesButton2" onclick="submitGroupChanges('groupNameInput2', 'professorNameInput2', 'courseNameInput2', 'courseCodeInput2', 'accessCodeLabel2', 'groupVisibilityLabel2')">Submit Changes</button>
    </div>
  </div>
  <!-- Hidden Post Creation Popup Window -->
  <div id="newPostPopup" class="popup hidden">
    <div class="popup-content">
      <button class="popup-button popup-button-right" onclick="closeNewPostMenu()">Close</button>
      <div class="popup-field">
        <label id="accessCodeLabel3"></label>
        <label id="groupVisibilityLabel3"></label>
      </div>
      <div class="popup-field">
        <label id="postTitleLabel"></label>
        <input type="text" id="postTitleInput" placeholder="Enter Post Title">
      </div>
      <div class="popup-field">
        <label id="postContentsLabel"></label>
        <input type="text" id="postContentsInput" placeholder="Enter Post Contents">
      </div>
      <div class="popup-field-checkbox">
        <label id="postAnonymous"><strong>Post Anonymously:</strong></label>
        <input type="checkbox" id="postAnonymouslyCheckbox">
      </div>
      <button class="popup-button popup-button-left" id="submitNewPostButton" onclick="submitNewPost('accessCodeLabel3', '<?php echo isset($email) ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : ''; ?>', 
      '<?php echo isset($username) ? htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : ''; ?>', 'postTitleInput', 'postContentsInput')">Submit Changes</button>
  </div>
</body>

</html>