<?php
if (session_status() == PHP_SESSION_NONE) {
session_start();
}

// Database connection
require 'php/connect.php';


// Get the incoming JSON data
$data = json_decode(file_get_contents('courses.json'), true);
if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1){
    if ($data) {
        // Delete all previous entries in the professors table (if needed)
        $db->query("DELETE FROM professors"); // Uncomment if necessary
        $db->query("DELETE FROM discussionGroups"); // Clear the discussionGroups table
        $db->query("DELETE FROM groupMessages"); // Clear the groupMessages table
        $db->query("DELETE FROM discussionGroupMembers"); // Clear the discussionGroupMembers table

        // Prepare SQL to insert new course data
        $professorSql = "INSERT INTO professors (
                            professorName, courseTitle, courseCode, rating, descriptionVal
                        ) VALUES (?, ?, ?, ?, ?)";
        $professorStmt = $db->prepare($professorSql);

        $discussionGroupSql = "INSERT INTO discussionGroups (
                                groupName, professorName, courseCode, courseTitle, accessCode, groupVisibility
                            ) VALUES (?, ?, ?, ?, ?, ?)";
        $discussionGroupStmt = $db->prepare($discussionGroupSql);

        if ($professorStmt && $discussionGroupStmt) {
            // Set to track unique access codes
            $accessCodeSet = [];

            // Function to generate a unique access code
            function generateUniqueAccessCode(&$accessCodeSet) {
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                do {
                    $code = '';
                    for ($i = 0; $i < 10; $i++) {
                        $code .= $characters[rand(0, strlen($characters) - 1)];
                    }
                } while (isset($accessCodeSet[$code])); // Check if the code is already in the set
                $accessCodeSet[$code] = true; // Add the code to the set
                return $code;
            }

            // Function to create an entry in the professors table
            function createProfessorEntry($professorName, $courseTitle, $courseCode, $rating, $descriptionVal, $professorStmt, $db) {
                $count = 0;
                // Check if the combination of courseTitle, courseCode, and professorName already exists in the table
                $checkSql = "SELECT COUNT(*) FROM professors WHERE courseTitle = ? AND courseCode = ? AND professorName = ?";
                $checkStmt = $db->prepare($checkSql);

                if ($checkStmt) {
                    $checkStmt->bind_param("sss", $courseTitle, $courseCode, $professorName);
                    $checkStmt->execute();
                    $checkStmt->bind_result($count);
                    if ($checkStmt->fetch() && $count > 0) {
                        // Check if all three match
                        echo "Combination of Course Title '$courseTitle', Course Code '$courseCode', and Professor Name '$professorName' already exists. Skipping insert.<br>";
                        $checkStmt->close();
                        return false; // Entry already exists
                    }
                    $checkStmt->close();
                } else {
                    echo "Error preparing check statement: " . $db->error . "<br>";
                    return false;
                }

                // Bind parameters with correct types for the insert
                $professorStmt->bind_param(
                    "sssds",
                    $professorName,
                    $courseTitle,
                    $courseCode,
                    $rating,
                    $descriptionVal
                );

                // Execute insertion
                if ($professorStmt->execute()) {
                    echo "Professor entry for course title '$courseTitle' inserted successfully.<br>";
                    return true;
                } else {
                    echo "Error inserting professor entry: " . $professorStmt->error . "<br>";
                    return false;
                }
            }

            // Function to create an entry in the discussionGroups table
            function createDiscussionGroupEntry($groupName, $professorName, $courseCode, $courseTitle, &$accessCodeSet, $groupVisibility, $discussionGroupStmt) {
                $accessCode = generateUniqueAccessCode($accessCodeSet); // Generate a unique access code

                // Bind parameters and execute the insert
                $discussionGroupStmt->bind_param(
                    "ssssss",
                    $groupName,
                    $professorName,
                    $courseCode,
                    $courseTitle,
                    $accessCode,
                    $groupVisibility
                );

                if ($discussionGroupStmt->execute()) {
                    echo "Discussion group for course '$groupName' created successfully with access code '$accessCode'.<br>";
                } else {
                    echo "Error creating discussion group: " . $discussionGroupStmt->error . "<br>";
                }
            }

            // Loop through the JSON to insert each course title
            foreach ($data as $courseArray) {
                foreach ($courseArray['courses'] as $course) {
                    $profNames = $course['sections'][0]['timeslots'][0]['instructor'];
                    $courseTitle = $course['title'];
                    $courseCode = $course['id'];
                    $rating = '5.0'; // Temporary
                    $descriptionVal = 'To be input at a later date!'; // Temporary
                    $profNamesArray = explode(', ', $profNames); // Using ', ' as the delimiter

                    // Iterate over the array and handle each professor name
                    foreach ($profNamesArray as $name) {
                        if ($name != "TBA" && $name != "" && $name != null) {
                            $professorInserted = createProfessorEntry($name, $courseTitle, $courseCode, $rating, $descriptionVal, $professorStmt, $db);
                            if ($professorInserted) {
                                $groupName = $courseTitle . " (" . $courseCode . ") with " . $name;
                                $groupVisibility = "Public";
                                createDiscussionGroupEntry($groupName, $name, $courseCode, $courseTitle, $accessCodeSet, $groupVisibility, $discussionGroupStmt);
                            }
                        }
                    }
                }
            }

            $professorStmt->close();
            $discussionGroupStmt->close();
        } else {
            echo "Error preparing statements: " . $db->error;
        }
        echo '<script>
          alert("Reset Course Data...redirecting");
          window.location.href = "../index.php";
        </script>';
        exit;
    } else {
        echo "Invalid JSON data.";
    }
} else {
    echo '<script>
          alert("YOU ARE NOT AN ADMIN...redirecting");
          window.location.href = "../index.php";
        </script>';
    exit;
}
$db->close();
?>
