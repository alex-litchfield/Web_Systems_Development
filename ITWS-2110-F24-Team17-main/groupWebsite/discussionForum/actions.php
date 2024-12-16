<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require '../resources/php/connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = trim($input['action'] ?? '');
    if (empty($action)) {
        echo json_encode(['error' => 'Action is missing.']);
        exit;
    }

    if ($action === 'getGroupMessages') {
        // Logic to fetch group messages
        $accessCode = trim($input['accessCode'] ?? '');

        if (empty($accessCode)) {
            echo json_encode(['error' => 'Access code is missing.']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT username, messageText, postTitle, messageTimeStamp, email, accessCode
            FROM groupMessages 
            WHERE accessCode = ?
            ORDER BY messageTimeStamp DESC
        ");
        $stmt->bind_param('s', $accessCode);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        echo json_encode(['messages' => $messages]);
    } 
    elseif ($action === 'deleteGroupMessage') {
        // Logic to delete a group message
        $accessCode = trim($input['accessCode'] ?? '');
        $messageText = trim($input['messageText'] ?? '');
        $username = trim($input['username'] ?? '');
        $email = trim($input['email'] ?? '');
        $postTitle = trim($input['postTitle'] ?? '');
        $messageTimeStamp = trim($input['messageTimeStamp'] ?? '');
        if (empty($accessCode) || empty($messageText) || empty($username) || empty($email) || empty($postTitle) || empty($messageTimeStamp)) {
            echo json_encode(['error' => 'Missing required fields.']);
            exit;
        }
        $stmt = $db->prepare("
            DELETE FROM groupMessages
            WHERE accessCode = ? 
              AND messageText = ? 
              AND username = ? 
              AND email = ? 
              AND postTitle = ? 
              AND messageTimeStamp = ?
        ");
        if (!$stmt) {
            echo json_encode(['error' => 'Failed to prepare the statement.']);
            exit;
        }
        $stmt->bind_param('ssssss', $accessCode, $messageText, $username, $email, $postTitle, $messageTimeStamp);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Post deleted successfully.']);
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'No matching post found.']);
        }
        $stmt->close();
    } 
    elseif ($action === 'submitGroupMessage') {
        // Logic to submit a new message
        $accessCode = trim($input['accessCode'] ?? '');
        $email = trim($input['email'] ?? '');
        $username = trim($input['username'] ?? '');
        $postTitle = trim($input['postTitle'] ?? '');
        $postContents = trim($input['postContents'] ?? '');

        if (!empty($accessCode) && !empty($email) && !empty($username) && !empty($postTitle) && !empty($postContents)) {
            try {
                $stmt = $db->prepare("
                    INSERT INTO groupMessages (accessCode, messageText, username, email, postTitle)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->bind_param('sssss', $accessCode, $postContents, $username, $email, $postTitle);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Post submitted successfully.']);
                } 
                else {
                    echo json_encode(['success' => false, 'error' => 'Failed to execute the statement.']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            }
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        }
    } 
    elseif ($action === 'getMemberStatus') {
        // Logic from the second PHP file to get member status
        $accessCode = $input['accessCode'] ?? '';

        // Ensure input is not empty
        if (empty($accessCode)) {
            echo json_encode(['error' => 'Access code is required.']);
            exit;
        }

        if (!isset($_SESSION['email'])) {
            echo json_encode(['memberStatus' => null]); // Output "null" for memberStatus if not logged in
            exit;
        }

        $email = $_SESSION['email'];
        try {
            // Prepare and execute the query to fetch the member status when provided an accessCode and memberEmail
            $query = $db->prepare("
                SELECT memberStatus 
                FROM discussionGroupMembers 
                WHERE accessCode = ? AND memberEmail = ?
            ");
            $query->bind_param('ss', $accessCode, $email);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode(['memberStatus' => $row['memberStatus']]);
            } 
            else {
                // If no instance is found in the table, the memberStatus is output as "noMemberStatus"
                echo json_encode(['memberStatus' => 'noMemberStatus']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } 
    elseif ($action === 'updateMemberStatus') {
        // Logic from the second PHP file to update member status
        if (!isset($_SESSION['email'])) {
            echo json_encode(['success' => false, 'error' => 'User not logged in.']);
            exit;
        }

        $currentUserEmail = $_SESSION['email'];
        $newAccessCode = trim($input['newAccessCode'] ?? '');
        $command = trim($input['command'] ?? '');

        if (empty($newAccessCode) || empty($command)) {
            echo json_encode(['success' => false, 'error' => 'Access code and command are required.']);
            exit;
        }

        try {
            switch ($command) {
                case "addMember":
                    $insertQuery = $db->prepare("
                        INSERT INTO discussionGroupMembers (memberEmail, accessCode, memberStatus)
                        VALUES (?, ?, 'member')
                    ");
                    $insertQuery->bind_param('ss', $currentUserEmail, $newAccessCode);
                    $insertQuery->execute();
                    echo json_encode(['success' => true, 'message' => 'Member added successfully.']);
                    break;

                case "removeMember":
                    $deleteQuery = $db->prepare("
                        DELETE FROM discussionGroupMembers 
                        WHERE accessCode = ? AND memberEmail = ? AND memberStatus = 'member'
                    ");
                    $deleteQuery->bind_param('ss', $newAccessCode, $currentUserEmail);
                    $deleteQuery->execute();
                    echo json_encode(['success' => true, 'message' => 'Member removed successfully.']);
                    break;

                case "addAdmin":
                    $insertQuery = $db->prepare("
                        INSERT INTO discussionGroupMembers (memberEmail, accessCode, memberStatus)
                        VALUES (?, ?, 'admin')
                    ");
                    $insertQuery->bind_param('ss', $currentUserEmail, $newAccessCode);
                    $insertQuery->execute();
                    echo json_encode(['success' => true, 'message' => 'Admin added successfully.']);
                    break;

                case "removeAdmin":
                    $deleteAdminQuery = $db->prepare("
                        DELETE FROM discussionGroupMembers 
                        WHERE accessCode = ? AND memberEmail = ? AND memberStatus = 'admin'
                    ");
                    $deleteAdminQuery->bind_param('ss', $newAccessCode, $currentUserEmail);
                    $deleteAdminQuery->execute();
                    $query = $db->prepare("
                        SELECT id FROM discussionGroupMembers WHERE accessCode = ?
                    ");
                    $query->bind_param('s', $newAccessCode);
                    $query->execute();
                    $result = $query->get_result();
                    if ($result->num_rows > 0) {
                        $firstMember = $result->fetch_assoc();
                        $updateQuery = $db->prepare("
                            UPDATE discussionGroupMembers 
                            SET memberStatus = 'admin'
                            WHERE id = ?
                        ");
                        $updateQuery->bind_param('i', $firstMember['id']);
                        $updateQuery->execute();
                        echo json_encode(['success' => true, 'message' => 'Admin removed and another member promoted to admin.']);
                    } 
                    else {
                        $deleteGroupQuery = $db->prepare("
                            DELETE FROM discussionGroups 
                            WHERE accessCode = ? AND groupVisibility = 'Private'
                        ");
                        $deleteGroupQuery->bind_param('s', $newAccessCode);
                        $deleteGroupQuery->execute();
                        $deleteMessagesQuery = $db->prepare("
                            DELETE FROM groupMessages 
                            WHERE accessCode = ?
                        ");
                        $deleteMessagesQuery->bind_param('s', $newAccessCode);
                        $deleteMessagesQuery->execute();
                        echo json_encode(['success' => true, 'message' => 'Group deleted successfully.']);
                    }
                    break;

                default:
                    echo json_encode(['success' => false, 'error' => 'Invalid command.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Error: ' . $e->getMessage()]);
        }
    }
    elseif ($action === 'generateNewAccessCode') {
        // Function to generate a random 10-character access code
        function generateAccessCode() {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $accessCode = '';
            for ($i = 0; $i < 10; $i++) {
                $accessCode .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $accessCode;
        }

        try {
            $isUnique = false;
            $newAccessCode = '';
            while (!$isUnique) {
                $newAccessCode = generateAccessCode();
                // Check if the generated code exists in the discussionGroups table already
                $query = $db->prepare("SELECT COUNT(*) FROM discussionGroups WHERE accessCode = ?");
                $query->bind_param('s', $newAccessCode);
                $query->execute();
                $query->bind_result($count);
                $query->fetch();
                $query->close();
                // If count is 0, the code is unique
                if ($count === 0) {
                    $isUnique = true;
                }
            }
            // Return the unique access code as JSON
            echo json_encode(['success' => true, 'accessCode' => $newAccessCode]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    elseif ($action === 'searchDiscussionGroupsByAccessCode') {
        $searchString = $input['search'] ?? '';

        // Ensure input is not empty
        if (empty($searchString)) {
            echo json_encode(['error' => 'Search string is empty.']);
            exit;
        }

        // Ensure the user is logged in
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'] ?? '';
        } else {
            echo json_encode(['error' => 'User is not logged in.']);
            exit;
        }

        // Prepare the query for searching user-specific groups (allows partial matching for group names)
        $memberQuery = $db->prepare("
            SELECT dg.groupName, dg.professorName, dg.courseCode, dg.courseTitle, dg.accessCode, dg.groupVisibility
            FROM discussionGroups dg
            JOIN discussionGroupMembers dgm ON dg.accessCode = dgm.accessCode
            WHERE dgm.memberEmail = ? AND 
                  dg.groupName LIKE ?
        ");
        $searchString = trim($searchString);
        $searchParam = "%" . $searchString . "%";
        $memberQuery->bind_param('ss', $email, $searchParam);
        $memberQuery->execute();
        $memberResults = $memberQuery->get_result();
        $memberGroups = $memberResults->fetch_all(MYSQLI_ASSOC);

        // Prepare the query for searching groups with matching access codes (exact match)
        $existingQuery = $db->prepare("
            SELECT groupName, professorName, courseCode, courseTitle, accessCode, groupVisibility
            FROM discussionGroups 
            WHERE accessCode = ?
        ");
        $existingQuery->bind_param('s', $searchString);
        $existingQuery->execute();
        $existingResults = $existingQuery->get_result();
        $existingGroups = $existingResults->fetch_all(MYSQLI_ASSOC);
        
        $memberQuery->close();
        $existingQuery->close();

        echo json_encode([
            'memberGroups' => $memberGroups,
            'existingGroups' => $existingGroups
        ]);
    }
    elseif ($action === 'getAllDiscussionGroupsForCurrentUser') {
        // Ensure the user is logged in
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
        } 
        else {
            echo json_encode(['error' => 'User is not logged in.']);
            exit;
        }

        // Prepare the query to get all groups that the user is a member of (through matching accessCodes)
        $memberQuery = $db->prepare("
            SELECT dg.groupName, dg.accessCode 
            FROM discussionGroups dg
            JOIN discussionGroupMembers dgm ON dg.accessCode = dgm.accessCode
            WHERE dgm.memberEmail = ?
        ");
        $memberQuery->bind_param('s', $email);
        $memberQuery->execute();
        $memberResults = $memberQuery->get_result();
        $memberGroups = $memberResults->fetch_all(MYSQLI_ASSOC);
        $memberQuery->close();
        echo json_encode([ 'memberGroups' => $memberGroups ]);
    }
    elseif ($action === 'updateDiscussionGroup') {
        // Taking in inputs
        $groupName = trim($input['groupName'] ?? '');
        $professorName = trim($input['professorName'] ?? '');
        $courseCode = trim($input['courseCode'] ?? '');
        $courseTitle = trim($input['courseTitle'] ?? '');
        $accessCode = trim($input['accessCode'] ?? '');
        $groupVisibility = trim($input['groupVisibility'] ?? '');
        // Ensure required inputs are not empty
        if (empty($accessCode)) {
            echo json_encode(['success' => false, 'error' => 'Access code is required.']);
            exit;
        }
        if (empty($groupName)) {
            echo json_encode(['success' => false, 'error' => 'Group name cannot be blank.']);
            exit;
        }
        try {
            // Check if a group with the given accessCode already exists in discussionGroups table
            $query = $db->prepare("SELECT id FROM discussionGroups WHERE accessCode = ?");
            $query->bind_param('s', $accessCode);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                // Update existing group with the new input values if an existing group was found in discussionGroups table
                $updateQuery = $db->prepare("
                    UPDATE discussionGroups 
                    SET groupName = ?, professorName = ?, courseCode = ?, courseTitle = ?, groupVisibility = ?
                    WHERE accessCode = ?
                ");
                $updateQuery->bind_param('ssssss', $groupName, $professorName, $courseCode, $courseTitle, $groupVisibility, $accessCode);
                $updateQuery->execute();
                if (!$updateQuery->execute()) {
                    echo json_encode(['success' => false, 'error' => $updateQuery->error]);
                    exit;
                }
                echo json_encode(['success' => true, 'message' => 'Group updated successfully.']);
            } 
            else {
                // Insert new group with the input values if an existing group was not found in discussionGroups table
                $insertQuery = $db->prepare("
                    INSERT INTO discussionGroups (groupName, professorName, courseCode, courseTitle, groupVisibility, accessCode) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $insertQuery->bind_param('ssssss', $groupName, $professorName, $courseCode, $courseTitle, $groupVisibility, $accessCode);
                $insertQuery->execute();
                echo json_encode(['success' => true, 'message' => 'Group added successfully.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    elseif ($action === 'searchPublicDiscussionGroups') {
        // Get the incoming search string from the request
        $searchString = trim($input['searchString'] ?? '');
        // Function to determine the memberStatus
        function getMemberStatus($accessCode, $db) {
            // Check if the user is logged in
            if (!isset($_SESSION['email'])) {
                return null;
            }
            $userEmail = $_SESSION['email'];
            // Prepare the query to find memberStatus based on matching memberEmail and accessCode
            $query = "SELECT memberStatus FROM discussionGroupMembers WHERE memberEmail = ? AND accessCode = ?";
            $stmt = $db->prepare($query);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $db->error);
            }
            $stmt->bind_param('ss', $userEmail, $accessCode);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Fetch memberStatus if a match is found
                $row = $result->fetch_assoc();
                return $row['memberStatus'];
            } 
            else {
                // No match found, return "noMemberStatus"
                return 'noMemberStatus';
            }
        }
        try {
            // Check if the search string is "courses" or "professors" (case-insensitive)
            if (strcasecmp($searchString, "courses") === 0 || strcasecmp($searchString, "professors") === 0) {
                // Query to get all public discussion groups
                $query = "SELECT * FROM discussionGroups WHERE groupVisibility = 'Public'";
                $result = $db->query($query);
                if (!$result) {
                    throw new Exception("Query failed: " . $db->error);
                }
                $publicGroups = [];
                while ($row = $result->fetch_assoc()) {
                    $accessCode = $row['accessCode'];
                    // Retrieve messages associated with the current group's accessCode
                    $messagesQuery = "SELECT * FROM groupMessages WHERE accessCode = ?";
                    $stmt = $db->prepare($messagesQuery);
                    if (!$stmt) {
                        throw new Exception("Failed to prepare statement: " . $db->error);
                    }
                    $stmt->bind_param('s', $accessCode);
                    $stmt->execute();
                    $messagesResult = $stmt->get_result();
                    // Place messages into a messages object
                    $messages = [];
                    while ($messageRow = $messagesResult->fetch_assoc()) {
                        $messages[] = [
                            'messageText' => $messageRow['messageText'],
                            'username' => $messageRow['username'],
                            'postTitle' => $messageRow['postTitle'],
                            'messageTimeStamp' => $messageRow['messageTimeStamp']
                        ];
                    }
                    // Place information into the publicGroups object
                    $publicGroups[] = [
                        'groupName' => $row['groupName'],
                        'professorName' => $row['professorName'],
                        'courseCode' => $row['courseCode'],
                        'courseTitle' => $row['courseTitle'],
                        'accessCode' => $row['accessCode'],
                        'groupVisibility' => $row['groupVisibility'],
                        'messages' => $messages,
                        'memberStatus' => getMemberStatus($row['accessCode'], $db)
                    ];
                }
                //Sorting cases
                if (strcasecmp($searchString, "courses") === 0){
                    // Sort $publicGroups by groupName alphabetically (case-insensitive)
                    usort($publicGroups, function($a, $b) {
                        return strcasecmp($a['groupName'], $b['groupName']);
                    });
                }
                else if (strcasecmp($searchString, "professors") === 0) {
                    // Sort $publicGroups by professorName, and then by groupName alphabetically (case-insensitive)
                    usort($publicGroups, function($a, $b) {
                        // First, compare professorName
                        $professorComparison = strcasecmp($a['professorName'], $b['professorName']);
                        // If professorNames are the same, compare groupName
                        if ($professorComparison === 0) {
                            return strcasecmp($a['groupName'], $b['groupName']);
                        }
                        return $professorComparison;
                    });
                }        
                echo json_encode(['publicGroups' => $publicGroups]);
            } 
            else {
                // Search for public discussion groups based on groupName containing the searchString
                $searchString = '%' . $searchString . '%';
                $query = "SELECT * FROM discussionGroups WHERE groupVisibility = 'public' AND groupName LIKE ?";
                $stmt = $db->prepare($query);
                if (!$stmt) {
                    throw new Exception("Failed to prepare statement: " . $db->error);
                }
                $stmt->bind_param('s', $searchString);
                $stmt->execute();
                $result = $stmt->get_result();
                if (!$result) {
                    throw new Exception("Query failed: " . $db->error);
                }
                $publicGroups = [];
                while ($row = $result->fetch_assoc()) {
                    $accessCode = $row['accessCode'];
                    // Retrieve messages associated with the current group's accessCode
                    $messagesQuery = "SELECT * FROM groupMessages WHERE accessCode = ?";
                    $stmt = $db->prepare($messagesQuery);
                    if (!$stmt) {
                        throw new Exception("Failed to prepare statement: " . $db->error);
                    }
                    $stmt->bind_param('s', $accessCode);
                    $stmt->execute();
                    $messagesResult = $stmt->get_result();
                    // Place messages into a message object
                    $messages = [];
                    while ($messageRow = $messagesResult->fetch_assoc()) {
                        $messages[] = [
                            'messageText' => $messageRow['messageText'],
                            'username' => $messageRow['username'],
                            'postTitle' => $messageRow['postTitle'],
                            'messageTimeStamp' => $messageRow['messageTimeStamp']
                        ];
                    }
                    // Place group info into the publicGroups object
                    $publicGroups[] = [
                        'groupName' => $row['groupName'],
                        'professorName' => $row['professorName'],
                        'courseCode' => $row['courseCode'],
                        'courseTitle' => $row['courseTitle'],
                        'accessCode' => $row['accessCode'],
                        'groupVisibility' => $row['groupVisibility'],
                        'messages' => $messages,
                        'memberStatus' => getMemberStatus($row['accessCode'], $db)
                    ];
                }
                // Sort $publicGroups by groupName alphabetically (case-insensitive)
                usort($publicGroups, function($a, $b) {
                    return strcasecmp($a['groupName'], $b['groupName']);
                });
                echo json_encode(['publicGroups' => $publicGroups]);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'Unexpected error: ' . $e->getMessage()]);
        }
    }
    else {
        echo json_encode(['error' => 'Invalid action specified.']);
    }
}
else {
    echo json_encode(['error' => 'Invalid request method.']);
}

$db->close();
?>
