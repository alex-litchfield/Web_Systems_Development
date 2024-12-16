// Deletes the provided post
function deletePost(postInfo) {
    console.log(postInfo);
    fetch('actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'deleteGroupMessage',
            accessCode: postInfo.accessCode,
            messageText: postInfo.messageText,
            username: postInfo.username,
            email: postInfo.email,
            postTitle: postInfo.postTitle,
            messageTimeStamp: postInfo.messageTimeStamp,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            //console.log(data.message);
            console.error(data.message || data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}


// Create a popup which will allow the user to create a new post to the discussion group
function submitNewPost(accessCodeLabelID, email, username, postTitleInputID, postContentsInputID) {
    const accessCode = document.getElementById(accessCodeLabelID).textContent.replace("Group Access Code: ", "").trim();
    const postTitle = document.getElementById(postTitleInputID).value.trim();
    const postContents = document.getElementById(postContentsInputID).value.trim();
    const isAnonymous = document.getElementById('postAnonymouslyCheckbox').checked;
    if (isAnonymous) {
        username = "Anonymous";
    }
    /*console.log("Submitting new post: " + accessCode);
    console.log("Email", email);
    console.log("Username", username);
    console.log("postTitle:", postTitle);
    console.log("postContents:", postContents);*/
    if (!accessCode || !email || !username || !postTitle || !postContents) {
        console.error("All fields must be filled out.");
        alert("Please fill out all fields before submitting.");
        return;
    }

    fetch('actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'submitGroupMessage',
            accessCode: accessCode,
            email: email,
            username: username,
            postTitle: postTitle,
            postContents: postContents,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            //console.log("Post submitted and uploaded:", data);
            console.error("Error with posting the data:", data);
        }
    })
    .catch(error => {
        console.error("Error submitting post:", error);
    });
    closeNewPostMenu();
    stallFunction(3, accessCode);
}


// Ensures that functions stay called in the correct order
async function stallFunction(selector, accessCode) {
    await wait(200);
    if (selector == 1) {
        getAllMemberDiscussionGroups(); //Updates study group side menu
        await wait(100);
        resetDiscussionChatBox();
    }
    if (selector == 2) {
        getAllMemberDiscussionGroups(); //Updates study group side menu
        await wait(100);
        fetchDiscussionGroupChat(accessCode); // displaying the discussion group chats again
        //await wait(200);
        resetDiscussionChatBox(); // resets the discussion box back to normal with no contents
    }
    if (selector == 3) {
        await wait(100);
        fetchDiscussionGroupChat(accessCode); // displaying the discussion group chats again
    }
}

function wait(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// Resets the discussion chat box (big box to the left) back to its original state from loadup
function resetDiscussionChatBox() {
    const contentContainers = document.getElementById("contentContainers");
    const discussionInfoHeader = document.getElementById("discussionInfoHeader");
    const addPublicPostBtn = document.getElementById("addPublicPostBtn");
    const groupInfoBtn = document.getElementById("groupInfoBtn");
    discussionInfoHeader.textContent = "Use the filter or search option to begin!";
    //console.log("Inside resetdiscussionchatbox()", discussionInfoHeader.textContent);
    addPublicPostBtn.style.display = 'none';
    groupInfoBtn.style.display = 'none';
    contentContainers.innerHTML = '';
}

// Should include an if statement that uses the existing textcontent of the button to determine if the function should be a removal or a addition to a group
function editGroupMemberStatus(newAccessCode, command) {
    //console.log("group member status altered!", command, newAccessCode);
    if (!newAccessCode) {
        alert("Access code is required.");
        return;
    }
    if (!command) {
        alert("Command is required.");
        return;
    }
    const requestData = {
        action: 'updateMemberStatus',
        newAccessCode: newAccessCode,
        command: command,
    };
    //console.log("requestData", requestData);
    fetch('actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestData),
    })
    .then((response) => response.json())
    .then((data) => {
        if (!data.success) {
            //console.log("Member status altered successfully.", data);
            console.error("Error saving group changes: " + (data.error || "Unknown error."));
        }
    })
    .catch((error) => {
        console.error("An error occurred while attempting to alter the member status.", error);
    });
}

// Generates an random 10 character access code
function generateAccessCode() {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let accessCode = '';
    for (let i = 0; i < 10; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        accessCode += characters[randomIndex];
    }
    return accessCode;
}

// Creates a new study group in the table, assigns the creator as an admin, and updates the page
function createNewStudyGroup() {
    openNewStudyGroupMenu();
    const accessCodeLabel2 = document.getElementById("accessCodeLabel2");
    const groupVisibilityLabel2 = document.getElementById("groupVisibilityLabel2");
    const groupNameLabel2 = document.getElementById("groupNameLabel2");
    const professorNameLabel2 = document.getElementById("professorNameLabel2");
    const courseNameLabel2 = document.getElementById("courseNameLabel2");
    const courseCodeLabel2 = document.getElementById("courseCodeLabel2");
    const groupNameInput2 = document.getElementById("groupNameInput2");
    const professorNameInput2 = document.getElementById("professorNameInput2");
    const courseNameInput2 = document.getElementById("courseNameInput2");
    const courseCodeInput2 = document.getElementById("courseCodeInput2");
    const submitGroupChangesButton2 = document.getElementById("submitGroupChangesButton2");
    fetch('actions.php', {
        method: 'POST', // Use POST since the PHP script expects it
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'generateNewAccessCode'
        })  // Sending an empty object as no additional data is needed
    })
    .then(response => response.json())  // Parse the JSON response
    .then(data => {
        // Check if there was an error in the response
        if (data.error) {
            console.error(data.error);  // Handle the error (if any)
        } 
        else {
            //console.log("New access code:", data.accessCode);
            accessCodeLabel2.innerHTML = "<strong>Group Access Code: " + data.accessCode + "</strong>";
            groupVisibilityLabel2.innerHTML = "<strong>Group Visibility: Private</strong>";
            groupNameInput2.style.display = "inline-block";
            professorNameInput2.style.display = "inline-block";
            courseNameInput2.style.display = "inline-block";
            courseCodeInput2.style.display = "inline-block";
            submitGroupChangesButton2.style.display = "inline-block";
            groupNameLabel2.innerHTML = "<strong>Group Name:</strong>";
            professorNameLabel2.innerHTML = "<strong>Professor (Optional):</strong>";
            courseNameLabel2.innerHTML = "<strong>Course Name (Optional):</strong>";
            courseCodeLabel2.innerHTML = "<strong>Course Code (Optional):</strong>";
            editGroupMemberStatus(data.accessCode, "addAdmin"); // makes the creator of the private group an admin
            stallFunction(2, data.accessCode);
        }
    })
    .catch(error => {
        console.error('Error fetching discussion groups:', error);
    });
}

// Saves alterations to study group stats in the database
function submitGroupChanges(groupNameInputID, professorNameInputID, courseNameInputID, courseCodeInputID, accessCodeLabelID, groupVisibilityLabelID) {
    const newGroupName = document.getElementById(groupNameInputID).value.trim();
    const newProfessorName = document.getElementById(professorNameInputID).value.trim();
    const newCourseName = document.getElementById(courseNameInputID).value.trim();
    const newCourseCode = document.getElementById(courseCodeInputID).value.trim();
    const accessCodeLabel = document.getElementById(accessCodeLabelID).textContent.replace("Group Access Code: ", "").trim();
    const groupVisibilityLabel = document.getElementById(groupVisibilityLabelID).textContent.replace("Group Visibility: ", "").trim();
    //const discussionInfoHeader = document.getElementById("discussionInfoHeader");
    //discussionInfoHeader.textContent = newGroupName;
    if (!accessCodeLabel) {
        alert("Access code is required.");
        return;
    }
    if (!newGroupName) {
        alert("Group name cannot be blank.");
        return;
    }
    //console.log("requestData", requestData);
    fetch('actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'updateDiscussionGroup',
            groupName: newGroupName,
            professorName: newProfessorName || null, // Optional field
            courseTitle: newCourseName || null, // Optinal field
            courseCode: newCourseCode || null, // Optional field
            accessCode: accessCodeLabel,
            groupVisibility: groupVisibilityLabel
        }),
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            //console.log("Group changes saved successfully.", data);
            getAllMemberDiscussionGroups(); //Forces the "My Study Groups" Tab to update its contents
        } 
        else {
            console.error("Error saving group changes: " + (data.error || "Unknown error."));
        }
    })
    .catch((error) => {
        console.error("An error occurred while saving group changes:", error);
    });
    closeGroupInfoMenu();
    closeNewStudyGroupMenu();
}

// Opens the menu containing the group code and other course info
function openGroupInfoMenu() {
    const popup = document.getElementById("groupInfoPopup");
    popup.classList.remove("hidden");
}

// Closes the menu containing the group code and other course info
function closeGroupInfoMenu() {
    const popup = document.getElementById("groupInfoPopup");
    popup.classList.add("hidden");
}

// Opens the menu for creating a new study group
function openNewStudyGroupMenu() {
    const popup = document.getElementById("newStudyGroupPopup");
    popup.classList.remove("hidden");
}

// Closes the menu for creating a new study group
function closeNewStudyGroupMenu() {
    const popup = document.getElementById("newStudyGroupPopup");
    popup.classList.add("hidden");
}

// Opens the menu for creating a new post
function openNewPostMenu() {
    const popup = document.getElementById("newPostPopup");
    popup.classList.remove("hidden");
}

// Closes the menu for creating a new post
function closeNewPostMenu() {
    const popup = document.getElementById("newPostPopup");
    popup.classList.add("hidden");
}

// Displays the content of the group chat in the discussion box (big box on the left). Ability to interact with this data is dependent
// on your role for the group (admin, member, noMemberStatus, or null)
function displayDiscussionGroupChat(groupInfo, groupMessages, memberStatus) {
    /*console.log("MEMBER STATUS: ", memberStatus);
    console.log("groupInfo:", groupInfo);
    console.log("groupMessages:", groupMessages);*/
    const contentContainers = document.getElementById("contentContainers");
    const discussionInfoHeader = document.getElementById("discussionInfoHeader");
    const addPublicPostBtn = document.getElementById("addPublicPostBtn");
    const groupInfoBtn = document.getElementById("groupInfoBtn");
    addPublicPostBtn.style.display = 'block';
    groupInfoBtn.style.display = 'block';
    contentContainers.innerHTML = '';
    discussionInfoHeader.textContent = groupInfo.groupName;
    const accessCodeLabel1 = document.getElementById("accessCodeLabel1");
    const groupVisibilityLabel1 = document.getElementById("groupVisibilityLabel1");
    const groupNameLabel1 = document.getElementById("groupNameLabel1");
    const professorNameLabel1 = document.getElementById("professorNameLabel1");
    const courseNameLabel1 = document.getElementById("courseNameLabel1");
    const courseCodeLabel1 = document.getElementById("courseCodeLabel1");
    const groupNameInput1 = document.getElementById("groupNameInput1");
    const professorNameInput1 = document.getElementById("professorNameInput1");
    const courseNameInput1 = document.getElementById("courseNameInput1");
    const courseCodeInput1 = document.getElementById("courseCodeInput1");
    const submitGroupChangesButton1 = document.getElementById("submitGroupChangesButton1");
    const editGroupMemberStatusButton = document.getElementById("editGroupMemberStatusButton");
    const accessCodeLabel3 = document.getElementById("accessCodeLabel3");
    const groupVisibilityLabel3 = document.getElementById("groupVisibilityLabel3");
    const postContentsInput = document.getElementById("postContentsInput");
    const postTitleInput = document.getElementById("postTitleInput");
    const postTitleLabel = document.getElementById("postTitleLabel");
    const postContentsLabel = document.getElementById("postContentsLabel");


    groupNameInput1.value = groupInfo.groupName;
    professorNameInput1.value = groupInfo.professorName;
    courseNameInput1.value = groupInfo.courseTitle;
    courseCodeInput1.value = groupInfo.courseCode;
    accessCodeLabel1.innerHTML = "<strong>Group Access Code: " + groupInfo.accessCode + "</strong>";
    groupVisibilityLabel1.innerHTML = "<strong>Group Visibility: " + groupInfo.groupVisibility + "</strong>";
    accessCodeLabel3.innerHTML = "<strong>Group Access Code: " + groupInfo.accessCode + "</strong>";
    groupVisibilityLabel3.innerHTML = "<strong>Group Visibility: " + groupInfo.groupVisibility + "</strong>";
    postContentsLabel.innerHTML = "<strong>Post Contents:</strong>";
    postTitleLabel.innerHTML = "<strong>Post Title:</strong>";
    groupNameInput1.style.display = "inline-block";
    professorNameInput1.style.display = "inline-block";
    courseNameInput1.style.display = "inline-block";
    courseCodeInput1.style.display = "inline-block";
    postContentsInput.style.display = "inline-block";
    postTitleInput.style.display = "inline-block";
    submitGroupChangesButton1.style.display = "inline-block";
    editGroupMemberStatusButton.textContent = "Add to My Study Groups";

    //The options for memeberStatus are "admin", which is an active admin for the group, "member", which is an active member of the group,
    //null, which is for users not logged in, and "noMemberStatus", for users logged in but who are not part of the group
    if (memberStatus == "member" || memberStatus == null || memberStatus == "noMemberStatus") {
        groupNameLabel1.innerHTML = "<strong>Group Name: </strong>" + groupInfo.groupName;
        professorNameLabel1.innerHTML = "<strong>Professor: </strong>" + groupInfo.professorName;
        courseNameLabel1.innerHTML = "<strong>Course Name: </strong>" + groupInfo.courseTitle;
        courseCodeLabel1.innerHTML = "<strong>Course Code: </strong>" + groupInfo.courseCode;
        groupNameInput1.style.display = "none";
        professorNameInput1.style.display = "none";
        courseNameInput1.style.display = "none";
        courseCodeInput1.style.display = "none";
        submitGroupChangesButton1.style.display = "none";
        if (memberStatus == null) {
            addPublicPostBtn.onclick = function() {
                window.location.href = "../login/index.html";
            };
            editGroupMemberStatusButton.onclick = function() {
                window.location.href = "../login/index.html";
            };
        }
        else if (memberStatus == "member" || memberStatus == "noMemberStatus") {
            addPublicPostBtn.onclick = function() {
                openNewPostMenu();
            };
            if (memberStatus == "member") {
                editGroupMemberStatusButton.textContent = "Remove from my Study Groups";
                editGroupMemberStatusButton.onclick = function() {
                    editGroupMemberStatus(groupInfo.accessCode, "removeMember"); //removes a member from the group
                    closeGroupInfoMenu(); // Closes popup menu
                    stallFunction(1, null); // Calls the rest of the needed functions after 100 ms
                };
            }
            else if (memberStatus == "noMemberStatus") {
                editGroupMemberStatusButton.onclick = function() {
                    editGroupMemberStatus(groupInfo.accessCode, "addMember"); //adds a new member to the group
                    closeGroupInfoMenu(); // Closes popup menu
                    stallFunction(1, null); // Calls the rest of the needed functions after 100 ms
                };
            }
        }
    }
    else if (memberStatus == "admin") {
        groupNameLabel1.innerHTML = "<strong>Group Name:</strong>";
        professorNameLabel1.innerHTML = "<strong>Professor:</strong>";
        courseNameLabel1.innerHTML = "<strong>Course Name:</strong>";
        courseCodeLabel1.innerHTML = "<strong>Course Code:</strong>";
        editGroupMemberStatusButton.textContent = "Remove from my Study Groups";
        addPublicPostBtn.onclick = function() {
            openNewPostMenu();
        };
        editGroupMemberStatusButton.onclick = function() {
            editGroupMemberStatus(groupInfo.accessCode, "removeAdmin"); //Removes the current admin from the group
            closeGroupInfoMenu(); //Closes popup menu
            stallFunction(1, null); // Calls the rest of the needed functions after 100 ms
        };
    }

    //Fetch discussion posts from the database and displays each item in the contentContainers box
    groupMessages.forEach(item => {
        /*console.log(memberStatus)
        console.log(userInfo.siteAdminStatus)
        console.log(item.email)
        console.log(userInfo.email);*/
        // console.log(userInfo);
        // console.log("The Item:", item);
        // console.log("The user info:", userInfo);
        // console.log("The member status:", memberStatus);

        const discussionPost = document.createElement("div");
        const discussionPostTitle = document.createElement("h3");
        const discussionPostUserStamp = document.createElement("p");
        const discussionPostContent = document.createElement("p"); 
        const deletePostButton = document.createElement("button");
        const discussionPostInnerContainer = document.createElement("div");    
        discussionPost.className = "reviewContainer";
        discussionPostUserStamp.innerHTML = "<strong>" + item.username + "</strong> on " + item.messageTimeStamp.split(' ')[0];
        discussionPostUserStamp.style.textAlign = 'left';
        discussionPostContent.textContent = item.messageText;
        discussionPostTitle.textContent = item.postTitle;
        // Delete Post Button only appears if the user is an admin of the specific discussion group,
        // if the user was the person who originally created said post,
        // or if the user is a site wide admin
        if (memberStatus == "admin" || userInfo.siteAdminStatus == true || (item.email == userInfo.email && item.email != null && userInfo.email != null)) {
            deletePostButton.textContent = "X";
            deletePostButton.className = "deletePostButton";
            deletePostButton.onclick = function() {
                deletePost(item); // Deletes current post
                fetchDiscussionGroupChat(groupInfo.accessCode); //Fetches new discussion group data from database
            };
            discussionPost.appendChild(deletePostButton);
        }
        discussionPostInnerContainer.appendChild(discussionPostTitle);
        discussionPostInnerContainer.appendChild(discussionPostContent);
        discussionPost.appendChild(discussionPostUserStamp);
        discussionPost.appendChild(discussionPostInnerContainer);
        contentContainers.appendChild(discussionPost);
    });
}

// Fetches the contents of the group chat
function fetchDiscussionGroupChat(groupAccessCode) {
    //console.log("fetchDiscussionGroupChat() has started", groupAccessCode);
    fetch('actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'getGroupMessages',
            accessCode: groupAccessCode,
        }),
    })
    .then(response1 => response1.json())
    .then(data1 => {
        if (data1.error) {
            console.error(`Error: ${data1.error}`);
        } 
        else {
            fetch('actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    search: groupAccessCode,
                    action: 'searchDiscussionGroupsByAccessCode'
                })
            })
            .then(response2 => response2.json())
            .then(data2 => {
                if (data2.error) {
                    console.error(data2.error);
                }
                else {
                    // Check if groups exist in memberGroups or existingGroups
                    if (data2.memberGroups.length == 0 && data2.existingGroups.length == 0) {
                        console.log("Both lists are empty...");
                    } 
                    else {
                        // Fetch the memberStatus
                        fetch('actions.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                action: 'getMemberStatus',
                                accessCode: groupAccessCode
                            }),
                        })
                        .then(response3 => response3.json())
                        .then(data3 => {
                            if (data3.error) {
                                console.error(data3.error);
                            } 
                            else {
                                if (data2.memberGroups.length >= 1) {
                                    displayDiscussionGroupChat(data2.memberGroups[0], data1.messages, data3.memberStatus);
                                }
                                if (data2.existingGroups.length >= 1) {
                                    displayDiscussionGroupChat(data2.existingGroups[0], data1.messages, data3.memberStatus);
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching member status:', error);
                        });
                    }
                }
            })
            .catch(error => {
                console.error('An error occurred while searching for groups:', error);
            });
        }
    })
    .catch(error => {
        console.error('Error fetching group messages:', error);
    });
}

// Fetches all discussion groups which the logged in user is a member/admin of
function getAllMemberDiscussionGroups() {
    fetch('actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'getAllDiscussionGroupsForCurrentUser'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error(data.error);
        }
        else {
            const allUserDiscussionGroups = data.memberGroups || [];
            // Sort the existingGroups alphabetically by groupName
            const sortedDiscussionGroups = allUserDiscussionGroups.sort((a, b) => {
                return a.groupName.localeCompare(b.groupName);
            });
            if (sortedDiscussionGroups.length >= 0) {
                displayDiscussionGroups(sortedDiscussionGroups);
            }
            //console.log(sortedDiscussionGroups.length);
            const studyGroupHeader = document.getElementById("studyGroupHeader");
            studyGroupHeader.textContent = "My Study Groups:";
        }
    })
    .catch(error => {
        console.error('Error fetching discussion groups:', error);
    });
}

// Searches for discussion groups that the user is already in which match what was searched (name and/or access codes) display them
// If there are no matches, search for existing groups with that access code and then display them.
// If there are none, do nothing
function searchDiscussionGroups(searchStringOriginal) {
    if (!searchStringOriginal.trim()) {
        alert('Please enter a search term.');
        return;
    }
    else {
        searchString = searchStringOriginal.trim();
    }
    //console.log("Search string:", searchString);
    //console.log("Data:", data);
    fetch('actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            search: searchString,
            action: 'searchDiscussionGroupsByAccessCode'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error(data.error);
        }
        else {
            // Sort the memberGroups alphabetically by groupName
            const sortedMemberGroups = data.memberGroups.sort((a, b) => {
                return a.groupName.localeCompare(b.groupName);
            });
            // Sort the existingGroups alphabetically by groupName
            const sortedExistingGroups = data.existingGroups.sort((a, b) => {
                return a.groupName.localeCompare(b.groupName);
            });
            // Check and process sortedMemberGroups
            if (sortedMemberGroups.length >= 1) {
                //console.log("Sorted Member Groups:", sortedMemberGroups);
                displayDiscussionGroups(sortedMemberGroups);
            }
            // Check and process sortedExistingGroups
            if (sortedExistingGroups.length >= 1) {
                //console.log("Sorted Existing Groups:", sortedExistingGroups);
                displayDiscussionGroups(sortedExistingGroups);
            }
            // Exists for error checking
            if (sortedExistingGroups.length == 0 && sortedMemberGroups.length == 0) {
                //console.log("Both lists are empty...");
                const emptyGroup = {"memberGroups": []};
                displayDiscussionGroups(emptyGroup);
            }
            const studyGroupHeader = document.getElementById("studyGroupHeader");
            studyGroupHeader.textContent = "Search Results for '" + searchString + "'";
        }
    })
    .catch(error => {
        console.error('An error occurred while searching for groups:', error);
    });
    const studyGroupFilter = document.getElementById("studyGroupFilter");
    studyGroupFilter.value = "";
}

// Will display a list of all the provided discussionGroups as buttons. Will create a 'back' button if there are no groups
function displayDiscussionGroups(discussionGroupList) {
    const studyGroupBox = document.getElementById("studyGroupBox");
    studyGroupBox.innerHTML = '';
    // The if statement prevents an error from coming up
    if (discussionGroupList.length > 0) {
        discussionGroupList.forEach(group => {
            const studyGroupButton = document.createElement("button");
            studyGroupButton.className = "searchStudyGroupResultButton";
            studyGroupButton.textContent = group.groupName;
            studyGroupButton.onclick = function() {
                fetchDiscussionGroupChat(group.accessCode);
            };
            studyGroupBox.appendChild(studyGroupButton);
        });
    }
}

// Searches for public discussion groups
function performSearch(searchStringOriginal) {
    const discussionInfoHeader = document.getElementById("discussionInfoHeader");
    const contentContainers = document.getElementById("contentContainers");
    const addPublicPostBtn = document.getElementById("addPublicPostBtn");
    const groupInfoBtn = document.getElementById("groupInfoBtn");
    const filterName = document.getElementById("filterName");
    filterName.value = "";
    contentContainers.innerHTML = '';
    addPublicPostBtn.style.display = 'none';
    groupInfoBtn.style.display = 'none';
    if (!searchStringOriginal.trim()) {
        alert('Please enter a search term.');
        return;
    }
    else {
        searchString = searchStringOriginal.trim();
        discussionInfoHeader.textContent = "Search Results for '"+ searchString + "'";  
    }
    //fetch(`./searchPublicDiscussionGroups.php?searchString=${encodeURIComponent(searchString)}`, {
    fetch('actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'searchPublicDiscussionGroups',
            searchString: searchString
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error("Error:", data.error);
            return;
        }
        else {
            //console.log(data.publicGroups);
            // Displays each item in the contentContainers box
            data.publicGroups.forEach(publicGroup => {
                const searchResultButton = document.createElement("button");
                searchResultButton.onclick = function() {
                    displayDiscussionGroupChat(publicGroup, publicGroup.messages, publicGroup.memberStatus)
                };
                searchResultButton.className = "searchResultButton";
                searchResultButton.textContent = publicGroup.groupName;
                contentContainers.appendChild(searchResultButton);
            });
            if (data.publicGroups.length == 0) {
                discussionInfoHeader.textContent = "No Search Results for '"+ searchString + "'";
            }
        }
    })
    .catch(error => {
        console.error('Error fetching Discussion Forum data:', error);
    });
}

// Fetches the logged in user's information (or null if they are not logged in)
async function fetchUserInfo() {
    try {
        const response = await fetch('getEmail.php');
        const data = await response.json();
        if (data.email) {
            /*console.log('Logged-in user email:', data.email);
            console.log('Logged-in user username:', data.username);*/
            return data;
        } 
        else {
            console.log('No user is logged in.');
            return data;
        }
    } catch (error) {
        console.error('Error fetching email:', error);
        return null;
    }
}

// Runs constantly
var userInfo;
window.onload = async function () {
    getAllMemberDiscussionGroups();
    // Allows search bar text to be searched by the click of the enter key on the keyboard
    const studyGroupFilter = document.getElementById("studyGroupFilter");
    studyGroupFilter.addEventListener("keypress", function (event) {
        if (event.key == "Enter") {
            event.preventDefault();
            fetchUserInfo().then((userInfo) => {
                if (userInfo) {
                    searchDiscussionGroups(document.getElementById('studyGroupFilter').value);
                } else {
                    window.location.href = "../login/index.html";
                }
            });
        }
    });

    // Changes the CreateNewStudyGroup function
    const createStudyGroupBtn = document.getElementById("createStudyGroupBtn");
    userInfo = await fetchUserInfo();

    if (userInfo.email != null) {
        createStudyGroupBtn.onclick = function () {
            createNewStudyGroup();
        };
    } else {
        createStudyGroupBtn.onclick = function () {
            window.location.href = "../login/index.html";
        };
    }
};

// Allows search bar text to be searched by the click of the enter key on they keyboard
const filterInput = document.getElementById("filterName")
filterInput.addEventListener("keypress", function(event) {
    if (event.key == "Enter") {
        event.preventDefault();
        document.getElementById("searchButton").click();
    }
});