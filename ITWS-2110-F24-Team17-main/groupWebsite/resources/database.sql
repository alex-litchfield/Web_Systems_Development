-- Create and use newly created database
CREATE DATABASE team17;
USE team17;

-- table for users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(255),
    lastName VARCHAR(255),
    username VARCHAR(1000),
    password VARCHAR(1000),
    email VARCHAR(1000),
    isAdmin INT,
    isActiveUser INT
);

-- table for clubs
CREATE TABLE clubs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clubName VARCHAR(255),
    memberCount INT,
    descriptionVal VARCHAR(10000),
    locationVal VARCHAR(255), 
    roomVal VARCHAR(255),
    startTime VARCHAR(255),
    endTime VARCHAR(255),
    dayOfWeek VARCHAR(255)
);

-- table for professors (for each class)
CREATE TABLE professors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    professorName VARCHAR(255),
    courseTitle VARCHAR(255),
    courseCode VARCHAR(10),
    rating FLOAT,
    descriptionVal VARCHAR(10000)
);

-- table for healthcare resources
CREATE TABLE healthcare (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resourceName VARCHAR(255),
    descriptionValue VARCHAR(10000),
    likes INT,
    datePosted timestamp NOT NULL DEFAULT current_timestamp()
);

-- table for storing all discussion groups
CREATE TABLE discussionGroups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    groupName VARCHAR(255), -- Will be empty for public forum groups and optional for private groups
    professorName VARCHAR(255), -- Will be required for public forum groups and optional for private groups
    courseCode VARCHAR(10), -- Will be required for public forum groups and optional for private groups
    courseTitle VARCHAR(255), -- Will be required for public forum groups and optional for private groups
    accessCode VARCHAR(10), -- Will be required for public forum and private groups - is a unique generated code
    groupVisibility VARCHAR(10) -- Will be "Public" or "Private" - "public" groups are those made from course_data.php and "private" groups are those custom made by users
);

-- table for storing what discussion groups a member can be in
CREATE TABLE discussionGroupMembers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    memberEmail VARCHAR(1000), -- Email of members with access to a group
    accessCode VARCHAR(10), -- Unique access code to the group
    memberStatus VARCHAR(10) -- Designates the permissions of members of the group
);

-- table for storing the messages sent in a discussion group
CREATE TABLE groupMessages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    accessCode VARCHAR(10), -- Unique access code to the group
    messageText VARCHAR(1000),
    username VARCHAR(1000),
    email VARCHAR(1000),
    postTitle VARCHAR(255),
    messageTimeStamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE locationLatLong (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    longitude VARCHAR(255),
    latitude VARCHAR(255)
)