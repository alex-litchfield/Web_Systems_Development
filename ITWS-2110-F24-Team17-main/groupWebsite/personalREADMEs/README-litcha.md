Alexander Litchfield README

Worklog: (Citations used on that date are in parentheses)

9/17/2024: Added the first 2 status reports into the repo.

9/20/2024: Added to the repo the Product Backlog, Sprint Backlog, Status Report #3, and Status Report #4.

9/27/2024: Added Status Report #5 to the repo.

10/1/2024: Created navbar structure and styling to be used across all pages and creating basic discussion form page structure/formatting. Sources used: (1), (2), (3)

10/2/2024: Further worked on styling and extremely light JS functionality to allow the user to toggle between "Professor" and "Courses" on one button found on the discussion forum page. I also dealt with a few minor merge conflicts, one of which was in my pull request, but the other was caused when I accidentally merged a newer pull request before merging the older one. In the end I reopened and closed pull requests a few times to try and fix it, eventually fixing everything.

10/7/2024: Added Status Report #6, Status Report #7, and Status Report #8 into the repo.

10/9/2024: Added Status Report #9 to the repo and changed how the documentation directory is organized.

10/15/2024: Created a web scraper to scrape data from (4), a bookmark of existing SIS data for the fall 2024 courses and other relevant information, and place it into a .txt file (8). I also read documentation on SQL python connectors (5), selenium (6), and beautifulSoup (7) because I figured I would need them in the future. I used beautifulSoup mostly to parse (4). Sources used: (4), (5), (6), (7), (8)

10/18/2024: Fixed slight CSS and HTML structural issues across several pages. The Health and Event Planner pages both had a white border which I removed. The Professor Picker had a navbar on it which someone else made, but since we decided on my navbar as a group to move forward with I removed it and pasted in the existing navbar code from other working pages. Finally, the login page contents were extremely off centered, such as the image and text inputs, which I then centered.

10/23/2024: Fixed formatting on the webscraper, but eventually realized that QUACS already had the information on hand in a parsed JSON file (9). It also occured to me that we would need professor last AND first names, which we did not previously have through my bookmark on SIS. To accomodate for this change, I combined the 2 diffent database values into one under just "professorName". I also fixed some syntax issues with our database.sql file. Finally, I created a php file that when executed will populate the professors data table with every course/prof combo that is present within the JSON (~900 for this JSON I believe). While making this I used my lab 3 code as a reference, and accidentally committed that as an extra file as well. Sources used: (9)

10/24/2024: Removed the extra file I accidentally committed the previous day. Regarding the discussion Forum, I finished everything from the final look/format of its contents to its functionality, provided it was something that didn't require the users to login. The specific things I did are as follows: I created a php file that upon loadup of the discussionForum will fetch all the information from the discussion table in the database, altered the CSS a little bit, and then implemented the search function, which would allow users to search for matching characters with any course name, course code, or professor name. Next, I made the container that displayed the search results scrollable, once I realized how many results there truly would be. I then had each of these search results become a clickable item, which would then show the user a list of corresponding professors/courses as well as a "go back" button. If someone were to click on a course, the menu would display all professors that have taught the course. If they were to click a professor, the menu would display all courses that the professor has taught. I realized that I could also make a blanket "Courses" and "Professors" search option via a filter button (though typing those would work too!). If a user search "Courses", all courses are displayed, and if they search "Professors", all professors are displayed. I then implemented the actual display of discussion posts, which shows the user each discussion post, title, and username of the poster, for a given course/professor combination they selected (Course -> Professor vs. Professor -> Course provides the same results). Finally I condensed some of the larger functions when I could. Sources used: (10), (11), (12), (13), (14), and (15)

10/25/2024: I went back to the discussion Forum and sorted all professor names alphabetically as well as preventing duplicate or blank names from appearing (I think the blank names came from classes that didn't have a professor directly tied to them in the JSON for some reason). I also improved the search function so that it is no longer case sensitive. I also accidentally committed the username and password as 'root' and '' instead of our team login info so I had to fix that. I then worked on the Event Planner page as it didn't currently have a backend. I created a php file that would pull from the clubs data table, and added functionality to use this information to populate a container with buttons for each club. Each button when clicked displays the member count and a course description at the moment. I also used snippets of my code from the discussion Forum to recreate a search function, that will show clubs with names that include the sequence of characters (not case sensitive). On default, however, all clubs are displayed. Finally, I added some slight CSS and HTML modifications that changed how the buttons were displayed. Added Status Report #13 to the repo. Sources used: (16)

11/18/24: Professors were still included when named "TBA" - which has now been fixed. All courses and professors would previously say "Professors who taught this course" on their linkage dropdown menu, which should only have been occurring for courses. Professor linkage dropdowns now say "Courses this professor has taught". The search function now works in such a way where if a user does not select the filter option and searches "Ba", all professors and courses with "ba" in their names will be displayed in a alphabetically sorted manner. Should "professors" be selected, only professors with "ba" in their names will be displayed in a sorted manner. Finally, should "courses" be selected, only courses with "ba" in their names will be displayed in a sorted manner. The navbar had some errors when it was copied over into the file. Everything displays and links correctly now. All instances of "var" that were not intended to be global variables have been changed to "let" to prevent security issues. Finally, I updated how the search result display text appears on the page.

11/19/24: Creating CSS/HTML alterations to prepare for study group data being added. This includes buttons, labels, and other displayed objects. Small javascript updates were included as well as several function creations (with 1 line in them only) to make sure I can keep track of what functions I will need to implement in the coming days. Sources used: (17)

11/22/24: Lots of changes were made to the discussion Forum during this commit. While this is listed as 11/22/24, these alteratiosn were made over the last 3 days and were not committed until today. The HTML and CSS layout was further updated to accommodate changes in the displayed data, such as popup menus and new label structures. The database.sql file was updated to remove the old discussion table and replace it with 3 new ones that exist for different functions related to the discusssion forum. The course_data.php file was updated to properly reflect structural changes to the databases related to the discussion forum. The general README was updated to explain how and when to use course_data.php (since it can wipe and refill several tables when called). The webscrape.py file was remvoed because it was not needed since we have used quacs data for our course information. I changed the url for the discussion forum page on each navbar in the code framework from .html to .php. Many new php files were created in order to help javascript functions before their tasks and create dynamically loaded features. Here is a list of the features which were altered or created:
1. The general search function was changed to display each course and class combo alphabetically within the larger discussionBox.
2. The discussionFilter button was removed due to a lack of usage and was replaced with a search button for the searchbar.
3. The course/prof combo dropdown was replaced due to a lack of usage.
4. Upon searching for a discussion group in the general search bar, it will be display the messages in the chat and general stats which can be accessed via a more information button.
5. The more information button was added and displays the access code, group visibility type (public vs. private), course code, course name, group name, and professor. If the user is not logged in, this will redirect the user to the login page. If they are logged in and are a member of the group, the information is displayed as labels. If they are logged in and are an admin of the group, the information is displayed as inputs and labels (access code and group visibility can never be changed).
6. Remove/Add groups button was added to the more information popup, which will say "add" only if the user is not a member/admin of the group. When selected, if the user is logged in, it will be added to their study group menu on the left. If the user is not logged in, it will redirect them to the login page. If the user is a member of the group, the "remove" button will remove the group from their study group menu. If the user is an admin of the group, ownership is transferred to another member of the group before removing the group from their study group menu. If there are no other group members, the group itself is completely deleted.
7. Create Study Group Button will check if the user is logged in. If they are not, it will redirect them to the login page. If they are, it will show a popup where they can input a groupName (required), course code (optional), professor (optional), and course name (optional). CURRENT BUG: If the popup is opened and the close button is selected and not the submit changes button, the user is still made an admin for the access code, despite no actual group being made to associate it with.
8. Make A Post Button check if the user is lgoged in. If they are not, it will redirect them to the login page. If they are logged in, it will call the makeNewPost() function - which has not yet been implemented past existing with an alert as a placeholder. 
Sources used: (18), (19), (20), (21), (22), (23)

11/27/24: I added the ability for users to make new discussion posts on the dicussion forum. Previously, these would display but would need to be added via phpmyadmin. I also use a favicon converter to create a set of favicons for the website.
Sources used: (24)

11/29/24: I edited the homepage so that it now has an About section which explains to users to purpose of our website. It also updates based on whether or not the user is logged in. I also fixed the navbar across all the webpages since they were pretty inconsistent in functionality. I then added a cursor pointer to all buttons on the discussion forum, as I realized this was the only page on the webiste that did not already have this as a css item. Finally, I redid the favicon folder (still using the same website as before) because I created a bunch of new variations to our logo which are clearer, centered, and can be used in different situations. Our latest status report was also added to the repo. I removed console logs that could have removed revealing information in the console.

11/30/24: I added the ability for users to delete posts under each discussion group if the individual is a site wide admin, an admin of the discussion group, or is the original creator of the post. I also condensed most of the php files into one new "actions.php" file. Doing this also fixed most of the bugs related to database alterations being out of order.

12/8/24: I fixed a bug with the homepage button where it was always green and an issue where some items on the health page were of different sizes when they were not supposed to be.

12/9/24. I updated documentation on the repo and also reworked the homepage layout to be more animated and lively for users.
Sources used: (25)

Citations:
1. https://www.w3schools.com/howto/howto_js_topnav.asp
2. https://www.w3schools.com/howto/howto_css_dropdown.asp
3. https://www.w3schools.com/html/html_classes.asp#:~:text=Multiple%20Classes,to%20all%20the%20classes%20specified.
4. https://sis.rpi.edu/reg/zft202409.htm
5. https://dev.mysql.com/doc/connector-python/en/connector-python-reference.html
6. https://www.selenium.dev/documentation/webdriver/
7. https://www.crummy.com/software/BeautifulSoup/bs4/doc/
8. https://www.w3schools.com/python/python_file_write.asp
9. https://github.com/quacs/quacs-data/blob/master/semester_data/202405/courses.json
10. https://stackoverflow.com/questions/13990629/html-css-invisible-button
11. https://stackoverflow.com/questions/63104351/how-to-make-the-enter-key-click-search-button
12. https://stackoverflow.com/questions/6208367/regex-to-match-stuff-between-parentheses
13. https://www.w3schools.com/jsref/jsref_match.asp
14. https://www.w3schools.com/jsref/met_document_createelement.asp
15. https://www.w3schools.com/jsref/met_node_appendchild.asp
16. https://www.w3schools.com/jsref/jsref_tolowercase.asp
17. https://www.w3schools.com/tags/tag_label.asp
18. https://www.w3schools.com/php/php_superglobals_post.asp
19. https://www.w3schools.com/php/php_superglobals_request.asp
20. https://www.w3schools.com/php/php_superglobals_get.asp
21. https://www.w3schools.com/php/php_functions.asp
22. https://www.w3schools.com/php/func_var_isset.asp
23. https://www.w3schools.com/php/php_sessions.asp
24. https://favicon.io/favicon-converter/
25. https://www.w3schools.com/cssref/atrule_keyframes.php